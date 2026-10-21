<?php
/**
 * Database-backed session storage.
 *
 * PHP's default handler keeps sessions as files in a temp directory. That is
 * fine on XAMPP or in Docker, where every request hits the same machine, but
 * it breaks on serverless hosting (Vercel): each request may be served by a
 * fresh instance with its own empty filesystem, so a session written by one
 * request is simply not there for the next one. The visible symptom is a login
 * that appears to succeed and then forgets you, and CSRF checks that fail at
 * random.
 *
 * Storing the session payload in MySQL makes it shared state, which every
 * instance can see. The handler is only installed when the app detects a
 * read-only/ephemeral filesystem (see startSession() in auth.php), so local
 * development keeps using plain files and needs no database table.
 */

final class DbSessionHandler implements SessionHandlerInterface, SessionUpdateTimestampHandlerInterface
{
    private int $ttl;

    public function __construct(int $ttl = 0)
    {
        // Fall back to PHP's own garbage-collection lifetime (default 1440s).
        $this->ttl = $ttl > 0 ? $ttl : (int) (ini_get('session.gc_maxlifetime') ?: 1440);
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $row = fetchOne(
            'SELECT payload FROM sessions WHERE id = ? AND last_activity > ?',
            [$id, time() - $this->ttl]
        );
        return $row === null ? '' : (string) $row['payload'];
    }

    public function write(string $id, string $data): bool
    {
        // One statement, so two concurrent requests cannot race between a
        // SELECT and an INSERT and hit the primary key.
        q(
            'INSERT INTO sessions (id, payload, last_activity) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE payload = VALUES(payload), last_activity = VALUES(last_activity)',
            [$id, $data, time()]
        );
        return true;
    }

    public function destroy(string $id): bool
    {
        q('DELETE FROM sessions WHERE id = ?', [$id]);
        return true;
    }

    /** @return int|false */
    #[\ReturnTypeWillChange]
    public function gc(int $maxLifetime)
    {
        return q('DELETE FROM sessions WHERE last_activity < ?', [time() - $maxLifetime])->rowCount();
    }

    /** Tells PHP whether an id already exists, so it never reuses a live one. */
    public function validateId(string $id): bool
    {
        return fetchValue('SELECT 1 FROM sessions WHERE id = ?', [$id]) !== null;
    }

    /** Called instead of write() when the payload has not changed. */
    public function updateTimestamp(string $id, string $data): bool
    {
        q('UPDATE sessions SET last_activity = ? WHERE id = ?', [time(), $id]);
        return true;
    }
}
