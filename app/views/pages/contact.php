<?php $offices = content_offices(); ?>
<?php page_head(t('contact.title')); ?>
<section class="section">
  <div class="container contact-grid">
    <div>
      <p class="eyebrow"><?= e(t('contact.offices_title')) ?></p>
      <h2 class="section-title" style="font-size:1.9rem;margin-bottom:26px"><?= e(t('contact.lead')) ?></h2>
      <?php foreach ($offices as $o): ?>
        <div class="office">
          <h3><?= e(loc($o['name'])) ?></h3>
          <p><?= e(loc($o['address'])) ?></p>
          <?php if (!empty($o['contact'])): ?><p>👤 <?= e($o['contact']) ?></p><?php endif; ?>
          <?php if (!empty($o['phone'])): ?><p>📞 <a href="tel:<?= e(preg_replace('/\s+/', '', $o['phone'])) ?>"><?= e($o['phone']) ?></a></p><?php endif; ?>
          <p>✉ <a href="mailto:<?= e($o['email']) ?>"><?= e($o['email']) ?></a></p>
          <?php if (!empty($o['map'])): ?><p><a class="card-link" href="<?= e($o['map']) ?>" target="_blank" rel="noopener"><?= e(t('common.get_directions')) ?></a></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div id="form">
      <p class="eyebrow"><?= e(t('contact.form_title')) ?></p>
      <h2 class="section-title" style="font-size:1.9rem;margin-bottom:22px"><?= e(t('contact.form_title')) ?></h2>
      <?= flash_html() ?>
      <form method="post" action="<?= e(url('contact')) ?>">
        <?= csrf_field() ?>
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
        <div class="row">
          <div class="field"><label><?= e(t('form.name')) ?> *</label><input type="text" name="name" required></div>
          <div class="field"><label><?= e(t('form.company')) ?></label><input type="text" name="company"></div>
        </div>
        <div class="row">
          <div class="field"><label><?= e(t('form.email')) ?> *</label><input type="email" name="email" required></div>
          <div class="field"><label><?= e(t('form.phone')) ?></label><input type="tel" name="phone"></div>
        </div>
        <div class="row">
          <div class="field"><label><?= e(t('form.country')) ?></label><input type="text" name="country"></div>
          <div class="field"><label><?= e(t('form.subject')) ?> *</label>
            <select name="subject" required>
              <option value=""><?= e(t('form.choose')) ?></option>
              <option value="<?= e(t('form.subj_general')) ?>"><?= e(t('form.subj_general')) ?></option>
              <option value="<?= e(t('form.subj_quote')) ?>"><?= e(t('form.subj_quote')) ?></option>
              <option value="<?= e(t('form.subj_product')) ?>"><?= e(t('form.subj_product')) ?></option>
              <option value="<?= e(t('form.subj_partnership')) ?>"><?= e(t('form.subj_partnership')) ?></option>
              <option value="<?= e(t('form.subj_other')) ?>"><?= e(t('form.subj_other')) ?></option>
            </select>
          </div>
        </div>
        <div class="field"><label><?= e(t('form.message')) ?> *</label><textarea name="message" required></textarea></div>
        <label class="check"><input type="checkbox" required> <span><?= e(t('form.privacy_agree')) ?></span></label>
        <p style="margin-top:22px"><button class="btn btn-primary" type="submit"><?= e(t('form.send')) ?></button></p>
      </form>
    </div>
  </div>
</section>
