<?php $q = content_quality(); ?>
<?php page_head(t('quality.title'), t('nav.corporate')); ?>
<section class="section">
  <div class="container split">
    <div class="video-box" data-yt="<?= e(cfg('quality_youtube_id')) ?>" data-title="<?= e(loc($q['lead'])) ?>"></div>
    <div>
      <h2 class="section-title"><?= e(loc($q['lead'])) ?></h2>
      <p class="prose"><?= e(loc($q['body'])) ?></p>
      <p style="margin-top:24px"><a class="btn btn-ghost" href="<?= e(url('certificates')) ?>"><?= e(t('nav.certificates')) ?></a></p>
    </div>
  </div>
</section>

<section class="section section-soft">
  <div class="container" style="max-width:900px">
    <h2 class="section-title"><?= e(loc($q['tests_title'])) ?></h2>
    <p class="prose" style="max-width:760px"><?= e(loc($q['tests_intro'])) ?></p>
    <ul class="onestop-list">
      <?php foreach ($q['tests'] as $test): ?>
        <li><?= e(loc($test)) ?></li>
      <?php endforeach; ?>
    </ul>
    <p class="prose" style="margin-top:26px;font-style:italic"><?= e(loc($q['note'])) ?></p>
  </div>
</section>
