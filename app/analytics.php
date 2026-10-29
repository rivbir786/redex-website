<?php
/** Basit, gizliliğe saygılı ziyaretçi kaydı. IP ham saklanmaz, günlük bazında hash'lenir. */

function log_visit(string $path, string $lang): void
{
    try {
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 300);
        $isBot = preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|crawler|wget|curl|python-requests/i', $ua) ? 1 : 0;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $day = date('Y-m-d');
        // IP'yi gün + gizli tuz ile hash'le (aynı ziyaretçiyi tekilleştirmek için, kimliği ifşa etmeden)
        $ipHash = substr(hash('sha256', $ip . '|' . $day . '|redex-salt'), 0, 24);
        $ref = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 300);

        $st = db()->prepare("INSERT INTO visits (path, lang, referrer, ua, ip_hash, is_bot, day, created_at)
                             VALUES (?,?,?,?,?,?,?,?)");
        $st->execute([$path, $lang, $ref, $ua, $ipHash, $isBot, $day, date('c')]);
    } catch (Throwable $e) {
        // istatistik hatası siteyi durdurmaz
    }
}
