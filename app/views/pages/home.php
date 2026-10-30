<?php
$products = array_slice(content_products(), 0, 4);
$services = content_services();
$home = content_home();
$os = $home['onestop'];
$q = $home['quality'];
?>
<section class="hero">
  <video class="hero-video" src="<?= e(cfg('hero_video')) ?>" autoplay muted loop playsinline preload="auto" aria-hidden="true"></video>
  <div class="container">
    <h1><?= e(loc($home['hero']['title'])) ?><span><?= e(loc($home['hero']['accent'])) ?></span></h1>
    <p><?= e(loc($home['hero']['sub'])) ?></p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= e(url('products')) ?>"><?= e(t('home.cta_products')) ?></a>
    </div>
  </div>
</section>

<!-- Intro + YouTube video -->
<section class="section">
  <div class="container split reveal">
    <div>
      <p class="eyebrow"><?= e(cfg('site_name')) ?></p>
      <h2 class="section-title"><?= e(t('home.intro_lead')) ?></h2>
      <p class="prose"><?= e(t('home.intro_body')) ?></p>
      <p style="margin-top:26px"><a class="btn btn-ghost" href="<?= e(url('about')) ?>"><?= e(t('common.learn_more')) ?></a></p>
    </div>
    <div class="video-box">
      <iframe src="https://www.youtube-nocookie.com/embed/<?= e(cfg('youtube_id')) ?>?rel=0" title="Redex Glass"
              loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>
</section>

<!-- Quality + inspection video -->
<section class="section section-soft">
  <div class="container split">
    <div class="video-box">
      <iframe src="https://www.youtube-nocookie.com/embed/<?= e(cfg('quality_youtube_id')) ?>?rel=0" title="<?= e(loc($q['title'])) ?>"
              loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div>
      <p class="eyebrow"><?= e(loc($q['eyebrow'])) ?></p>
      <h2 class="section-title"><?= e(loc($q['title'])) ?></h2>
      <p class="prose"><?= e(loc($q['body'])) ?></p>
      <p style="margin-top:26px"><a class="btn btn-primary" href="<?= e(url('quality')) ?>"><?= e(loc($q['cta'])) ?></a></p>
    </div>
  </div>
</section>

<!-- One-Stop Shop -->
<section class="section">
  <div class="container split">
    <div>
      <p class="eyebrow"><?= e(loc($os['eyebrow'])) ?></p>
      <h2 class="section-title"><?= e(loc($os['title'])) ?></h2>
      <p class="prose"><?= e(loc($os['body'])) ?></p>
      <ul class="onestop-list">
        <?php foreach ($os['items'] as $it): ?>
          <li><?= e(loc($it)) ?></li>
        <?php endforeach; ?>
      </ul>
      <p style="margin-top:26px"><a class="btn btn-ghost" href="<?= e(url('services')) ?>"><?= e(t('common.our_services')) ?></a></p>
    </div>
    <img src="<?= e(asset('img/onestop.jpg')) ?>" alt="<?= e(loc($os['title'])) ?>" loading="lazy">
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
