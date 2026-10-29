<?php
/**
 * Redex Glass — Admin paneli
 * Ziyaretçi istatistikleri + iletişim mesajları + iş başvuruları.
 * İlk açılışta admin şifresi belirlenir (storage/admin.php içine hash yazılır).
 */
session_start();
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/db.php';
date_default_timezone_set(cfg('timezone'));
mb_internal_encoding('UTF-8');

$hashFile = cfg('admin_hash_file');
$hasPassword = is_file($hashFile);

function admin_csrf(): string { if (empty($_SESSION['acsrf'])) $_SESSION['acsrf'] = bin2hex(random_bytes(16)); return $_SESSION['acsrf']; }
function admin_csrf_ok(): bool { return isset($_POST['_c']) && hash_equals($_SESSION['acsrf'] ?? '', $_POST['_c']); }
function admin_base(): string { return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); }

$error = '';

// --- İlk kurulum: şifre belirle ---
if (!$hasPassword) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_ok()) {
        $p1 = $_POST['password'] ?? ''; $p2 = $_POST['password2'] ?? '';
        if (strlen($p1) < 8) $error = 'Şifre en az 8 karakter olmalı.';
        elseif ($p1 !== $p2) $error = 'Şifreler eşleşmiyor.';
        else {
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            file_put_contents($hashFile, "<?php return " . var_export($hash, true) . ";\n");
            $_SESSION['admin'] = true;
            header('Location: ' . admin_base() . '/'); exit;
        }
    }
    render_setup($error); exit;
}

$storedHash = require $hashFile;

// --- Çıkış ---
if (isset($_GET['logout'])) { session_destroy(); header('Location: ' . admin_base() . '/'); exit; }

