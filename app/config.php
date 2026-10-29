<?php
/**
 * Redex Glass — Site yapılandırması
 * Tek dosyada tüm temel ayarlar. Yeni sunucuya/alan adına taşırken burayı güncelleyin.
 */

return [
    // Site kimliği
    'site_name'   => 'Redex Glass',
    'company'     => 'Redex Glass',
    'base_url'    => rtrim(getenv('REDEX_BASE_URL') ?: '', '/'), // boş = otomatik algıla
    'default_lang'=> 'en',
    // Sıra: İngilizce, Çince, İspanyolca, Rusça, Fransızca, Lehçe, Almanca, İbranice
    'languages'   => [
        'en' => ['label' => 'English',  'native' => 'English',  'dir' => 'ltr', 'locale' => 'en_US', 'flag' => '🇬🇧'],
        'zh' => ['label' => 'Chinese',  'native' => '中文',      'dir' => 'ltr', 'locale' => 'zh_CN', 'flag' => '🇨🇳'],
        'es' => ['label' => 'Spanish',  'native' => 'Español',  'dir' => 'ltr', 'locale' => 'es_ES', 'flag' => '🇪🇸'],
        'ru' => ['label' => 'Russian',  'native' => 'Русский',  'dir' => 'ltr', 'locale' => 'ru_RU', 'flag' => '🇷🇺'],
        'fr' => ['label' => 'French',   'native' => 'Français', 'dir' => 'ltr', 'locale' => 'fr_FR', 'flag' => '🇫🇷'],
        'pl' => ['label' => 'Polish',   'native' => 'Polski',   'dir' => 'ltr', 'locale' => 'pl_PL', 'flag' => '🇵🇱'],
        'de' => ['label' => 'German',   'native' => 'Deutsch',  'dir' => 'ltr', 'locale' => 'de_DE', 'flag' => '🇩🇪'],
        'he' => ['label' => 'Hebrew',   'native' => 'עברית',    'dir' => 'rtl', 'locale' => 'he_IL', 'flag' => '🇮🇱'],
    ],

    // Marka
    'brand_color'      => '#1E969C', // Redex teal (mevcut logodan)
    'brand_color_dark' => '#13343a',
    'accent'           => '#0e6f74',

    // Medya (şimdilik mevcut siteden; yeni hostinge geçişte /assets/ içine taşınacak)
    'hero_video'   => 'https://redexglass.com/wp-content/uploads/2026/01/video.mp4',
    'youtube_id'   => 'lSn_ngEGOWI',
    'catalog_pdf'  => '/assets/catalog/Redex_Catalog_v3.1.pdf', // pakete dahil; katalog butonu bunu açar

    // Kalite bölümü videosu (şişe kontrol videosu). MP4 gelince: '/assets/video/bottle-inspection.mp4'
    // Boşsa quality_image gösterilir.
    'quality_video'  => '/assets/video/bottle-inspection.mp4',
    'quality_poster' => '/assets/video/bottle-inspection-poster.jpg',
    'quality_image'  => 'https://redexglass.com/wp-content/uploads/2026/01/Ekran-Resmi-2026-01-06-14.02.56-scaled.png',

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
