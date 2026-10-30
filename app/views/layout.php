<?php /** @var array $seo @var string $__view */ ?>
<!doctype html>
<html lang="<?= e(lang()) ?>" dir="<?= e(lang_dir()) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']) ?></title>
    <meta name="description" content="<?= e($seo['description']) ?>">
    <link rel="canonical" href="<?= e($seo['canonical']) ?>">
    <?php foreach ($seo['alternates'] as $l => $href): ?>
    <link rel="alternate" hreflang="<?= e($l) ?>" href="<?= e($href) ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= e($seo['alternates'][cfg('default_lang')]) ?>">

    <!-- Open Graph / Twitter -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(cfg('site_name')) ?>">
    <meta property="og:title" content="<?= e($seo['title']) ?>">
    <meta property="og:description" content="<?= e($seo['description']) ?>">
    <meta property="og:url" content="<?= e($seo['canonical']) ?>">
    <meta property="og:locale" content="<?= e(cfg('languages')[lang()]['locale']) ?>">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="https://redexglass.com/wp-content/uploads/2026/01/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">

    <!-- Organization schema (SEO) -->
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Redex Glass',
        'url' => base_url(),
        'logo' => 'https://redexglass.com/wp-content/uploads/2026/01/favicon.png',
        'sameAs' => array_values(cfg('socials')),
        'email' => cfg('email'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
</head>
<body class="lang-<?= e(lang()) ?>">
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
    <?php require $__view; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>
