<?php $stats = content_stats(); ?>
<?php page_head(t('about.title'), t('nav.corporate')); ?>
<section class="section">
  <div class="container split">
    <div>
      <h2 class="section-title"><?= e(t('about.lead')) ?></h2>
      <p class="prose"><?= e(t('about.body')) ?></p>
    </div>
    <img src="https://redexglass.com/wp-content/uploads/2026/01/Ekran-Resmi-2026-01-06-14.02.56-scaled.png" alt="Redex Glass" loading="lazy">
  </div>
</section>
<section class="section section-soft">
  <div class="container center">
    <p class="eyebrow"><?= e(t('about.stats_title')) ?></p>
    <div class="stats">
      <?php foreach ($stats as $s): ?>
        <div class="stat"><div class="num"><?= e($s['value']) ?></div><div class="lbl"><?= e(loc($s['label'])) ?></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
