<?php $products = content_products(); ?>
<?php page_head(t('products.title')); ?>
<section class="section">
  <div class="container">
    <p class="lead" style="margin-bottom:46px"><?= e(t('products.lead')) ?></p>
    <div class="grid grid-3">
      <?php foreach ($products as $p): ?>
        <a class="card" href="<?= e(url('products/' . $p['slug'])) ?>">
          <div class="card-media"><img src="<?= e($p['img']) ?>" alt="<?= e(loc($p['name'])) ?>" loading="lazy"></div>
          <div class="card-body">
            <h3><?= e(loc($p['name'])) ?></h3>
            <p><?= e(loc($p['excerpt'])) ?></p>
            <span class="card-link"><?= e(t('common.learn_more')) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
