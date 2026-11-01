<?php page_head(t('sustainability.title'), t('nav.corporate')); ?>

<section class="section">
  <div class="container split">
    <div>
      <p class="eyebrow"><?= e(t('sustainability.title')) ?></p>
      <h2 class="section-title" style="font-size:2.15rem;line-height:1.2;max-width:15ch"><?= e(t('sustainability.lead')) ?></h2>
      <p class="prose" style="font-size:1.08rem"><?= e(t('sustainability.intro')) ?></p>
    </div>
    <img src="<?= e(asset('img/sustainability-hero.jpg')) ?>" alt="<?= e(cfg('company')) ?> — amber and flint glass bottles with fresh botanicals" loading="lazy">
  </div>
</section>

<section class="section section-soft">
  <div class="container">
    <div class="center">
      <h2 class="section-title"><?= e(t('sustainability.material_title')) ?></h2>
      <p class="lead"><?= e(t('sustainability.material_lead')) ?></p>
    </div>
    <div class="stats" style="grid-template-columns:repeat(3,1fr)">
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <div class="stat">
          <div class="num"><?= e(t("sustainability.stat{$i}_n")) ?></div>
          <div class="lbl"><?= e(t("sustainability.stat{$i}_t")) ?></div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="center">
      <h2 class="section-title"><?= e(t('sustainability.pillars_title')) ?></h2>
      <p class="lead"><?= e(t('sustainability.pillars_lead')) ?></p>
    </div>
    <div class="values-grid">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="value">
          <span class="value-n"><?= sprintf('%02d', $i) ?></span>
          <h3><?= e(t("sustainability.p{$i}_t")) ?></h3>
          <p><?= e(t("sustainability.p{$i}_d")) ?></p>
        </div>
      <?php endfor; ?>
    </div>
    <p class="mv-closing"><?= e(t('sustainability.closing')) ?></p>
  </div>
</section>
