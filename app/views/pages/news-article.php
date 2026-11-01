<?php /** @var array $article */ $tr = news_tr($article); ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $tr['title'],
    'description' => $tr['excerpt'],
    'image' => !empty($article['image']) ? asset($article['image']) : asset('img/og-default.jpg'),
    'datePublished' => $article['date'],
    'dateModified' => $article['date'],
    'inLanguage' => lang(),
    'author' => ['@type' => 'Organization', 'name' => 'Redex Glass'],
    'publisher' => ['@type' => 'Organization', 'name' => 'Redex Glass',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('img/wp/favicon.png')]],
    'mainEntityOfPage' => url('news/' . $article['slug']),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
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
