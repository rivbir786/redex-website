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
require __DIR__ . '/app/redirects.php';

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
// Sayfada geçirilen süre beacon'ı (JS sendBeacon) — dil ön ekinden önce yakala
if ($uri === '/_track' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    record_visit_time((string)($_POST['t'] ?? ''), (int)($_POST['s'] ?? 0));
    http_response_code(204); exit;
}

// --- Eski WordPress URL'leri -> yeni yapı (kalıcı 301; SEO değerini korur) ---
if (($legacy = legacy_redirect($uri)) !== null) {
    header('Location: ' . $legacy, true, 301);
    exit;
}

// --- Sondaki eğik çizgiyi normalize et (/en/about/ -> /en/about; yinelenen URL'leri önler) ---
if ($uri !== '/' && str_ends_with($uri, '/')) {
    $qs = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: ' . base_url() . rtrim($uri, '/') . $qs, true, 301);
    exit;
}

// --- Dil önekini ayır ---
$segments = array_values(array_filter(explode('/', trim($uri, '/')), fn($s) => $s !== ''));
$langs = array_keys(cfg('languages'));
$uriLang = $segments[0] ?? '';

if (!in_array($uriLang, $langs, true)) {
    // Dil yok → tercih edilen/varsayılan dile yönlendir
    $target = detect_preferred_lang();
    $rest = trim($uri, '/');
    header('Location: ' . base_url() . '/' . $target . ($rest ? '/' . $rest : ''), true, 302);
    exit;
}

$GLOBALS['__lang'] = $uriLang;
array_shift($segments);
$route = implode('/', $segments);
$GLOBALS['__route'] = $route;

// --- Kaldırılan servis sayfaları → /services (kalıcı 301 yönlendirme, SEO) ---
if ($route === 'services/fast-delivery' || $route === 'services/quality-assurance') {
    header('Location: ' . url('services'), true, 301);
    exit;
}

// --- POST işleyicileri ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_post($route);
    exit;
}

// --- Ziyaretçi kaydı (botları işaretleyerek) ---
$GLOBALS['__vtoken'] = log_visit('/' . $route, lang());

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
elseif ($route === 'distributors') { $page = 'distributors'; }
elseif ($route === 'news') { $page = 'news'; }
elseif (preg_match('#^news/([a-z0-9\-]+)$#', $route, $m)) {
    $art = find_news($m[1]);
    if ($art) { $page = 'news-article'; $data['article'] = $art; }
}
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

// Meta description'i kelime sinirinda kirpar (SEO: Google ~160 karakter gosterir;
// kelime ortasindan kesmez, kesilirse "…" ekler). Bosluksuz diller (zh vb.) icin sert kirpar.
function seo_clip(string $s, int $max = 160): string
{
    $s = trim(preg_replace('/\s+/', ' ', $s));
    if (mb_strlen($s) <= $max) return $s;
    $cut = mb_substr($s, 0, $max);
    $sp = mb_strrpos($cut, ' ');
    if ($sp !== false && $sp >= $max * 0.6) $cut = mb_substr($cut, 0, $sp);
    return rtrim($cut, " ,.;:—-") . '…';
}

// <title> 60 karakteri asarsa marka ekini dusur (marka og:site_name ile zaten tasinir).
function seo_title(string $t, int $max = 60): string
{
    $suffix = ' | ' . cfg('site_name');
    if (mb_strlen($t) <= $max || !str_ends_with($t, $suffix)) return $t;
    return mb_substr($t, 0, mb_strlen($t) - mb_strlen($suffix));
}

// Istege bagli ceviri anahtari: yoksa null (t() anahtarin kendisini dondurur, bu yuzden ayri).
function t_opt(string $key): ?string
{
    $v = lang_get(load_lang(lang()), $key);
    if ($v === null && lang() !== cfg('default_lang')) $v = lang_get(load_lang(cfg('default_lang')), $key);
    return ($v === null || $v === '') ? null : $v;
}

