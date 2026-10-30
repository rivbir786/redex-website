<?php /* Çerez / gizlilik onayı — banner + tercih modalı. Metinler app/lang/*.php 'cookie' bölümünden. */ ?>
<!-- Cookie / privacy consent -->
<div class="cc-banner cc-hidden" id="ccBanner" role="dialog" aria-live="polite" aria-label="<?= e(t('cookie.title')) ?>">
  <div class="cc-head"><span class="cc-logo" aria-hidden="true"></span><h3><?= e(t('cookie.title')) ?></h3></div>
  <p><?= e(t('cookie.intro')) ?> <a href="<?= e(url('privacy-policy')) ?>"><?= e(t('cookie.policy_link')) ?></a></p>
  <div class="cc-actions">
    <div class="cc-actions-row">
      <button type="button" class="btn btn-ghost" id="ccEssential"><?= e(t('cookie.essential_only')) ?></button>
      <button type="button" class="btn btn-primary" id="ccAll"><?= e(t('cookie.accept_all')) ?></button>
    </div>
    <button type="button" class="cc-link-btn" id="ccPrefs"><?= e(t('cookie.individual')) ?></button>
  </div>
</div>

<div class="cc-modal-back cc-hidden" id="ccModalBack" role="dialog" aria-modal="true" aria-label="<?= e(t('cookie.title')) ?>">
  <div class="cc-modal">
    <div class="cc-modal-head">
      <span class="cc-logo" aria-hidden="true"></span>
      <h2><?= e(t('cookie.title')) ?></h2>
      <button type="button" class="cc-x" id="ccClose" aria-label="<?= e(t('cookie.close')) ?>">&times;</button>
    </div>
    <div class="cc-modal-body">
      <p><?= e(t('cookie.modal_intro')) ?></p>

      <div class="cc-cat">
        <div class="cc-cat-top">
          <h4><?= e(t('cookie.cat_essential')) ?></h4>
          <span class="cc-badge"><?= e(t('cookie.always_active')) ?></span>
          <label class="cc-switch"><input type="checkbox" checked disabled aria-label="<?= e(t('cookie.cat_essential')) ?>"><span class="cc-slider"></span></label>
        </div>
        <p><?= e(t('cookie.cat_essential_desc')) ?></p>
      </div>

      <div class="cc-cat">
        <div class="cc-cat-top">
          <h4><?= e(t('cookie.cat_statistics')) ?></h4>
          <label class="cc-switch"><input type="checkbox" id="ccStat" aria-label="<?= e(t('cookie.cat_statistics')) ?>"><span class="cc-slider"></span></label>
        </div>
        <p><?= e(t('cookie.cat_statistics_desc')) ?></p>
      </div>

      <div class="cc-cat">
        <div class="cc-cat-top">
          <h4><?= e(t('cookie.cat_external')) ?></h4>
          <label class="cc-switch"><input type="checkbox" id="ccExt" aria-label="<?= e(t('cookie.cat_external')) ?>"><span class="cc-slider"></span></label>
        </div>
        <p><?= e(t('cookie.cat_external_desc')) ?></p>
      </div>
    </div>
    <div class="cc-modal-links">
      <a href="<?= e(url('privacy-policy')) ?>"><?= e(t('privacy.title')) ?></a>
    </div>
    <div class="cc-modal-foot">
      <button type="button" class="btn btn-ghost" id="ccSave"><?= e(t('cookie.save')) ?></button>
      <button type="button" class="btn btn-primary" id="ccAll2"><?= e(t('cookie.accept_all')) ?></button>
    </div>
  </div>
</div>
