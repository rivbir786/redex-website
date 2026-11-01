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

    $pdo->exec("CREATE TABLE IF NOT EXISTS distributors (
        id $autoInc,
        company TEXT, contact_name TEXT, email TEXT, phone TEXT, website TEXT,
        country TEXT, city TEXT, address TEXT, annual_volume TEXT, message TEXT,
        lang TEXT, ip TEXT, is_read INTEGER DEFAULT 0, created_at TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS visits (
        id $autoInc,
        path TEXT, lang TEXT, referrer TEXT, ua TEXT, ip_hash TEXT,
        is_bot INTEGER DEFAULT 0, day TEXT, created_at TEXT
    )");
    // Analitik: ülke + sayfada geçirilen süre (sonradan eklenen sütunlar)
    foreach (['country' => 'TEXT', 'duration' => 'INTEGER DEFAULT 0', 'vtoken' => 'TEXT'] as $col => $type) {
        try { $pdo->exec("ALTER TABLE visits ADD COLUMN $col $type"); } catch (Throwable $e) {}
    }

    // Yönetici kullanıcılar (rol tabanlı: admin / editor)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id $autoInc,
        username TEXT UNIQUE, password_hash TEXT, role TEXT DEFAULT 'editor',
        created_at TEXT, last_login TEXT
    )");

    // Haberler (çok dilli; backend'den yönetilir)
    $pdo->exec("CREATE TABLE IF NOT EXISTS news (
        id $autoInc,
        slug TEXT UNIQUE, image TEXT, published_at TEXT, langs TEXT,
        is_published INTEGER DEFAULT 1, created_at TEXT, updated_at TEXT
    )");

    db_seed($pdo);
}

/** İlk kurulum tohumlaması: haberleri JSON'dan DB'ye, eski tek şifreyi users'a taşı. */
function db_seed(PDO $pdo): void
{
    // 1) Haberler: tablo boşsa app/news-data.json'dan içe aktar
    try {
        $count = (int) $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
        if ($count === 0) {
            $json = __DIR__ . '/news-data.json';
            if (is_file($json)) {
                $items = json_decode(file_get_contents($json), true) ?: [];
                $st = $pdo->prepare("INSERT OR IGNORE INTO news (slug,image,published_at,langs,is_published,created_at,updated_at)
                                     VALUES (?,?,?,?,1,?,?)");
                foreach ($items as $a) {
                    $now = date('c');
                    $st->execute([$a['slug'] ?? '', $a['image'] ?? '', $a['date'] ?? date('Y-m-d'),
                                  json_encode($a['langs'] ?? [], JSON_UNESCAPED_UNICODE), $now, $now]);
                }
            }
        }
    } catch (Throwable $e) {}

    // 1b) Eksik dilleri JSON'dan mevcut haber satırlarına ekle (ör. sonradan eklenen Türkçe).
    //     Yalnızca eksik dil anahtarını ekler; mevcut dilleri (admin düzenlemeleri dahil) ezmez.
    try {
        $json = __DIR__ . '/news-data.json';
        if (is_file($json)) {
            $items = json_decode(file_get_contents($json), true) ?: [];
            $sel = $pdo->prepare("SELECT langs FROM news WHERE slug=?");
            $upd = $pdo->prepare("UPDATE news SET langs=?, updated_at=? WHERE slug=?");
            foreach ($items as $a) {
                $slug = $a['slug'] ?? '';
                if ($slug === '') continue;
                $sel->execute([$slug]);
                $row = $sel->fetch();
                if (!$row) continue;
                $dbLangs   = json_decode($row['langs'] ?: '{}', true) ?: [];
                $jsonLangs = $a['langs'] ?? [];
                $changed = false;
                foreach ($jsonLangs as $lc => $content) {
                    if (!isset($dbLangs[$lc]) && is_array($content)) {
                        $dbLangs[$lc] = $content;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $upd->execute([json_encode($dbLangs, JSON_UNESCAPED_UNICODE), date('c'), $slug]);
                }
            }
        }
    } catch (Throwable $e) {}

    // 2) Kullanıcılar: tablo boşsa, eski tek-şifre kurulumunu 'admin' kullanıcısına taşı
    try {
        $ucount = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($ucount === 0) {
            $hashFile = cfg('admin_hash_file');
            if (is_file($hashFile)) {
                $hash = require $hashFile;
                if (is_string($hash) && $hash !== '') {
                    $st = $pdo->prepare("INSERT OR IGNORE INTO users (username,password_hash,role,created_at) VALUES (?,?,?,?)");
                    $st->execute(['admin', $hash, 'admin', date('c')]);
                }
            }
        }
    } catch (Throwable $e) {}
}
