<?php $certs = content_certificates(); ?>
<?php page_head(t('certificates.title'), t('nav.corporate')); ?>
<section class="section">
  <div class="container">
    <p class="lead" style="margin-bottom:40px"><?= e(t('certificates.lead')) ?></p>
    <div class="cert-grid">
      <?php foreach ($certs as $c): ?>
        <div class="cert">
          <div class="code"><?= e($c['code']) ?></div>
          <div class="d"><?= e(loc($c['desc'])) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