// --- Giriş ---
if (empty($_SESSION['admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_ok()) {
        if (password_verify($_POST['password'] ?? '', $storedHash)) {
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            header('Location: ' . admin_base() . '/'); exit;
        }
        $error = 'Hatalı şifre.';
    }
    render_login($error); exit;
}

// --- Giriş yapıldı: işlemler ---
$pdo = db();

// CV indir
if (!empty($_GET['cv'])) {
    $file = basename($_GET['cv']);
    $path = __DIR__ . '/../storage/uploads/' . $file;
    if (is_file($path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        readfile($path); exit;
    }
    http_response_code(404); exit('Dosya bulunamadı.');
}

// Okundu işaretle
if (!empty($_GET['read']) && !empty($_GET['t'])) {
    $tbl = $_GET['t'] === 'app' ? 'applications' : 'messages';
    $st = $pdo->prepare("UPDATE $tbl SET is_read=1 WHERE id=?");
    $st->execute([(int)$_GET['read']]);
    header('Location: ' . admin_base() . '/?view=' . ($_GET['t'] === 'app' ? 'applications' : 'messages')); exit;
}

$view = $_GET['view'] ?? 'dashboard';

// --- İstatistik verileri ---
$today = date('Y-m-d');
$stat = function($sql, $params = []) use ($pdo) { $s = $pdo->prepare($sql); $s->execute($params); return $s->fetchColumn(); };

$totalVisits = $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0");
$todayVisits = $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day=?", [$today]);
$visits7 = $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-6 days'))]);
$visits30 = $stat("SELECT COUNT(*) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-29 days'))]);
$uniqueVisitors = $stat("SELECT COUNT(DISTINCT ip_hash) FROM visits WHERE is_bot=0 AND day>=?", [date('Y-m-d', strtotime('-29 days'))]);
$unreadMsg = $stat("SELECT COUNT(*) FROM messages WHERE is_read=0");
$unreadApp = $stat("SELECT COUNT(*) FROM applications WHERE is_read=0");

// Son 14 gün grafik
$days = [];
for ($i = 13; $i >= 0; $i--) $days[date('Y-m-d', strtotime("-$i days"))] = 0;
$rows = $pdo->query("SELECT day, COUNT(*) c FROM visits WHERE is_bot=0 AND day>='" . date('Y-m-d', strtotime('-13 days')) . "' GROUP BY day")->fetchAll();
foreach ($rows as $r) if (isset($days[$r['day']])) $days[$r['day']] = (int)$r['c'];
$maxDay = max(1, max($days));

$byLang = $pdo->query("SELECT lang, COUNT(*) c FROM visits WHERE is_bot=0 GROUP BY lang ORDER BY c DESC")->fetchAll();
$topPages = $pdo->query("SELECT path, COUNT(*) c FROM visits WHERE is_bot=0 GROUP BY path ORDER BY c DESC LIMIT 8")->fetchAll();

render_header($view, $unreadMsg, $unreadApp);

if ($view === 'messages') {
    $list = $pdo->query("SELECT * FROM messages ORDER BY id DESC LIMIT 300")->fetchAll();
    render_messages($list);
} elseif ($view === 'applications') {
    $list = $pdo->query("SELECT * FROM applications ORDER BY id DESC LIMIT 300")->fetchAll();
    render_applications($list);
} else {
    render_dashboard(compact('totalVisits','todayVisits','visits7','visits30','uniqueVisitors','unreadMsg','unreadApp','days','maxDay','byLang','topPages'));
}
render_footer();


/* ============ Görünümler ============ */
function admin_style(): string {
    return '<style>
    *{box-sizing:border-box;margin:0;font-family:system-ui,-apple-system,"Segoe UI",sans-serif}
    body{background:#eef2f3;color:#1a2430}
    a{color:#1E969C;text-decoration:none}
    .wrap{display:flex;min-height:100vh}
    .side{width:240px;background:#13343a;color:#cfdadd;padding:24px 0;flex-shrink:0}
    .side .logo{font-size:1.3rem;font-weight:700;color:#fff;padding:0 24px 24px;letter-spacing:1px}
    .side .logo span{color:#1E969C}
    .side a{display:block;color:#cfdadd;padding:13px 24px;font-size:14.5px}
    .side a:hover,.side a.on{background:rgba(255,255,255,.08);color:#fff;border-inline-start:3px solid #1E969C}
    .side .badge{background:#e2574c;color:#fff;border-radius:20px;padding:1px 8px;font-size:12px;margin-inline-start:6px}
    .main{flex:1;padding:32px 40px}
    .topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px}
    .topbar h1{font-size:1.5rem;color:#13343a}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:18px;margin-bottom:28px}
    .kpi{background:#fff;border-radius:14px;padding:22px;box-shadow:0 6px 24px rgba(19,52,58,.06)}
    .kpi .n{font-size:2rem;font-weight:700;color:#1E969C}
    .kpi .l{color:#5a6b78;font-size:13.5px;margin-top:4px}
    .panel{background:#fff;border-radius:14px;padding:24px;box-shadow:0 6px 24px rgba(19,52,58,.06);margin-bottom:24px}
    .panel h2{font-size:1.1rem;color:#13343a;margin-bottom:18px}
    .chart{display:flex;align-items:flex-end;gap:8px;height:180px}
    .bar{flex:1;background:linear-gradient(#1E969C,#55bcc1);border-radius:6px 6px 0 0;min-height:3px;position:relative;transition:.2s}
    .bar:hover{opacity:.85}
    .bar span{position:absolute;top:-20px;left:0;right:0;text-align:center;font-size:11px;color:#5a6b78}
    .bar small{position:absolute;bottom:-22px;left:0;right:0;text-align:center;font-size:10px;color:#9aa7b0}
    .two{display:grid;grid-template-columns:1fr 1fr;gap:24px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{text-align:start;padding:11px 12px;border-bottom:1px solid #eef2f3;vertical-align:top}
    th{color:#5a6b78;font-weight:600;font-size:12.5px;text-transform:uppercase;letter-spacing:.05em}
    tr.unread td{background:#f0fafa}
    .pill{display:inline-block;background:#eef2f3;border-radius:20px;padding:2px 10px;font-size:12px;color:#5a6b78}
    .login{max-width:380px;margin:12vh auto;background:#fff;padding:40px;border-radius:16px;box-shadow:0 20px 60px rgba(19,52,58,.12)}
    .login h1{color:#13343a;margin-bottom:6px}.login p{color:#5a6b78;font-size:14px;margin-bottom:24px}
    .login .logo{font-size:1.4rem;font-weight:700;color:#13343a;margin-bottom:20px}.login .logo span{color:#1E969C}
    input{width:100%;padding:12px 14px;border:1px solid #dde4e6;border-radius:9px;font-size:15px;margin-bottom:14px}
    input:focus{outline:none;border-color:#1E969C}
    button{width:100%;background:#1E969C;color:#fff;border:0;padding:13px;border-radius:9px;font-size:15px;font-weight:600;cursor:pointer}
    button:hover{background:#0e6f74}
    .err{background:#fdecec;color:#a4302a;padding:10px 14px;border-radius:8px;font-size:14px;margin-bottom:16px}
    .muted{color:#9aa7b0;font-size:13px}
    @media(max-width:720px){.side{width:64px}.side .logo,.side a span{display:none}.two{grid-template-columns:1fr}.main{padding:20px}}
    </style>';
}
function render_setup($error){ ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Admin Kurulumu</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="login"><div class="logo">REDEX <span>GLASS</span></div><h1>Admin Kurulumu</h1><p>İlk giriş için bir yönetici şifresi belirleyin.</p>
<?php if($error) echo '<div class="err">'.e($error).'</div>'; ?>
<form method="post"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>">
<input type="password" name="password" placeholder="Şifre (min 8 karakter)" required>
<input type="password" name="password2" placeholder="Şifre (tekrar)" required>
<button type="submit">Şifreyi Belirle</button></form></div></body></html>
<?php }
function render_login($error){ ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Admin Giriş</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="login"><div class="logo">REDEX <span>GLASS</span></div><h1>Yönetici Girişi</h1><p>Panele erişmek için şifrenizi girin.</p>
<?php if($error) echo '<div class="err">'.e($error).'</div>'; ?>
<form method="post"><input type="hidden" name="_c" value="<?= e(admin_csrf()) ?>">
<input type="password" name="password" placeholder="Şifre" autofocus required>
<button type="submit">Giriş Yap</button></form></div></body></html>
<?php }
function render_header($view, $um, $ua){ $b = admin_base(); ?>
<!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Redex Admin</title><meta name="viewport" content="width=device-width,initial-scale=1"><?= admin_style() ?></head><body>
<div class="wrap"><aside class="side">
  <div class="logo">REDEX <span>GLASS</span></div>
  <a href="<?= $b ?>/?view=dashboard" class="<?= $view==='dashboard'?'on':'' ?>"><span>📊 Panel</span></a>
  <a href="<?= $b ?>/?view=messages" class="<?= $view==='messages'?'on':'' ?>"><span>✉ Mesajlar</span><?= $um?'<span class="badge">'.$um.'</span>':'' ?></a>
  <a href="<?= $b ?>/?view=applications" class="<?= $view==='applications'?'on':'' ?>"><span>👤 Başvurular</span><?= $ua?'<span class="badge">'.$ua.'</span>':'' ?></a>
  <a href="<?= e(base_url()) ?>" target="_blank"><span>🌐 Siteyi Aç</span></a>
  <a href="<?= $b ?>/?logout=1"><span>⎋ Çıkış</span></a>
</aside><main class="main">
<?php }
function render_dashboard($d){ extract($d); ?>
<div class="topbar"><h1>Genel Bakış</h1><span class="muted"><?= date('d.m.Y H:i') ?></span></div>
<div class="cards">
  <div class="kpi"><div class="n"><?= number_format($todayVisits) ?></div><div class="l">Bugünkü ziyaret</div></div>
  <div class="kpi"><div class="n"><?= number_format($visits7) ?></div><div class="l">Son 7 gün</div></div>
  <div class="kpi"><div class="n"><?= number_format($visits30) ?></div><div class="l">Son 30 gün</div></div>
  <div class="kpi"><div class="n"><?= number_format($uniqueVisitors) ?></div><div class="l">Tekil ziyaretçi (30g)</div></div>
  <div class="kpi"><div class="n"><?= number_format($totalVisits) ?></div><div class="l">Toplam görüntülenme</div></div>
  <div class="kpi"><div class="n"><?= $unreadMsg ?></div><div class="l">Okunmamış mesaj</div></div>
</div>
<div class="panel"><h2>Son 14 gün — sayfa görüntülenmeleri</h2>
  <div class="chart">
    <?php foreach ($days as $day => $c): ?>
      <div class="bar" style="height:<?= round($c / $maxDay * 100) ?>%" title="<?= $day ?>: <?= $c ?>">
        <span><?= $c ?></span><small><?= date('d.m', strtotime($day)) ?></small>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<div class="two">
  <div class="panel"><h2>Dillere göre</h2><table>
    <?php foreach ($byLang as $r): ?><tr><td><span class="pill"><?= e(strtoupper($r['lang'])) ?></span></td><td style="text-align:end"><?= number_format($r['c']) ?></td></tr><?php endforeach; ?>
    <?php if(!$byLang) echo '<tr><td class="muted">Henüz veri yok</td></tr>'; ?>
  </table></div>
  <div class="panel"><h2>En çok görüntülenen sayfalar</h2><table>
    <?php foreach ($topPages as $r): ?><tr><td><?= e($r['path']) ?></td><td style="text-align:end"><?= number_format($r['c']) ?></td></tr><?php endforeach; ?>
    <?php if(!$topPages) echo '<tr><td class="muted">Henüz veri yok</td></tr>'; ?>
  </table></div>
</div>
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
      <td><?= e($m['subject'] ?? '') ?></td>
      <td><?= e($m['country'] ?? '') ?></td>
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
function render_footer(){ echo '</main></div></body></html>'; }