// hreflang kodu: config'de 'hreflang' tanimliysa onu kullan (or. zh -> zh-Hans), yoksa dil kodu.
function hreflang_code(string $l): string
{
    return cfg('languages')[$l]['hreflang'] ?? $l;
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
        'distributors' => t('distributors.title') . ' | ' . $site,
        'news' => t('news.title') . ' | ' . $site,
        'contact' => t('contact.title') . ' | ' . $site,
        'privacy' => t('privacy.title') . ' | ' . $site,
        '404' => 'Not Found | ' . $site,
    ];
    $descs = [
        'home' => t('meta.home_desc'), 'about' => t('meta.about_desc'),
        'products' => t('meta.products_desc'), 'contact' => t('meta.contact_desc'),
        'sustainability' => t('sustainability.meta_desc'), 'quality' => t('meta.quality_desc'),
        'careers' => t('meta.careers_desc'),
        'distributors' => t('distributors.lead'),
        'mission' => t('mission.meta_desc'), 'certificates' => t('certificates.lead'),
        'services' => t('services.lead'), 'news' => t('news.lead'),
    ];

    $title = $titles[$page] ?? $site;
    $desc  = $descs[$page] ?? t('meta.home_desc');
    // Sayfa basina anahtar kelimeli SEO basligi/aciklamasi tanimliysa (meta.{sayfa}_title / _desc) onu kullan
    if (($o = t_opt("meta.{$page}_title")) !== null) $title = $o . ' | ' . $site;
    if (($o = t_opt("meta.{$page}_desc"))  !== null) $desc  = $o;
    if ($page === 'privacy') $desc = t_opt('meta.privacy_desc') ?? (t('privacy.title') . ' — ' . $site);
    $ogImage = asset('img/og-default.jpg');
    $ogType  = 'website';

    if ($page === 'product' && !empty($data['product'])) {
        $title = loc($data['product']['name']) . ' | ' . $site;
        $desc  = loc($data['product']['excerpt']);
        // Urune ozel SEO basligi/aciklamasi (content.php: seo_title / seo_desc loc dizileri) varsa onu kullan
        if (!empty($data['product']['seo_title'])) $title = loc($data['product']['seo_title']) . ' | ' . $site;
        if (!empty($data['product']['seo_desc']))  $desc  = loc($data['product']['seo_desc']);
        if (!empty($data['product']['img'])) $ogImage = $data['product']['img'];
    }
    if ($page === 'service' && !empty($data['service'])) {
        $title = loc($data['service']['title']) . ' | ' . $site;
        $desc  = loc($data['service']['excerpt']);
        if (!empty($data['service']['img'])) $ogImage = $data['service']['img'];
    }
    if ($page === 'news-article' && !empty($data['article'])) {
        $tr = news_tr($data['article']);
        $title = $tr['title'] . ' | ' . $site;
        $desc  = $tr['excerpt'];
        $ogType = 'article';
        if (!empty($data['article']['image'])) $ogImage = asset($data['article']['image']);
    }

    // hreflang alternatifleri
    $alts = [];
    foreach (array_keys(cfg('languages')) as $l) {
        $alts[$l] = url($route, $l);
    }

    // --- Yapisal veri (JSON-LD) ---
    $orgId = base_url() . '/#organization';
    $org = [
        '@type' => 'Organization', '@id' => $orgId,
        'name' => $site, 'alternateName' => 'Redex',
        'url' => base_url() . '/' . lang(),
        'logo' => ['@type' => 'ImageObject', 'url' => asset('img/logo.png'), 'width' => 1200, 'height' => 420],
        'image' => asset('img/og-default.jpg'),
        'description' => t('footer.tagline'),
        'foundingDate' => '2001',
        'email' => cfg('email'),
        'sameAs' => array_values(cfg('socials')),
    ];
    $contacts = []; $addr = null;
    foreach (content_offices() as $o) {
        $cp = ['@type' => 'ContactPoint', 'contactType' => 'sales', 'email' => $o['email'], 'areaServed' => 'Worldwide',
               'availableLanguage' => ['English', 'Chinese', 'Turkish', 'Spanish', 'Russian', 'French', 'Polish', 'German', 'Hebrew']];
        if (!empty($o['phone'])) $cp['telephone'] = $o['phone'];
        $contacts[] = $cp;
        if ($addr === null && str_contains(strtolower($o['name']['en'] ?? ''), 'shanghai')) {
            $addr = ['@type' => 'PostalAddress', 'streetAddress' => $o['address']['en'] ?? '', 'addressLocality' => 'Shanghai', 'addressCountry' => 'CN'];
        }
    }
    if ($contacts) $org['contactPoint'] = $contacts;
    if ($addr) $org['address'] = $addr;
    $ld = [$org, [
        '@type' => 'WebSite', '@id' => base_url() . '/#website', 'url' => base_url() . '/' . lang(),
        'name' => $site, 'inLanguage' => cfg('languages')[lang()]['hreflang'] ?? lang(),
        'publisher' => ['@id' => $orgId],
    ]];
    // Breadcrumb
    $crumbs = [[t('nav.home'), url('')]];
    if ($page === 'product')      { $crumbs[] = [t('nav.products'), url('products')]; $crumbs[] = [loc($data['product']['name']), url($route)]; }
    elseif ($page === 'service')  { $crumbs[] = [t('nav.services'), url('services')]; $crumbs[] = [loc($data['service']['title']), url($route)]; }
    elseif ($page === 'news-article') { $crumbs[] = [t('news.title'), url('news')]; $crumbs[] = [news_tr($data['article'])['title'], url($route)]; }
    elseif ($page !== 'home' && $page !== '404') { $crumbs[] = [preg_replace('/ \| .*$/', '', $title), url($route)]; }
    if (count($crumbs) > 1) {
        $items = [];
        foreach ($crumbs as $i => [$name, $href]) $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => $href];
        $ld[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }
    if ($page === 'product' && !empty($data['product'])) {
        $p = $data['product'];
        $ld[] = ['@type' => 'Product', 'name' => loc($p['name']), 'description' => seo_clip(loc($p['body'] ?? $p['excerpt']), 300),
                 'image' => $p['img'] ?? $ogImage, 'url' => url($route), 'brand' => ['@type' => 'Brand', 'name' => $site],
                 'manufacturer' => ['@id' => $orgId], 'material' => 'Glass', 'category' => 'Glass packaging'];
    }
    if ($page === 'news-article' && !empty($data['article'])) {
        $a = $data['article']; $tr = news_tr($a);
        $ld[] = ['@type' => 'BlogPosting', 'headline' => $tr['title'], 'description' => seo_clip($tr['excerpt']),
                 'image' => !empty($a['image']) ? asset($a['image']) : $ogImage, 'datePublished' => $a['date'] ?? '',
                 'dateModified' => $a['date'] ?? '', 'inLanguage' => cfg('languages')[lang()]['hreflang'] ?? lang(),
                 'author' => ['@id' => $orgId], 'publisher' => ['@id' => $orgId],
                 'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url($route)]];
    }

    return [
        'title' => seo_title($title),
        'description' => seo_clip($desc),
        'jsonld' => $ld,
        'canonical' => url($route),
        'alternates' => $alts,
        'og_image' => $ogImage,
        'og_type' => $ogType,
    ];
}

