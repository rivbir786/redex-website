<?php /** @var array $article */ $tr = news_tr($article); ?>
<?php page_head($tr['title'], t('nav.news'), [t('nav.news') => url('news')]); ?>
<section class="section">
  <div class="container" style="max-width:820px">
    <p class="post-date"><?= e(date('d M Y', strtotime($article['date']))) ?></p>
    <?php if (!empty($article['image'])): ?>
      <img class="news-hero" src="<?= e(asset($article['image'])) ?>" alt="<?= e($tr['title']) ?>" loading="lazy">
    <?php endif; ?>
    <div class="prose news-body"><?= $tr['body'] ?></div>
    <p style="margin-top:40px"><a class="btn btn-ghost" href="<?= e(url('news')) ?>"><?= e(t('news.back')) ?></a></p>
  </div>
</section>
