<?php
/**
 * Serves files kept in the private Blob store.
 *
 * The store answers 403 to anyone without the project's token, so a file is
 * only reachable through here — which means access can be decided per folder
 * instead of relying on a URL being hard to guess.
 *
 * Site artwork (committee portraits, event banners, gallery photos) is open to
 * everyone, exactly as it is on the pages that show it. The two folders that
 * hold other people's personal documents are not: a payment screenshot carries
 * a name, a phone number and a transaction id, so only an administrator may
 * open one, and a member's own photo is visible to that member and to
 * administrators.
 */

require_once BASE_PATH . '/app/blob.php';

// index.php has already stripped anything outside [A-Za-z0-9/_-], so a dot
// cannot reach here; the pattern below is what allows the extension back, and
// nothing else. No traversal is possible: no "." segment can survive both.
$rel = implode('/', $params);
if (!preg_match('#^([a-z_]+)/([A-Za-z0-9_-]+\.[A-Za-z0-9]{1,5})$#', $rel, $m)) {
    notFound();
}
[$all, $folder, $name] = $m;

$isPrivate = in_array($folder, BLOB_PRIVATE_FOLDERS, true);

if ($isPrivate) {
    $user = currentUser();
    $stored = BLOB_MARKER . $folder . '/' . $name;
    $allowed = isAdmin();

    // A member may see their own photo, nobody else's. Payment screenshots are
    // for administrators only -- the person who uploaded one has no page that
    // shows it back to them, so there is nothing to grant here.
    if (!$allowed && $folder === 'members' && $user) {
        $allowed = ($user['avatar'] ?? null) === $stored
            || fetchValue('SELECT 1 FROM members WHERE user_id = ? AND photo = ?',
                          [$user['id'], $stored]) !== null;
    }

    if (!$allowed) {
        notFound();   // not 403: whether the file exists is itself not public
    }
}

blobStream($folder . '/' . $name, !$isPrivate);
