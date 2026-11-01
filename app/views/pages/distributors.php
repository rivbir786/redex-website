<?php $d = content_distributors(); ?>
<?php page_head(t('distributors.title')); ?>
<section class="section">
  <div class="container" style="max-width:860px">
    <h2 class="section-title"><?= e(loc($d['lead'])) ?></h2>
    <p class="prose"><?= e(loc($d['intro'])) ?></p>
    <p class="prose"><?= e(loc($d['body'])) ?></p>

    <h3 style="margin-top:44px;color:var(--brand-dark);font-family:var(--head);font-size:1.3rem"><?= e(loc($d['offer_title'])) ?></h3>
    <ul class="onestop-list">
      <?php foreach ($d['offer'] as $it): ?><li><?= e(loc($it)) ?></li><?php endforeach; ?>
    </ul>

    <h3 style="margin-top:36px;color:var(--brand-dark);font-family:var(--head);font-size:1.3rem"><?= e(loc($d['seek_title'])) ?></h3>
    <ul class="onestop-list">
      <?php foreach ($d['seek'] as $it): ?><li><?= e(loc($it)) ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section section-soft" id="form">
  <div class="container" style="max-width:760px">
    <div class="center"><p class="eyebrow"><?= e(t('nav.distributors')) ?></p><h2 class="section-title"><?= e(t('distributors.title')) ?></h2></div>
    <p class="prose" style="max-width:640px;margin:0 auto 10px;text-align:center"><?= e(loc($d['form_note'])) ?></p>
    <?= flash_html() ?>
    <form method="post" action="<?= e(url('distributors')) ?>" style="margin-top:26px">
      <?= csrf_field() ?>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
      <div class="row">
        <div class="field"><label><?= e(t('form.company')) ?> *</label><input type="text" name="company" required></div>
        <div class="field"><label><?= e(t('form.contact_name')) ?> *</label><input type="text" name="contact_name" required></div>
      </div>
      <div class="row">
        <div class="field"><label><?= e(t('form.email')) ?> *</label><input type="email" name="email" required></div>
        <div class="field"><label><?= e(t('form.phone')) ?></label><input type="tel" name="phone"></div>
      </div>
      <div class="row">
        <div class="field"><label><?= e(t('form.website')) ?></label><input type="url" name="website_url" placeholder="https://"></div>
        <div class="field"><label><?= e(t('form.annual_volume')) ?></label><input type="text" name="annual_volume"></div>
      </div>
      <div class="row">
        <div class="field"><label><?= e(t('form.country')) ?></label><input type="text" name="country"></div>
        <div class="field"><label><?= e(t('form.city')) ?></label><input type="text" name="city"></div>
      </div>
      <div class="field"><label><?= e(t('form.address')) ?></label><input type="text" name="address"></div>
      <div class="field"><label><?= e(t('form.message')) ?></label><textarea name="message"></textarea></div>
      <?= captcha_field() ?>
      <label class="check"><input type="checkbox" required> <span><?= e(t('form.privacy_agree')) ?></span></label>
      <p style="margin-top:22px"><button class="btn btn-primary" type="submit"><?= e(t('form.submit')) ?></button></p>
    </form>
  </div>
</section>
