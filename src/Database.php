<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $path = __DIR__ . '/../database/eshop.db';
            $dsn = 'sqlite:' . $path;
            self::$pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }
        return self::$pdo;
    }
}
