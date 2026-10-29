<?php page_head(t('mission.title'), t('nav.corporate')); ?>
<section class="section">
  <div class="container">
    <div class="grid" style="grid-template-columns:1fr 1fr;gap:40px">
      <div class="cert" style="padding:40px">
        <p class="eyebrow"><?= e(t('mission.mission_title')) ?></p>
        <p class="prose"><?= e(t('mission.mission_body')) ?></p>
      </div>
      <div class="cert" style="padding:40px">
        <p class="eyebrow"><?= e(t('mission.vision_title')) ?></p>
        <p class="prose"><?= e(t('mission.vision_body')) ?></p>
      </div>
    </div>
  </div>
</section>
