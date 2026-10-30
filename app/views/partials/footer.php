<?php
$corporate =['about'=>t('nav.about'),'mission-vision'=>t('nav.mission'),'sustainability'=>t('nav.sustainability'),'quality'=>t('nav.quality'),'certificates'=>t('nav.certificates')];
$quick = ['products'=>t('nav.products'),'services'=>t('nav.services'),'careers'=>t('nav.careers'),'news'=>t('nav.news'),'contact'=>t('nav.contact')];
$socialIcons = [
  'linkedin' => 'M4.98 3.5a2.5 2.5 0 11-.02 5 2.5 2.5 0 01.02-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6V21h-4v-5.3c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9V21H9z',
  'facebook' => 'M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.75-1.6 1.5V12h2.7l-.4 2.9h-2.3v7A10 10 0 0022 12z',
  'instagram'=> 'M12 2.2c3.2 0 3.6 0 4.9.07 1.2.06 1.8.25 2.2.42.56.22.96.48 1.38.9.42.42.68.82.9 1.38.17.44.36 1 .42 2.2.06 1.3.07 1.7.07 4.9s0 3.6-.07 4.9c-.06 1.2-.25 1.8-.42 2.2a3.7 3.7 0 01-.9 1.38 3.7 3.7 0 01-1.38.9c-.44.17-1 .36-2.2.42-1.3.06-1.7.07-4.9.07s-3.6 0-4.9-.07c-1.2-.06-1.8-.25-2.2-.42a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.17-.44-.36-1-.42-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.9c.06-1.2.25-1.8.42-2.2.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.44-.17 1-.36 2.2-.42C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.07-1.1.05-1.7.24-2.1.4-.5.2-.9.44-1.3.84-.4.4-.64.8-.84 1.3-.16.4-.35 1-.4 2.1C2.6 10 2.6 8.9 2.6 12s0 2 .06 3.2c.05 1.1.24 1.7.4 2.1.2.5.44.9.84 1.3.4.4.8.64 1.3.84.4.16 1 .35 2.1.4 1.2.07 1.6.07 4.7.07s3.5 0 4.7-.07c1.1-.05 1.7-.24 2.1-.4.5-.2.9-.44 1.3-.84.4-.4.64-.8.84-1.3.16-.4.35-1 .4-2.1.07-1.2.07-1.6.07-4.7s0-3.5-.07-4.7c-.05-1.1-.24-1.7-.4-2.1a3.5 3.5 0 00-.84-1.3 3.5 3.5 0 00-1.3-.84c-.4-.16-1-.35-2.1-.4C15.5 4 15.1 4 12 4zm0 3.1a4.9 4.9 0 110 9.8 4.9 4.9 0 010-9.8zm0 1.8a3.1 3.1 0 100 6.2 3.1 3.1 0 000-6.2zm5.1-.9a1.15 1.15 0 110 2.3 1.15 1.15 0 010-2.3z',
  'youtube'  => 'M23 7.5c-.26-1-1-1.74-2-2C19.2 5 12 5 12 5s-7.2 0-9 .5c-1 .26-1.74 1-2 2C.5 9.3.5 12 .5 12s0 2.7.5 4.5c.26 1 1 1.74 2 2C4.8 19 12 19 12 19s7.2 0 9-.5c1-.26 1.74-1 2-2 .5-1.8.5-4.5.5-4.5s0-2.7-.5-4.5zM9.8 15.3V8.7l6 3.3-6 3.3z',
];
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col footer-brand">
      <div class="footer-logo">REDEX <span>GLASS</span></div>
      <p><?= e(t('footer.tagline')) ?></p>
      <div class="socials">
        <?php foreach (cfg('socials') as $net => $href): if (empty($socialIcons[$net])) continue; ?>
          <a href="<?= e($href) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($net)) ?>">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="<?= $socialIcons[$net] ?>"/></svg>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="footer-col">
      <h4><?= e(t('footer.corporate')) ?></h4>
      <ul>
        <?php foreach ($corporate as $slug => $label): ?>
          <li><a href="<?= e(url($slug)) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="footer-col">
      <h4><?= e(t('footer.quick_links')) ?></h4>
      <ul>
        <?php foreach ($quick as $slug => $label): ?>
          <li><a href="<?= e(url($slug)) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
        <li><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <span>&copy; <?= date('Y') ?> Redex Glass. <?= e(t('footer.rights')) ?></span>
      <a href="<?= e(url('privacy-policy')) ?>"><?= e(t('privacy.title')) ?></a>
    </div>
    <div class="container footer-meta">
      Last updated <?= e(date('d M Y', strtotime(cfg('updated')) ?: time())) ?> &middot; v<?= e(cfg('version')) ?> &middot; by Redex IT Team
    </div>
  </div>
</footer>
