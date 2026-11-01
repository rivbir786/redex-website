<?php /** @var array $service */ ?>
<?php page_head(loc($service['title']), t('nav.services'), [t('nav.services') => url('services')]); ?>
<?php if (!empty($service['wide_img'])): ?>
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
