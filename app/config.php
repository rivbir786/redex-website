<?php
/**
 * Redex Glass — Site yapılandırması
 * Tek dosyada tüm temel ayarlar. Yeni sunucuya/alan adına taşırken burayı güncelleyin.
 */

return [
    // Site kimliği
    'site_name'   => 'Redex Glass',
    'company'     => 'Redex Glass',
    'version'     => '4.1',          // footer'da gosterilir; her degisiklikte guncellenir
    'updated'     => '2026-11-03',   // footer "son guncelleme" tarihi (YYYY-MM-DD)
    // Guvenlik: kabul edilen Host basliklari (Host header injection'a karsi). Ilk eleman kanonik alan adidir.
    'allowed_hosts' => ['redexglass.com', 'www.redexglass.com', 'localhost', '127.0.0.1'],
    // Admin paneline erisebilecek IP'ler (bos = herkes). Ornek: ['1.2.3.4', '5.6.7.0/24']
    'admin_ip_allowlist' => [],
    'admin_idle_timeout' => 7200,    // admin oturumu: islem yapilmazsa X saniye sonra kapanir (2 saat)
    // Arama motoru indekslemesi: deneme/staging asamasinda TRUE (botlar engellenir).
    // Site yayina hazir olunca FALSE yapin -> robots.txt acilir, meta noindex kalkar, sitemap sunulur.
    // 2026-11-03: CANLI yayin -> FALSE (botlar serbest, sitemap + hreflang aktif).
    'noindex'     => false,
    'base_url'    => rtrim(getenv('REDEX_BASE_URL') ?: '', '/'), // boş = otomatik algıla
    'default_lang'=> 'en',
    // Sıra: İngilizce, Çince, İspanyolca, Rusça, Fransızca, Lehçe, Türkçe, Almanca, İbranice
    'languages'   => [
        'en' => ['label' => 'English',  'native' => 'English',  'dir' => 'ltr', 'locale' => 'en_US', 'flag' => '🇬🇧'],
        'zh' => ['label' => 'Chinese',  'native' => '中文',      'dir' => 'ltr', 'locale' => 'zh_CN', 'hreflang' => 'zh-Hans', 'flag' => '🇨🇳'],
        'es' => ['label' => 'Spanish',  'native' => 'Español',  'dir' => 'ltr', 'locale' => 'es_ES', 'flag' => '🇪🇸'],
        'ru' => ['label' => 'Russian',  'native' => 'Русский',  'dir' => 'ltr', 'locale' => 'ru_RU', 'flag' => '🇷🇺'],
        'fr' => ['label' => 'French',   'native' => 'Français', 'dir' => 'ltr', 'locale' => 'fr_FR', 'flag' => '🇫🇷'],
        'pl' => ['label' => 'Polish',   'native' => 'Polski',   'dir' => 'ltr', 'locale' => 'pl_PL', 'flag' => '🇵🇱'],
        'tr' => ['label' => 'Turkish',  'native' => 'Türkçe',   'dir' => 'ltr', 'locale' => 'tr_TR', 'flag' => '🇹🇷'],
        'de' => ['label' => 'German',   'native' => 'Deutsch',  'dir' => 'ltr', 'locale' => 'de_DE', 'flag' => '🇩🇪'],
        'he' => ['label' => 'Hebrew',   'native' => 'עברית',    'dir' => 'rtl', 'locale' => 'he_IL', 'flag' => '🇮🇱'],
    ],

    // Marka
    'brand_color'      => '#1E969C', // Redex teal (mevcut logodan)
    'brand_color_dark' => '#13343a',
    'accent'           => '#0e6f74',

    // Hero (üst) videosu — mevcut sunucuda; tarayıcı doğrudan oynatır (hızlı yükleme)
    'hero_video'   => 'video/hero.mp4', // pakete dahil; asset() ile servis edilir
    'catalog_pdf'  => '/assets/catalog/Redex_Catalog_v3.3.pdf', // pakete dahil; katalog butonu bunu açar

    // Ana sayfa YouTube videoları — Stoelzle tarzı kutuda, ziyaretçi YouTube üzerinden izler
    'youtube_id'         => 'lSn_ngEGOWI', // intro: "Reliable glass packaging..." yanındaki tanıtım videosu
    'quality_youtube_id' => 'oHo_Id4RhoM', // kalite bölümü: şişe kontrol videosu (https://youtu.be/oHo_Id4RhoM)

    // İletişim
    'email'        => 'info@redexglass.com',
    'socials'      => [
        'facebook'  => 'https://www.facebook.com/redexglass',
        'youtube'   => 'https://www.youtube.com/@RedexGlass',
        'instagram' => 'https://www.instagram.com/redexglass/',
        'linkedin'  => 'https://www.linkedin.com/company/redexglass',
    ],

    // Veritabanı: SQLite (sıfır kurulum). MySQL'e geçmek isterseniz 'driver' => 'mysql' yapıp bilgileri girin.
    'db' => [
        'driver' => 'sqlite',
        'sqlite_path' => __DIR__ . '/../storage/database.sqlite',
        // MySQL alternatifi:
        'mysql' => [
            'host' => 'localhost', 'name' => '', 'user' => '', 'pass' => '', 'charset' => 'utf8mb4',
        ],
    ],

    // Admin: şifre ilk girişte /admin üzerinden belirlenir, storage/admin.php içine hash olarak yazılır.
    'admin_hash_file' => __DIR__ . '/../storage/admin.php',

    // Bildirim e-postası (mail() çalışırsa yeni mesaj/başvuru buraya gider; çalışmasa da DB'ye kaydedilir)
    'notify_email' => 'info@redexglass.com',

    'timezone' => 'Asia/Shanghai',
];
