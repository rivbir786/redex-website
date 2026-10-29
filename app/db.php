<?php
/**
 * Veritabanı bağlantısı (PDO) ve şema kurulumu.
 * Varsayılan: SQLite — ek kurulum gerektirmez, sadece storage/ klasörünün yazılabilir olması yeter.
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $cfg = cfg('db');

    if (($cfg['driver'] ?? 'sqlite') === 'mysql') {
        $m = $cfg['mysql'];
        $dsn = "mysql:host={$m['host']};dbname={$m['name']};charset={$m['charset']}";
        $pdo = new PDO($dsn, $m['user'], $m['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } else {
        $path = $cfg['sqlite_path'];
        if (!is_dir(dirname($path))) {
            @mkdir(dirname($path), 0775, true);
        }
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA journal_mode = WAL;');
    }

    db_migrate($pdo);
    return $pdo;
}

function db_migrate(PDO $pdo): void
{
    $isMysql = str_starts_with($pdo->getAttribute(PDO::ATTR_DRIVER_NAME), 'mysql');
    $autoInc = $isMysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';

    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id $autoInc,
        name TEXT, email TEXT, phone TEXT, company TEXT, country TEXT, subject TEXT, message TEXT,
        lang TEXT, source TEXT, ip TEXT, is_read INTEGER DEFAULT 0,
        created_at TEXT
    )");
    // Mevcut kurulumlar için eksik sütunları ekle (hata görmezden gelinir)
    foreach (['country' => 'TEXT', 'subject' => 'TEXT'] as $col => $type) {
        try { $pdo->exec("ALTER TABLE messages ADD COLUMN $col $type"); } catch (Throwable $e) {}
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS applications (
        id $autoInc,
        name TEXT, email TEXT, phone TEXT, position TEXT, gender TEXT,
        marital TEXT, message TEXT, cv_file TEXT, lang TEXT, ip TEXT,
        is_read INTEGER DEFAULT 0, created_at TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS visits (
        id $autoInc,
        path TEXT, lang TEXT, referrer TEXT, ua TEXT, ip_hash TEXT,
        is_bot INTEGER DEFAULT 0, day TEXT, created_at TEXT
    )");
}
