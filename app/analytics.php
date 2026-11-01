<?php
/** Basit, gizliliğe saygılı ziyaretçi kaydı. IP ham saklanmaz, günlük bazında hash'lenir.
 *  Ülke (CDN başlıkları / opsiyonel GeoIP) ve sayfada geçirilen süre (JS beacon) desteklenir. */

/** Ziyaretçinin ülkesi — harici çağrı yapmadan. CDN/proxy başlıkları + (varsa) PHP geoip eklentisi. */
function geo_country(string $ip): string
{
    // 1) CDN/proxy başlıkları (Cloudflare: CF-IPCountry, App Engine, genel proxy'ler)
    foreach (['HTTP_CF_IPCOUNTRY', 'HTTP_X_COUNTRY_CODE', 'HTTP_X_GEO_COUNTRY', 'HTTP_X_APPENGINE_COUNTRY'] as $h) {
        $v = $_SERVER[$h] ?? '';
        if ($v && strlen($v) === 2 && ctype_alpha($v) && strtoupper($v) !== 'XX') {
            return strtoupper($v);
        }
    }
    // 2) Sunucuda GeoIP eklentisi varsa (opsiyonel, harici çağrı yok)
    if (function_exists('geoip_country_code_by_name') && $ip) {
        $c = @geoip_country_code_by_name($ip);
        if ($c) return strtoupper($c);
    }
    return '';
}

function log_visit(string $path, string $lang): string
{
    // Çerez onayı: "Statistics" kabul edilmediyse kayıt tutulmaz (GDPR/ePrivacy).
    $consent = json_decode($_COOKIE['redex_consent'] ?? '', true);
    if (!is_array($consent) || empty($consent['statistics'])) {
        return '';
    }
    $token = '';
    try {
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 300);
        $isBot = preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|crawler|wget|curl|python-requests/i', $ua) ? 1 : 0;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $day = date('Y-m-d');
        // IP'yi gün + gizli tuz ile hash'le (tekilleştirme için, kimliği ifşa etmeden)
        $ipHash = substr(hash('sha256', $ip . '|' . $day . '|redex-salt'), 0, 24);
        $ref = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 300);
        $country = $isBot ? '' : geo_country($ip);
        $token = bin2hex(random_bytes(9)); // süre beacon'ı bu ziyareti bulsun diye

        $st = db()->prepare("INSERT INTO visits (path, lang, referrer, ua, ip_hash, is_bot, day, country, vtoken, created_at)
                             VALUES (?,?,?,?,?,?,?,?,?,?)");
        $st->execute([$path, $lang, $ref, $ua, $ipHash, $isBot, $day, $country, $token, date('c')]);
    } catch (Throwable $e) {
        // istatistik hatası siteyi durdurmaz
    }
    return $token;
}

/** Sayfada geçirilen süreyi (saniye) ilgili ziyarete yaz (JS beacon'dan gelir). */
function record_visit_time(string $token, int $seconds): void
{
    if ($token === '' || $seconds <= 0 || $seconds > 3600) return;
    if (!preg_match('/^[a-f0-9]{10,24}$/', $token)) return;
    try {
        $st = db()->prepare("UPDATE visits SET duration=? WHERE vtoken=? AND (duration IS NULL OR duration < ?)");
        $st->execute([$seconds, $token, $seconds]);
    } catch (Throwable $e) {}
}
