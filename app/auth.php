<?php
/**
 * Session-based authentication.
 * Passwords are hashed with password_hash()/password_verify() — never stored raw.
 */

function startSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_name('DUETCSSESS');
    session_start();
}

/**
 * The logged-in user row, or null.
 *
 * The result is cached for the request. Anything that changes who is signed in
 * must call resetAuthCache() afterwards — otherwise a null cached before login
 * makes isAdmin() report false for the rest of the request.
 */
function currentUser(): ?array
{
    if (array_key_exists('__auth_user', $GLOBALS)) {
        return $GLOBALS['__auth_user'];
    }
    $GLOBALS['__auth_user'] = null;   // set first so logoutUser() cannot recurse

    $id = $_SESSION['user_id'] ?? null;
    if ($id) {
        $row = fetchOne('SELECT * FROM users WHERE id = ? AND status = "active"', [$id]);
        if ($row) {
            $GLOBALS['__auth_user'] = $row;
        } else {
            logoutUser();             // the account was deleted or blocked mid-session
        }
    }
    return $GLOBALS['__auth_user'] ?? null;
}

/** Forget the cached user after a login, logout or role change. */
function resetAuthCache(): void
{
    unset($GLOBALS['__auth_user']);
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

function isAdmin(): bool
{
    $u = currentUser();
    return $u !== null && in_array($u['role'], ['admin', 'moderator'], true);
}

function isSuperAdmin(): bool
{
    $u = currentUser();
    return $u !== null && $u['role'] === 'admin';
}

/** Attempt a login. Returns true on success. */
function attemptLogin(string $identifier, string $password): bool
{
    // Native prepared statements require a distinct placeholder per binding,
    // so the identifier is bound twice under different names.
    $user = fetchOne(
        'SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1',
        ['email' => $identifier, 'username' => $identifier]
    );
    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }
    if ($user['status'] !== 'active') {
        return false;
    }
    // Transparently upgrade legacy hashes.
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        update('users', ['password' => password_hash($password, PASSWORD_DEFAULT)], 'id = :uid', ['uid' => $user['id']]);
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    resetAuthCache();
    update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = :uid', ['uid' => $user['id']]);
    return true;
}

function logoutUser(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    startSession();
    resetAuthCache();
}

/** Gate a page behind login. */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['intended'] = currentRoute();
        flash('Please sign in to continue.', 'warning');
        redirect('login');
    }
}

/** Gate a page behind the admin panel. */
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        flash('You do not have permission to open the admin panel.', 'error');
        redirect('dashboard');
    }
}

/** The membership record for the logged-in user, if any. */
function currentMember(): ?array
{
    $u = currentUser();
    if (!$u) {
        return null;
    }
    return fetchOne('SELECT * FROM members WHERE user_id = ? ORDER BY id DESC LIMIT 1', [$u['id']]);
}
