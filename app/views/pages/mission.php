<?php page_head(t('mission.title'), t('nav.corporate')); ?>

<section class="section">
  <div class="container split">
    <div>
      <p class="eyebrow"><?= e(t('mission.title')) ?></p>
      <h2 class="section-title" style="font-size:2.15rem;line-height:1.2;max-width:14ch"><?= e(t('mission.lead')) ?></h2>
      <p class="prose" style="font-size:1.08rem"><?= e(t('mission.intro')) ?></p>
    </div>
    <img src="<?= e(asset('img/mission-hero.jpg')) ?>" alt="<?= e(cfg('company')) ?> — amber glass, lit from within" loading="lazy">
  </div>
</section>

<section class="section section-soft">
  <div class="container">
    <div class="mv-statements">
      <div class="mv-stmt">
        <p class="eyebrow"><?= e(t('mission.mission_title')) ?></p>
        <p class="mv-text"><?= e(t('mission.mission_body')) ?></p>
      </div>
      <div class="mv-stmt">
        <p class="eyebrow"><?= e(t('mission.vision_title')) ?></p>
        <p class="mv-text"><?= e(t('mission.vision_body')) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="center">
      <h2 class="section-title"><?= e(t('mission.values_title')) ?></h2>
      <p class="lead"><?= e(t('mission.values_lead')) ?></p>
    </div>
    <div class="values-grid">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="value">
          <span class="value-n"><?= sprintf('%02d', $i) ?></span>
          <h3><?= e(t("mission.v{$i}_t")) ?></h3>
          <p><?= e(t("mission.v{$i}_d")) ?></p>
        </div>
      <?php endfor; ?>
    </div>
    <p class="mv-closing"><?= e(t('mission.closing')) ?></p>
  </div>
</section>
