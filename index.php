<?php
/**
 * Redex Glass — Ön denetleyici (front controller).
 * Tüm istekler .htaccess ile buraya yönlenir. URL yapısı: /{dil}/{rota}
 */

session_start();

require __DIR__ . '/app/helpers.php';
require __DIR__ . '/app/i18n.php';
require __DIR__ . '/app/db.php';
require __DIR__ . '/app/analytics.php';
require __DIR__ . '/app/content.php';

date_default_timezone_set(cfg('timezone'));
mb_internal_encoding('UTF-8');

// --- URL'yi çöz ---
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$uri  = urldecode($uri);

// Alt klasörde çalışıyorsa taban yolu çıkar (otomatik)
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir));
}
$uri = '/' . ltrim($uri, '/');

// --- Dilden bağımsız özel rotalar ---
if ($uri === '/robots.txt') { render_robots(); exit; }
if ($uri === '/sitemap.xml') { render_sitemap(); exit; }

// --- Dil önekini ayır ---
$segments = array_values(array_filter(explode('/', trim($uri, '/')), fn($s) => $s !== ''));
$langs = array_keys(cfg('languages'));
$uriLang = $segments[0] ?? '';

if (!in_array($uriLang, $langs, true)) {
    // Dil yok → tercih edilen/varsayılan dile yönlendir
    $target = detect_preferred_lang();
    $rest = trim($uri, '/');
    header('Location: ' . base_url() . '/' . $target . ($rest ? '/' . $rest : '/'), true, 302);
    exit;
}

$GLOBALS['__lang'] = $uriLang;
array_shift($segments);
$route = implode('/', $segments);
$GLOBALS['__route'] = $route;

// --- POST işleyicileri ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_post($route);
    exit;
}

// --- Ziyaretçi kaydı (botları işaretleyerek) ---
log_visit('/' . $route, lang());

// --- Yönlendirme tablosu ---
$page = null; $data = [];

if ($route === '' ) { $page = 'home'; }
elseif ($route === 'about') { $page = 'about'; }
elseif ($route === 'mission-vision') { $page = 'mission'; }
elseif ($route === 'sustainability') { $page = 'sustainability'; }
elseif ($route === 'quality') { $page = 'quality'; }
elseif ($route === 'certificates') { $page = 'certificates'; }
elseif ($route === 'products') { $page = 'products'; }
elseif (preg_match('#^products/([a-z0-9\-]+)$#', $route, $m)) {
    $prod = find_product($m[1]);
    if ($prod) { $page = 'product'; $data['product'] = $prod; }
}
elseif ($route === 'services') { $page = 'services'; }
elseif (preg_match('#^services/([a-z0-9\-]+)$#', $route, $m)) {
    $svcs = content_services();
    if (isset($svcs[$m[1]])) { $page = 'service'; $data['service'] = $svcs[$m[1]]; $data['service_key'] = $m[1]; }
}
elseif ($route === 'careers') { $page = 'careers'; }
elseif ($route === 'news') { $page = 'news'; }
elseif ($route === 'contact') { $page = 'contact'; }
elseif ($route === 'privacy-policy') { $page = 'privacy'; }

if ($page === null) {
    http_response_code(404);
    $page = '404';
}

// SEO meta'yı hazırla
$data['seo'] = build_seo($page, $route, $data);

// Görünümü çiz
$viewFile = __DIR__ . '/app/views/pages/' . $page . '.php';
render_layout($viewFile, $data);


/* ================= Fonksiyonlar ================= */

function detect_preferred_lang(): string
{
    $langs = array_keys(cfg('languages'));
    $al = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    foreach (explode(',', $al) as $part) {
        $code = strtolower(trim(substr($part, 0, 2)));
        if (in_array($code, $langs, true)) return $code;
    }
    return cfg('default_lang');
}

function render_layout(string $viewFile, array $data): void
{
    extract($data);
    $__view = $viewFile;
    require __DIR__ . '/app/views/layout.php';
}

function build_seo(string $page, string $route, array $data): array
{
    $site = cfg('site_name');
    $titles = [
        'home' => t('home.hero_title') . ' | ' . $site,
        'about' => t('about.title') . ' | ' . $site,
        'mission' => t('mission.title') . ' | ' . $site,
        'sustainability' => t('sustainability.title') . ' | ' . $site,
        'quality' => t('quality.title') . ' | ' . $site,
        'certificates' => t('certificates.title') . ' | ' . $site,
        'products' => t('products.title') . ' | ' . $site,
        'services' => t('services.title') . ' | ' . $site,
        'careers' => t('careers.title') . ' | ' . $site,
        'news' => t('news.title') . ' | ' . $site,
        'contact' => t('contact.title') . ' | ' . $site,
        'privacy' => t('privacy.title') . ' | ' . $site,
        '404' => 'Not Found | ' . $site,
    ];
    $descs = [
        'home' => t('meta.home_desc'), 'about' => t('meta.about_desc'),
        'products' => t('meta.products_desc'), 'contact' => t('meta.contact_desc'),
        'sustainability' => t('meta.sustainability_desc'), 'quality' => t('meta.quality_desc'),
        'careers' => t('meta.careers_desc'),
        'mission' => t('mission.mission_body'), 'certificates' => t('certificates.lead'),
        'services' => t('services.lead'), 'news' => t('news.lead'),
    ];

    $title = $titles[$page] ?? $site;
    $desc  = $descs[$page] ?? t('meta.home_desc');

    if ($page === 'product' && !empty($data['product'])) {
        $title = loc($data['product']['name']) . ' | ' . $site;
        $desc  = loc($data['product']['excerpt']);
    }
    if ($page === 'service' && !empty($data['service'])) {
        $title = loc($data['service']['title']) . ' | ' . $site;
        $desc  = loc($data['service']['excerpt']);
    }

    // hreflang alternatifleri
    $alts = [];
    foreach (array_keys(cfg('languages')) as $l) {
        $alts[$l] = url($route, $l);
    }

    return [
        'title' => $title,
        'description' => mb_substr(trim(preg_replace('/\s+/', ' ', $desc)), 0, 180),
        'canonical' => url($route),
        'alternates' => $alts,
        'og_image' => asset('img/og-default.jpg'),
    ];
}

