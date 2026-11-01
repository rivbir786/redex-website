<?php /** @var array $service */ ?>
<?php page_head(loc($service['title']), t('nav.services'), [t('nav.services') => url('services')]); ?>
<?php if (!empty($service['closures'])): ?>
<!-- One-Stop: kapaklar ve kapama ürünleri (geniş görsel + kategori listesi) -->
<section class="section">
  <div class="container">
    <img class="process-img" src="<?= e($service['wide_img']) ?>" alt="<?= e(loc($service['title'])) ?>" loading="lazy" style="box-shadow:none;border-radius:0;background:#fff">
    <div class="process-copy">
      <p class="lead"><?= e(loc($service['excerpt'])) ?></p>
      <p class="prose" style="max-width:820px;margin:18px auto 0;text-align:center"><?= e(loc($service['body'])) ?></p>
    </div>
    <div class="grid grid-3" style="margin-top:48px">
      <?php foreach ($service['closures'] as $c): ?>
        <div class="card"><div class="card-body">
          <h3><?= e(loc($c['name'])) ?></h3>
          <p><?= e(loc($c['desc'])) ?></p>
        </div></div>
      <?php endforeach; ?>
    </div>
    <p class="center" style="margin-top:38px"><a class="btn btn-primary" href="<?= e(url('contact')) ?>"><?= e(t('contact.title')) ?></a></p>
  </div>
</section>
<?php elseif (!empty($service['wide_img'])): ?>
<!-- Geniş süreç görselli servis (custom bottle design) -->
<section class="section">
  <div class="container">
    <img class="process-img" src="<?= e($service['wide_img']) ?>" alt="<?= e(loc($service['title'])) ?>" loading="lazy">
    <div class="process-copy">
      <p class="lead"><?= e(loc($service['excerpt'])) ?></p>
      <div class="process-tracks">
        <div>
          <h3 class="process-h"><?= e(loc($service['glass_h'])) ?></h3>
          <p class="prose"><?= e(loc($service['glass_t'])) ?></p>
        </div>
        <div>
          <h3 class="process-h"><?= e(loc($service['plastic_h'])) ?></h3>
          <p class="prose"><?= e(loc($service['plastic_t'])) ?></p>
        </div>
      </div>
      <p class="center" style="margin-top:30px"><a class="btn btn-primary" href="<?= e(url('contact')) ?>"><?= e(t('contact.title')) ?></a></p>
    </div>
  </div>
</section>
<?php else: ?>
<section class="section">
  <div class="container split">
    <img src="<?= e($service['img']) ?>" alt="<?= e(loc($service['title'])) ?>" loading="lazy">
    <div>
      <h2 class="section-title"><?= e(loc($service['title'])) ?></h2>
      <p class="prose"><?= e(loc($service['excerpt'])) ?></p>
      <p class="prose"><?= e(loc($service['body'])) ?></p>
      <p style="margin-top:28px"><a class="btn btn-primary" href="<?= e(url('contact')) ?>"><?= e(t('contact.title')) ?></a></p>
    </div>
  </div>
</section>
<?php endif; ?>
