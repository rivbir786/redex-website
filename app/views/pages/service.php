<?php /** @var array $service */ ?>
<?php page_head(loc($service['title']), t('nav.services'), [t('nav.services') => url('services')]); ?>
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