function render_robots(): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /admin/\n";
    echo "Disallow: /storage/\n\n";
    echo "Sitemap: " . base_url() . "/sitemap.xml\n";
}

function render_sitemap(): void
{
    header('Content-Type: application/xml; charset=utf-8');
    $routes = ['', 'about', 'mission-vision', 'sustainability', 'quality', 'certificates',
               'products', 'services', 'careers', 'news', 'contact', 'privacy-policy'];
    foreach (content_products() as $p) $routes[] = 'products/' . $p['slug'];
    foreach (array_keys(content_services()) as $k) $routes[] = 'services/' . $k;

    $langs = array_keys(cfg('languages'));
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
    foreach ($routes as $r) {
        foreach ($langs as $l) {
            echo "  <url>\n    <loc>" . e(url($r, $l)) . "</loc>\n";
            foreach ($langs as $alt) {
                echo '    <xhtml:link rel="alternate" hreflang="' . $alt . '" href="' . e(url($r, $alt)) . "\"/>\n";
            }
            echo '    <xhtml:link rel="alternate" hreflang="x-default" href="' . e(url($r, cfg('default_lang'))) . "\"/>\n";
            echo "  </url>\n";
        }
    }
    echo '</urlset>';
}

function handle_post(string $route): void
{
    if (!csrf_check()) {
        flash_redirect($route, 'error', t('form.error'));
    }
    // Bal küpü (spam botları)
    if (!empty($_POST['website'])) {
        flash_redirect($route, 'success', t('form.success'));
    }

    if ($route === 'contact') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $msg = trim($_POST['message'] ?? '');
        if ($name === '' || $email === '' || $msg === '') {
            flash_redirect($route, 'error', t('form.required'));
        }
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $st = db()->prepare("INSERT INTO messages (name,email,phone,company,country,subject,message,lang,source,ip,created_at)
                             VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $st->execute([$name, $email, $phone, $company, $country, $subject,
                      $msg, lang(), 'contact', $_SERVER['REMOTE_ADDR'] ?? '', date('c')]);
        // info@redexglass.com adresine e-posta (müşteriye yanıt için Reply-To ayarlı)
        $body = "New contact message from the website\n"
              . "----------------------------------------\n"
              . "Name:    $name\n"
              . "Company: $company\n"
              . "Email:   $email\n"
              . "Phone:   $phone\n"
              . "Country: $country\n"
              . "Subject: $subject\n"
              . "Language: " . strtoupper(lang()) . "\n"
              . "----------------------------------------\n"
              . "Message:\n$msg\n";
        notify_email('[Website] ' . ($subject ?: 'New message') . ' — ' . $name, $body, $email, $name);
        flash_redirect($route, 'success', t('form.success'));
    }

    if ($route === 'careers') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if ($name === '' || $email === '') {
            flash_redirect($route, 'error', t('form.required'));
        }
        $cvFile = handle_cv_upload();
        $st = db()->prepare("INSERT INTO applications (name,email,phone,position,gender,marital,message,cv_file,lang,ip,created_at)
                             VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $st->execute([$name, $email, trim($_POST['phone'] ?? ''), trim($_POST['position'] ?? ''),
                      trim($_POST['gender'] ?? ''), trim($_POST['marital'] ?? ''),
                      trim($_POST['message'] ?? ''), $cvFile, lang(), $_SERVER['REMOTE_ADDR'] ?? '', date('c')]);
        notify_email('New job application from ' . $name, "Name: $name\nEmail: $email\nPosition: " . ($_POST['position'] ?? ''));
        flash_redirect($route, 'success', t('form.app_success'));
    }

    flash_redirect($route, 'error', t('form.error'));
}

function handle_cv_upload(): string
{
    if (empty($_FILES['cv']['name']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) return '';
    $f = $_FILES['cv'];
    if ($f['size'] > 5 * 1024 * 1024) return '';
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'], true)) return '';
    $dir = __DIR__ . '/storage/uploads';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $fname = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], $dir . '/' . $fname)) return $fname;
    return '';
}

function notify_email(string $subject, string $body, string $replyTo = '', string $replyName = ''): void
{
    $to = cfg('notify_email');
    if (!$to) return;
    $host = $_SERVER['HTTP_HOST'] ?? 'redexglass.com';
    $headers = "From: Redex Website <no-reply@" . preg_replace('/^www\./', '', $host) . ">\r\n";
    if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $rn = $replyName ? '=?UTF-8?B?' . base64_encode($replyName) . '?= ' : '';
        $headers .= "Reply-To: {$rn}<{$replyTo}>\r\n";
    }
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $subjectEnc = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    @mail($to, $subjectEnc, $body, $headers);
}

function flash_redirect(string $route, string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    header('Location: ' . url($route) . '#form', true, 303);
    exit;
}
