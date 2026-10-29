# Redex Glass — Yeni Web Sitesi (PHP)

Kurumsal, çok dilli (EN / ES / HE), WordPress'siz saf PHP site + yönetici paneli.
Stoelzle tarzı sade kurumsal yapı; Redex'in kendi içeriği, markası ve renkleriyle.

## Özellikler
- **Çok dillilik (8 dil):** İngilizce (varsayılan), Çince, İspanyolca, Rusça, Fransızca, Lehçe, Almanca, İbranice. İbranice tam RTL (sağdan sola). Her dil için ayrı SEO (başlık, açıklama, hreflang). Çeviriler sektör terimleriyle (SGD Pharma, Gerresheimer, ISO/BSI kaynakları) yapıldı; yayından önce ana dil gözden geçirmesi önerilir.
- **SEO:** her sayfada benzersiz başlık/açıklama, `canonical`, `hreflang` (dil alternatifleri),
  Open Graph, Organization schema, otomatik `sitemap.xml` ve `robots.txt`.
- **Ana sayfa:** üstte videolu hero (mevcut `video.mp4`), ayrı bir kutuda YouTube videosu (Stoelzle tarzı),
  istatistikler, ürün ve hizmet önizlemeleri.
- **Sayfalar:** Anasayfa, Hakkımızda, Misyon & Vizyon, Sürdürülebilirlik, Kalite, Sertifikalar,
  Ürünler (+ detay), Hizmetler (+ detay), Kariyer (CV'li başvuru formu), Haberler, İletişim.
- **İletişim:** yalnızca Shanghai ofisi ve Yancheng fabrikası (İtalya ve Özbekistan kaldırıldı).
- **Yönetici paneli (`/admin`):** ziyaretçi istatistikleri (grafik, dil/sayfa dağılımı),
  gelen iletişim mesajları ve iş başvuruları (CV indirme).
- **Veritabanı:** SQLite (kurulum gerektirmez). İstenirse MySQL'e tek ayarla geçilir.

## Gereksinimler
- PHP 7.4+ (8.x önerilir), `pdo_sqlite` (çoğu paylaşımlı hostingde açık).
- Apache + `mod_rewrite` (LiteSpeed dahil destekler) — `.htaccess` ile güzel URL'ler.

## Kurulum (deneme.redexglass.com)
1. Paketin **içindeki tüm dosyaları** alt alan adının kök klasörüne yükleyin
   (cPanel File Manager veya FileZilla ile). `index.php` doğrudan kökte olmalı.
2. `storage/` klasörünün **yazılabilir** olduğundan emin olun (genelde 755 yeterli; gerekirse 775).
   Veritabanı ilk ziyarette otomatik oluşur.
3. Siteyi açın: `https://deneme.redexglass.com` — `/en/` adresine yönlenir.
4. Yönetici panelini açın: `https://deneme.redexglass.com/admin`
   İlk açılışta bir **yönetici şifresi** belirlemeniz istenir. Belirledikten sonra panele girersiniz.

> Alt alan adı değil de ana sunucuya kurulumda da aynı adımlar geçerlidir. Alt klasöre kurarsanız
> (`/deneme/`), site taban yolunu otomatik algılar.

## Yapılandırma — `app/config.php`
- `hero_video`, `youtube_id`, `catalog_pdf`, `email`, sosyal medya linkleri.
- `brand_color` = `#1E969C` (Redex teal).
- `base_url`: boş bırakın (otomatik algılanır) veya sabitlemek isterseniz yazın.
- **MySQL'e geçiş:** `db.driver` = `mysql` yapıp `db.mysql` bilgilerini girin.

## İçerik düzenleme — `app/content.php`
- **Ürünler:** `content_products()` — ad, görsel, kısa ve uzun açıklama (ES/HE çevirileriyle).
- **Ofisler:** `content_offices()`.
- **Sertifikalar:** `content_certificates()`.
- **Hizmetler:** `content_services()`.
- **Açık pozisyonlar:** `content_positions()` — şu an boş; pozisyon bilgilerini buraya ekleyin,
  Kariyer sayfasında otomatik listelenir.

## Çeviri / dil — `app/lang/en.php`, `es.php`, `he.php`
Arayüz ve sayfa metinleri burada. Bir dilde eksik anahtar, otomatik olarak İngilizceye düşer.
Yeni dil eklemek için `config.php` içindeki `languages`'a ekleyip bir `app/lang/<kod>.php` oluşturun.

## Medya hakkında
Görseller ve hero videosu şu an mevcut siteden (`redexglass.com`) çekiliyor; aynı alan adı altında
olduğu için sorunsuz çalışır. Yeni hostinge tamamen taşınırken bu dosyaları `assets/img/` içine
indirip `app/content.php` ve `app/config.php`'deki URL'leri güncellemek en temizi olur.

## İletişim formu ve e-posta
- Form alanları: Ad Soyad, Firma, E-posta, Telefon, Ülke, Konu (açılır liste), Mesaj, gizlilik onayı.
- Gönderimler hem **veritabanına** kaydedilir (admin panelinde görünür) hem de **info@redexglass.com** adresine e-posta olarak gönderilir (PHP `mail()`, Reply-To müşterinin e-postası).
- ⚠️ Paylaşımlı hostinglerde PHP `mail()` bazen engellenir veya spam'e düşer. E-postalar gelmiyorsa, bir SMTP eklentisi/ayarı (ör. hosting SMTP veya PHPMailer + SMTP) önerilir. Veritabanı kaydı her hâlükârda güvenilir kaynaktır — mesajları `/admin` üzerinden de görürsünüz.

## Güvenlik
- `app/` ve `storage/` klasörlerine web'den doğrudan erişim `.htaccess` ile kapalıdır.
- Admin şifresi `storage/admin.php` içinde hash'li tutulur (düz metin saklanmaz).
- Formlarda CSRF koruması ve bal küpü (spam) alanı vardır.

## Notlar
- Ürünlerin **uzun** açıklamaları şu an İngilizce; ES/HE çevirileri eklenene kadar bu dillerde
  İngilizce metne düşer. Ürün adları ve kısa açıklamalar üç dilde de hazırdır.
- İbranice ve İspanyolca çeviriler iyi bir başlangıç taslağıdır; yayına almadan önce ana dili
  konuşan biri tarafından gözden geçirilmesi önerilir.
- Gizlilik politikası metni (`/privacy-policy`) taslaktır; hukuki gözden geçirme önerilir.
