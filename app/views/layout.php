<?php /** @var array $seo @var string $__view */ ?>
<!doctype html>
<html lang="<?= e(lang()) ?>" dir="<?= e(lang_dir()) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']) ?></title>
    <meta name="description" content="<?= e($seo['description']) ?>">
<?php if (cfg('noindex')): ?>
    <meta name="robots" content="noindex, nofollow">
<?php else: ?>
    <meta name="robots" content="index, follow, max-image-preview:large">
<?php endif; ?>
    <meta name="theme-color" content="<?= e(cfg('brand_color')) ?>">
    <link rel="canonical" href="<?= e($seo['canonical']) ?>">
    <?php foreach ($seo['alternates'] as $l => $href): ?>
    <link rel="alternate" hreflang="<?= e(hreflang_code($l)) ?>" href="<?= e($href) ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= e($seo['alternates'][cfg('default_lang')]) ?>">

    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="<?= e($seo['og_type'] ?? 'website') ?>">
    <meta property="og:site_name" content="<?= e(cfg('site_name')) ?>">
    <meta property="og:title" content="<?= e($seo['title']) ?>">
    <meta property="og:description" content="<?= e($seo['description']) ?>">
    <meta property="og:url" content="<?= e($seo['canonical']) ?>">
    <meta property="og:locale" content="<?= e(cfg('languages')[lang()]['locale']) ?>">
<?php foreach ($seo['alternates'] as $l => $href): if ($l === lang()) continue; ?>
    <meta property="og:locale:alternate" content="<?= e(cfg('languages')[$l]['locale']) ?>">
<?php endforeach; ?>
    <meta property="og:image" content="<?= e($seo['og_image']) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seo['title']) ?>">
    <meta name="twitter:description" content="<?= e($seo['description']) ?>">
    <meta name="twitter:image" content="<?= e($seo['og_image']) ?>">

    <link rel="icon" href="<?= e(asset('img/wp/favicon.png')) ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
    <link rel="preload" href="<?= e(asset('fonts/jost-latin-400-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= e(asset('css/fonts.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">

    <!-- Organization schema (SEO) -->
    <?php foreach ($seo['jsonld'] ?? [] as $__ld): ?>
    <script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org'] + $__ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endforeach; ?>
</head>
<body class="lang-<?= e(lang()) ?>">
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
    <?php require $__view; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/cookie-consent.php'; ?>
<script nonce="<?= e(csp_nonce()) ?>">window.REDEX_CC={blocked:<?= json_encode(t('cookie.blocked_notice'), JSON_UNESCAPED_UNICODE) ?>,load:<?= json_encode(t('cookie.load_video'), JSON_UNESCAPED_UNICODE) ?>};</script>
<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?php if (!empty($GLOBALS['__vtoken'])): ?>
<script nonce="<?= e(csp_nonce()) ?>">(function(){var t=<?= json_encode($GLOBALS['__vtoken']) ?>,start=Date.now(),sent=false;
function send(){if(sent)return;var sec=Math.round((Date.now()-start)/1000);if(sec<2)return;sent=true;
try{navigator.sendBeacon(<?= json_encode(base_url().'/_track') ?>,new URLSearchParams({t:t,s:sec}));}catch(e){}}
document.addEventListener('visibilitychange',function(){if(document.visibilityState==='hidden')send();});
window.addEventListener('pagehide',send);})();</script>
<?php endif; ?>
</body>
</html>
