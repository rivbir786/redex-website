<?php
$svcs = content_services();
$corp_company = [
    'about'          => ['label' => t('nav.about'),          'desc' => t('menu.d_about')],
    'mission-vision' => ['label' => t('nav.mission'),         'desc' => t('menu.d_mission')],
    'sustainability' => ['label' => t('nav.sustainability'),  'desc' => t('menu.d_sustainability')],
];
$corp_trust = [
    'quality'      => ['label' => t('nav.quality'),      'desc' => t('menu.d_quality')],
    'certificates' => ['label' => t('nav.certificates'), 'desc' => t('menu.d_certificates')],
];
?>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="<?= e(url('')) ?>" class="brand" aria-label="Redex Glass">
      <svg class="brand-logo" xmlns="http://www.w3.org/2000/svg" width="248" height="84" viewBox="0 0 248 84" fill="none" role="img" aria-label="Redex Glass">
        <g clip-path="url(#clip0)">
          <mask id="m0" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="248" height="84"><path d="M248 0H0V84H248V0Z" fill="white"/></mask>
          <g mask="url(#m0)">
            <mask id="m1" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="6" y="17" width="237" height="51"><path d="M242.033 17.0212H6.02344V67.3823H242.033V17.0212Z" fill="white"/></mask>
            <g mask="url(#m1)">
              <path d="M6.02344 17.7195H17.9148C26.0776 17.7195 29.8061 21.5663 29.8061 29.5408V31.9894C29.8061 37.0958 28.1352 40.3128 24.5995 41.852V42.0623C28.3918 43.2514 29.8699 46.8891 29.8699 53.114V60.3189C29.7491 62.4797 30.055 64.6432 30.7698 66.6836H22.6058C22.1564 65.3547 21.8986 64.6557 21.8986 60.2484V52.5584C21.8986 47.9383 20.5482 46.4028 16.8209 46.4028H14.0574V66.6824H6.02344V17.7195ZM17.0149 38.6313C20.1639 38.6313 21.7709 37.1626 21.7709 33.3146V30.4502C21.7709 27.0228 20.5494 25.4836 17.7859 25.4836H14.0574V38.6338L17.0149 38.6313Z"/>
              <path d="M34.0469 17.7195H55.9647V25.4836H42.0808V37.5845H53.1361V45.3487H42.0808V58.9183H55.9635V66.6824H34.0469V17.7195Z"/>
              <path d="M58.9844 17.7195H71.26C79.1663 17.7195 83.2152 22.2666 83.2152 30.6593V53.6721C83.2152 62.1353 79.1663 66.6812 71.26 66.6812H58.9844V17.7195ZM71.1323 58.9183C73.832 58.9183 75.1812 57.5201 75.1812 53.9517V30.4502C75.1812 26.883 73.832 25.4836 71.1323 25.4836H67.0183V58.9183H71.1323Z"/>
              <path d="M87.1367 17.7195H109.055V25.4836H95.1707V37.5845H106.226V45.3487H95.1707V58.9183H109.053V66.6824H87.1367V17.7195Z"/>
              <path d="M119.469 41.5712L110.596 17.7195H119.21L124.48 33.3876L129.943 17.7195H137.653L128.787 41.5712L138.042 66.6824H129.489L123.769 49.6854L117.855 66.6824H110.207L119.469 41.5712Z"/>
              <path d="M149.289 55.2814V29.0577C149.289 21.2936 152.824 17.0212 159.444 17.0212C166.129 17.0212 169.664 21.2875 169.664 29.0577V33.0443H164.908V28.7027C164.908 24.0826 162.98 21.9878 159.573 21.9878C156.232 21.9878 154.303 24.0863 154.303 28.7027V55.7009C154.303 60.3878 156.23 62.4158 159.573 62.4158C162.98 62.4158 164.908 60.3878 164.908 55.7009V44.7197H159.959V39.7568H169.665V55.2814C169.665 63.0456 166.13 67.3823 159.446 67.3823C152.82 67.3823 149.29 63.0456 149.29 55.2814"/>
              <path d="M178.277 17.7195H173.457V66.6824H178.277V17.7195Z"/>
              <path d="M196.659 66.6831C196.367 65.1133 196.195 63.5232 196.144 61.9269C195.655 63.5412 194.655 64.949 193.296 65.9344C191.938 66.9198 190.297 67.4284 188.625 67.3822C184.189 67.3822 181.426 64.0253 181.426 58.7086C181.426 53.2521 183.611 50.1749 189.46 47.167C194.859 44.5092 195.565 43.8101 195.565 41.432V40.5919C195.565 37.0952 194.151 35.3457 191.387 35.3457C188.56 35.3457 186.889 37.2338 186.889 40.7317V41.781H182.326V41.0114C182.326 34.4363 185.669 30.7986 191.452 30.7986C197.623 30.7986 200.386 34.4363 200.386 41.4308V57.0952C200.3 60.3055 200.537 63.5163 201.093 66.6782L196.659 66.6831ZM195.566 54.7925V47.7335C194.923 48.643 193.639 49.4819 190.874 51.0162C187.66 52.7645 186.311 54.863 186.311 57.9463C186.311 61.0296 187.66 62.7731 190.296 62.7731C193.574 62.7731 195.566 59.8357 195.566 54.7986"/>
              <path d="M203.469 57.2416V56.0526H208.161V57.3815C208.161 61.159 209.961 62.7675 212.597 62.7675C215.232 62.7675 217.031 61.2283 217.031 57.6611C217.031 54.2337 214.396 52.6252 211.439 51.0155C207.839 48.917 203.79 46.6787 203.79 40.1742C203.79 34.438 207.004 30.8015 212.532 30.8015C217.802 30.8015 221.273 34.4392 221.273 40.5936V41.2903H216.709V40.4514C216.709 36.8842 215.038 35.345 212.595 35.345C210.346 35.345 208.481 36.8842 208.481 40.1012C208.481 43.6684 211.181 45.2769 214.264 46.9559C217.863 48.9839 221.853 51.2927 221.853 57.5869C221.853 63.6027 218.639 67.379 212.854 67.379C206.941 67.379 203.47 63.6015 203.47 57.2368"/>
              <path d="M223.651 57.2416V56.0526H228.343V57.3815C228.343 61.159 230.143 62.7675 232.779 62.7675C235.414 62.7675 237.213 61.2283 237.213 57.6611C237.213 54.2337 234.578 52.6252 231.621 51.0155C228.021 48.917 223.972 46.6787 223.972 40.1742C223.972 34.438 227.187 30.8015 232.714 30.8015C237.984 30.8015 241.455 34.4392 241.455 40.5936V41.2903H236.888V40.4514C236.888 36.8842 235.217 35.345 232.774 35.345C230.525 35.345 228.66 36.8842 228.66 40.1012C228.66 43.6684 231.36 45.2769 234.442 46.9559C238.042 48.9839 242.032 51.2927 242.032 57.5869C242.032 63.6027 238.818 67.379 233.033 67.379C227.119 67.379 223.648 63.6015 223.648 57.2368"/>
            </g>
          </g>
        </g>
        <defs><clipPath id="clip0"><rect width="248" height="84" fill="white"/></clipPath></defs>
      </svg>
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="mainNav" aria-label="Main">
      <ul class="nav-list">
        <li class="has-sub">
          <a href="<?= e(url('about')) ?>" aria-haspopup="true"><?= e(t('nav.corporate')) ?> <i class="caret"></i></a>
          <div class="mega">
            <div class="mega-wrap">
              <div class="mega-col">
                <span class="mega-label"><?= e(t('menu.company')) ?></span>
                <?php foreach ($corp_company as $slug => $it): ?>
                  <a class="mega-item" href="<?= e(url($slug)) ?>"><span class="t"><?= e($it['label']) ?></span><span class="d"><?= e($it['desc']) ?></span></a>
                <?php endforeach; ?>
              </div>
              <div class="mega-col">
                <span class="mega-label"><?= e(t('menu.trust')) ?></span>
                <?php foreach ($corp_trust as $slug => $it): ?>
                  <a class="mega-item" href="<?= e(url($slug)) ?>"><span class="t"><?= e($it['label']) ?></span><span class="d"><?= e($it['desc']) ?></span></a>
                <?php endforeach; ?>
              </div>
              <div class="mega-promo">
                <div>
                  <h4><?= e(t('menu.promo_corp_title')) ?></h4>
                  <p><?= e(t('menu.promo_corp_text')) ?></p>
                </div>
                <a class="btn" href="<?= e(cfg('catalog_pdf')) ?>" target="_blank" rel="noopener"><?= e(t('menu.download_catalog')) ?></a>
              </div>
            </div>
          </div>
        </li>
        <li><a href="<?= e(url('products')) ?>" <?= is_active('products') ? 'class="active"' : '' ?>><?= e(t('nav.products')) ?></a></li>
        <li class="has-sub">
          <a href="<?= e(url('services')) ?>" aria-haspopup="true"><?= e(t('nav.services')) ?> <i class="caret"></i></a>
          <div class="mega">
            <div class="mega-wrap">
              <div class="mega-col">
                <span class="mega-label"><?= e(t('menu.what_we_do')) ?></span>
                <div class="mega-items cols2">
                  <?php foreach ($svcs as $k => $s): ?>
                    <a class="mega-item" href="<?= e(url('services/' . $k)) ?>"><span class="t"><?= e(loc($s['title'])) ?></span><span class="d"><?= e(mb_strimwidth(loc($s['excerpt']), 0, 46, '…')) ?></span></a>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="mega-promo">
                <div>
                  <h4><?= e(t('menu.promo_svc_title')) ?></h4>
                  <p><?= e(t('menu.promo_svc_text')) ?></p>
                </div>
                <a class="btn" href="<?= e(url('contact')) ?>"><?= e(t('menu.contact_us')) ?></a>
              </div>
            </div>
          </div>
        </li>
        <li><a href="<?= e(url('careers')) ?>" <?= is_active('careers') ? 'class="active"' : '' ?>><?= e(t('nav.careers')) ?></a></li>
        <li><a href="<?= e(url('news')) ?>" <?= is_active('news') ? 'class="active"' : '' ?>><?= e(t('nav.news')) ?></a></li>
        <li><a href="<?= e(url('contact')) ?>" <?= is_active('contact') ? 'class="active"' : '' ?>><?= e(t('nav.contact')) ?></a></li>
      </ul>

      <div class="nav-actions">
        <div class="lang-switch">
          <button class="lang-current" type="button" aria-haspopup="true" aria-expanded="false">
            <?= e(strtoupper(lang())) ?> <i class="caret"></i>
          </button>
          <ul class="lang-menu">
            <?php foreach (cfg('languages') as $code => $info): ?>
              <li><a href="<?= e(url($GLOBALS['__route'] ?? '', $code)) ?>" <?= $code === lang() ? 'aria-current="true"' : '' ?>><?= e($info['native']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <a class="btn btn-catalog" href="<?= e(cfg('catalog_pdf')) ?>" target="_blank" rel="noopener"><?= e(t('nav.catalog')) ?></a>
      </div>
    </nav>
  </div>
</header>
<div class="nav-backdrop" id="navBackdrop"></div>
