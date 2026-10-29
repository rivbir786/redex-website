<?php
/** Yardımcı fonksiyonlar: yapılandırma, kaçış, URL üretimi, dil, SEO, CSRF. */

function cfg(?string $key = null)
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    if ($key === null) return $config;
    return $config[$key] ?? null;
}

/** HTML kaçışı */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/** Taban URL'yi otomatik algıla (config boşsa) */
function base_url(): string
{
    $set = cfg('base_url');
    if ($set) return $set;
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

/** Aktif dil */
function lang(): string
{
    return $GLOBALS['__lang'] ?? cfg('default_lang');
}

function lang_dir(): string
{
    return cfg('languages')[lang()]['dir'] ?? 'ltr';
}

/** Bir sayfa için çok dilli URL üret: url('products') => /en/products */
function url(string $path = '', ?string $forLang = null): string
{
    $l = $forLang ?: lang();
    $path = ltrim($path, '/');
    return base_url() . '/' . $l . ($path ? '/' . $path : '');
}

/** Statik varlık URL'si */
function asset(string $path): string
{
    return base_url() . '/assets/' . ltrim($path, '/');
}

/** Aktif menü için yardımcı */
function is_active(string $path): bool
{
    $cur = trim($GLOBALS['__route'] ?? '', '/');
    return $cur === trim($path, '/');
}

/** CSRF */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): bool
{
    return isset($_POST['_csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['_csrf']);
}

/** Basit slug */
function slugify(string $s): string
{
    $s = preg_replace('/[^\p{L}\p{N}]+/u', '-', $s);
    return strtolower(trim($s, '-'));
}

/** İç sayfa başlığı + breadcrumb */
function page_head(string $title, string $eyebrow = '', array $crumbs = []): void
{
    echo '<section class="page-head"><div class="container">';
    echo '<div class="crumbs"><a href="' . e(url('')) . '">' . e(t('nav.home')) . '</a>';
    foreach ($crumbs as $label => $href) {
        echo ' / ' . ($href ? '<a href="' . e($href) . '">' . e($label) . '</a>' : e($label));
    }
    echo ' / ' . e($title) . '</div>';
    if ($eyebrow) echo '<p class="eyebrow">' . e($eyebrow) . '</p>';
    echo '<h1>' . e($title) . '</h1>';
    echo '</div></section>';
}

/** Flash mesajı (form sonrası) */
function flash_html(): string
{
    if (empty($_SESSION['flash'])) return '';
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<div class="flash ' . e($f['type']) . '">' . e($f['msg']) . '</div>';
}
