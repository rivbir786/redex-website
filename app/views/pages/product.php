<?php /** @var array $product */
$related = array_values(array_filter(content_products(), fn($p) => $p['slug'] !== $product['slug']));
$related = array_slice($related, 0, 3);
?>
<?php page_head(loc($product['name']), t('nav.products'), [t('nav.products') => url('products')]); ?>
<section class="section">
  <div class="container split">
    <div class="card-media" style="border:1px solid var(--line);border-radius:var(--radius);aspect-ratio:1/1">
      <img src="<?= e($product['img']) ?>" alt="<?= e(loc($product['name'])) ?>">
    </div>
    <div>
      <h2 class="section-title" style="font-size:1.9rem"><?= e(loc($product['name'])) ?></h2>
      <p class="prose"><?= e(loc($product['excerpt'])) ?></p>
      <p class="prose"><?= e(loc($product['body'])) ?></p>
      <?php if (!empty($product['specs'])): $sp = $product['specs']; ?>
        <table class="spec-table">
          <?php if (!empty($sp['sizes'])): ?><tr><th><?= e(t('products.spec_sizes')) ?></th><td><?= e($sp['sizes']) ?></td></tr><?php endif; ?>
          <?php if (!empty($sp['colors'])): ?><tr><th><?= e(t('products.spec_colors')) ?></th><td><?= e($sp['colors']) ?></td></tr><?php endif; ?>
          <?php if (!empty($sp['neck']) && $sp['neck'] !== '—'): ?><tr><th><?= e(t('products.spec_neck')) ?></th><td><?= e($sp['neck']) ?></td></tr><?php endif; ?>
          <?php if (!empty($sp['code'])): ?><tr><th><?= e(t('products.spec_code')) ?></th><td><?= e($sp['code']) ?></td></tr><?php endif; ?>
        </table>
        <p class="spec-note"><?= e(t('products.specs_title')) ?> — <a href="<?= e(cfg('catalog_pdf')) ?>" target="_blank" rel="noopener"><?= e(t('nav.catalog')) ?> (PDF)</a></p>
      <?php endif; ?>
      <p style="margin-top:28px"><a class="btn btn-primary" href="<?= e(url('contact')) ?>"><?= e(t('products.detail_cta')) ?></a></p>
    </div>
  </div>
</section>

<?php $specTables = content_specs($product['slug']); if ($specTables): ?>
<section class="section">
  <div class="container">
    <h2 class="section-title" style="font-size:1.6rem"><?= e(t('products.range_title')) ?></h2>
    <p class="prose" style="margin-top:8px"><?= e(t('products.range_intro')) ?></p>
    <div class="spec-tables">
      <?php foreach ($specTables as $tbl): ?>
        <?php if (!empty($tbl['label'])): ?><h3 class="spec-caption"><?= e($tbl['label']) ?></h3><?php endif; ?>
        <div class="spec-scroll">
          <table class="spec-list">
            <thead><tr>
              <?php foreach ($tbl['headers'] as $h): ?><th><?= e($h) ?></th><?php endforeach; ?>
            </tr></thead>
            <tbody>
              <?php foreach ($tbl['rows'] as $row): ?>
                <tr>
                  <?php foreach ($row as $cell): ?>
                    <td><?php if ($cell === '' ) { echo ''; } elseif (strtoupper($cell) === 'N/A') { echo '<span class="na">—</span>'; } else { echo e($cell); } ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:22px"><a class="btn btn-ghost" href="<?= e(cfg('catalog_pdf')) ?>" target="_blank" rel="noopener"><?= e(t('nav.catalog')) ?> (PDF)</a></p>
  </div>
</section>
<?php endif; ?>

<section class="section section-soft">
  <div class="container">
    <div class="center"><p class="eyebrow"><?= e(t('common.our_capabilities')) ?></p><h2 class="section-title"><?= e(t('products.title')) ?></h2></div>
    <div class="grid grid-3" style="margin-top:44px">
      <?php foreach ($related as $p): ?>
        <a class="card" href="<?= e(url('products/' . $p['slug'])) ?>">
          <div class="card-media"><img src="<?= e($p['img']) ?>" alt="<?= e(loc($p['name'])) ?>" loading="lazy"></div>
          <div class="card-body"><h3><?= e(loc($p['name'])) ?></h3><span class="card-link"><?= e(t('common.learn_more')) ?></span></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