function render_robots(): void
{
    header('Content-Type: text/plain; charset=utf-8');
    if (cfg('noindex')) {
        // Staging/deneme: tüm arama botlarını engelle (site yayına hazır olunca config'de noindex=false yapın)
        echo "User-agent: *\n";
        echo "Disallow: /\n";
        return;
    }
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
               'products', 'services', 'careers', 'distributors', 'news', 'contact', 'privacy-policy'];
    foreach (content_products() as $p) $routes[] = 'products/' . $p['slug'];
    foreach (array_keys(content_services()) as $k) $routes[] = 'services/' . $k;
    $lastmod = [];
    foreach (content_news() as $a) { $routes[] = 'news/' . $a['slug']; $lastmod['news/' . $a['slug']] = substr((string)($a['date'] ?? ''), 0, 10); }
    $siteMod = cfg('updated');
    // Gizlilik sayfasi dusuk SEO degerli: sitemap'ten cikar (indekslenebilir kalir)
    $routes = array_values(array_filter($routes, fn($r) => $r !== 'privacy-policy'));

    $langs = array_keys(cfg('languages'));
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
    foreach ($routes as $r) {
        foreach ($langs as $l) {
            $lm = $lastmod[$r] ?? $siteMod;
            echo "  <url>\n    <loc>" . e(url($r, $l)) . "</loc>\n";
            if ($lm) echo "    <lastmod>" . e($lm) . "</lastmod>\n";
            foreach ($langs as $alt) {
                echo '    <xhtml:link rel="alternate" hreflang="' . hreflang_code($alt) . '" href="' . e(url($r, $alt)) . "\"/>\n";
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
    // CAPTCHA (basit matematik) — spam koruması
    if (!captcha_check()) {
        flash_redirect($route, 'error', t('form.captcha_error'));
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

    if ($route === 'distributors') {
        $company = trim($_POST['company'] ?? '');
        $contact = trim($_POST['contact_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if ($company === '' || $contact === '' || $email === '') {
            flash_redirect($route, 'error', t('form.required'));
        }
        $phone = trim($_POST['phone'] ?? '');
        $website = trim($_POST['website_url'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $volume = trim($_POST['annual_volume'] ?? '');
        $msg = trim($_POST['message'] ?? '');
        $st = db()->prepare("INSERT INTO distributors (company,contact_name,email,phone,website,country,city,address,annual_volume,message,lang,ip,created_at)
                             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $st->execute([$company, $contact, $email, $phone, $website, $country, $city, $address,
                      $volume, $msg, lang(), $_SERVER['REMOTE_ADDR'] ?? '', date('c')]);
        $body = "New distributorship application from the website\n"
              . "----------------------------------------\n"
              . "Company:       $company\n"
              . "Contact:       $contact\n"
              . "Email:         $email\n"
              . "Phone:         $phone\n"
              . "Website:       $website\n"
              . "Country:       $country\n"
              . "City:          $city\n"
              . "Address:       $address\n"
              . "Annual volume: $volume\n"
              . "Language:      " . strtoupper(lang()) . "\n"
              . "----------------------------------------\n"
              . "Message:\n$msg\n";
        notify_email('[Distributor] ' . $company . ' — ' . $country, $body, $email, $contact);
        flash_redirect($route, 'success', t('form.success'));
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
