<?php $articles = content_news(); ?>
<?php page_head(t('news.title')); ?>
<section class="section">
  <div class="container">
    <p class="lead" style="margin-bottom:46px"><?= e(t('news.lead')) ?></p>
    <?php if (empty($articles)): ?>
      <div class="cert" style="text-align:center;padding:60px 24px"><p class="prose" style="margin:0"><?= e(t('news.empty')) ?></p></div>
    <?php else: ?>
    <div class="grid grid-3">
      <?php foreach ($articles as $a): $tr = news_tr($a); ?>
        <a class="card" href="<?= e(url('news/' . $a['slug'])) ?>">
          <?php if (!empty($a['image'])): ?>
            <div class="card-media photo"><img src="<?= e(asset($a['image'])) ?>" alt="<?= e($tr['title']) ?>" loading="lazy"></div>
          <?php endif; ?>
          <div class="card-body">
            <p class="post-date"><?= e(date('d M Y', strtotime($a['date']))) ?></p>
            <h3><?= e($tr['title']) ?></h3>
            <p><?= e(mb_strimwidth($tr['excerpt'], 0, 130, '…')) ?></p>
            <span class="card-link"><?= e(t('news.read_more')) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
