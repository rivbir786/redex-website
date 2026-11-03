<?php $stats = content_stats(); ?>
<?php page_head(t('about.title'), t('nav.corporate')); ?>

<section class="section">
  <div class="container split">
    <div>
      <p class="eyebrow"><?= e(t('about.title')) ?></p>
      <h2 class="section-title" style="font-size:2.15rem;line-height:1.2;max-width:16ch"><?= e(t('about.lead')) ?></h2>
      <p class="prose" style="font-size:1.08rem"><?= e(t('about.intro')) ?></p>
    </div>
    <img src="<?= e(asset('img/wp/about.jpg')) ?>" alt="<?= e(t('about.img_alt')) ?>" loading="lazy">
  </div>
</section>

<section class="section section-soft">
  <div class="container center">
    <h2 class="section-title"><?= e(t('about.story_title')) ?></h2>
    <p class="prose" style="max-width:760px;margin:18px auto 0"><?= e(t('about.story_body')) ?></p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="center">
      <h2 class="section-title"><?= e(t('about.values_title')) ?></h2>
      <p class="lead"><?= e(t('about.values_lead')) ?></p>
    </div>
    <div class="values-grid">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="value">
          <span class="value-n"><?= sprintf('%02d', $i) ?></span>
          <h3><?= e(t("about.a{$i}_t")) ?></h3>
          <p><?= e(t("about.a{$i}_d")) ?></p>
        </div>
      <?php endfor; ?>
    </div>
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
    <p class="mv-closing"><?= e(t('about.closing')) ?></p>
  </div>
</section>
