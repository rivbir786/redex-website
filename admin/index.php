<?php
/**
 * Redex Glass — Admin paneli
 * Rol tabanlı giriş (admin / editor), haber yönetimi, ziyaretçi istatistikleri
 * (ülke + süre dahil), iletişim/kariyer/distribütör kayıtları.
 */
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/security.php';
require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/content.php';
date_default_timezone_set(cfg('timezone'));
mb_internal_encoding('UTF-8');
secure_session_start();
security_headers(true);

/* ---------- IP kısıtı (config: admin_ip_allowlist) ---------- */
if (!admin_ip_allowed(client_ip())) { http_response_code(403); exit('Forbidden'); }

$pdo = db(); // şema + tohumlama burada çalışır

function admin_csrf(): string { if (empty($_SESSION['acsrf'])) $_SESSION['acsrf'] = bin2hex(random_bytes(16)); return $_SESSION['acsrf']; }
function admin_csrf_ok(): bool { return isset($_POST['_c']) && hash_equals($_SESSION['acsrf'] ?? '', $_POST['_c']); }
function admin_base(): string { return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); }
function is_admin(): bool { return ($_SESSION['admin_role'] ?? '') === 'admin'; }
function require_admin(): void { if (!is_admin()) { header('Location: ' . admin_base() . '/?view=news'); exit; } }
function admin_ip_allowed(string $ip): bool {
    $list = cfg('admin_ip_allowlist') ?: [];
    if (!$list) return true;
    foreach ($list as $rule) {
        if (strpos($rule, '/') === false) { if ($rule === $ip) return true; continue; }
        [$net, $bits] = explode('/', $rule, 2);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($net, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $mask = -1 << (32 - (int)$bits);
            if ((ip2long($ip) & $mask) === (ip2long($net) & $mask)) return true;
        }
    }
    return false;
}
function admin_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) { $p = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']); }
    session_destroy();
}

$error = '';
$userCount = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

/* ---------- İlk kurulum: ilk admin kullanıcısı ---------- */
if ($userCount === 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_ok()) {
        $u = trim($_POST['username'] ?? '');
        $p1 = $_POST['password'] ?? ''; $p2 = $_POST['password2'] ?? '';
        if (strlen($u) < 3 || !preg_match('/^[A-Za-z0-9._-]{3,40}$/', $u)) $error = 'Kullanıcı adı 3–40 karakter; harf, rakam, nokta, tire olabilir.';
        elseif (strlen($p1) < 12) $error = 'Şifre en az 12 karakter olmalı.';
        elseif ($p1 !== $p2) $error = 'Şifreler eşleşmiyor.';
        else {
            $st = $pdo->prepare("INSERT INTO users (username,password_hash,role,created_at) VALUES (?,?,?,?)");
            $st->execute([$u, password_hash($p1, PASSWORD_DEFAULT), 'admin', date('c')]);
            session_regenerate_id(true);
            $_SESSION['admin_uid'] = (int)$pdo->lastInsertId();
            $_SESSION['admin_role'] = 'admin';
            $_SESSION['admin_user'] = $u;
            header('Location: ' . admin_base() . '/'); exit;
        }
    }
    render_setup($error); exit;
}

/* ---------- Çıkış ---------- */
if (isset($_GET['logout'])) {
    if (hash_equals($_SESSION['acsrf'] ?? '', (string)$_GET['logout'])) admin_logout(); // CSRF korumalı çıkış
    header('Location: ' . admin_base() . '/'); exit;
}

