<?php page_head(t('privacy.title')); ?>
<section class="section">
  <div class="container" style="max-width:820px">
    <div class="prose">
      <p>This Privacy Policy explains how <?= e(cfg('company')) ?> (“we”, “us”, “our”) collects, uses, and protects personal data when you visit this website, in accordance with applicable data protection regulations, including the EU General Data Protection Regulation (GDPR) and the ePrivacy Directive.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">1. Data Controller</h3>
      <p>The controller responsible for data processing on this website is <?= e(cfg('company')) ?>. For any privacy-related request, you can contact us at <a href="mailto:<?= e(cfg('email')) ?>" style="color:var(--brand)"><?= e(cfg('email')) ?></a>.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">2. Information We Collect</h3>
      <p>We collect information that you provide directly through our contact and application forms — such as your name, email address, phone number, company, country, subject, message content, and any documents you choose to upload (e.g. a CV). When you submit a form, we also store the time of submission and technical information needed to process your request.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">3. How We Use Your Information</h3>
      <p>Your information is used to respond to your inquiries, evaluate job applications, operate and secure the website, and improve our services. We do not sell your personal data, and we do not use it for third-party advertising.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">4. Legal Basis</h3>
      <p>We process personal data on the basis of your consent (Art. 6(1)(a) GDPR) for optional cookies and embedded media, the performance of pre-contractual or contractual steps (Art. 6(1)(b)) when you contact us or apply for a position, and our legitimate interest (Art. 6(1)(f)) in operating a secure and functional website.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">5. Cookies &amp; Consent</h3>
      <p>On your first visit we ask for your consent before setting any non-essential cookies or loading third-party content. You can change your choice at any time via the “<?= e(t('cookie.settings')) ?>” link in the footer. We use the following categories:</p>
      <ul class="onestop-list" style="grid-template-columns:1fr;margin-top:18px">
        <li><strong><?= e(t('cookie.cat_essential')) ?>:</strong> <?= e(t('cookie.cat_essential_desc')) ?></li>
        <li><strong><?= e(t('cookie.cat_statistics')) ?>:</strong> <?= e(t('cookie.cat_statistics_desc')) ?></li>
        <li><strong><?= e(t('cookie.cat_external')) ?>:</strong> <?= e(t('cookie.cat_external_desc')) ?></li>
      </ul>

      <h3 style="margin-top:34px;color:var(--brand-dark)">6. Embedded Content (YouTube)</h3>
      <p>Pages on this site may include videos embedded from YouTube (operated by Google). These are loaded only after you enable the “<?= e(t('cookie.cat_external')) ?>” category. When loaded, YouTube may set cookies and transfer data — including to servers outside your country — under Google’s own privacy policy. We use the privacy-enhanced <code>youtube-nocookie.com</code> domain.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">7. Statistics</h3>
      <p>If you allow the “<?= e(t('cookie.cat_statistics')) ?>” category, we record anonymous visit data (such as the page visited and language) on our own server to understand how the site is used. IP addresses are not stored in identifiable form, and this data is not shared with advertising networks.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">8. Data Retention</h3>
      <p>We keep contact messages and job applications only as long as necessary to handle your request and to comply with legal obligations, after which they are deleted or anonymized.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">9. Your Rights</h3>
      <p>Subject to applicable law, you have the right to access, rectify, or erase your personal data, to restrict or object to processing, and to data portability. You may also withdraw consent at any time without affecting prior processing, and lodge a complaint with a supervisory authority. To exercise these rights, contact us at <a href="mailto:<?= e(cfg('email')) ?>" style="color:var(--brand)"><?= e(cfg('email')) ?></a>.</p>

      <h3 style="margin-top:34px;color:var(--brand-dark)">10. Changes to This Policy</h3>
      <p>We may update this Privacy Policy from time to time. The latest version is always available on this page.</p>

      <h3 style="margin-top:40px;color:var(--brand-dark)"><?= e(t('privacy.imprint_title')) ?></h3>
      <p><strong><?= e(cfg('company')) ?></strong><br>
         Email: <a href="mailto:<?= e(cfg('email')) ?>" style="color:var(--brand)"><?= e(cfg('email')) ?></a></p>

      <p style="margin-top:34px;color:var(--muted);font-size:14px"><em>Bu metin profesyonel bir taslaktır. Yayına almadan önce şirketin yasal bilgilerini (ticari unvan, adres, ticaret sicil/vergi no, sorumlu kişi) tamamlamanız ve bir hukuk danışmanına gözden geçirtmeniz önerilir.</em></p>
    </div>
  </div>
</section>
