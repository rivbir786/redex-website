<?php $services = content_services(); ?>
<?php page_head(t('services.title')); ?>
<section class="section">
  <div class="container">
    <p class="lead" style="margin-bottom:46px"><?= e(t('services.lead')) ?></p>
    <div class="grid grid-3">
      <?php foreach ($services as $k => $s): ?>
        <a class="card" href="<?= e(url('services/' . $k)) ?>">
          <div class="card-media"><img src="<?= e($s['img']) ?>" alt="<?= e(loc($s['title'])) ?>" loading="lazy"></div>
          <div class="card-body">
            <h3><?= e(loc($s['title'])) ?></h3>
            <p><?= e(loc($s['excerpt'])) ?></p>
            <span class="card-link"><?= e(t('common.learn_more')) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
