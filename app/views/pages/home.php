<?php
$stats = content_stats();
$products = array_slice(content_products(), 0, 4);
$services = content_services();
?>
<section class="hero">
  <video class="hero-video" autoplay muted loop playsinline poster="https://redexglass.com/wp-content/uploads/2026/01/Mockup-1--scaled.jpg">
    <source src="<?= e(cfg('hero_video')) ?>" type="video/mp4">
  </video>
  <div class="container">
    <h1><?= e(t('home.hero_title')) ?><span><?= e(t('home.hero_accent')) ?></span></h1>
    <p><?= e(t('home.hero_sub')) ?></p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= e(url('products')) ?>"><?= e(t('home.cta_products')) ?></a>
      <a class="btn btn-outline" href="<?= e(url('contact')) ?>"><?= e(t('home.cta_contact')) ?></a>
    </div>
  </div>
</section>

<!-- Intro -->
<section class="section">
  <div class="container split reveal">
    <div>
      <p class="eyebrow"><?= e(cfg('site_name')) ?></p>
      <h2 class="section-title"><?= e(t('home.intro_lead')) ?></h2>
      <p class="prose"><?= e(t('home.intro_body')) ?></p>
      <p style="margin-top:26px"><a class="btn btn-ghost" href="<?= e(url('about')) ?>"><?= e(t('common.learn_more')) ?></a></p>
    </div>
    <img src="https://redexglass.com/wp-content/uploads/2026/01/Ekran-Resmi-2026-01-06-14.02.56-scaled.png" alt="Redex Glass production" loading="lazy">
  </div>
</section>

<!-- Stats -->
<section class="section section-soft">
  <div class="container center">
    <p class="eyebrow"><?= e(t('about.stats_title')) ?></p>
    <div class="stats">
      <?php foreach ($stats as $s): ?>
        <div class="stat reveal"><div class="num"><?= e($s['value']) ?></div><div class="lbl"><?= e(loc($s['label'])) ?></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Products preview -->
<section class="section">
  <div class="container">
    <div class="center">
      <p class="eyebrow"><?= e(t('common.our_capabilities')) ?></p>
      <h2 class="section-title"><?= e(t('products.title')) ?></h2>
      <p class="lead"><?= e(t('products.lead')) ?></p>
    </div>
    <div class="grid grid-4" style="margin-top:50px">
      <?php foreach ($products as $p): ?>
        <a class="card reveal" href="<?= e(url('products/' . $p['slug'])) ?>">
          <div class="card-media"><img src="<?= e($p['img']) ?>" alt="<?= e(loc($p['name'])) ?>" loading="lazy"></div>
          <div class="card-body"><h3><?= e(loc($p['name'])) ?></h3><span class="card-link"><?= e(t('common.learn_more')) ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="center" style="margin-top:40px"><a class="btn btn-ghost" href="<?= e(url('products')) ?>"><?= e(t('common.all_products')) ?></a></p>
  </div>
</section>

<!-- Services -->
<section class="section section-soft">
  <div class="container">
    <div class="center">
      <p class="eyebrow"><?= e(t('home.services_title')) ?></p>
      <h2 class="section-title"><?= e(t('common.our_services')) ?></h2>
      <p class="lead"><?= e(t('home.services_intro')) ?></p>
    </div>
    <div class="svc-list" style="margin-top:46px">
      <?php $i=1; foreach ($services as $k => $s): ?>
        <a class="svc reveal" href="<?= e(url('services/' . $k)) ?>">
          <span class="n"><?= $i++ ?></span>
          <div><h3><?= e(loc($s['title'])) ?></h3><p><?= e(loc($s['excerpt'])) ?></p></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- YouTube video box -->
<section class="section">
  <div class="container center">
    <p class="eyebrow"><?= e(cfg('site_name')) ?></p>
    <h2 class="section-title"><?= e(t('home.video_title')) ?></h2>
    <p class="lead" style="margin-bottom:40px"><?= e(t('home.video_sub')) ?></p>
    <div class="video-box" data-yt="<?= e(cfg('youtube_id')) ?>" role="button" tabindex="0" aria-label="Play video">
      <img class="video-thumb" src="https://i.ytimg.com/vi/<?= e(cfg('youtube_id')) ?>/maxresdefault.jpg" alt="Redex Glass video" loading="lazy">
      <span class="video-play" aria-hidden="true"></span>
    </div>
  </div>
</section>
