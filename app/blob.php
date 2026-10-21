<?php
/**
 * Vercel Blob file storage.
 *
 * A serverless instance cannot write to its own filesystem, so uploaded images
 * are stored here instead and the database keeps the URL that comes back.
 * upload() in helpers.php already returns an absolute URL untouched, so views
 * and templates need no change — a record's image column simply holds a URL
 * rather than a bare filename.
 *
 * This speaks the same REST contract as the official @vercel/blob client
 * (API version 13). BLOB_READ_WRITE_TOKEN is set on the deployment
 * automatically once a Blob store is linked to the project; when it is absent
 * — on XAMPP or in Docker — nothing here is used and uploads go to uploads/.
 */

const BLOB_API_BASE    = 'https://vercel.com/api/blob';
const BLOB_API_VERSION = '13';

/** Shared request plumbing. Returns [httpCode, decodedBody, transportError]. */
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
 * Store a file and return its public URL, or null with $error set.
 *
 * $pathname is the key inside the store, e.g. "committee/ab12cd34-1792.jpg".
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

    // The key goes in the query string, not the path: the API expects
    // PUT /?pathname=<urlencoded>, which is what the official client sends.
    [$code, $json, $transportError] = blobRequest(
        BLOB_API_BASE . '/?' . http_build_query(['pathname' => ltrim($pathname, '/')]),
        'PUT',
        [
            'x-vercel-blob-access: public',
            'x-content-type: ' . $mime,
            // saveUpload() already gives every file a random name, so the
            // pathname stands as written instead of gaining a second suffix.
            'x-add-random-suffix: 0',
            'x-cache-control-max-age: 31536000',
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
    return (string) $json['url'];
}

/** Remove a stored file by its URL. Failure is not worth interrupting a save. */
function blobDelete(string $url): bool
{
    if (BLOB_TOKEN === '') {
        return false;
    }
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
