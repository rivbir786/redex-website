<?php
/**
 * Yerel önizleme yönlendiricisi — SADECE PHP yerleşik sunucusu (php -S) içindir.
 * Üretimde ETKİSİZDİR: Apache/LiteSpeed'de doğrudan çağrılırsa 404 döner.
 * .htaccess'in ön denetleyici davranışını taklit eder: var olan statik dosyayı
 * doğrudan sun, gerisini index.php'ye yönlendir.
 *
 * Kullanım:  php -S 127.0.0.1:8900 router.php   →   http://127.0.0.1:8900/
 */
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false; // statik dosyayı yerleşik sunucu kendi sunsun
}
require __DIR__ . '/index.php';