/* ---------- Giriş ---------- */
if (empty($_SESSION['admin_uid'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_ok()) {
        $u = trim($_POST['username'] ?? '');
        $ipKey = 'login|ip|' . client_ip(); $userKey = 'login|user|' . strtolower($u);
        // Kaba kuvvet koruması: IP başına 15 dk'da 10, kullanıcı adı başına 15 dk'da 5 başarısız deneme
        if (!throttle($ipKey, 10, 900) || !throttle($userKey, 5, 900)) {
            usleep(500000);
            $error = 'Çok fazla deneme. Lütfen 15 dakika sonra tekrar deneyin.';
        } else {
            $st = $pdo->prepare("SELECT * FROM users WHERE username=?"); $st->execute([$u]);
            $row = $st->fetch();
            // Kullanıcı yoksa da sabit sürede doğrulama yap (zamanlama sızıntısını azaltır)
            $hash = $row['password_hash'] ?? '$2y$10$M3Y/VLXLGg9ETIpc.Q0WHuBm8hc.76me80IltJUB7atEyJvES86uG';
            if ($row && password_verify($_POST['password'] ?? '', $hash)) {
                throttle_reset($ipKey); throttle_reset($userKey);
                session_regenerate_id(true);
                $_SESSION['admin_uid'] = (int)$row['id'];
                $_SESSION['admin_role'] = $row['role'];
                $_SESSION['admin_user'] = $row['username'];
                $_SESSION['admin_last'] = time();
                $pdo->prepare("UPDATE users SET last_login=? WHERE id=?")->execute([date('c'), $row['id']]);
                header('Location: ' . admin_base() . '/'); exit;
            }
            usleep(300000);
            $error = 'Hatalı kullanıcı adı veya şifre.';
        }
    }
    render_login($error); exit;
}

/* ================= Giriş yapıldı ================= */
// Hareketsizlik zaman aşımı
$idle = (int)(cfg('admin_idle_timeout') ?: 7200);
if (time() - (int)($_SESSION['admin_last'] ?? 0) > $idle) { admin_logout(); header('Location: ' . admin_base() . '/?expired=1'); exit; }
$_SESSION['admin_last'] = time();
$msg = '';

/* ---------- POST işlemleri ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_ok()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'news_save') {
        $langs = [];
        foreach (array_keys(cfg('languages')) as $lc) {
            $t = trim($_POST["title_$lc"] ?? '');
            $ex = trim($_POST["excerpt_$lc"] ?? '');
            $bd = trim($_POST["body_$lc"] ?? '');
            if ($t !== '' || $ex !== '' || $bd !== '') {
                $langs[$lc] = ['title' => mb_substr($t, 0, 300), 'excerpt' => mb_substr($ex, 0, 1000), 'body' => safe_html(news_bodyize($bd))];
            }
        }
        $id = (int)($_POST['id'] ?? 0);
        $slug = slugify(trim($_POST['slug'] ?? ''));
        if ($slug === '') $slug = slugify($langs['en']['title'] ?? ($langs[array_key_first($langs)]['title'] ?? 'haber'));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug) ?: 'haber';
        $date = trim($_POST['published_at'] ?? '');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = date('Y-m-d');
        $image = trim($_POST['current_image'] ?? '');
        if (!preg_match('#^uploads/news/[A-Za-z0-9_\-]+\.(jpg|jpeg|png|webp)$#', $image) && !preg_match('#^img/[A-Za-z0-9_/\-]+\.(jpg|jpeg|png|webp)$#', $image)) $image = '';
        $up = news_handle_upload();
        if ($up) $image = $up;

        if (empty($langs['en']['title'] ?? '') && empty($langs[array_key_first($langs ?: ['en'=>1])]['title'] ?? '')) {
            $msg = 'En az bir dilde başlık gerekli.';
        } else {
            $langsJson = json_encode($langs, JSON_UNESCAPED_UNICODE);
            if ($id > 0) {
                $pdo->prepare("UPDATE news SET slug=?,image=?,published_at=?,langs=?,is_published=?,updated_at=? WHERE id=?")
                    ->execute([$slug, $image, $date, $langsJson, isset($_POST['is_published']) ? 1 : 0, date('c'), $id]);
            } else {
                // benzersiz slug
                $base = $slug; $i = 2;
                while ($pdo->prepare("SELECT 1 FROM news WHERE slug=?")->execute([$slug]) && $pdo->query("SELECT COUNT(*) FROM news WHERE slug=" . $pdo->quote($slug))->fetchColumn() > 0) { $slug = $base . '-' . $i++; }
                $pdo->prepare("INSERT INTO news (slug,image,published_at,langs,is_published,created_at,updated_at) VALUES (?,?,?,?,?,?,?)")
                    ->execute([$slug, $image, $date, $langsJson, isset($_POST['is_published']) ? 1 : 0, date('c'), date('c')]);
            }
            header('Location: ' . admin_base() . '/?view=news&ok=1'); exit;
        }
    }

    if ($action === 'news_delete') { require_admin();
        $pdo->prepare("DELETE FROM news WHERE id=?")->execute([(int)($_POST['id'] ?? 0)]);
        header('Location: ' . admin_base() . '/?view=news&ok=1'); exit;
    }

    if ($action === 'user_add') { require_admin();
        $u = trim($_POST['username'] ?? ''); $p = $_POST['password'] ?? ''; $r = ($_POST['role'] ?? 'editor') === 'admin' ? 'admin' : 'editor';
        if (!preg_match('/^[A-Za-z0-9._-]{3,40}$/', $u) || strlen($p) < 12) { $msg = 'Kullanıcı adı 3–40 karakter (harf/rakam/nokta/tire), şifre en az 12 karakter olmalı.'; }
        else {
            try {
                $pdo->prepare("INSERT INTO users (username,password_hash,role,created_at) VALUES (?,?,?,?)")
                    ->execute([$u, password_hash($p, PASSWORD_DEFAULT), $r, date('c')]);
                header('Location: ' . admin_base() . '/?view=users&ok=1'); exit;
            } catch (Throwable $e) { $msg = 'Bu kullanıcı adı zaten var.'; }
        }
    }

    if ($action === 'user_delete') { require_admin();
        $id = (int)($_POST['id'] ?? 0);
        if ($id === (int)$_SESSION['admin_uid']) { $msg = 'Kendi hesabınızı silemezsiniz.'; }
        elseif ((int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn() <= 1 && (string)$pdo->query("SELECT role FROM users WHERE id=" . $id)->fetchColumn() === 'admin') { $msg = 'Son admin silinemez.'; }
        else { $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]); header('Location: ' . admin_base() . '/?view=users&ok=1'); exit; }
    }
}

/* ---------- GET işlemleri ---------- */
if (!empty($_GET['cv'])) { require_admin();
    $file = basename($_GET['cv']); $path = __DIR__ . '/../storage/uploads/' . $file;
    if (preg_match('/^[A-Za-z0-9_]+\.(pdf|jpg|png)$/', $file) && is_file($path)) {
        header('Content-Type: application/octet-stream'); header('X-Content-Type-Options: nosniff');
        header('Content-Disposition: attachment; filename="' . $file . '"'); header('Content-Length: ' . filesize($path)); readfile($path); exit; }
    http_response_code(404); exit('Dosya bulunamadı.');
}
if (!empty($_GET['read']) && !empty($_GET['t'])) { require_admin();
    $map = ['app' => 'applications', 'msg' => 'messages', 'dist' => 'distributors'];
    $tbl = $map[$_GET['t']] ?? 'messages';
    $pdo->prepare("UPDATE $tbl SET is_read=1 WHERE id=?")->execute([(int)$_GET['read']]);
    header('Location: ' . admin_base() . '/?view=' . ($_GET['t'] === 'app' ? 'applications' : ($_GET['t'] === 'dist' ? 'distributors' : 'messages'))); exit;
}

$view = $_GET['view'] ?? (is_admin() ? 'dashboard' : 'news');
// Editör yalnızca haberlere erişir
if (!is_admin() && !in_array($view, ['news', 'news_edit'], true)) $view = 'news';

/* ---------- İstatistik (dashboard) ---------- */
$today = date('Y-m-d');
$stat = function($sql, $params = []) use ($pdo) { $s = $pdo->prepare($sql); $s->execute($params); return $s->fetchColumn(); };
$counts = [
  'news' => (int)$pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
  'msg'  => (int)$pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn(),
  'app'  => (int)$pdo->query("SELECT COUNT(*) FROM applications WHERE is_read=0")->fetchColumn(),
  'dist' => (int)$pdo->query("SELECT COUNT(*) FROM distributors WHERE is_read=0")->fetchColumn(),
];

render_header($view, $counts);
echo $msg ? '<div class="flashmsg">' . e($msg) . '</div>' : (isset($_GET['ok']) ? '<div class="flashmsg ok">Kaydedildi.</div>' : '');

if ($view === 'dashboard') { require_admin_or_redirect();
    $d = [
      'today'  => $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day=?", [$today]),
      'v7'     => $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-6 days'))]),
      'v30'    => $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-29 days'))]),
      'uniq'   => $stat("SELECT COUNT(DISTINCT ip_hash) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-29 days'))]),
      'total'  => $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0"),
      'avgt'   => (int)round((float)$stat("SELECT AVG(duration) FROM visits WHERE is_bot=0 AND duration>0")),
    ];
    $days = [];
    for ($i = 13; $i >= 0; $i--) $days[date('Y-m-d', strtotime("-$i days"))] = 0;
    foreach ($pdo->query("SELECT day, COUNT(*) c FROM visits WHERE is_bot=0 AND day>='" . date('Y-m-d', strtotime('-13 days')) . "' GROUP BY day")->fetchAll() as $r)
        if (isset($days[$r['day']])) $days[$r['day']] = (int)$r['c'];
    $byLang = $pdo->query("SELECT lang, COUNT(*) c FROM visits WHERE is_bot=0 GROUP BY lang ORDER BY c DESC")->fetchAll();
    $byCountry = $pdo->query("SELECT country, COUNT(*) c FROM visits WHERE is_bot=0 AND country IS NOT NULL AND country!='' GROUP BY country ORDER BY c DESC LIMIT 12")->fetchAll();
    $topPages = $pdo->query("SELECT path, COUNT(*) c, AVG(CASE WHEN duration>0 THEN duration END) avgt FROM visits WHERE is_bot=0 GROUP BY path ORDER BY c DESC LIMIT 10")->fetchAll();
    render_dashboard($d, $days, $byLang, $byCountry, $topPages);
}
elseif ($view === 'news') {
    $list = $pdo->query("SELECT * FROM news ORDER BY published_at DESC, id DESC")->fetchAll();
    render_news_list($list);
}
elseif ($view === 'news_edit') {
    $row = null;
    if (!empty($_GET['id'])) { $st = $pdo->prepare("SELECT * FROM news WHERE id=?"); $st->execute([(int)$_GET['id']]); $row = $st->fetch(); }
    render_news_form($row);
}
elseif ($view === 'users') { require_admin_or_redirect();
    render_users($pdo->query("SELECT id,username,role,created_at,last_login FROM users ORDER BY id")->fetchAll());
}
elseif ($view === 'distributors') { require_admin_or_redirect();
    render_distributors($pdo->query("SELECT * FROM distributors ORDER BY id DESC LIMIT 300")->fetchAll());
}
elseif ($view === 'messages') { require_admin_or_redirect();
    render_messages($pdo->query("SELECT * FROM messages ORDER BY id DESC LIMIT 300")->fetchAll());
}
elseif ($view === 'applications') { require_admin_or_redirect();
    render_applications($pdo->query("SELECT * FROM applications ORDER BY id DESC LIMIT 300")->fetchAll());
}
render_footer();


/* ================= Yardımcılar ================= */
function require_admin_or_redirect(): void { if (!is_admin()) { echo '<p class="muted">Bu bölüme yalnızca yöneticiler erişebilir.</p>'; render_footer(); exit; } }

/** Düz metni basit <p> paragraflarına çevir (HTML yoksa). */
function news_bodyize(string $body): string {
    if ($body === '' || preg_match('/<(p|h2|h3|ul|ol|li|br|div|img|b|strong|em)\b/i', $body)) return $body;
    $parts = preg_split('/\n{2,}/', trim($body));
    return implode("\n", array_map(fn($p) => '<p>' . nl2br(e(trim($p))) . '</p>', array_filter($parts)));
}

/** Haber görseli yükleme → assets/uploads/news/ ; döndürür: 'uploads/news/<ad>' veya '' */
function news_handle_upload(): string {
    if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) return '';
    $f = $_FILES['image'];
    if ($f['size'] > 6 * 1024 * 1024) return '';
    $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!isset($allowed[$ext]) || !is_uploaded_file($f['tmp_name']) || upload_mime($f['tmp_name']) !== $allowed[$ext]) return '';
    if (@getimagesize($f['tmp_name']) === false) return ''; // gerçekten görsel mi?
    if ($ext === 'jpeg') $ext = 'jpg';
    $dir = __DIR__ . '/../assets/uploads/news';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $name = date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], $dir . '/' . $name)) return 'uploads/news/' . $name;
    return '';
}

