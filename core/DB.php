<?php

/**
 * DB — lightweight PDO wrapper.
 *
 * Usage:
 *   DB::fetchAll('SELECT * FROM users WHERE status = ?', ['active']);
 *   DB::fetch('SELECT * FROM users WHERE id = ?', [$id]);
 *   DB::insert('users', ['name' => 'Alice', 'email' => 'alice@test.com']);
 *   DB::update('users', ['status' => 'inactive'], 'id = ?', [$id]);
 *   DB::delete('users', 'id = ?', [$id]);
 *   DB::query('UPDATE users SET last_login = NOW() WHERE id = ?', [$id]);
 */
class DB
{
    private static ?PDO $pdo = null;

    /** Get (or create) the PDO connection */
    public static function connect(): PDO
    {
        if (self::$pdo !== null) return self::$pdo;

        $cfg = require APP_PATH . '/config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $cfg['host'],
            $cfg['port'] ?? 3306,
            $cfg['database']
        );

        self::$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        return self::$pdo;
    }

    /** Execute a raw prepared statement */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Fetch a single row or null */
    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row !== false ? $row : null;
    }

    /** Fetch all rows */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /** Fetch a single scalar value */
    public static function scalar(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    /**
     * INSERT a row and return the new ID.
     */
    public static function insert(string $table, array $data): int
    {
        $cols         = implode(', ', array_map(fn($k) => "`{$k}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        self::query("INSERT INTO `{$table}` ({$cols}) VALUES ({$placeholders})", array_values($data));
        return (int) self::connect()->lastInsertId();
    }

    /**
     * UPDATE rows matching $where.
     * Returns affected row count.
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set  = implode(', ', array_map(fn($k) => "`{$k}` = ?", array_keys($data)));
        $stmt = self::query(
            "UPDATE `{$table}` SET {$set} WHERE {$where}",
            [...array_values($data), ...$whereParams]
        );
        return $stmt->rowCount();
    }

    /**
     * DELETE rows matching $where.
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        return self::query("DELETE FROM `{$table}` WHERE {$where}", $params)->rowCount();
    }

    /** Last inserted ID */
    public static function lastInsertId(): int
    {
        return (int) self::connect()->lastInsertId();
    }

    /** Check if DB is reachable (used in docs/setup pages) */
    public static function isConnected(): bool
    {
        try {
            self::connect();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
