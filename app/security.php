<?php
/**
 * Güvenlik katmanı: oturum sertleştirme, güvenlik başlıkları (CSP nonce),
 * hız sınırlama (throttle), HTML temizleme, hata gizleme, host doğrulama.
 * index.php ve admin/index.php en başta bunu yükler.
 */

/* ---------- Hata gösterimi: üretimde asla ekrana basma, dosyaya yaz ---------- */
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/storage/php-error.log');
error_reporting(E_ALL);
header_remove('X-Powered-By');

set_exception_handler(function (Throwable $e) {
    error_log('[redex] ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) { http_response_code(500); header('Content-Type: text/html; charset=utf-8'); }
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Error</title></head><body style="font-family:system-ui;padding:40px">'
       . '<h1>Something went wrong</h1><p>Please try again later.</p></body></html>';
    exit;
});

/* ---------- HTTPS tespiti ---------- */
function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') == 443)
        || (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

/* ---------- Host başlığı doğrulama (Host header injection'a karşı) ---------- */
function request_host(): string
{
    $host = strtolower(trim($_SERVER['HTTP_HOST'] ?? ''));
    $allowed = cfg('allowed_hosts') ?: [];
    if (!$allowed) return $host ?: 'localhost';
    $bare = preg_replace('/:\d+$/', '', $host);
    foreach ($allowed as $a) {
        if ($bare === $a) return $host;
    }
    return $allowed[0]; // bilinmeyen Host -> kanonik alan adı
}

/* ---------- Oturum: HttpOnly + Secure + SameSite + strict mode ---------- */
function secure_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.gc_maxlifetime', '7200');
    session_name('redexsid');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'domain' => '',
        'secure' => is_https(), 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

/* ---------- CSP nonce ---------- */
function csp_nonce(): string
{
    static $n = null;
    if ($n === null) $n = base64_encode(random_bytes(16));
    return $n;
}

/* ---------- Güvenlik başlıkları (HTML yanıtları için; statikler .htaccess'ten) ---------- */
function security_headers(bool $admin = false): void
{
    if (headers_sent()) return;
    $nonce = csp_nonce();
    $csp = [
        "default-src 'self'",
        "script-src 'self' 'nonce-{$nonce}'",
        "style-src 'self' 'unsafe-inline'",
        "img-src 'self' data: https://i.ytimg.com",
        "font-src 'self'",
        "media-src 'self'",
        "frame-src https://www.youtube-nocookie.com",
        "connect-src 'self'",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'none'",
    ];
    if (is_https()) $csp[] = "upgrade-insecure-requests";
    if ($admin) {
        $csp[2] = "style-src 'self' 'unsafe-inline'"; $csp[3] = "img-src 'self' data:"; $csp[6] = "frame-src 'none'";
    }
    header('Content-Security-Policy: ' . implode('; ', $csp));
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    if (is_https()) header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    if ($admin) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Robots-Tag: noindex, nofollow');
    }
}

/* ---------- İstemci IP'si ---------- */
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/* ---------- Hız sınırlama: $max istek / $window saniye (SQLite) ---------- */
function throttle(string $key, int $max, int $window): bool
{
    try {
        $pdo = db();
        $now = time();
        $k = hash('sha256', $key);
        $pdo->prepare("DELETE FROM throttle WHERE exp < ?")->execute([$now - 86400]);
        $st = $pdo->prepare("SELECT n, exp FROM throttle WHERE k=?"); $st->execute([$k]); $row = $st->fetch();
        if (!$row || (int)$row['exp'] < $now) {
            $pdo->prepare("INSERT OR REPLACE INTO throttle (k,n,exp) VALUES (?,?,?)")->execute([$k, 1, $now + $window]);
            return true;
        }
        if ((int)$row['n'] >= $max) return false;
        $pdo->prepare("UPDATE throttle SET n=n+1 WHERE k=?")->execute([$k]);
        return true;
    } catch (Throwable $e) {
        return true; // sınırlayıcı arızası siteyi durdurmaz
    }
}
function throttle_reset(string $key): void
{
    try { db()->prepare("DELETE FROM throttle WHERE k=?")->execute([hash('sha256', $key)]); } catch (Throwable $e) {}
}

/* ---------- Yüklenen dosyanın gerçek türü (uzantı değil, içerik) ---------- */
function upload_mime(string $tmp): string
{
    if (!is_file($tmp)) return '';
    $fi = new finfo(FILEINFO_MIME_TYPE);
    return (string) $fi->file($tmp);
}

/* ---------- Metin girdisi: kırp + uzunluk sınırla + kontrol karakterlerini at ---------- */
function clean_text(?string $s, int $max = 500, bool $multiline = false): string
{
    $s = trim((string) $s);
    $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s) ?? '';
    if (!$multiline) $s = preg_replace('/[\r\n]+/', ' ', $s) ?? ''; // tek satirli alanlarda satir sonu yok (e-posta baslik enjeksiyonu)
    if (!mb_check_encoding($s, 'UTF-8')) $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8');
    return mb_substr($s, 0, $max);
}

/* ---------- Haber gövdesi HTML temizleyici (beyaz liste) ---------- */
function safe_html(string $html): string
{
    if (trim($html) === '') return '';
    $allowedTags = ['p','br','h2','h3','h4','ul','ol','li','b','strong','i','em','u','a','img','blockquote',
                    'table','thead','tbody','tr','th','td','figure','figcaption','span','div','hr','small','sub','sup'];
    $allowedAttrs = ['href','src','alt','title','class','width','height','target','rel','loading'];
    $prev = libxml_use_internal_errors(true);
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->loadHTML('<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>', LIBXML_NONET | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors(); libxml_use_internal_errors($prev);
    $root = $doc->getElementById('__root');
    if (!$root) return '';
    $walk = function (DOMNode $node) use (&$walk, $allowedTags, $allowedAttrs) {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $c = $node->childNodes->item($i);
            if ($c instanceof DOMElement) {
                $tag = strtolower($c->tagName);
                if (!in_array($tag, $allowedTags, true)) {
                    if (in_array($tag, ['script','style','iframe','object','embed','form','input','button','textarea','select','link','meta','svg','math'], true)) {
                        $node->removeChild($c); continue; // tehlikeli: içeriğiyle birlikte at
                    }
                    while ($c->firstChild) $node->insertBefore($c->firstChild, $c); // bilinmeyen etiket: sadece sarmalı at
                    $node->removeChild($c); continue;
                }
                foreach (iterator_to_array($c->attributes) as $attr) {
                    $an = strtolower($attr->name); $av = trim($attr->value);
                    if (!in_array($an, $allowedAttrs, true) || str_starts_with($an, 'on')) { $c->removeAttribute($attr->name); continue; }
                    if (($an === 'href' || $an === 'src') && !preg_match('#^(https?://|/|\#|mailto:)#i', $av)) { $c->removeAttribute($attr->name); continue; }
                    if ($an === 'src' && $tag === 'img' && !preg_match('#^(https?://|/)#i', $av)) { $c->removeAttribute($attr->name); }
                }
                if ($tag === 'a' && $c->getAttribute('target') === '_blank') $c->setAttribute('rel', 'noopener noreferrer nofollow');
                $walk($c);
            } elseif (!($c instanceof DOMText)) {
                $node->removeChild($c); // yorum, CDATA, PI vb.
            }
        }
    };
    $walk($root);
    $out = '';
    foreach ($root->childNodes as $c) $out .= $doc->saveHTML($c);
    return $out;
}
