<?php
/**
 * Eski WordPress (Polylang) URL'lerini yeni /{dil}/{rota} yapısına 301 ile yönlendirir.
 * Amaç: Google'da indeksli eski adreslerin SEO değerini ve trafiğini korumak.
 *
 * Eski yapı:  EN kökte (/about-us/), diğer diller öneki ile (/de/ueber-uns/, /he/odotenu/).
 *             Ürünler /product/{slug}/, yazılar /{slug}/. Diller: EN, DE, IT, PL, RU, NL, HE.
 * Yeni yapı:  /{dil}/{rota}. IT ve NL yeni sitede yok -> İngilizceye yönlendirilir.
 */

function legacy_redirect(string $uri): ?string
{
    $path = trim(rawurldecode($uri), '/');
    if ($path === '') return null;                 // kök: dil algılama zaten çalışıyor
    // Eski sitemap/feed adresleri -> yeni sitemap
    if (preg_match('#^(sitemap_index\.xml|[a-z\-]+-sitemap\.xml|feed)$#', $path)) {
        return base_url() . '/sitemap.xml';
    }
    $seg = explode('/', $path);
    $oldLangs = ['de' => 'de', 'pl' => 'pl', 'ru' => 'ru', 'he' => 'he', 'it' => 'en', 'nl' => 'en'];
    $newLangs = array_keys(cfg('languages'));
    $lang = 'en';
    if (isset($oldLangs[$seg[0]])) {
        $lang = $oldLangs[$seg[0]]; array_shift($seg);
    } elseif (in_array($seg[0], $newLangs, true)) {
        $lang = $seg[0]; array_shift($seg);        // yeni dil kodu ama eski slug olabilir (ör. /de/ueber-uns)
    }
    $rest = trim(implode('/', $seg), '/');
    if ($rest === '') return null;                 // /de/ gibi: zaten yeni yapıyla aynı

    $target = legacy_route($rest, $lang);
    if ($target === null) return null;
    $full = url($target, $lang);
    // Hedef, gerçekten gelen adresle aynıysa (ör. /pl/products -> /pl/products) döngüye girme
    $incoming = base_url() . '/' . $path;
    if (rtrim($full, '/') === rtrim($incoming, '/')) return null;
    return $full;
}