/* ================= Görünümler ================= */
function admin_style(): string {
    return '<style>
    *{box-sizing:border-box;margin:0;font-family:system-ui,-apple-system,"Segoe UI",sans-serif}
    body{background:#eef2f3;color:#1a2430}
    a{color:#1E969C;text-decoration:none}
    .wrap{display:flex;min-height:100vh}
    .side{width:240px;background:#13343a;color:#cfdadd;padding:24px 0;flex-shrink:0}
    .side .logo{font-size:1.3rem;font-weight:700;color:#fff;padding:0 24px 8px;letter-spacing:1px}
    .side .logo span{color:#1E969C}
    .side .who{padding:0 24px 20px;font-size:12px;color:#7f939b}
    .side a{display:block;color:#cfdadd;padding:13px 24px;font-size:14.5px}
    .side a:hover,.side a.on{background:rgba(255,255,255,.08);color:#fff;border-inline-start:3px solid #1E969C}
    .side .badge{background:#e2574c;color:#fff;border-radius:20px;padding:1px 8px;font-size:12px;margin-inline-start:6px}
    .main{flex:1;padding:32px 40px}
    .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
    .topbar h1{font-size:1.5rem;color:#13343a}
    .flashmsg{background:#fff6d9;border:1px solid #f0d98a;color:#7a5c00;padding:11px 16px;border-radius:9px;margin-bottom:18px;font-size:14px}
    .flashmsg.ok{background:#e6f7f2;border-color:#9fdcc9;color:#0e6f4a}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:18px;margin-bottom:28px}
    .kpi{background:#fff;border-radius:14px;padding:20px;box-shadow:0 6px 24px rgba(19,52,58,.06)}
    .kpi .n{font-size:1.9rem;font-weight:700;color:#1E969C}
    .kpi .l{color:#5a6b78;font-size:13px;margin-top:4px}
    .panel{background:#fff;border-radius:14px;padding:24px;box-shadow:0 6px 24px rgba(19,52,58,.06);margin-bottom:24px}
    .panel h2{font-size:1.1rem;color:#13343a;margin-bottom:18px}
    .chart{display:flex;align-items:flex-end;gap:8px;height:170px}
    .bar{flex:1;background:linear-gradient(#1E969C,#55bcc1);border-radius:6px 6px 0 0;min-height:3px;position:relative}
    .bar span{position:absolute;top:-20px;left:0;right:0;text-align:center;font-size:11px;color:#5a6b78}
    .bar small{position:absolute;bottom:-22px;left:0;right:0;text-align:center;font-size:10px;color:#9aa7b0}
    .three{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
    .two{display:grid;grid-template-columns:1fr 1fr;gap:24px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{text-align:start;padding:11px 12px;border-bottom:1px solid #eef2f3;vertical-align:top}
    th{color:#5a6b78;font-weight:600;font-size:12.5px;text-transform:uppercase;letter-spacing:.05em}
    tr.unread td{background:#f0fafa}
    .pill{display:inline-block;background:#eef2f3;border-radius:20px;padding:2px 10px;font-size:12px;color:#5a6b78}
    .pill.adm{background:#e6f7f2;color:#0e6f4a}.pill.edi{background:#eef2f3;color:#5a6b78}
    .btn{display:inline-block;background:#1E969C;color:#fff;border:0;padding:9px 16px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer}
    .btn:hover{background:#0e6f74}.btn.sm{padding:6px 12px;font-size:13px}.btn.gray{background:#64757e}.btn.red{background:#e2574c}
    .login{max-width:400px;margin:11vh auto;background:#fff;padding:40px;border-radius:16px;box-shadow:0 20px 60px rgba(19,52,58,.12)}
    .login h1{color:#13343a;margin-bottom:6px}.login p{color:#5a6b78;font-size:14px;margin-bottom:22px}
    .login .logo{font-size:1.4rem;font-weight:700;color:#13343a;margin-bottom:20px}.login .logo span{color:#1E969C}
    label{display:block;font-size:13px;color:#5a6b78;margin:12px 0 5px;font-weight:600}
    input,select,textarea{width:100%;padding:11px 13px;border:1px solid #dde4e6;border-radius:9px;font-size:14.5px;font-family:inherit}
    input:focus,select:focus,textarea:focus{outline:none;border-color:#1E969C}
    textarea{min-height:150px;resize:vertical}
    .err{background:#fdecec;color:#a4302a;padding:10px 14px;border-radius:8px;font-size:14px;margin-bottom:16px}
    .muted{color:#9aa7b0;font-size:13px}
    .row2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .tabs{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;border-bottom:1px solid #e3e9ea;padding-bottom:12px}
    .tabs button{width:auto;background:#eef2f3;color:#5a6b78;padding:7px 14px;border-radius:8px;font-weight:600;font-size:13px;border:0;cursor:pointer}
    .tabs button.on{background:#1E969C;color:#fff}
    .langpane{display:none}.langpane.on{display:block}
    .reqstar{color:#e2574c}
    @media(max-width:860px){.side{width:60px}.side .logo,.side .who,.side a span{display:none}.three,.two,.row2{grid-template-columns:1fr}.main{padding:18px}}
    </style>';
}
function render_setup($error){ ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Admin Kurulumu</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="login"><div class="logo">REDEX <span>GLASS</span></div><h1>İlk Admin Hesabı</h1><p>Panel için ilk yönetici hesabını oluşturun.</p>
<?php if($error) echo '<div class="err">'.e($error).'</div>'; ?>
<form method="post"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>">
<label>Kullanıcı adı</label><input type="text" name="username" required>
<label>Şifre (min 12)</label><input type="password" name="password" required autocomplete="new-password">
<label>Şifre (tekrar)</label><input type="password" name="password2" required>
<p style="margin-top:18px"><button class="btn" type="submit" style="width:100%">Hesabı Oluştur</button></p></form></div></body></html>
<?php }
function render_login($error){ ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Admin Giriş</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="login"><div class="logo">REDEX <span>GLASS</span></div><h1>Yönetici Girişi</h1><p>Panele erişmek için giriş yapın.</p>
<?php if($error) echo '<div class="err">'.e($error).'</div>'; elseif(isset($_GET['expired'])) echo '<div class="err">Oturum zaman aşımına uğradı; lütfen tekrar giriş yapın.</div>'; ?>
<form method="post"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>">
<label>Kullanıcı adı</label><input type="text" name="username" autofocus required autocomplete="username">
<label>Şifre</label><input type="password" name="password" required>
<p style="margin-top:18px"><button class="btn" type="submit" style="width:100%">Giriş Yap</button></p></form></div></body></html>
<?php }
function render_header($view, $counts){ $b = admin_base(); $admin = is_admin(); ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Redex Admin</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="wrap"><aside class="side">
  <div class="logo">REDEX <span>GLASS</span></div>
  <div class="who"><?= e($_SESSION['admin_user'] ?? '') ?> · <?= $admin ? 'admin' : 'editör' ?></div>
  <?php if($admin): ?><a href="<?= $b ?>/?view=dashboard" class="<?= $view==='dashboard'?'on':'' ?>"><span>📊 Panel</span></a><?php endif; ?>
  <a href="<?= $b ?>/?view=news" class="<?= in_array($view,['news','news_edit'])?'on':'' ?>"><span>📰 Haberler</span></a>
  <?php if($admin): ?>
  <a href="<?= $b ?>/?view=messages" class="<?= $view==='messages'?'on':'' ?>"><span>✉ Mesajlar</span><?= $counts['msg']?'<span class="badge">'.$counts['msg'].'</span>':'' ?></a>
  <a href="<?= $b ?>/?view=applications" class="<?= $view==='applications'?'on':'' ?>"><span>👤 Başvurular</span><?= $counts['app']?'<span class="badge">'.$counts['app'].'</span>':'' ?></a>
  <a href="<?= $b ?>/?view=distributors" class="<?= $view==='distributors'?'on':'' ?>"><span>🤝 Distribütörler</span><?= $counts['dist']?'<span class="badge">'.$counts['dist'].'</span>':'' ?></a>
  <a href="<?= $b ?>/?view=users" class="<?= $view==='users'?'on':'' ?>"><span>⚙ Kullanıcılar</span></a>
  <?php endif; ?>
  <a href="<?= e(base_url()) ?>" target="_blank"><span>🌐 Siteyi Aç</span></a>
  <a href="<?= $b ?>/?logout=<?= e(admin_csrf()) ?>"><span>⎋ Çıkış</span></a>
</aside><main class="main">
<?php }
function render_dashboard($d, $days, $byLang, $byCountry, $topPages){ $maxDay = max(1, max($days)); ?>
<div class="topbar"><h1>Genel Bakış</h1><span class="muted"><?= date('d.m.Y H:i') ?></span></div>
<div class="cards">
  <div class="kpi"><div class="n"><?= number_format($d['today']) ?></div><div class="l">Bugünkü ziyaret</div></div>
  <div class="kpi"><div class="n"><?= number_format($d['v7']) ?></div><div class="l">Son 7 gün</div></div>
  <div class="kpi"><div class="n"><?= number_format($d['v30']) ?></div><div class="l">Son 30 gün</div></div>
  <div class="kpi"><div class="n"><?= number_format($d['uniq']) ?></div><div class="l">Tekil ziyaretçi (30g)</div></div>
  <div class="kpi"><div class="n"><?= $d['avgt'] ? gmdate($d['avgt']>=3600?'H:i:s':'i:s', $d['avgt']) : '—' ?></div><div class="l">Ort. sayfada süre</div></div>
  <div class="kpi"><div class="n"><?= number_format($d['total']) ?></div><div class="l">Toplam görüntülenme</div></div>
</div>
<div class="panel"><h2>Son 14 gün — sayfa görüntülenmeleri</h2>
  <div class="chart"><?php foreach ($days as $day => $c): ?>
    <div class="bar" style="height:<?= round($c / $maxDay * 100) ?>%" title="<?= $day ?>: <?= $c ?>"><span><?= $c ?></span><small><?= date('d.m', strtotime($day)) ?></small></div>
  <?php endforeach; ?></div>
</div>
<div class="three">
  <div class="panel"><h2>Ülkeye göre</h2><table>
    <?php foreach ($byCountry as $r): ?><tr><td><span class="pill"><?= e($r['country']) ?></span> <?= e(country_name($r['country'])) ?></td><td style="text-align:end"><?= number_format($r['c']) ?></td></tr><?php endforeach; ?>
    <?php if(!$byCountry) echo '<tr><td class="muted">Ülke verisi yok. (Site bir CDN/Cloudflare arkasında değilse ülke boş kalabilir.)</td></tr>'; ?>
  </table></div>
  <div class="panel"><h2>Dillere göre</h2><table>
    <?php foreach ($byLang as $r): ?><tr><td><span class="pill"><?= e(strtoupper($r['lang'])) ?></span></td><td style="text-align:end"><?= number_format($r['c']) ?></td></tr><?php endforeach; ?>
    <?php if(!$byLang) echo '<tr><td class="muted">Henüz veri yok</td></tr>'; ?>
  </table></div>
  <div class="panel"><h2>En çok görüntülenen sayfalar</h2><table>
    <tr><th>Sayfa</th><th style="text-align:end">Görüntü</th><th style="text-align:end">Ort. süre</th></tr>
    <?php foreach ($topPages as $r): ?><tr><td><?= e($r['path']) ?></td><td style="text-align:end"><?= number_format($r['c']) ?></td><td style="text-align:end" class="muted"><?= $r['avgt']?gmdate('i:s',(int)round($r['avgt'])):'—' ?></td></tr><?php endforeach; ?>
    <?php if(!$topPages) echo '<tr><td class="muted">Henüz veri yok</td></tr>'; ?>
  </table></div>
</div>
<?php }
function render_news_list($list){ $b = admin_base(); ?>
<div class="topbar"><h1>Haberler</h1><a class="btn" href="<?= $b ?>/?view=news_edit">+ Yeni Haber</a></div>
<div class="panel"><table>
  <tr><th>Tarih</th><th>Başlık (EN)</th><th>Diller</th><th>Durum</th><th></th></tr>
  <?php foreach ($list as $n): $L = json_decode($n['langs'] ?: '{}', true) ?: []; $title = $L['en']['title'] ?? ($L[array_key_first($L ?: ['x'=>1])]['title'] ?? '—'); ?>
    <tr>
      <td class="muted"><?= e($n['published_at']) ?></td>
      <td><?= e($title) ?><br><span class="muted"><?= e($n['slug']) ?></span></td>
      <td><?php foreach (array_keys($L) as $lc) echo '<span class="pill">'.e(strtoupper($lc)).'</span> '; ?></td>
      <td><?= $n['is_published'] ? '<span class="pill adm">Yayında</span>' : '<span class="pill">Taslak</span>' ?></td>
      <td style="white-space:nowrap">
        <a class="btn sm gray" href="<?= $b ?>/?view=news_edit&id=<?= $n['id'] ?>">Düzenle</a>
        <?php if(is_admin()): ?>
        <form method="post" style="display:inline" data-confirm="Bu haber silinsin mi?">
          <input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>"><input type="hidden" name="action" value="news_delete"><input type="hidden" name="id" value="<?= $n['id'] ?>">
          <button class="btn sm red" type="submit">Sil</button>
        </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if(!$list) echo '<tr><td colspan="5" class="muted">Henüz haber yok. "+ Yeni Haber" ile ekleyin.</td></tr>'; ?>
</table></div>
<?php if(!is_admin()): ?><p class="muted">Editör olarak haber ekleyip düzenleyebilirsiniz; silme yetkisi yöneticilerdedir.</p><?php endif; ?>
<?php }
function render_news_form($row){ $b = admin_base(); $L = $row ? (json_decode($row['langs'] ?: '{}', true) ?: []) : []; $langs = cfg('languages'); ?>
<div class="topbar"><h1><?= $row ? 'Haberi Düzenle' : 'Yeni Haber' ?></h1><a class="muted" href="<?= $b ?>/?view=news">← Listeye dön</a></div>
<div class="panel">
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>"><input type="hidden" name="action" value="news_save">
  <?php if($row): ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><?php endif; ?>
  <input type="hidden" name="current_image" value="<?= e($row['image'] ?? '') ?>">
  <div class="row2">
    <div><label>Yayın tarihi</label><input type="date" name="published_at" value="<?= e($row['published_at'] ?? date('Y-m-d')) ?>"></div>
    <div><label>URL kısaltması (slug) <span class="muted">— boş bırakılırsa başlıktan üretilir</span></label><input type="text" name="slug" value="<?= e($row['slug'] ?? '') ?>" placeholder="ornek-haber-basligi"></div>
  </div>
  <div class="row2" style="margin-top:4px">
    <div><label>Görsel <span class="muted">(jpg/png/webp, maks 6MB)</span></label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></div>
    <div><label>Durum</label><select name="is_published"><option value="1" <?= (!$row || $row['is_published'])?'selected':'' ?>>Yayında</option><option value="0" <?= ($row && !$row['is_published'])?'selected':'' ?>>Taslak</option></select>
      <?php if(!empty($row['image'])): ?><p class="muted" style="margin-top:6px">Mevcut: <?= e($row['image']) ?></p><?php endif; ?></div>
  </div>

  <h2 style="margin:26px 0 10px;font-size:1.05rem;color:#13343a">İçerik (dillere göre)</h2>
  <p class="muted" style="margin-bottom:12px">İngilizce (EN) önerilir; girilmeyen diller otomatik İngilizce'ye düşer. Metin kutusuna düz paragraf yazabilir ya da HTML kullanabilirsiniz.</p>
  <div class="tabs">
    <?php $first=true; foreach ($langs as $lc=>$info): ?>
      <button type="button" class="tabbtn <?= $first?'on':'' ?>" data-lc="<?= e($lc) ?>"><?= e(strtoupper($lc)) ?><?= $lc==='en'?' <span class="reqstar">*</span>':'' ?></button>
    <?php $first=false; endforeach; ?>
  </div>
  <?php $first=true; foreach ($langs as $lc=>$info): $d = $L[$lc] ?? []; ?>
    <div class="langpane <?= $first?'on':'' ?>" data-lc="<?= e($lc) ?>">
      <label>Başlık (<?= e(strtoupper($lc)) ?>)</label><input type="text" name="title_<?= e($lc) ?>" value="<?= e($d['title'] ?? '') ?>">
      <label>Özet</label><textarea name="excerpt_<?= e($lc) ?>" style="min-height:70px"><?= e($d['excerpt'] ?? '') ?></textarea>
      <label>İçerik (HTML veya düz metin)</label><textarea name="body_<?= e($lc) ?>"><?= e($d['body'] ?? '') ?></textarea>
    </div>
  <?php $first=false; endforeach; ?>
  <p style="margin-top:20px"><button class="btn" type="submit">Kaydet</button></p>
</form>
</div>
<script nonce="<?= e(csp_nonce()) ?>">
document.querySelectorAll('.tabbtn').forEach(function(btn){btn.addEventListener('click',function(){
  var lc=btn.dataset.lc;
  document.querySelectorAll('.tabbtn').forEach(b=>b.classList.toggle('on',b===btn));
  document.querySelectorAll('.langpane').forEach(p=>p.classList.toggle('on',p.dataset.lc===lc));
});});
</script>
<?php }
function render_users($list){ $b = admin_base(); ?>
<div class="topbar"><h1>Kullanıcılar</h1></div>
<div class="panel"><table>
  <tr><th>Kullanıcı</th><th>Rol</th><th>Oluşturma</th><th>Son giriş</th><th></th></tr>
  <?php foreach ($list as $u): ?>
    <tr><td><?= e($u['username']) ?></td>
      <td><span class="pill <?= $u['role']==='admin'?'adm':'edi' ?>"><?= $u['role']==='admin'?'Admin':'Editör' ?></span></td>
      <td class="muted"><?= e(substr($u['created_at'],0,10)) ?></td>
      <td class="muted"><?= $u['last_login']?e(date('d.m.Y H:i',strtotime($u['last_login']))):'—' ?></td>
      <td><?php if($u['id']!=($_SESSION['admin_uid']??0)): ?>
        <form method="post" style="display:inline" data-confirm="Kullanıcı silinsin mi?"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>"><input type="hidden" name="action" value="user_delete"><input type="hidden" name="id" value="<?= $u['id'] ?>"><button class="btn sm red" type="submit">Sil</button></form>
      <?php else: ?><span class="muted">(siz)</span><?php endif; ?></td></tr>
  <?php endforeach; ?>
</table></div>
<div class="panel" style="max-width:520px"><h2>Yeni kullanıcı ekle</h2>
<form method="post"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>"><input type="hidden" name="action" value="user_add">
  <div class="row2"><div><label>Kullanıcı adı</label><input type="text" name="username" required></div>
  <div><label>Rol</label><select name="role"><option value="editor">Editör — yalnızca haber ekler/düzenler</option><option value="admin">Admin — her şey + silme</option></select></div></div>
  <label>Şifre (min 12)</label><input type="password" name="password" required autocomplete="new-password">
  <p style="margin-top:16px"><button class="btn" type="submit">Kullanıcı Ekle</button></p>
</form></div>
<?php }
function render_distributors($list){ $b = admin_base(); ?>
<div class="topbar"><h1>Distribütörlük Başvuruları</h1><span class="muted"><?= count($list) ?> kayıt</span></div>
<div class="panel"><table>
  <tr><th>Tarih</th><th>Firma</th><th>Yetkili / İletişim</th><th>Ülke / Şehir</th><th>Yıllık hacim</th><th>Web</th><th>Mesaj</th><th></th></tr>
  <?php foreach ($list as $r): ?>
    <tr class="<?= $r['is_read']?'':'unread' ?>">
      <td class="muted"><?= date('d.m.Y H:i', strtotime($r['created_at'])) ?></td>
      <td><?= e($r['company']) ?></td>
      <td><?= e($r['contact_name']) ?><br><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a><br><span class="muted"><?= e($r['phone']) ?></span></td>
      <td><?= e(trim($r['country'].' '.$r['city'])) ?><br><span class="muted"><?= e($r['address']) ?></span></td>
      <td><?= e($r['annual_volume']) ?></td>
      <td><?php if($r['website']): ?><a href="<?= e($r['website']) ?>" target="_blank" rel="noopener">link</a><?php endif; ?></td>
      <td style="max-width:280px"><?= nl2br(e($r['message'])) ?></td>
      <td><?php if(!$r['is_read']): ?><a href="<?= $b ?>/?read=<?= $r['id'] ?>&t=dist">✓ okundu</a><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if(!$list) echo '<tr><td colspan="8" class="muted">Henüz başvuru yok.</td></tr>'; ?>
</table></div>
<?php }
function render_messages($list){ $b = admin_base(); ?>
<div class="topbar"><h1>İletişim Mesajları</h1><span class="muted"><?= count($list) ?> kayıt</span></div>
<div class="panel"><table>
  <tr><th>Tarih</th><th>Ad / Firma</th><th>İletişim</th><th>Konu</th><th>Ülke</th><th>Mesaj</th><th>Dil</th><th></th></tr>
  <?php foreach ($list as $m): ?>
    <tr class="<?= $m['is_read']?'':'unread' ?>">
      <td class="muted"><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></td>
      <td><?= e($m['name']) ?><br><span class="muted"><?= e($m['company']) ?></span></td>
      <td><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a><br><span class="muted"><?= e($m['phone']) ?></span></td>
      <td><?= e($m['subject'] ?? '') ?></td><td><?= e($m['country'] ?? '') ?></td>
      <td style="max-width:320px"><?= nl2br(e($m['message'])) ?></td>
      <td><span class="pill"><?= e(strtoupper($m['lang'])) ?></span></td>
      <td><?php if(!$m['is_read']): ?><a href="<?= $b ?>/?read=<?= $m['id'] ?>&t=msg">✓ okundu</a><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if(!$list) echo '<tr><td colspan="8" class="muted">Henüz mesaj yok.</td></tr>'; ?>
</table></div>
<?php }
function render_applications($list){ $b = admin_base(); ?>
<div class="topbar"><h1>İş Başvuruları</h1><span class="muted"><?= count($list) ?> kayıt</span></div>
<div class="panel"><table>
  <tr><th>Tarih</th><th>Ad</th><th>İletişim</th><th>Pozisyon</th><th>Detay</th><th>CV</th><th></th></tr>
  <?php foreach ($list as $a): ?>
    <tr class="<?= $a['is_read']?'':'unread' ?>">
      <td class="muted"><?= date('d.m.Y H:i', strtotime($a['created_at'])) ?></td>
      <td><?= e($a['name']) ?></td>
      <td><a href="mailto:<?= e($a['email']) ?>"><?= e($a['email']) ?></a><br><span class="muted"><?= e($a['phone']) ?></span></td>
      <td><?= e($a['position']) ?></td>
      <td style="max-width:280px"><span class="muted"><?= e(trim($a['gender'].' '.$a['marital'])) ?></span><br><?= nl2br(e($a['message'])) ?></td>
      <td><?php if($a['cv_file']): ?><a href="<?= $b ?>/?cv=<?= urlencode($a['cv_file']) ?>">⬇ indir</a><?php else: ?><span class="muted">—</span><?php endif; ?></td>
      <td><?php if(!$a['is_read']): ?><a href="<?= $b ?>/?read=<?= $a['id'] ?>&t=app">✓ okundu</a><?php endif; ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if(!$list) echo '<tr><td colspan="7" class="muted">Henüz başvuru yok.</td></tr>'; ?>
</table></div>
<?php }
function render_footer(){ echo '<script nonce="' . e(csp_nonce()) . '">document.querySelectorAll("form[data-confirm]").forEach(function(f){f.addEventListener("submit",function(ev){if(!confirm(f.dataset.confirm))ev.preventDefault();});});</script></main></div></body></html>'; }

/** ISO ülke kodu -> görünen ad (kısa liste; bilinmeyen kod olduğu gibi döner) */
function country_name(string $code): string {
    static $m = ['TR'=>'Türkiye','CN'=>'Çin','US'=>'ABD','GB'=>'İngiltere','DE'=>'Almanya','FR'=>'Fransa','ES'=>'İspanya','IT'=>'İtalya','NL'=>'Hollanda','RU'=>'Rusya','PL'=>'Polonya','IL'=>'İsrail','PE'=>'Peru','AE'=>'BAE','SA'=>'S.Arabistan','IN'=>'Hindistan','BR'=>'Brezilya','MX'=>'Meksika','CA'=>'Kanada','AU'=>'Avustralya','ZA'=>'G.Afrika','UA'=>'Ukrayna','GR'=>'Yunanistan','PT'=>'Portekiz','BE'=>'Belçika','SE'=>'İsveç','CH'=>'İsviçre','AT'=>'Avusturya','RO'=>'Romanya','CZ'=>'Çekya','HU'=>'Macaristan','JP'=>'Japonya','KR'=>'G.Kore','EG'=>'Mısır','MA'=>'Fas','NG'=>'Nijerya','ID'=>'Endonezya','PK'=>'Pakistan','BG'=>'Bulgaristan'];
    return $m[strtoupper($code)] ?? $code;
}
