<?php
/**
 * Vercel Blob file storage (private store).
 *
 * A serverless instance cannot write to its own filesystem, so uploaded files
 * are stored here. The store is configured with private access: its URLs
 * answer 403 to anyone without the project's token, so the bytes are only
 * reachable through this application, which decides who may see them —
 * authorisation, rather than an unguessable URL.
 *
 * What goes in the database is the key inside the store, marked with a
 * "blob:" prefix so it cannot be confused with a filename committed under
 * uploads/. upload() turns that into a /media/... link, which media.php
 * serves after checking the viewer is allowed to see it.
 *
 * This speaks the same REST contract as the official @vercel/blob client
 * (API version 13). BLOB_READ_WRITE_TOKEN is set on the deployment
 * automatically by linking the store to the project; without it nothing here
 * is used and uploads go to uploads/ as they do on XAMPP and in Docker.
 */

const BLOB_API_BASE    = 'https://vercel.com/api/blob';
const BLOB_API_VERSION = '13';
/**
 * The store's own hostname, derived from the token.
 *
 * The token reads vercel_blob_rw_<storeId>_<secret>, and a private store
 * answers at <storeid>.private.blob.vercel-storage.com. Deriving it means the
 * host never has to be configured separately, and a store swap needs no code
 * change — relinking the store is enough.
 */
function blobHost(): ?string
{
    $parts = explode('_', BLOB_TOKEN);
    if (count($parts) < 5 || $parts[0] !== 'vercel' || $parts[1] !== 'blob') {
        return null;
    }
    return strtolower($parts[3]) . '.private.blob.vercel-storage.com';
}

/** Shared request plumbing. Returns [httpCode, decodedJson, transportError]. */
function blobRequest(string $url, string $method, array $headers, ?string $body): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_HTTPHEADER     => array_merge([
            'authorization: Bearer ' . BLOB_TOKEN,
            'x-api-version: ' . BLOB_API_VERSION,
        ], $headers),
    ]);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    $raw  = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $cerr = curl_error($ch);
    // No curl_close(): it has done nothing since PHP 8.0 and is deprecated in
    // 8.5, where the notice would be printed into the page.

    if ($raw === false) {
        return [0, null, $cerr !== '' ? $cerr : 'request failed'];
    }
    return [$code, json_decode((string) $raw, true), null];
}

/**
 * Store a file and return the database value for it ("blob:folder/name"),
 * or null with $error set.
 */
function blobPut(string $pathname, string $sourceFile, string $mime, ?string &$error = null): ?string
{
    if (BLOB_TOKEN === '') {
        $error = 'File storage is not configured for this deployment.';
        return null;
    }

    $bytes = @file_get_contents($sourceFile);
    if ($bytes === false) {
        $error = 'Could not read the uploaded file.';
        return null;
    }

    $pathname = ltrim($pathname, '/');

    // The key goes in the query string, not the path: the API expects
    // PUT /?pathname=<urlencoded>, which is what the official client sends.
    [$code, $json, $transportError] = blobRequest(
        BLOB_API_BASE . '/?' . http_build_query(['pathname' => $pathname]),
        'PUT',
        [
            'x-vercel-blob-access: private',
            'x-content-type: ' . $mime,
            // saveUpload() already gives every file a random name, so the
            // pathname stands as written instead of gaining a second suffix.
            'x-add-random-suffix: 0',
            'content-length: ' . strlen($bytes),
        ],
        $bytes
    );

    if ($transportError !== null) {
        $error = 'Could not reach the file store: ' . $transportError;
        return null;
    }
    if ($code < 200 || $code >= 300 || !is_array($json) || empty($json['url'])) {
        $error = 'The file store rejected the upload (' . blobErrorText($json, $code) . ').';
        return null;
    }
    return BLOB_MARKER . $pathname;
}

/**
 * Send a stored file to the browser and stop. The caller is responsible for
 * deciding that this viewer is allowed to see it.
 */
function blobStream(string $pathname, bool $public): void
{
    $host = blobHost();
    if ($host === null) {
        http_response_code(500);
        exit;
    }

    $url = 'https://' . $host . '/' . implode('/', array_map('rawurlencode', explode('/', ltrim($pathname, '/'))));

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_HTTPHEADER     => ['authorization: Bearer ' . BLOB_TOKEN],
    ]);
    $body  = curl_exec($ch);
    $code  = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $ctype = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if ($body === false || $code < 200 || $code >= 300) {
        http_response_code($code === 404 ? 404 : 502);
        exit;
    }

    header('Content-Type: ' . ($ctype !== '' ? $ctype : 'application/octet-stream'));
    header('Content-Length: ' . strlen((string) $body));
    if ($public) {
        // Site artwork never changes once written, so let the CDN keep it and
        // stop waking this function for every view.
        header('Cache-Control: public, max-age=31536000, immutable');
    } else {
        // Someone else's personal document: no shared cache, ever.
        header('Cache-Control: private, no-store');
    }
    echo $body;
    exit;
}

/** Remove a stored file. Failure is not worth interrupting a save. */
function blobDelete(string $pathname): bool
{
    $host = blobHost();
    if (BLOB_TOKEN === '' || $host === null) {
        return false;
    }
    $url = 'https://' . $host . '/' . ltrim($pathname, '/');
    [$code] = blobRequest(
        BLOB_API_BASE . '/delete',
        'POST',
        ['content-type: application/json'],
        json_encode(['urls' => [$url]])
    );
    return $code >= 200 && $code < 300;
}

/** Pull a human-readable reason out of an API error body. */
function blobErrorText($json, int $code): string
{
    if (is_array($json)) {
        if (isset($json['error']['message'])) {
            return (string) $json['error']['message'];
        }
        if (isset($json['message'])) {
            return (string) $json['message'];
        }
    }
    return 'HTTP ' . $code;
}