/** Eski slug (dil öneksiz) -> yeni rota. Bilinmiyorsa null. */
function legacy_route(string $rest, string $lang): ?string
{
    static $pages = [
        'home' => '', 'startseite' => '',
        'about-us' => 'about', 'ueber-uns' => 'about', 'odotenu' => 'about',
        'products' => 'products', 'produkte' => 'products', 'mutzarim' => 'products', 'product' => 'products',
        'quality' => 'quality', 'qualitaet' => 'quality', 'eykhut' => 'quality',
        'certificates' => 'certificates', 'zertifikate' => 'certificates', 'teudot' => 'certificates',
        'sustainability' => 'sustainability', 'nachhaltigkeit' => 'sustainability', 'kayamut' => 'sustainability',
        'mission-vision' => 'mission-vision',
        'contact' => 'contact', 'kontakt' => 'contact',
        'career' => 'careers', 'karriere' => 'careers',
        'news-blog' => 'news', 'aktuelles-blog' => 'news', 'chadashot-ve-blog' => 'news',
        'custom-production' => 'services/custom-bottle-design',
        'kundenspezifische-produktion' => 'services/custom-bottle-design',
        'yitzur-behitama-ishit' => 'services/custom-bottle-design',
        'fast-delivery' => 'services', 'schnelle-lieferung' => 'services', 'mishloach-mehir' => 'services',
        '24-7-support' => 'services', 'tmicha-24-7' => 'services',
        'privacy-policy' => 'privacy-policy', 'kvkk' => 'privacy-policy',
    ];
    static $products = [
        // EN
        'injection-vials-made-of-borosilicate-glass-tubing' => 'injection-vials-borosilicate-tubing',
        'boston-round-bottle' => 'boston-round-bottles', 'glass-cream-jars' => 'glass-cream-jars',
        'dropper-dispensing-bottles' => 'dropper-dispensing-bottles', 'clear-infusion-bottles-for-syrup' => 'glass-infusion-bottles',
        'injection-vials-glass-bottles' => 'injection-vials-glass-bottles', 'glass-bottles-for-syrup' => 'glass-bottles-syrup',
        'glass-bottles-for-tablets' => 'glass-bottles-tablets', 'caps-and-accessorices' => 'caps-and-accessories',
        // DE
        'injektionsflaeschchen-aus-borosilikatglasrohr' => 'injection-vials-borosilicate-tubing',
        'injektionsflaeschchen-aus-borosilikatglasrohr-2' => 'injection-vials-glass-bottles',
        'boston-rundflasche' => 'boston-round-bottles', 'glas-cremedosen' => 'glass-cream-jars',
        'tropfflaschen-mit-dosiersystem' => 'dropper-dispensing-bottles', 'klare-infusionsflaschen-fuer-sirup' => 'glass-infusion-bottles',
        'glasflaschen-fuer-sirup' => 'glass-bottles-syrup', 'glasflaschen-fuer-tabletten' => 'glass-bottles-tablets',
        'verschluesse-und-zubehoer' => 'caps-and-accessories',
        // HE (transliterasyon)
        'bakbukonei-hazraka-asuyim-tzinorot-zchuchit-borosilikat' => 'injection-vials-borosilicate-tubing',
        'kovaim-veavizarim' => 'caps-and-accessories', 'bakbukei-zchuchit-letabletim' => 'glass-bottles-tablets',
        'bakbukei-zchuchit-lesirop' => 'glass-bottles-syrup', 'bakbukonei-hazraka-bakbukei-zchuchit' => 'injection-vials-glass-bottles',
        'bakbukei-iruy-shkufim-lesirop' => 'glass-infusion-bottles', 'bakbukei-chalukat-taftafet' => 'dropper-dispensing-bottles',
        'tzintzanot-krem-zchuchit' => 'glass-cream-jars', 'bakbuk-agol-shel-boston' => 'boston-round-bottles',
        // PL
        'fiolki-do-iniekcji-wykonane-z-borokrzemowego-szkla-tubularnego' => 'injection-vials-borosilicate-tubing',
        'butelka-typu-boston-round' => 'boston-round-bottles', 'szklane-sloiki-na-krem' => 'glass-cream-jars',
        'butelki-z-zakraplaczem' => 'dropper-dispensing-bottles', 'przezroczyste-butelki-do-syropu' => 'glass-infusion-bottles',
        'fiolki-do-iniekcji-butelki-szklane' => 'injection-vials-glass-bottles', 'szklane-butelki-do-syropu' => 'glass-bottles-syrup',
        'szklane-butelki-na-tabletki' => 'glass-bottles-tablets', 'zakretki-i-akcesoria' => 'caps-and-accessories',
        // RU (çözülmüş Kiril slug)
        'бутылки-с-капельным-дозатором' => 'dropper-dispensing-bottles', 'прозрачные-бутылки-для-сиропов' => 'glass-infusion-bottles',
        'стеклянные-флаконы-для-инъекций' => 'injection-vials-glass-bottles', 'стеклянные-бутылки-для-сиропов' => 'glass-bottles-syrup',
        'стеклянные-бутылки-для-таблеток' => 'glass-bottles-tablets', 'крышки-и-аксессуары' => 'caps-and-accessories',
        'бутылки-boston-round-из-янтарного-стекла' => 'boston-round-bottles', 'стеклянные-кремовые-баночки' => 'glass-cream-jars',
        // IT
        'fiale-per-iniezione-in-vetro-borosilicato-tubolare' => 'injection-vials-borosilicate-tubing',
        'fiale-per-iniezione-realizzate-in-vetro-borosilicato-tubolare' => 'injection-vials-glass-bottles',
        'bottiglie-in-vetro-per-sciroppi' => 'glass-bottles-syrup', 'bottiglie-in-vetro-per-compresse' => 'glass-bottles-tablets',
        'tappi-e-accessori' => 'caps-and-accessories', 'bottiglia-boston-round' => 'boston-round-bottles',
        'vasetti-in-vetro-per-creme' => 'glass-cream-jars', 'bottiglie-con-contagocce-dosatore' => 'dropper-dispensing-bottles',
        'bottiglie-trasparenti-per-sciroppi' => 'glass-infusion-bottles',
        // NL
        'injectieflacons-van-borosilicaatglasbuizen' => 'injection-vials-borosilicate-tubing', 'boston-round-fles' => 'boston-round-bottles',
        'glazen-cremepotten' => 'glass-cream-jars', 'druppelflessen' => 'dropper-dispensing-bottles',
        'heldere-infusieflessen-voor-siroop' => 'glass-infusion-bottles', 'injectieflacons-van-glas' => 'injection-vials-glass-bottles',
        'glazen-flessen-voor-siroop' => 'glass-bottles-syrup', 'glazen-flessen-voor-tabletten' => 'glass-bottles-tablets',
        'doppen-en-accessoires' => 'caps-and-accessories',
    ];
    static $newsEn = [
        'in-dropper-products-things-to-consider-for-a-better-dosage-experience-more-precise-dosage',
        'nose-spray-finding-the-best-options-for-your-nasal-health',
        'stylish-solutions-for-the-cosmetics-and-health-industry-glass-cream-jars',
        'ultimate-guide-to-wholesale-amber-glass-bottles-for-packaging',
        'amber-glass-bottles-wholesale-uk-top-quality-best-prices-at',
        'preserve-quality-with-amber-glass-bottles',
        'amber-glass-bottles-protecting-liquid-supplements',
        'how-to-choose-the-right-medicine-bottle-for-your-needs',
        'black-dropper-bottles-for-essential-oils-ultimate-guide',
        'amber-glass-bottles-for-storing-essential-oils',
        'the-benefits-of-using-amber-glass-bottles-a-complete-guidesave',
        'the-benefits-of-using-white-hdpe-bottles-for-your-business',
    ];
    static $newsMap = [
        // DE
        'braunglasflaschen-zur-aufbewahrung-aetherischer-oele' => 'amber-glass-bottles-for-storing-essential-oils',
        'die-vorteile-der-verwendung-von-braunglasflaschen-ein-vollstaendiger-leitfaden' => 'the-benefits-of-using-amber-glass-bottles-a-complete-guidesave',
        'wie-sie-die-richtige-medizinflasche-fuer-ihre-beduerfnisse-auswaehlen' => 'how-to-choose-the-right-medicine-bottle-for-your-needs',
        'schwarze-tropfflaschen-fuer-aetherische-oele-ultimativer-leitfaden' => 'black-dropper-bottles-for-essential-oils-ultimate-guide',
        'die-vorteile-der-verwendung-von-weissen-hdpe-flaschen-fuer-ihr-unternehmen' => 'the-benefits-of-using-white-hdpe-bottles-for-your-business',
        'braunglasflaschen-schutz-fuer-fluessige-nahrungsergaenzungsmittel' => 'amber-glass-bottles-protecting-liquid-supplements',
        'qualitaet-bewahren-mit-braunglasflaschen' => 'preserve-quality-with-amber-glass-bottles',
        'ultimativer-leitfaden-fuer-braunglasflaschen-im-grosshandel-fuer-verpackungen' => 'ultimate-guide-to-wholesale-amber-glass-bottles-for-packaging',
        'braunglasflaschen-grosshandel-uk-top-qualitaet-beste-preise-bei' => 'amber-glass-bottles-wholesale-uk-top-quality-best-prices-at',
        'stilvolle-loesungen-fuer-die-kosmetik-und-gesundheitsbranche-glas-cremedosen' => 'stylish-solutions-for-the-cosmetics-and-health-industry-glass-cream-jars',
        'bei-tropfprodukten-wichtige-aspekte-fuer-ein-besseres-dosiererlebnis-und-eine-praezisere-dosierung' => 'in-dropper-products-things-to-consider-for-a-better-dosage-experience-more-precise-dosage',
        'nasenspray-die-besten-optionen-fuer-ihre-nasengesundheit-finden' => 'nose-spray-finding-the-best-options-for-your-nasal-health',
        // HE (transliterasyon)
        'keitzad-livchor-et-bakbuk-hatrufot-hamatim-letzarachim-shelcha' => 'how-to-choose-the-right-medicine-bottle-for-your-needs',
        'bakbukei-taftafet-shchorim-leshemanim-eteriyim-madrich-haultimativi' => 'black-dropper-bottles-for-essential-oils-ultimate-guide',
        'bakbukei-zchuchit-inbar-leichsun-shemanim-eteriyim' => 'amber-glass-bottles-for-storing-essential-oils',
        'haitronot-shel-shimush-bebakbukei-zchuchit-inbar-madrich-male-shmor' => 'the-benefits-of-using-amber-glass-bottles-a-complete-guidesave',
        'haitronot-shel-shimush-bebakbukei-hdpe-levanim-avor-haesek-shelcha' => 'the-benefits-of-using-white-hdpe-bottles-for-your-business',
        'bakbukei-zchuchit-inbar-hagana-al-tosfei-nozlim' => 'amber-glass-bottles-protecting-liquid-supplements',
        'hamadrich-haultimativi-lesitonai-bakbukei-zchuchit-inbar-laariza' => 'ultimate-guide-to-wholesale-amber-glass-bottles-for-packaging',
        'shmor-al-eichut-im-bakbukei-zchuchit-inbar' => 'preserve-quality-with-amber-glass-bottles',
        'bakbukei-zchuchit-inbar-sitonai-britania-eichut-meula-hamechirim-hatovim-beyoter-be' => 'amber-glass-bottles-wholesale-uk-top-quality-best-prices-at',
        'bimotzrei-tapetet-dvarim-she-keday-lakachat-bachshbon-avur-chavayat-minun-tova-yeter-minun-medayak-yeter' => 'in-dropper-products-things-to-consider-for-a-better-dosage-experience-more-precise-dosage',
    ];

    if (isset($pages[$rest])) return $pages[$rest];
    if (preg_match('#^product/(.+)$#', $rest, $m)) {
        $slug = trim($m[1], '/');
        if (isset($products[$slug])) return 'products/' . $products[$slug];
        if (str_starts_with($slug, 'флаконы-для-инъекций-из-боросиликат')) return 'products/injection-vials-borosilicate-tubing';
        return 'products';
    }
    if (preg_match('#^(category|tag|author)/#', $rest)) return 'news';
    if (in_array($rest, $newsEn, true)) return 'news/' . $rest;
    if (isset($newsMap[$rest])) return 'news/' . $newsMap[$rest];
    return null;
}
