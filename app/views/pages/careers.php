<?php $positions = content_positions(); ?>
<?php page_head(t('careers.title')); ?>
<section class="section">
  <div class="container">
    <h2 class="section-title"><?= e(t('careers.lead')) ?></h2>
    <p class="prose" style="max-width:760px"><?= e(t('careers.intro')) ?></p>

    <h3 style="margin-top:50px;color:var(--brand-dark);font-family:var(--serif);font-size:1.5rem"><?= e(t('careers.open_positions')) ?></h3>
    <?php if (empty($positions)): ?>
      <p class="prose"><?= e(t('careers.no_positions')) ?></p>
    <?php else: ?>
      <div class="grid grid-3" style="margin-top:26px">
        <?php foreach ($positions as $pos): ?>
          <div class="card"><div class="card-body">
            <h3><?= e(loc($pos['title'])) ?></h3>
            <p><?= e(($pos['location'] ?? '') . ' · ' . ($pos['type'] ?? '')) ?></p>
            <?php if (!empty($pos['desc'])): ?><p><?= e(loc($pos['desc'])) ?></p><?php endif; ?>
          </div></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section section-soft" id="form">
  <div class="container" style="max-width:760px">
    <div class="center"><p class="eyebrow"><?= e(t('careers.title')) ?></p><h2 class="section-title"><?= e(t('careers.apply_title')) ?></h2></div>
    <?= flash_html() ?>
    <form method="post" action="<?= e(url('careers')) ?>" enctype="multipart/form-data" style="margin-top:30px">
      <?= csrf_field() ?>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
      <div class="row">
        <div class="field"><label><?= e(t('form.name')) ?> *</label><input type="text" name="name" required></div>
        <div class="field"><label><?= e(t('form.email')) ?> *</label><input type="email" name="email" required></div>
      </div>
      <div class="row">
        <div class="field"><label><?= e(t('form.phone')) ?></label><input type="tel" name="phone"></div>
        <div class="field"><label><?= e(t('form.position')) ?></label><input type="text" name="position"></div>
      </div>
      <div class="row">
        <div class="field"><label><?= e(t('form.gender')) ?></label>
          <select name="gender"><option value=""></option><option><?= e(t('form.male')) ?></option><option><?= e(t('form.female')) ?></option></select></div>
        <div class="field"><label><?= e(t('form.marital')) ?></label>
          <select name="marital"><option value=""></option><option><?= e(t('form.single')) ?></option><option><?= e(t('form.married')) ?></option></select></div>
      </div>
      <div class="field"><label><?= e(t('form.message')) ?></label><textarea name="message"></textarea></div>
      <div class="field"><label><?= e(t('form.cv')) ?> <small style="color:var(--muted)">(<?= e(t('form.cv_hint')) ?>)</small></label>
        <input type="file" name="cv" accept=".pdf,.jpg,.jpeg,.png"></div>
      <label class="check"><input type="checkbox" required> <span><?= e(t('form.privacy_agree')) ?></span></label>
      <p style="margin-top:22px"><button class="btn btn-primary" type="submit"><?= e(t('careers.apply')) ?></button></p>
    </form>
  </div>
</section>
