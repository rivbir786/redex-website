<?php
/**
 * Site içeriği (yapısal veri). Metinler çok dilli: her alan dil kodlarına göre dizidir.
 * loc() aktif dili seçer, yoksa İngilizceye düşer.
 * Diller: en, zh, es, ru, fr, pl, de, he. Teknik veriler Redex_Catalog_v3.1'den.
 */

function loc($field): string
{
    if (is_array($field)) {
        return $field[lang()] ?? $field['en'] ?? reset($field);
    }
    return (string) $field;
}

function content_stats(): array
{
    // Rakamlar Satış El Kitabı (v5) ile uyumlu: 2 fırın, 9 hat, ~1.7M şişe/gün, ~80 konteyner/ay Avrupa.
    return [
        ['value' => '1.7M', 'label' => [
            'en' => 'Bottles per day', 'zh' => '产能（瓶/天）', 'es' => 'Botellas por día',
            'ru' => 'Бутылок в день', 'fr' => 'Flacons par jour', 'pl' => 'Butelek dziennie',
            'de' => 'Flaschen pro Tag', 'he' => 'בקבוקים ביום']],
        ['value' => '9', 'label' => [
            'en' => 'Production lines', 'zh' => '生产线', 'es' => 'Líneas de producción',
            'ru' => 'Производственных линий', 'fr' => 'Lignes de production', 'pl' => 'Linii produkcyjnych',
            'de' => 'Produktionslinien', 'he' => 'קווי ייצור']],
        ['value' => '2', 'label' => [
            'en' => 'Glass furnaces', 'zh' => '玻璃窑炉', 'es' => 'Hornos de vidrio',
            'ru' => 'Стекловаренные печи', 'fr' => 'Fours verriers', 'pl' => 'Piece szklarskie',
            'de' => 'Glasschmelzöfen', 'he' => 'כבשני זכוכית']],
        ['value' => '80+', 'label' => [
            'en' => 'Containers / month', 'zh' => '集装箱/月', 'es' => 'Contenedores / mes',
            'ru' => 'Контейнеров в месяц', 'fr' => 'Conteneurs / mois', 'pl' => 'Kontenerów / miesiąc',
            'de' => 'Container / Monat', 'he' => 'מכולות בחודש']],
    ];
}

/** Ana sayfaya özel çok dilli metinler (hero, tek elden tedarik, kalite bölümü). */
function content_home(): array
{
    return [
        'hero' => [
            'title' => ['en' => 'Glass That Protects What Matters', 'zh' => '守护每一份珍贵',
                        'es' => 'Vidrio que protege lo que importa', 'ru' => 'Стекло, которое защищает важное',
                        'fr' => 'Le verre qui protège l’essentiel', 'pl' => 'Szkło, które chroni to, co ważne',
                        'de' => 'Glas, das schützt, worauf es ankommt', 'he' => 'זכוכית ששומרת על מה שחשוב'],
            'accent' => ['en' => 'Pharma & cosmetic packaging, perfected', 'zh' => '医药与化妆品包装，臻于至善',
                         'es' => 'Envases para farmacia y cosmética, perfeccionados', 'ru' => 'Упаковка для фармацевтики и косметики — безупречно',
                         'fr' => 'Emballage pharma & cosmétique, perfectionné', 'pl' => 'Opakowania dla farmacji i kosmetyki, dopracowane',
                         'de' => 'Verpackung für Pharma & Kosmetik, perfektioniert', 'he' => 'אריזות לתרופות וקוסמטיקה, במושלמות'],
            'sub' => ['en' => 'Amber, flint and black glass bottles — manufactured, inspected and shipped worldwide.',
                      'zh' => '棕色、透明与黑色玻璃瓶——自主制造、逐瓶检测、全球发运。',
                      'es' => 'Botellas de vidrio ámbar, transparente y negro — fabricadas, inspeccionadas y enviadas a todo el mundo.',
                      'ru' => 'Флаконы из янтарного, прозрачного и чёрного стекла — производим, проверяем и отгружаем по всему миру.',
                      'fr' => 'Flacons en verre ambré, transparent et noir — fabriqués, inspectés et expédiés dans le monde entier.',
                      'pl' => 'Butelki ze szkła oranżowego, bezbarwnego i czarnego — produkowane, kontrolowane i wysyłane na cały świat.',
                      'de' => 'Flaschen aus Braun-, Klar- und Schwarzglas — gefertigt, geprüft und weltweit geliefert.',
                      'he' => 'בקבוקי זכוכית ענבר, שקופה ושחורה — מיוצרים, נבדקים ונשלחים ברחבי העולם.'],
        ],
        'onestop' => [
            'eyebrow' => ['en' => 'One-Stop Shop', 'zh' => '一站式采购', 'es' => 'Todo en un solo proveedor',
                          'ru' => 'Всё у одного поставщика', 'fr' => 'Guichet unique', 'pl' => 'Wszystko u jednego dostawcy',
                          'de' => 'Alles aus einer Hand', 'he' => 'ספק אחד לכל הצרכים'],
            'title' => ['en' => 'One partner for your complete packaging', 'zh' => '一个伙伴，完整的包装方案',
                        'es' => 'Un solo socio para su envase completo', 'ru' => 'Один партнёр для всей упаковки',
                        'fr' => 'Un seul partenaire pour tout votre emballage', 'pl' => 'Jeden partner dla kompletnego opakowania',
                        'de' => 'Ein Partner für Ihre komplette Verpackung', 'he' => 'שותף אחד לכל פתרון האריזה'],
            'body' => ['en' => 'Bottles, decoration, caps, droppers and accessories — coordinated by Redex, so you deal with one supplier instead of many.',
                       'zh' => '瓶子、装饰、瓶盖、滴管与配件——由 Redex 统一协调，让您只需对接一个供应商，而非多个。',
                       'es' => 'Botellas, decoración, tapas, cuentagotas y accesorios — coordinados por Redex, para que trate con un solo proveedor en lugar de muchos.',
                       'ru' => 'Бутылки, декор, крышки, капельницы и аксессуары — всё координирует Redex, и вы работаете с одним поставщиком вместо нескольких.',
                       'fr' => 'Flacons, décoration, bouchons, compte-gouttes et accessoires — coordonnés par Redex, pour traiter avec un seul fournisseur au lieu de plusieurs.',
                       'pl' => 'Butelki, zdobienie, zakrętki, kroplomierze i akcesoria — koordynowane przez Redex, dzięki czemu masz jednego dostawcę zamiast wielu.',
                       'de' => 'Flaschen, Veredelung, Verschlüsse, Tropfeinsätze und Zubehör — von Redex koordiniert, damit Sie mit einem Lieferanten statt vielen arbeiten.',
                       'he' => 'בקבוקים, עיטור, פקקים, טפטפות ואביזרים — בתיאום Redex, כך שאתם עובדים מול ספק אחד במקום רבים.'],
            'items' => [
                ['en' => 'Glass bottles', 'zh' => '玻璃瓶', 'es' => 'Botellas de vidrio', 'ru' => 'Стеклянные бутылки',
                 'fr' => 'Flacons en verre', 'pl' => 'Butelki szklane', 'de' => 'Glasflaschen', 'he' => 'בקבוקי זכוכית'],
                ['en' => 'Decoration & printing', 'zh' => '装饰与印刷', 'es' => 'Decoración e impresión', 'ru' => 'Декор и печать',
                 'fr' => 'Décoration et impression', 'pl' => 'Zdobienie i nadruk', 'de' => 'Veredelung & Druck', 'he' => 'עיטור והדפסה'],
                ['en' => 'Caps & closures', 'zh' => '瓶盖与封口', 'es' => 'Tapas y cierres', 'ru' => 'Крышки и укупорка',
                 'fr' => 'Bouchons et fermetures', 'pl' => 'Zakrętki i zamknięcia', 'de' => 'Verschlüsse & Kappen', 'he' => 'פקקים וסגירה'],
                ['en' => 'Droppers, sprays & accessories', 'zh' => '滴管、喷头与配件', 'es' => 'Cuentagotas, sprays y accesorios',
                 'ru' => 'Капельницы, спреи и аксессуары', 'fr' => 'Compte-gouttes, sprays et accessoires',
                 'pl' => 'Kroplomierze, spraye i akcesoria', 'de' => 'Tropfeinsätze, Sprays & Zubehör', 'he' => 'טפטפות, תרסיסים ואביזרים'],
            ],
        ],
        'quality' => [
            'eyebrow' => ['en' => 'Quality', 'zh' => '质量', 'es' => 'Calidad', 'ru' => 'Качество',
                          'fr' => 'Qualité', 'pl' => 'Jakość', 'de' => 'Qualität', 'he' => 'איכות'],
            'title' => ['en' => 'Quality you can trust', 'zh' => '值得信赖的品质',
                        'es' => 'Calidad de confianza', 'ru' => 'Качество, которому можно доверять',
                        'fr' => 'Une qualité de confiance', 'pl' => 'Jakość godna zaufania',
                        'de' => 'Qualität, der Sie vertrauen können', 'he' => 'איכות שאפשר לסמוך עליה'],
            'body' => ['en' => 'Automatic camera inspection checks each bottle’s dimensions, neck threads, air bubbles and visual defects — one step in a multi-stage quality process from forming to final QC.',
                       'zh' => '自动相机检测会逐瓶检查外形尺寸、瓶口螺纹、气泡与外观缺陷——这是从成型到最终质检的多道质量控制流程中的一环。',
                       'es' => 'La inspección automática por cámara comprueba en cada botella las dimensiones, la rosca del cuello, las burbujas de aire y los defectos visuales — una etapa de un proceso de calidad multifase, del moldeo al control final.',
                       'ru' => 'Автоматический камерный контроль проверяет у каждого флакона размеры, резьбу горловины, воздушные пузырьки и визуальные дефекты — один из этапов многоступенчатого контроля качества: от формования до финальной проверки.',
                       'fr' => 'L’inspection automatique par caméra vérifie sur chaque flacon les dimensions, le filetage du col, les bulles d’air et les défauts visuels — une étape d’un contrôle qualité en plusieurs phases, du formage au contrôle final.',
                       'pl' => 'Automatyczna kontrola kamerą sprawdza w każdej butelce wymiary, gwint szyjki, pęcherzyki powietrza i wady wizualne — to jeden z etapów wielostopniowej kontroli jakości, od formowania po kontrolę końcową.',
                       'de' => 'Die automatische Kamerainspektion prüft bei jeder Flasche Maße, Mündungsgewinde, Luftblasen und optische Defekte — ein Schritt in einem mehrstufigen Qualitätsprozess vom Formen bis zur Endkontrolle.',
                       'he' => 'בדיקת מצלמה אוטומטית בודקת בכל בקבוק מידות, תבריג צוואר, בועות אוויר ופגמים חזותיים — שלב אחד בתהליך איכות רב-שלבי, מהעיצוב ועד בקרת הגמר.'],
            'cta' => ['en' => 'Our quality process', 'zh' => '了解我们的质量', 'es' => 'Nuestro proceso de calidad',
                      'ru' => 'Наш контроль качества', 'fr' => 'Notre démarche qualité', 'pl' => 'Nasz proces jakości',
                      'de' => 'Unser Qualitätsprozess', 'he' => 'תהליך האיכות שלנו'],
        ],
    ];
}

function content_offices(): array
{
    // Sıra: Shandong fabrika, Yancheng Office, Shanghai Office, Israel Office.
    // (Italy ve Uzbekistan kapandığı için yok.)
    return [
        [
            'name' => ['en' => 'Shandong Factory', 'zh' => '山东工厂', 'es' => 'Fábrica de Shandong',
                       'ru' => 'Завод в Шаньдуне', 'fr' => 'Usine du Shandong', 'pl' => 'Fabryka Shandong',
                       'de' => 'Werk Shandong', 'he' => 'מפעל שאנדונג'],
            'phone' => '',
            'email' => 'info@redexglass.com',
            'address' => ['en' => '3-106, Jianglong Commercial Street, Boshanshi Town, Zibo City, Shandong Province, China',
                          'zh' => '中国山东省淄博市博山石镇江龙商业街 3-106',
                          'es' => '3-106, Jianglong Commercial Street, Boshanshi Town, Zibo, Provincia de Shandong, China',
                          'ru' => '3-106, Jianglong Commercial Street, пос. Бошань, г. Цзыбо, пров. Шаньдун, Китай',
                          'fr' => '3-106, Jianglong Commercial Street, Boshanshi, Zibo, province du Shandong, Chine',
                          'pl' => '3-106, Jianglong Commercial Street, Boshanshi, Zibo, prowincja Shandong, Chiny',
                          'de' => '3-106, Jianglong Commercial Street, Boshanshi, Zibo, Provinz Shandong, China',
                          'he' => '3-106, רחוב ג׳יאנגלונג המסחרי, בושאנשי, זיבו, מחוז שאנדונג, סין'],
            'map' => 'https://www.google.com/maps/search/?api=1&query=Jianglong+Commercial+Street+Boshan+Zibo+Shandong+China',
        ],
        [
            'name' => ['en' => 'Yancheng Office', 'zh' => '盐城办事处', 'es' => 'Oficina de Yancheng',
                       'ru' => 'Офис в Яньчэне', 'fr' => 'Bureau de Yancheng', 'pl' => 'Biuro Yancheng',
                       'de' => 'Büro Yancheng', 'he' => 'משרד יאנצ׳נג'],
            'phone' => '+86 515 8851 0515',
            'email' => 'info@redexglass.com',
            'address' => ['en' => 'No. 10 Jianshe Road, Sanlong Town, Dafeng, Yancheng City, China',
                          'zh' => '中国盐城市大丰区三龙镇建设路 10 号',
                          'es' => 'No. 10 Jianshe Road, Sanlong, Dafeng, Yancheng, China',
                          'ru' => 'No. 10 Jianshe Road, пос. Санлун, Дафэн, г. Яньчэн, Китай',
                          'fr' => 'No. 10 Jianshe Road, Sanlong, Dafeng, Yancheng, Chine',
                          'pl' => 'No. 10 Jianshe Road, Sanlong, Dafeng, Yancheng, Chiny',
                          'de' => 'No. 10 Jianshe Road, Sanlong, Dafeng, Yancheng, China',
                          'he' => 'רחוב ג׳יאנשה 10, סנלונג, דאפנג, יאנצ׳נג, סין'],
            'map' => 'https://maps.app.goo.gl/QCoRckbb7LDsYtP2A',
        ],
        [
            'name' => ['en' => 'Shanghai Office', 'zh' => '上海办事处', 'es' => 'Oficina de Shanghái',
                       'ru' => 'Офис в Шанхае', 'fr' => 'Bureau de Shanghai', 'pl' => 'Biuro Szanghaj',
                       'de' => 'Büro Shanghai', 'he' => 'משרד שנגחאי'],
            'phone' => '+86 137 6455 1610',
            'email' => 'info@redexglass.com',
            'address' => ['en' => 'Room 1106, No. 288 Changyang Road, Hongkou District, Shanghai, China',
                          'zh' => '中国上海市虹口区长阳路 288 号 1106 室',
                          'es' => 'Sala 1106, No. 288 Changyang Road, Distrito de Hongkou, Shanghái, China',
                          'ru' => 'Room 1106, No. 288 Changyang Road, р-н Хункоу, Шанхай, Китай',
                          'fr' => 'Bureau 1106, No. 288 Changyang Road, district de Hongkou, Shanghai, Chine',
                          'pl' => 'Lokal 1106, No. 288 Changyang Road, dystrykt Hongkou, Szanghaj, Chiny',
                          'de' => 'Raum 1106, No. 288 Changyang Road, Bezirk Hongkou, Shanghai, China',
                          'he' => 'חדר 1106, רחוב צ׳אנגיאנג 288, מחוז הונגקו, שנגחאי, סין'],
            'map' => 'https://maps.app.goo.gl/VGLVZYWmymwb55o59',
        ],
        [
            'name' => ['en' => 'Israel Office', 'zh' => '以色列办事处', 'es' => 'Oficina de Israel',
                       'ru' => 'Офис в Израиле', 'fr' => 'Bureau d’Israël', 'pl' => 'Biuro Izrael',
                       'de' => 'Büro Israel', 'he' => 'משרד ישראל'],
            'phone' => '+972 52 470 8204',
            'email' => 'info@redexglass.com',
            'contact' => 'Yaniv Levi',
            'address' => ['en' => 'Eshbol 22, Israel', 'zh' => 'Eshbol 22, 以色列',
                          'es' => 'Eshbol 22, Israel', 'ru' => 'Эшбол 22, Израиль',
                          'fr' => 'Eshbol 22, Israël', 'pl' => 'Eshbol 22, Izrael',
                          'de' => 'Eshbol 22, Israel', 'he' => 'אשבול 22, ישראל'],
            'map' => 'https://www.google.com/maps/search/?api=1&query=Eshbol+22+Israel',
        ],
    ];
}

function content_certificates(): array
{
    return [
        ['code' => 'ISO 9001:2015', 'desc' => [
            'en' => 'Quality Management', 'zh' => '质量管理', 'es' => 'Gestión de Calidad',
            'ru' => 'Менеджмент качества', 'fr' => 'Management de la qualité', 'pl' => 'Zarządzanie jakością',
            'de' => 'Qualitätsmanagement', 'he' => 'ניהול איכות']],
        ['code' => 'ISO 15378:2017', 'desc' => [
            'en' => 'Primary Packaging Materials for Medicinal Products (GMP)', 'zh' => '药品初级包装材料（GMP）',
            'es' => 'Materiales de Envasado Primario para Productos Medicinales (GMP)',
            'ru' => 'Первичные упаковочные материалы для лекарственных средств (GMP)',
            'fr' => 'Articles de conditionnement primaire des médicaments (BPF)',
            'pl' => 'Podstawowe materiały opakowaniowe dla produktów leczniczych (GMP)',
            'de' => 'Primärpackmittel für Arzneimittel (GMP)', 'he' => 'חומרי אריזה ראשוניים למוצרי תרופות (GMP)']],
        ['code' => 'ISO 22000:2018', 'desc' => [
            'en' => 'Food Safety Management', 'zh' => '食品安全管理', 'es' => 'Gestión de Seguridad Alimentaria',
            'ru' => 'Менеджмент безопасности пищевой продукции', 'fr' => 'Management de la sécurité des denrées alimentaires',
            'pl' => 'Zarządzanie bezpieczeństwem żywności', 'de' => 'Lebensmittelsicherheitsmanagement',
            'he' => 'ניהול בטיחות מזון']],
        ['code' => 'ISO 22301:2019', 'desc' => [
            'en' => 'Business Continuity Management', 'zh' => '业务连续性管理', 'es' => 'Gestión de Continuidad del Negocio',
            'ru' => 'Менеджмент непрерывности бизнеса', 'fr' => 'Management de la continuité d’activité',
            'pl' => 'Zarządzanie ciągłością działania', 'de' => 'Business-Continuity-Management',
            'he' => 'ניהול המשכיות עסקית']],
        ['code' => 'ISO 45001:2018', 'desc' => [
            'en' => 'Occupational Health & Safety', 'zh' => '职业健康与安全', 'es' => 'Salud y Seguridad Ocupacional',
            'ru' => 'Охрана труда и безопасность', 'fr' => 'Santé et sécurité au travail',
            'pl' => 'Bezpieczeństwo i higiena pracy', 'de' => 'Arbeits- und Gesundheitsschutz',
            'he' => 'בריאות ובטיחות בתעסוקה']],
    ];
}

function content_services(): array
{
    return [
        'custom-bottle-design' => [
            'title' => ['en' => 'Custom Bottle Design', 'zh' => '瓶型定制设计', 'es' => 'Diseño de Botellas Personalizado',
                        'ru' => 'Индивидуальный дизайн флаконов', 'fr' => 'Conception de flacons sur mesure',
                        'pl' => 'Indywidualny projekt butelek', 'de' => 'Individuelle Flaschengestaltung',
                        'he' => 'עיצוב בקבוקים בהתאמה אישית'],
            'img' => 'https://redexglass.com/wp-content/uploads/2025/12/leftproduction.png',
            'wide_img' => asset('img/custom-bottle-design-process.jpg'),
            'excerpt' => ['en' => 'From your sample or drawing to series production — custom glass bottles and any plastic component, in one coordinated, approval-driven process.', 'zh' => '从您的样品或图纸到量产——定制玻璃瓶与所需的任何塑料部件，在一个每步确认、协同一致的流程中完成。', 'es' => 'De su muestra o plano a la producción en serie: botellas de vidrio a medida y cualquier componente plástico, en un proceso coordinado y aprobado paso a paso.', 'ru' => 'От вашего образца или чертежа до серийного производства — флаконы под заказ и любые пластиковые компоненты в едином согласованном процессе с утверждением на каждом шаге.', 'fr' => 'De votre échantillon ou plan à la production en série — flacons sur mesure et tout composant plastique, dans un processus coordonné validé à chaque étape.', 'pl' => 'Od Twojej próbki lub rysunku do produkcji seryjnej — butelki na zamówienie i dowolny komponent z tworzywa, w jednym spójnym procesie z akceptacją każdego etapu.', 'de' => 'Von Ihrem Muster oder Ihrer Zeichnung bis zur Serienproduktion – maßgefertigte Flaschen und jede Kunststoffkomponente, in einem koordinierten, schrittweise freigegebenen Prozess.', 'he' => 'מהדוגמה או השרטוט שלכם ועד לייצור סדרתי — בקבוקים בהתאמה אישית וכל רכיב פלסטיק, בתהליך מתואם אחד עם אישור בכל שלב.'],
            'glass_h' => ['en' => 'Glass bottle', 'zh' => '玻璃瓶', 'es' => 'Botella de vidrio', 'ru' => 'Стеклянный флакон', 'fr' => 'Flacon en verre', 'pl' => 'Szklana butelka', 'de' => 'Glasflasche', 'he' => 'בקבוק זכוכית'],
            'glass_t' => ['en' => 'We review your sample or drawing and prepare a mould and production drawing suited to our manufacturing technology. Once you approve the drawings, we cut a single-cavity sample mould and send you the glass sample. As soon as you approve it, we open the series-production moulds.', 'zh' => '我们评估您的样品或图纸，制定符合我们生产工艺的模具与产品图纸。图纸经您确认后，我们开制单腔样品模具并将玻璃样品寄给您；样品一经确认，便立即开制量产模具。', 'es' => 'Revisamos su muestra o plano y preparamos un molde y un plano de producción adecuados a nuestra tecnología de fabricación. Tras su aprobación, fabricamos un molde de muestra de una cavidad y le enviamos la muestra de vidrio. En cuanto la aprueba, abrimos los moldes de producción en serie.', 'ru' => 'Мы изучаем ваш образец или чертёж и готовим форму и производственный чертёж с учётом нашей технологии. После утверждения чертежей изготавливаем одноместную пробную форму и отправляем вам стеклянный образец. Как только вы его утвердите, запускаем серийные формы.', 'fr' => 'Nous étudions votre échantillon ou plan et préparons un moule et un plan de production adaptés à notre technologie. Après validation des plans, nous réalisons un moule d’essai à une empreinte et vous envoyons l’échantillon en verre. Dès votre accord, nous lançons les moules de série.', 'pl' => 'Analizujemy Twoją próbkę lub rysunek i przygotowujemy formę oraz rysunek produkcyjny dopasowany do naszej technologii. Po akceptacji rysunków wykonujemy jednogniazdową formę próbną i wysyłamy Ci szklaną próbkę. Gdy ją zatwierdzisz, otwieramy formy do produkcji seryjnej.', 'de' => 'Wir prüfen Ihr Muster oder Ihre Zeichnung und erstellen eine Form und Produktionszeichnung passend zu unserer Fertigungstechnik. Nach Freigabe der Zeichnungen fertigen wir eine Einfach-Musterform und senden Ihnen das Glasmuster. Sobald Sie es freigeben, fertigen wir die Serienformen.', 'he' => 'אנו בוחנים את הדוגמה או השרטוט שלכם ומכינים תבנית ושרטוט ייצור המותאמים לטכנולוגיית הייצור שלנו. לאחר אישור השרטוטים אנו מייצרים תבנית דוגמה בחלל אחד ושולחים לכם את דוגמת הזכוכית. עם אישורה, אנו פותחים את תבניות הייצור הסדרתי.'],
            'plastic_h' => ['en' => 'Optional plastic component', 'zh' => '可选塑料部件', 'es' => 'Componente plástico opcional', 'ru' => 'Дополнительный пластиковый компонент', 'fr' => 'Composant plastique en option', 'pl' => 'Opcjonalny komponent z tworzywa', 'de' => 'Optionale Kunststoffkomponente', 'he' => 'רכיב פלסטיק אופציונלי'],
            'plastic_t' => ['en' => 'If a plastic part goes on the bottle — a cap, dropper or sprayer — we work the same way: from your technical or hand drawing we prepare the part design and show exactly how it sits on the bottle through drawings and a 3D render. After approval, we send a 3D-printed prototype together with the glass sample. Once you approve the complete set, we open the series-production moulds for both the glass bottle and the plastic part.', 'zh' => '如果瓶子上还需配套塑料部件——瓶盖、滴管或喷头——流程相同：我们依据您的技术图纸或手绘草图绘制产品图，并通过图纸与 3D 渲染展示该部件在瓶上的装配关系。确认后，我们将 3D 打印样件与样品模具的玻璃瓶一并寄送。整套经您确认后，我们同时开制玻璃瓶与塑料部件的量产模具。', 'es' => 'Si la botella lleva una pieza plástica —tapa, cuentagotas o pulverizador— trabajamos igual: a partir de su plano técnico o un boceto a mano preparamos el diseño de la pieza y mostramos cómo encaja en la botella mediante planos y un render 3D. Tras la aprobación, enviamos un prototipo impreso en 3D junto con la muestra de vidrio. Cuando aprueba el conjunto, abrimos los moldes de serie tanto de la botella como de la pieza plástica.', 'ru' => 'Если на флакон устанавливается пластиковая деталь — крышка, пипетка или распылитель — работаем так же: по вашему техническому или эскизному чертежу готовим дизайн детали и показываем, как она садится на флакон, с помощью чертежей и 3D-рендера. После утверждения отправляем напечатанный на 3D-принтере прототип вместе со стеклянным образцом. После одобрения комплекта запускаем серийные формы и для флакона, и для пластиковой детали.', 'fr' => 'Si un élément plastique vient sur le flacon — bouchon, compte-gouttes ou spray — nous procédons de même : à partir de votre plan technique ou d’un croquis, nous réalisons le dessin de la pièce et montrons son positionnement sur le flacon par des plans et un rendu 3D. Après validation, nous envoyons un prototype imprimé en 3D avec l’échantillon en verre. Une fois l’ensemble validé, nous lançons les moules de série du flacon et de la pièce plastique.', 'pl' => 'Jeśli na butelce ma znaleźć się element z tworzywa — zakrętka, zakraplacz lub atomizer — postępujemy tak samo: na podstawie rysunku technicznego lub szkicu przygotowujemy projekt części i pokazujemy, jak osadza się na butelce, za pomocą rysunków i renderu 3D. Po akceptacji wysyłamy prototyp z druku 3D wraz ze szklaną próbką. Po zatwierdzeniu całości otwieramy formy seryjne zarówno butelki, jak i części z tworzywa.', 'de' => 'Kommt ein Kunststoffteil auf die Flasche — Verschluss, Tropfer oder Sprüher — arbeiten wir genauso: Aus Ihrer technischen Zeichnung oder Skizze erstellen wir das Teiledesign und zeigen per Zeichnung und 3D-Render, wie es auf der Flasche sitzt. Nach Freigabe senden wir einen 3D-gedruckten Prototyp zusammen mit dem Glasmuster. Sobald Sie das Set freigeben, fertigen wir die Serienformen für Flasche und Kunststoffteil.', 'he' => 'אם על הבקבוק מורכב רכיב פלסטיק — פקק, טפטפת או מרסס — אנו פועלים באותו אופן: מתוך השרטוט הטכני או סקיצה ביד מכינים את עיצוב הרכיב ומראים בדיוק כיצד הוא יושב על הבקבוק באמצעות שרטוטים ורנדר תלת-ממד. לאחר אישור, אנו שולחים אב-טיפוס בהדפסת תלת-ממד יחד עם דוגמת הזכוכית. לאחר אישור הסט המלא, אנו פותחים את תבניות הייצור הסדרתי הן לבקבוק והן לרכיב הפלסטיק.'],
            'body' => ['en' => 'Have your own bottle drawing or a sample? We turn it into production. We prepare custom moulds and manufacture to your dimensions, in amber, flint or black glass, with DIN or GPI neck finishes and optional frosting, painting and printing. New bottle shapes come from your design; we assess manufacturability, build the tooling and produce to a consistent, repeatable standard.',
                        'zh' => '有自己的瓶型图纸或样品？我们将其转化为量产。我们开制专属模具，按您的尺寸生产，提供棕色、透明或黑色玻璃，配 DIN 或 GPI 瓶口，并可选蒙砂、喷涂与印刷。新瓶型源自您的设计；我们评估可制造性、开模并以稳定、可重复的标准进行生产。',
                        'es' => '¿Tiene su propio plano de botella o una muestra? La llevamos a producción. Preparamos moldes propios y fabricamos según sus dimensiones, en vidrio ámbar, transparente o negro, con bocas DIN o GPI y, de forma opcional, esmerilado, pintado e impresión. Las nuevas formas parten de su diseño; evaluamos la fabricabilidad, construimos el utillaje y producimos con un estándar constante y repetible.',
                        'ru' => 'Есть собственный чертёж флакона или образец? Мы доводим его до производства. Изготавливаем индивидуальные формы и выпускаем изделие по вашим размерам — из янтарного, прозрачного или чёрного стекла, с горловинами DIN или GPI и, по желанию, матированием, окраской и печатью. Новые формы создаются по вашему дизайну; мы оцениваем технологичность, изготавливаем оснастку и производим со стабильным, воспроизводимым качеством.',
                        'fr' => 'Vous avez votre propre plan de flacon ou un échantillon ? Nous le mettons en production. Nous réalisons des moules dédiés et fabriquons à vos dimensions, en verre ambré, transparent ou noir, avec cols DIN ou GPI et, en option, dépolissage, peinture et impression. Les nouvelles formes viennent de votre dessin ; nous évaluons la faisabilité, réalisons l’outillage et produisons selon un standard constant et reproductible.',
                        'pl' => 'Masz własny rysunek butelki lub próbkę? Przekładamy to na produkcję. Wykonujemy dedykowane formy i produkujemy według Twoich wymiarów, w szkle oranżowym, bezbarwnym lub czarnym, z szyjkami DIN lub GPI oraz opcjonalnie matowieniem, malowaniem i nadrukiem. Nowe kształty powstają z Twojego projektu; oceniamy wykonalność, budujemy oprzyrządowanie i produkujemy w stałym, powtarzalnym standardzie.',
                        'de' => 'Sie haben eine eigene Flaschenzeichnung oder ein Muster? Wir bringen sie in die Produktion. Wir fertigen eigene Formen und produzieren nach Ihren Maßen, in Braun-, Klar- oder Schwarzglas, mit DIN- oder GPI-Mündungen und optional Mattierung, Lackierung und Druck. Neue Formen entstehen aus Ihrem Design; wir prüfen die Fertigbarkeit, bauen die Werkzeuge und produzieren in gleichbleibendem, reproduzierbarem Standard.',
                        'he' => 'יש לכם שרטוט בקבוק משלכם או דוגמה? אנחנו הופכים אותו לייצור. אנו מכינים תבניות ייעודיות ומייצרים לפי המידות שלכם, בזכוכית ענבר, שקופה או שחורה, עם צווארי DIN או GPI ואופציונלית מיט, צביעה והדפסה. צורות חדשות מגיעות מהעיצוב שלכם; אנו בוחנים יכולת ייצור, בונים את התבניות ומייצרים בסטנדרט עקבי וחוזר.'],
        ],
        'decoration' => [
            'title' => ['en' => 'Decoration', 'zh' => '表面装饰', 'es' => 'Decoración',
                        'ru' => 'Декорирование', 'fr' => 'Décoration', 'pl' => 'Zdobienie',
                        'de' => 'Veredelung', 'he' => 'עיטור והדפסה'],
            'img' => 'https://redexglass.com/wp-content/uploads/2025/12/camkremkavonozlari.png',
            'excerpt' => ['en' => 'Screen printing, hot stamping, frosting, coloring, and labeling to bring your brand to the bottle.',
                          'zh' => '丝网印刷、烫金、蒙砂、喷色与贴标，让您的品牌呈现在瓶身上。',
                          'es' => 'Serigrafía, estampado en caliente, esmerilado, coloración y etiquetado para llevar su marca a la botella.',
                          'ru' => 'Шелкография, горячее тиснение, матирование, окрашивание и этикетирование, чтобы перенести ваш бренд на флакон.',
                          'fr' => 'Sérigraphie, marquage à chaud, dépolissage, coloration et étiquetage pour mettre votre marque sur le flacon.',
                          'pl' => 'Sitodruk, tłoczenie na gorąco, matowienie, barwienie i etykietowanie, aby umieścić Twoją markę na butelce.',
                          'de' => 'Siebdruck, Heißprägung, Mattierung, Färbung und Etikettierung, um Ihre Marke auf die Flasche zu bringen.',
                          'he' => 'הדפסת רשת, הטבעה חמה, מיט, צביעה ותיוג כדי להביא את המותג שלכם אל הבקבוק.'],
            'body' => ['en' => 'Our decoration service adds the finishing touches that make your packaging stand out on the shelf. We offer screen printing, hot stamping and foiling, frosting and acid etching, spray coloring, and precise labeling. Each technique is applied with consistent registration and durable results, so your branding stays sharp through filling, handling, and use.',
                        'zh' => '我们的表面装饰服务为您的包装增添画龙点睛之笔，使其在货架上脱颖而出。我们提供丝网印刷、烫金/烫箔、蒙砂与酸蚀、喷色以及精准贴标。每道工艺都保证套位一致、效果持久，让您的品牌在灌装、搬运与使用过程中始终清晰。',
                        'es' => 'Nuestro servicio de decoración añade los toques finales que hacen destacar su envase en el lineal. Ofrecemos serigrafía, estampado en caliente y foil, esmerilado y grabado al ácido, coloración por pulverización y etiquetado preciso. Cada técnica se aplica con registro constante y resultados duraderos, para que su marca se mantenga nítida durante el llenado, la manipulación y el uso.',
                        'ru' => 'Услуга декорирования добавляет финальные штрихи, благодаря которым упаковка выделяется на полке. Мы предлагаем шелкографию, горячее тиснение и фольгирование, матирование и травление, окрашивание напылением и точное этикетирование. Каждая техника выполняется с постоянной приводкой и стойким результатом, поэтому брендинг остаётся чётким при наполнении, транспортировке и использовании.',
                        'fr' => 'Notre service de décoration apporte les finitions qui font ressortir votre emballage en rayon. Nous proposons sérigraphie, marquage à chaud et dorure, dépolissage et gravure à l’acide, coloration par pulvérisation et étiquetage précis. Chaque technique est appliquée avec un repérage constant et des résultats durables, afin que votre marque reste nette lors du remplissage, de la manipulation et de l’usage.',
                        'pl' => 'Nasza usługa zdobienia dodaje wykończenia, dzięki którym opakowanie wyróżnia się na półce. Oferujemy sitodruk, tłoczenie na gorąco i foliowanie, matowienie i trawienie, barwienie natryskowe oraz precyzyjne etykietowanie. Każda technika jest wykonywana z powtarzalnym pasowaniem i trwałym efektem, dzięki czemu marka pozostaje wyraźna podczas napełniania, transportu i użytkowania.',
                        'de' => 'Unser Veredelungsservice setzt die letzten Akzente, mit denen Ihre Verpackung im Regal auffällt. Wir bieten Siebdruck, Heißprägung und Folienveredelung, Mattierung und Ätzung, Sprühfärbung und präzise Etikettierung. Jede Technik wird mit gleichbleibender Passgenauigkeit und dauerhaftem Ergebnis umgesetzt, damit Ihr Branding beim Abfüllen, Handling und Gebrauch scharf bleibt.',
                        'he' => 'שירות העיטור שלנו מוסיף את הגימור שגורם לאריזה שלכם לבלוט על המדף. אנו מציעים הדפסת רשת, הטבעה חמה ורדיד, מיט וצריבת חומצה, צביעה בהתזה ותיוג מדויק. כל טכניקה מיושמת ברישום עקבי ובתוצאה עמידה, כך שהמיתוג נשאר חד לאורך המילוי, השינוע והשימוש.'],
        ],
        'fast-delivery' => [
            'title' => ['en' => 'Fast Delivery', 'zh' => '快速交付', 'es' => 'Entrega Rápida',
                        'ru' => 'Быстрая доставка', 'fr' => 'Livraison rapide', 'pl' => 'Szybka dostawa',
                        'de' => 'Schnelle Lieferung', 'he' => 'משלוח מהיר'],
            'img' => 'https://redexglass.com/wp-content/uploads/2025/12/damlalik.png',
            'excerpt' => ['en' => 'Optimized production and logistics with local stock to ensure reliable, on-time delivery for global orders.',
                          'zh' => '优化的生产与物流，配合本地库存，确保全球订单可靠、准时交付。',
                          'es' => 'Producción y logística optimizadas con stock local para garantizar entregas fiables y puntuales en pedidos globales.',
                          'ru' => 'Оптимизированные производство и логистика с локальными запасами обеспечивают надёжную и своевременную доставку по миру.',
                          'fr' => 'Production et logistique optimisées avec stock local pour des livraisons fiables et ponctuelles à l’international.',
                          'pl' => 'Zoptymalizowana produkcja i logistyka z lokalnym zapasem zapewniają niezawodne i terminowe dostawy globalne.',
                          'de' => 'Optimierte Produktion und Logistik mit lokalem Lagerbestand sichern zuverlässige, pünktliche Lieferung weltweit.',
                          'he' => 'ייצור ולוגיסטיקה מיטביים עם מלאי מקומי להבטחת משלוח אמין ובזמן להזמנות גלובליות.'],
            'body' => ['en' => 'Local product storage solutions are designed to ensure faster and more efficient delivery by positioning products closer to key markets. This reduces lead times and supports reliable order fulfillment across regions, with strategic storage near customer markets, scalable solutions for businesses of all sizes, and secure, trackable inventory.',
                        'zh' => '本地仓储方案通过将产品布局在靠近主要市场的位置，实现更快、更高效的交付。这缩短了交货周期，支持跨区域可靠履约——在客户市场附近设有战略仓储，提供可扩展的方案以适应各种规模的企业，并确保库存安全、可追踪。',
                        'es' => 'Las soluciones de almacenamiento local están diseñadas para una entrega más rápida y eficiente, situando los productos cerca de los mercados clave. Esto reduce los plazos y respalda un cumplimiento fiable en distintas regiones, con almacenamiento estratégico, soluciones escalables e inventario seguro y rastreable.',
                        'ru' => 'Решения локального хранения обеспечивают более быструю и эффективную доставку, размещая продукцию ближе к ключевым рынкам. Это сокращает сроки и поддерживает надёжное выполнение заказов в разных регионах — со стратегическими складами рядом с рынками клиентов, масштабируемыми решениями и безопасным, отслеживаемым запасом.',
                        'fr' => 'Les solutions de stockage local assurent une livraison plus rapide et efficace en rapprochant les produits des marchés clés. Cela réduit les délais et soutient un traitement fiable des commandes selon les régions, avec un stockage stratégique, des solutions évolutives et un inventaire sécurisé et traçable.',
                        'pl' => 'Rozwiązania lokalnego magazynowania zapewniają szybszą i wydajniejszą dostawę, umieszczając produkty bliżej kluczowych rynków. Skraca to czas realizacji i wspiera niezawodną obsługę zamówień w regionach — z magazynami w pobliżu rynków klientów, skalowalnymi rozwiązaniami i bezpiecznym, śledzonym zapasem.',
                        'de' => 'Lokale Lagerlösungen sorgen für schnellere, effizientere Lieferung, indem Produkte näher an Schlüsselmärkten platziert werden. Das verkürzt Durchlaufzeiten und unterstützt zuverlässige Auftragsabwicklung über Regionen hinweg — mit strategischen Lagern nahe den Kundenmärkten, skalierbaren Lösungen und sicherem, nachverfolgbarem Bestand.',
                        'he' => 'פתרונות אחסון מקומיים מבטיחים משלוח מהיר ויעיל יותר בכך שהם ממקמים מוצרים קרוב לשווקים המרכזיים. כך מתקצרים זמני האספקה ונתמך מילוי הזמנות אמין בין אזורים — עם אחסון אסטרטגי, פתרונות מדרגיים ומלאי מאובטח ונתן למעקב.'],
        ],
        'quality-assurance' => [
            'title' => ['en' => 'Quality Assurance', 'zh' => '质量保证', 'es' => 'Garantía de Calidad',
                        'ru' => 'Контроль качества', 'fr' => 'Assurance qualité', 'pl' => 'Zapewnienie jakości',
                        'de' => 'Qualitätssicherung', 'he' => 'הבטחת איכות'],
            'img' => 'https://redexglass.com/wp-content/uploads/2026/01/Ekran-Resmi-2026-01-06-14.02.56-scaled.png',
            'excerpt' => ['en' => 'Manufactured to pharmaceutical and cosmetic industry standards with consistent, batch-level quality control.',
                          'zh' => '按照医药与化妆品行业标准生产，实施稳定的批次级质量控制。',
                          'es' => 'Fabricado según estándares de las industrias farmacéutica y cosmética con control de calidad por lotes.',
                          'ru' => 'Производится по стандартам фармацевтической и косметической отраслей с постоянным контролем качества на уровне партий.',
                          'fr' => 'Fabriqué selon les normes des industries pharmaceutique et cosmétique, avec un contrôle qualité constant par lot.',
                          'pl' => 'Produkcja zgodnie ze standardami branży farmaceutycznej i kosmetycznej, ze stałą kontrolą jakości na poziomie partii.',
                          'de' => 'Gefertigt nach Standards der Pharma- und Kosmetikindustrie mit gleichbleibender Qualitätskontrolle auf Chargenebene.',
                          'he' => 'מיוצר לפי תקני תעשיות התרופות והקוסמטיקה עם בקרת איכות עקבית ברמת האצווה.'],
            'body' => ['en' => 'High-quality bottle manufacturing is achieved through defined production standards, advanced technologies, and controlled processes. Carefully selected raw materials ensure durability and safety; structured quality control measures are applied throughout production to maintain reliability and batch consistency.',
                        'zh' => '高质量的瓶子制造依托明确的生产标准、先进的技术与受控的工艺。精心挑选的原材料确保耐用与安全；在整个生产过程中实施系统化的质量控制措施，以保持可靠性与批次一致性。',
                        'es' => 'La fabricación de alta calidad se logra mediante estándares definidos, tecnologías avanzadas y procesos controlados. Las materias primas seleccionadas garantizan durabilidad y seguridad, y se aplican controles de calidad estructurados durante toda la producción para mantener la fiabilidad y la consistencia entre lotes.',
                        'ru' => 'Высокое качество изготовления флаконов достигается за счёт установленных стандартов, передовых технологий и контролируемых процессов. Тщательно отобранное сырьё обеспечивает прочность и безопасность; на всех этапах применяются структурированные меры контроля качества для надёжности и однородности партий.',
                        'fr' => 'Une fabrication de haute qualité repose sur des standards définis, des technologies avancées et des procédés maîtrisés. Des matières premières soigneusement sélectionnées garantissent durabilité et sécurité ; des contrôles qualité structurés sont appliqués tout au long de la production pour assurer fiabilité et homogénéité des lots.',
                        'pl' => 'Wysoką jakość produkcji butelek osiągamy dzięki określonym standardom, zaawansowanym technologiom i kontrolowanym procesom. Starannie dobrane surowce zapewniają trwałość i bezpieczeństwo; w całym procesie stosujemy ustrukturyzowaną kontrolę jakości dla niezawodności i jednorodności partii.',
                        'de' => 'Hochwertige Flaschenfertigung wird durch definierte Standards, moderne Technologien und kontrollierte Prozesse erreicht. Sorgfältig ausgewählte Rohstoffe sichern Langlebigkeit und Sicherheit; strukturierte Qualitätskontrollen über die gesamte Produktion gewährleisten Zuverlässigkeit und Chargenkonsistenz.',
                        'he' => 'ייצור בקבוקים באיכות גבוהה מושג באמצעות תקנים מוגדרים, טכנולוגיות מתקדמות ותהליכים מבוקרים. חומרי גלם נבחרים מבטיחים עמידות ובטיחות; אמצעי בקרת איכות מובנים מיושמים לאורך הייצור לשמירה על אמינות ואחידות בין אצוות.'],
        ],
    ];
}

function content_products(): array
{
    $u = 'https://redexglass.com/wp-content/uploads/2025/12/';
    return [
        [
            'slug' => 'injection-vials-borosilicate-tubing',
            'img'  => $u . 'p1.png',
            'name' => ['en' => 'Injection Vials Made of Borosilicate Glass Tubing', 'zh' => '硼硅玻璃管制西林瓶',
                       'es' => 'Viales de Inyección de Tubo de Vidrio Borosilicato', 'ru' => 'Флаконы для инъекций из боросиликатного трубчатого стекла',
                       'fr' => 'Flacons injectables en verre tubulaire borosilicate', 'pl' => 'Fiolki iniekcyjne ze szkła borokrzemowego rurowego',
                       'de' => 'Injektionsfläschchen aus Borosilikat-Röhrenglas', 'he' => 'בקבוקוני הזרקה מזכוכית בורוסיליקט'],
            'excerpt' => ['en' => 'High thermal and chemical resistance for pharmaceutical, biotech, and laboratory applications.',
                          'zh' => '具有高耐热与耐化学性，适用于医药、生物技术与实验室领域。',
                          'es' => 'Alta resistencia térmica y química para aplicaciones farmacéuticas, biotecnológicas y de laboratorio.',
                          'ru' => 'Высокая термическая и химическая стойкость для фармацевтики, биотехнологий и лабораторий.',
                          'fr' => 'Haute résistance thermique et chimique pour les applications pharmaceutiques, biotech et de laboratoire.',
                          'pl' => 'Wysoka odporność termiczna i chemiczna do zastosowań farmaceutycznych, biotechnologicznych i laboratoryjnych.',
                          'de' => 'Hohe thermische und chemische Beständigkeit für Pharma-, Biotech- und Laboranwendungen.',
                          'he' => 'עמידות תרמית וכימית גבוהה ליישומים פרמצבטיים, ביוטכנולוגיים ומעבדתיים.'],
            'body' => ['en' => 'Injection vials made from borosilicate glass tubing provide high thermal and chemical resistance for pharmaceutical, biotech, and laboratory applications. They offer excellent clarity, low alkali release, and strong durability under demanding conditions.',
                        'zh' => '硼硅玻璃管制西林瓶具有高耐热与耐化学性，适用于医药、生物技术与实验室领域。瓶身透明度高、碱金属析出低，并在严苛条件下保持优异的耐用性。',
                        'es' => 'Los viales de inyección de tubo de vidrio borosilicato ofrecen alta resistencia térmica y química para aplicaciones farmacéuticas, biotecnológicas y de laboratorio. Destacan por su excelente transparencia, baja liberación de álcalis y gran durabilidad en condiciones exigentes.',
                        'ru' => 'Флаконы для инъекций из боросиликатного трубчатого стекла обладают высокой термической и химической стойкостью для фармацевтики, биотехнологий и лабораторий. Они отличаются превосходной прозрачностью, низким выделением щелочей и прочностью в сложных условиях.',
                        'fr' => 'Les flacons injectables en verre tubulaire borosilicate offrent une haute résistance thermique et chimique pour les applications pharmaceutiques, biotech et de laboratoire. Ils se distinguent par une excellente transparence, une faible libération alcaline et une grande durabilité.',
                        'pl' => 'Fiolki iniekcyjne ze szkła borokrzemowego rurowego zapewniają wysoką odporność termiczną i chemiczną do zastosowań farmaceutycznych, biotechnologicznych i laboratoryjnych. Cechują się doskonałą przejrzystością, niskim uwalnianiem alkaliów i trwałością w wymagających warunkach.',
                        'de' => 'Injektionsfläschchen aus Borosilikat-Röhrenglas bieten hohe thermische und chemische Beständigkeit für Pharma-, Biotech- und Laboranwendungen. Sie überzeugen durch hervorragende Klarheit, geringe Alkaliabgabe und starke Haltbarkeit unter anspruchsvollen Bedingungen.',
                        'he' => 'בקבוקוני הזרקה מזכוכית בורוסיליקט מספקים עמידות תרמית וכימית גבוהה ליישומים פרמצבטיים, ביוטכנולוגיים ומעבדתיים. הם מציעים צלילות מצוינת, שחרור בסיסים נמוך ועמידות גבוהה בתנאים תובעניים.'],
            'specs' => ['sizes' => '2 ml – 30 ml', 'colors' => 'Clear', 'neck' => 'ISO13 / ISO20', 'code' => 'RG'],
        ],
        [
            'slug' => 'boston-round-bottles',
            'img'  => $u . 'p3.png',
            'name' => ['en' => 'Boston Round Bottle', 'zh' => '波士顿圆瓶', 'es' => 'Botella Boston Round',
                       'ru' => 'Бутылка Boston Round', 'fr' => 'Flacon Boston Round', 'pl' => 'Butelka Boston Round',
                       'de' => 'Boston-Round-Flasche', 'he' => 'בקבוק בוסטון עגול'],
            'excerpt' => ['en' => 'Classic amber or clear bottles that protect light-sensitive products; sizes 1/2 oz–32 oz.',
                          'zh' => '经典棕色或透明瓶，保护对光敏感的产品；规格 1/2 oz–32 oz。',
                          'es' => 'Botellas clásicas ámbar o transparentes que protegen productos sensibles a la luz; tamaños 1/2 oz–32 oz.',
                          'ru' => 'Классические янтарные или прозрачные бутылки для светочувствительных продуктов; объёмы 1/2 oz–32 oz.',
                          'fr' => 'Flacons classiques ambrés ou transparents qui protègent les produits sensibles à la lumière ; tailles 1/2 oz–32 oz.',
                          'pl' => 'Klasyczne butelki oranżowe lub bezbarwne chroniące produkty wrażliwe na światło; pojemności 1/2 oz–32 oz.',
                          'de' => 'Klassische Braun- oder Klarglasflaschen zum Schutz lichtempfindlicher Produkte; Größen 1/2 oz–32 oz.',
                          'he' => 'בקבוקים קלאסיים בענבר או שקופים המגנים על מוצרים רגישים לאור; גדלים 1/2 oz–32 oz.'],
            'body' => ['en' => 'Boston Round Bottles are a classic, functional packaging solution available in both amber and clear glass. Amber protects light-sensitive contents, while clear offers full product visibility. Available in sizes from 1/2 oz to 32 oz, they suit pharmaceuticals, cosmetics, essential oils, and herbal extracts.',
                        'zh' => '波士顿圆瓶是一种经典实用的包装方案，提供棕色与透明两种玻璃。棕色可保护对光敏感的内容物，透明则便于观察产品。规格从 1/2 oz 到 32 oz，适用于医药、化妆品、精油与草本提取物。',
                        'es' => 'Las botellas Boston Round son una solución de envasado clásica y funcional, disponible en vidrio ámbar y transparente. El ámbar protege el contenido sensible a la luz y el transparente ofrece plena visibilidad. Disponibles de 1/2 oz a 32 oz, son ideales para farmacia, cosmética, aceites esenciales y extractos.',
                        'ru' => 'Бутылки Boston Round — классическое функциональное решение, доступное из янтарного и прозрачного стекла. Янтарное защищает светочувствительное содержимое, прозрачное обеспечивает полную видимость. Объёмы от 1/2 oz до 32 oz; подходят для фармацевтики, косметики, эфирных масел и экстрактов.',
                        'fr' => 'Les flacons Boston Round sont une solution d’emballage classique et fonctionnelle, disponibles en verre ambré et transparent. L’ambré protège le contenu sensible à la lumière, le transparent offre une visibilité totale. De 1/2 oz à 32 oz, ils conviennent à la pharmacie, la cosmétique, les huiles essentielles et les extraits.',
                        'pl' => 'Butelki Boston Round to klasyczne, funkcjonalne rozwiązanie dostępne w szkle oranżowym i bezbarwnym. Oranżowe chroni zawartość wrażliwą na światło, bezbarwne zapewnia pełną widoczność. W pojemnościach od 1/2 oz do 32 oz, odpowiednie dla farmacji, kosmetyków, olejków eterycznych i ekstraktów.',
                        'de' => 'Boston-Round-Flaschen sind eine klassische, funktionale Verpackungslösung in Braun- und Klarglas. Braunglas schützt lichtempfindliche Inhalte, Klarglas bietet volle Produktsicht. In Größen von 1/2 oz bis 32 oz eignen sie sich für Pharma, Kosmetik, ätherische Öle und Extrakte.',
                        'he' => 'בקבוקי בוסטון עגולים הם פתרון אריזה קלאסי ופונקציונלי, זמין בזכוכית ענבר ושקופה. הענבר מגן על תכולה רגישה לאור, והשקוף מאפשר נראות מלאה. בגדלים מ-1/2 oz עד 32 oz, מתאימים לתרופות, קוסמטיקה, שמנים אתריים ותמציות.'],
            'specs' => ['sizes' => '1/2 oz – 32 oz', 'colors' => 'Amber, Clear', 'neck' => 'GPI 18/400 – 33/400', 'code' => 'RR'],
        ],
        [
            'slug' => 'glass-cream-jars',
            'img'  => $u . 'p4.png',
            'name' => ['en' => 'Glass Cream Jars', 'zh' => '玻璃膏霜瓶', 'es' => 'Tarros de Crema de Vidrio',
                       'ru' => 'Косметические банки из стекла', 'fr' => 'Pots cosmétiques en verre', 'pl' => 'Słoiki kosmetyczne szklane',
                       'de' => 'Glas-Cremetiegel', 'he' => 'צנצנות קרם מזכוכית'],
            'excerpt' => ['en' => 'Elegant, durable packaging for skincare and cosmetics in clear, amber, frosted, blue, and green.',
                          'zh' => '优雅耐用的护肤与化妆品包装，提供透明、棕色、蒙砂、蓝色与绿色。',
                          'es' => 'Envase elegante y duradero para cuidado de la piel en transparente, ámbar, esmerilado, azul y verde.',
                          'ru' => 'Элегантная прочная упаковка для ухода за кожей: прозрачная, янтарная, матовая, синяя и зелёная.',
                          'fr' => 'Emballage élégant et durable pour le soin et la cosmétique, en transparent, ambré, dépoli, bleu et vert.',
                          'pl' => 'Eleganckie i trwałe opakowanie do pielęgnacji w wersji bezbarwnej, oranżowej, matowej, niebieskiej i zielonej.',
                          'de' => 'Elegante, robuste Verpackung für Pflege und Kosmetik in Klar, Braun, Satiniert, Blau und Grün.',
                          'he' => 'אריזה אלגנטית ועמידה לטיפוח העור בשקוף, ענבר, מט, כחול וירוק.'],
            'body' => ['en' => 'Our glass cream jars provide an elegant, durable packaging solution for skincare and cosmetic products such as face creams, eye balms, and moisturizers. They are available in various sizes and a wide range of colors.',
                        'zh' => '我们的玻璃膏霜瓶为面霜、眼霜与保湿霜等护肤及化妆品提供优雅耐用的包装方案，具有多种规格与丰富的颜色可选。',
                        'es' => 'Nuestros tarros de crema de vidrio ofrecen un envase elegante y duradero para productos de cuidado de la piel y cosmética, como cremas faciales, bálsamos y hidratantes. Disponibles en varios tamaños y una amplia gama de colores.',
                        'ru' => 'Наши стеклянные косметические банки — элегантная и прочная упаковка для кремов для лица, бальзамов и увлажняющих средств. Доступны в разных размерах и широкой палитре цветов.',
                        'fr' => 'Nos pots cosmétiques en verre offrent un emballage élégant et durable pour les soins — crèmes visage, baumes et hydratants. Disponibles en plusieurs tailles et une large palette de couleurs.',
                        'pl' => 'Nasze szklane słoiki kosmetyczne to eleganckie, trwałe opakowanie dla kremów do twarzy, balsamów i preparatów nawilżających. Dostępne w różnych rozmiarach i szerokiej gamie kolorów.',
                        'de' => 'Unsere Glas-Cremetiegel bieten elegante, robuste Verpackung für Pflegeprodukte wie Gesichtscremes, Balsame und Feuchtigkeitscremes. In verschiedenen Größen und einer breiten Farbpalette erhältlich.',
                        'he' => 'צנצנות הקרם מזכוכית שלנו מספקות אריזה אלגנטית ועמידה לקרמי פנים, משחות ולחות. זמינות במגוון גדלים וקשת צבעים רחבה.'],
            'specs' => ['sizes' => '10 ml – 100 ml', 'colors' => 'Amber, Clear, Clear Frosted, Black, Blue, Green', 'neck' => '—', 'code' => 'RC'],
        ],
        [
            'slug' => 'dropper-dispensing-bottles',
            'img'  => $u . 'Dropper-Dispensing-Bottles_-removebg-preview.png',
            'name' => ['en' => 'Dropper Dispensing Bottles', 'zh' => '滴管瓶', 'es' => 'Botellas Cuentagotas',
                       'ru' => 'Флаконы-капельницы', 'fr' => 'Flacons compte-gouttes', 'pl' => 'Butelki z kroplomierzem',
                       'de' => 'Tropfflaschen', 'he' => 'בקבוקי טפטפת'],
            'excerpt' => ['en' => 'Precise, controlled dispensing for essential oils, serums, tinctures, and formulations.',
                          'zh' => '精准可控地滴取精油、精华、酊剂与各类配方。',
                          'es' => 'Dosificación precisa y controlada para aceites esenciales, sueros, tinturas y formulaciones.',
                          'ru' => 'Точное дозирование эфирных масел, сывороток, настоек и составов.',
                          'fr' => 'Distribution précise et contrôlée des huiles essentielles, sérums, teintures et formulations.',
                          'pl' => 'Precyzyjne, kontrolowane dozowanie olejków eterycznych, serum, nalewek i formulacji.',
                          'de' => 'Präzise, kontrollierte Dosierung für ätherische Öle, Seren, Tinkturen und Formulierungen.',
                          'he' => 'מתן מדויק ומבוקר לשמנים אתריים, סרומים, תמציות ותרכובות.'],
            'body' => ['en' => 'Our dropper bottles are designed for precise, controlled dispensing of cosmetic, pharmaceutical, and laboratory liquids such as essential oils, serums, and tinctures. They are available in various sizes and colors with matching dropper assemblies.',
                        'zh' => '我们的滴管瓶专为精油、精华、酊剂等化妆品、医药及实验室液体的精准可控滴取而设计，提供多种规格与颜色，并配有配套滴管组件。',
                        'es' => 'Nuestras botellas cuentagotas están diseñadas para una dosificación precisa y controlada de líquidos cosméticos, farmacéuticos y de laboratorio, como aceites esenciales, sueros y tinturas. Disponibles en varios tamaños y colores con conjuntos cuentagotas a juego.',
                        'ru' => 'Наши флаконы-капельницы предназначены для точного дозирования косметических, фармацевтических и лабораторных жидкостей — эфирных масел, сывороток и настоек. Доступны в разных размерах и цветах с подходящими пипетками.',
                        'fr' => 'Nos flacons compte-gouttes assurent une distribution précise et contrôlée de liquides cosmétiques, pharmaceutiques et de laboratoire — huiles essentielles, sérums, teintures. Disponibles en plusieurs tailles et couleurs avec ensembles compte-gouttes assortis.',
                        'pl' => 'Nasze butelki z kroplomierzem zapewniają precyzyjne dozowanie płynów kosmetycznych, farmaceutycznych i laboratoryjnych — olejków, serum, nalewek. Dostępne w różnych rozmiarach i kolorach z dopasowanymi zestawami kroplomierzy.',
                        'de' => 'Unsere Tropfflaschen ermöglichen präzise, kontrollierte Dosierung kosmetischer, pharmazeutischer und Laborflüssigkeiten wie ätherischer Öle, Seren und Tinkturen. In verschiedenen Größen und Farben mit passenden Tropfeinsätzen erhältlich.',
                        'he' => 'בקבוקי הטפטפת שלנו מיועדים למתן מדויק ומבוקר של נוזלים קוסמטיים, פרמצבטיים ומעבדתיים כמו שמנים אתריים, סרומים ותמציות. זמינים במגוון גדלים וצבעים עם מכלולי טפטפת תואמים.'],
            'specs' => ['sizes' => '5 ml – 150 ml', 'colors' => 'Amber, Clear, Black, Blue, Green', 'neck' => 'DIN18', 'code' => 'RD'],
        ],
        [
            'slug' => 'glass-infusion-bottles',
            'img'  => $u . 'gorsel1.png',
            'name' => ['en' => 'Clear Infusion Bottles', 'zh' => '透明输液瓶', 'es' => 'Botellas de Infusión Transparentes',
                       'ru' => 'Прозрачные инфузионные флаконы', 'fr' => 'Flacons de perfusion transparents', 'pl' => 'Przezroczyste butelki infuzyjne',
                       'de' => 'Transparente Infusionsflaschen', 'he' => 'בקבוקי עירוי שקופים'],
            'excerpt' => ['en' => 'Clear glass bottles for infusion and injectable solutions with excellent visibility and protection.',
                          'zh' => '透明玻璃瓶，用于输液与注射溶液，可视性好、保护性强。',
                          'es' => 'Botellas de vidrio transparente para soluciones de infusión e inyectables, con gran visibilidad y protección.',
                          'ru' => 'Прозрачные стеклянные флаконы для инфузионных и инъекционных растворов с отличной видимостью и защитой.',
                          'fr' => 'Flacons en verre transparent pour solutions de perfusion et injectables, grande visibilité et protection.',
                          'pl' => 'Przezroczyste butelki szklane do roztworów infuzyjnych i iniekcyjnych, z doskonałą widocznością i ochroną.',
                          'de' => 'Klarglasflaschen für Infusions- und Injektionslösungen mit hervorragender Sicht und Schutz.',
                          'he' => 'בקבוקי זכוכית שקופים לתמיסות עירוי והזרקה, עם נראות והגנה מעולות.'],
            'body' => ['en' => 'Glass infusion bottles are designed for reliable packaging of infusion and injectable pharmaceutical solutions. Made from high-quality transparent glass with an ISO32 neck finish, they provide excellent product visibility and effective protection against contamination.',
                        'zh' => '玻璃输液瓶专为输液及注射类药液的可靠包装而设计。采用优质透明玻璃，配 ISO32 瓶口，可视性好，并有效防止污染。',
                        'es' => 'Las botellas de infusión de vidrio están diseñadas para el envasado fiable de soluciones farmacéuticas de infusión e inyectables. Fabricadas en vidrio transparente de alta calidad con boca ISO32, ofrecen excelente visibilidad y protección frente a la contaminación.',
                        'ru' => 'Инфузионные стеклянные флаконы предназначены для надёжной упаковки инфузионных и инъекционных растворов. Изготовлены из качественного прозрачного стекла с горловиной ISO32, обеспечивают отличную видимость и защиту от загрязнения.',
                        'fr' => 'Les flacons de perfusion en verre assurent un conditionnement fiable des solutions de perfusion et injectables. En verre transparent de qualité avec col ISO32, ils offrent une excellente visibilité et une protection efficace contre la contamination.',
                        'pl' => 'Szklane butelki infuzyjne zapewniają niezawodne opakowanie roztworów infuzyjnych i iniekcyjnych. Wykonane z wysokiej jakości szkła bezbarwnego z szyjką ISO32, dają doskonałą widoczność i skuteczną ochronę przed zanieczyszczeniem.',
                        'de' => 'Glas-Infusionsflaschen sind für die zuverlässige Verpackung von Infusions- und Injektionslösungen ausgelegt. Aus hochwertigem Klarglas mit ISO32-Mündung bieten sie hervorragende Produktsicht und wirksamen Schutz vor Kontamination.',
                        'he' => 'בקבוקי עירוי מזכוכית מיועדים לאריזה אמינה של תמיסות עירוי והזרקה. עשויים מזכוכית שקופה איכותית עם גימור צוואר ISO32, מספקים נראות מצוינת והגנה יעילה מפני זיהום.'],
            'specs' => ['sizes' => '50 ml – 500 ml', 'colors' => 'Clear', 'neck' => 'ISO32', 'code' => 'RI'],
        ],
        [
            'slug' => 'injection-vials-glass-bottles',
            'img'  => asset('img/molded-glass-injection-vials.jpg'),
            'name' => ['en' => 'Molded Glass Injection Vials', 'zh' => '模制玻璃西林瓶', 'es' => 'Viales de inyección de vidrio moldeado',
                       'ru' => 'Формованные стеклянные флаконы для инъекций', 'fr' => 'Flacons injectables en verre moulé', 'pl' => 'Formowane szklane fiolki iniekcyjne',
                       'de' => 'Injektionsfläschchen aus Formglas', 'he' => 'בקבוקוני הזרקה מזכוכית יצוקה'],
            'excerpt' => ['en' => 'Moulded vials with excellent chemical resistance and thermal stability; amber and clear.',
                          'zh' => '模制西林瓶，耐化学性与热稳定性优异；提供棕色与透明。',
                          'es' => 'Viales moldeados con excelente resistencia química y estabilidad térmica; ámbar y transparente.',
                          'ru' => 'Формованные флаконы с отличной химстойкостью и термостабильностью; янтарные и прозрачные.',
                          'fr' => 'Flacons moulés à excellente résistance chimique et stabilité thermique ; ambré et transparent.',
                          'pl' => 'Fiolki formowane o doskonałej odporności chemicznej i stabilności termicznej; oranżowe i bezbarwne.',
                          'de' => 'Formgeblasene Fläschchen mit hervorragender Chemikalienbeständigkeit und Thermostabilität; braun und klar.',
                          'he' => 'בקבוקונים יצוקים עם עמידות כימית ויציבות תרמית מעולות; ענבר ושקוף.'],
            'body' => ['en' => 'Our moulded injection vial glass bottles are designed for pharmaceutical, laboratory, and medical applications. They offer excellent chemical resistance, thermal stability, and durability, and are available in both amber and clear glass.',
                        'zh' => '我们的模制西林瓶适用于医药、实验室与医疗领域，具有优异的耐化学性、热稳定性与耐用性，提供棕色与透明两种玻璃。',
                        'es' => 'Nuestros viales de inyección moldeados están diseñados para aplicaciones farmacéuticas, de laboratorio y médicas. Ofrecen excelente resistencia química, estabilidad térmica y durabilidad, en vidrio ámbar y transparente.',
                        'ru' => 'Наши формованные флаконы для инъекций предназначены для фармацевтики, лабораторий и медицины. Они отличаются химстойкостью, термостабильностью и прочностью; доступны из янтарного и прозрачного стекла.',
                        'fr' => 'Nos flacons injectables moulés sont conçus pour les applications pharmaceutiques, de laboratoire et médicales. Ils offrent une excellente résistance chimique, une stabilité thermique et une durabilité, en verre ambré et transparent.',
                        'pl' => 'Nasze formowane fiolki iniekcyjne są przeznaczone do zastosowań farmaceutycznych, laboratoryjnych i medycznych. Oferują doskonałą odporność chemiczną, stabilność termiczną i trwałość; w szkle oranżowym i bezbarwnym.',
                        'de' => 'Unsere formgeblasenen Injektionsflaschen eignen sich für Pharma-, Labor- und Medizinanwendungen. Sie bieten hervorragende Chemikalienbeständigkeit, Thermostabilität und Haltbarkeit, in Braun- und Klarglas.',
                        'he' => 'בקבוקוני ההזרקה היצוקים שלנו מיועדים ליישומים פרמצבטיים, מעבדתיים ורפואיים. הם מציעים עמידות כימית, יציבות תרמית ועמידות, וזמינים בזכוכית ענבר ושקופה.'],
            'specs' => ['sizes' => '7 ml – 100 ml', 'colors' => 'Amber, Clear', 'neck' => 'ISO13 / ISO20', 'code' => 'RA'],
        ],
        [
            'slug' => 'glass-bottles-syrup',
            'img'  => $u . 'gorsel3.png',
            'name' => ['en' => 'Glass Bottles for Syrup', 'zh' => '糖浆瓶', 'es' => 'Botellas de Vidrio para Jarabe',
                       'ru' => 'Флаконы для сиропа', 'fr' => 'Flacons pour sirop', 'pl' => 'Butelki na syrop',
                       'de' => 'Sirupflaschen', 'he' => 'בקבוקים לסירופ'],
            'excerpt' => ['en' => 'Amber or clear bottles for pharmaceutical, nutritional, and food-grade syrups.',
                          'zh' => '棕色或透明瓶，适用于医药、营养与食品级糖浆。',
                          'es' => 'Botellas ámbar o transparentes para jarabes farmacéuticos, nutricionales y alimentarios.',
                          'ru' => 'Янтарные или прозрачные флаконы для фармацевтических, нутрициологических и пищевых сиропов.',
                          'fr' => 'Flacons ambrés ou transparents pour sirops pharmaceutiques, nutritionnels et alimentaires.',
                          'pl' => 'Butelki oranżowe lub bezbarwne na syropy farmaceutyczne, suplementacyjne i spożywcze.',
                          'de' => 'Braun- oder Klarglasflaschen für pharmazeutische, ernährungsphysiologische und lebensmitteltaugliche Sirupe.',
                          'he' => 'בקבוקים בענבר או שקופים לסירופים פרמצבטיים, תזונתיים ובדרגת מזון.'],
            'body' => ['en' => 'Glass bottles for syrup offer a reliable packaging solution for pharmaceutical, nutritional, and food applications. Available in amber and clear glass across a wide range of sizes and neck finishes, they preserve product quality and flavor.',
                        'zh' => '糖浆瓶为医药、营养与食品应用提供可靠的包装方案。提供棕色与透明玻璃、多种规格与瓶口形式，有效保持产品品质与风味。',
                        'es' => 'Las botellas de vidrio para jarabe ofrecen una solución de envasado fiable para aplicaciones farmacéuticas, nutricionales y alimentarias. Disponibles en vidrio ámbar y transparente, con amplia gama de tamaños y bocas, preservan la calidad y el sabor.',
                        'ru' => 'Флаконы для сиропа — надёжная упаковка для фармацевтических, нутрициологических и пищевых продуктов. Доступны из янтарного и прозрачного стекла в широком диапазоне объёмов и типов горловины, сохраняют качество и вкус.',
                        'fr' => 'Les flacons pour sirop offrent une solution d’emballage fiable pour les applications pharmaceutiques, nutritionnelles et alimentaires. En verre ambré et transparent, dans de nombreuses tailles et cols, ils préservent la qualité et le goût.',
                        'pl' => 'Butelki na syrop to niezawodne opakowanie do zastosowań farmaceutycznych, suplementacyjnych i spożywczych. Dostępne w szkle oranżowym i bezbarwnym, w szerokim zakresie pojemności i szyjek, zachowują jakość i smak.',
                        'de' => 'Sirupflaschen bieten eine zuverlässige Verpackung für pharmazeutische, ernährungs- und lebensmitteltaugliche Anwendungen. In Braun- und Klarglas, in vielen Größen und Mündungen, bewahren sie Qualität und Geschmack.',
                        'he' => 'בקבוקים לסירופ מספקים אריזה אמינה ליישומים פרמצבטיים, תזונתיים ולמזון. זמינים בזכוכית ענבר ושקופה במגוון רחב של גדלים וסוגי צוואר, ושומרים על איכות וטעם המוצר.'],
            'specs' => ['sizes' => '10 ml – 500 ml', 'colors' => 'Amber, Clear', 'neck' => 'DIN28 / PP18 – PP28', 'code' => 'RS'],
        ],
        [
            'slug' => 'glass-bottles-tablets',
            'img'  => $u . 'gorsel4.png',
            'name' => ['en' => 'Glass Bottles for Tablets', 'zh' => '片剂瓶', 'es' => 'Botellas de Vidrio para Comprimidos',
                       'ru' => 'Флаконы для таблеток', 'fr' => 'Flacons pour comprimés (piluliers)', 'pl' => 'Butelki na tabletki',
                       'de' => 'Tablettenflaschen', 'he' => 'בקבוקים לטבליות'],
            'excerpt' => ['en' => 'Wide-mouth packaging for tablets, capsules, and supplements with moisture and UV protection.',
                          'zh' => '宽口包装，适用于片剂、胶囊与保健品，具防潮与抗紫外线保护。',
                          'es' => 'Envase de boca ancha para comprimidos, cápsulas y suplementos con protección frente a humedad y UV.',
                          'ru' => 'Широкогорлая упаковка для таблеток, капсул и добавок с защитой от влаги и УФ.',
                          'fr' => 'Emballage à large col pour comprimés, gélules et compléments, avec protection contre l’humidité et les UV.',
                          'pl' => 'Opakowanie szerokootworowe na tabletki, kapsułki i suplementy z ochroną przed wilgocią i UV.',
                          'de' => 'Weithals-Verpackung für Tabletten, Kapseln und Nahrungsergänzung mit Feuchte- und UV-Schutz.',
                          'he' => 'אריזה בפתח רחב לטבליות, כמוסות ותוספים עם הגנה מלחות וקרינת UV.'],
            'body' => ['en' => 'Glass bottles for tablets provide a durable, wide-mouth packaging solution for pharmaceutical and nutraceutical products. Designed to protect tablets, capsules, and supplements, they offer excellent moisture resistance and UV protection, particularly in amber glass.',
                        'zh' => '片剂瓶为医药与保健品提供耐用的宽口包装方案。专为保护片剂、胶囊与保健品而设计，具优异的防潮性能与抗紫外线保护，尤以棕色玻璃为佳。',
                        'es' => 'Las botellas de vidrio para comprimidos ofrecen un envase duradero y de boca ancha para productos farmacéuticos y nutracéuticos. Diseñadas para proteger comprimidos, cápsulas y suplementos, destacan por su resistencia a la humedad y protección UV, especialmente en vidrio ámbar.',
                        'ru' => 'Флаконы для таблеток — прочная широкогорлая упаковка для фармацевтических и нутрицевтических продуктов. Созданы для защиты таблеток, капсул и добавок, обеспечивают отличную влагостойкость и УФ-защиту, особенно в янтарном стекле.',
                        'fr' => 'Les flacons pour comprimés offrent un emballage durable à large col pour produits pharmaceutiques et nutraceutiques. Conçus pour protéger comprimés, gélules et compléments, ils assurent une excellente résistance à l’humidité et une protection UV, surtout en verre ambré.',
                        'pl' => 'Butelki na tabletki to trwałe, szerokootworowe opakowanie dla produktów farmaceutycznych i nutraceutycznych. Zaprojektowane do ochrony tabletek, kapsułek i suplementów, zapewniają doskonałą odporność na wilgoć i ochronę UV, zwłaszcza w szkle oranżowym.',
                        'de' => 'Tablettenflaschen bieten eine robuste Weithals-Verpackung für pharmazeutische und nutrazeutische Produkte. Zum Schutz von Tabletten, Kapseln und Nahrungsergänzung bieten sie ausgezeichneten Feuchteschutz und UV-Schutz, besonders in Braunglas.',
                        'he' => 'בקבוקים לטבליות מספקים אריזה עמידה בפתח רחב למוצרים פרמצבטיים ונוטרה-צבטיים. מיועדים להגן על טבליות, כמוסות ותוספים, ומציעים עמידות מצוינת ללחות והגנת UV, במיוחד בזכוכית ענבר.'],
            'specs' => ['sizes' => '60 ml – 750 ml', 'colors' => 'Amber, Blue', 'neck' => 'GPI 33/400 – 53/400', 'code' => 'RT'],
        ],
        [
            'slug' => 'caps-and-accessories',
            'img'  => $u . 'gorsel5.png',
            'name' => ['en' => 'Caps and Accessories', 'zh' => '瓶盖与配件', 'es' => 'Tapas y Accesorios',
                       'ru' => 'Крышки и аксессуары', 'fr' => 'Bouchons et accessoires', 'pl' => 'Zakrętki i akcesoria',
                       'de' => 'Verschlüsse und Zubehör', 'he' => 'פקקים ואביזרים'],
            'excerpt' => ['en' => 'Aluminum & plastic caps, droppers, pipettes, and sprays to complete your glass packaging.',
                          'zh' => '铝盖、塑料盖、滴管、吸管与喷头，完善您的玻璃包装。',
                          'es' => 'Tapas de aluminio y plástico, cuentagotas, pipetas y pulverizadores para completar su envase de vidrio.',
                          'ru' => 'Алюминиевые и пластиковые крышки, капельницы, пипетки и спреи, дополняющие вашу стеклянную упаковку.',
                          'fr' => 'Bouchons aluminium et plastique, compte-gouttes, pipettes et sprays pour compléter votre emballage en verre.',
                          'pl' => 'Zakrętki aluminiowe i plastikowe, kroplomierze, pipety i spraye uzupełniające opakowanie szklane.',
                          'de' => 'Aluminium- und Kunststoffverschlüsse, Tropfeinsätze, Pipetten und Sprühköpfe zur Vervollständigung Ihrer Glasverpackung.',
                          'he' => 'פקקי אלומיניום ופלסטיק, טפטפות, פיפטות ותרסיסים להשלמת אריזת הזכוכית שלכם.'],
            'body' => ['en' => 'Our caps and accessories complete glass packaging solutions with functionality, safety, and visual consistency. The range includes aluminum multi caps, metal and wooden covers, dropper assemblies, rubber teats, plastic dropper covers, atomizer sprays, nose sprays, and throat sprays — in a wide range of finishes.',
                        'zh' => '我们的瓶盖与配件以功能性、安全性与视觉一致性完善玻璃包装方案。产品范围包括铝质多件盖、金属与木纹盖、滴管组件、橡胶头、塑料滴管盖、喷雾泵、鼻喷与喉喷——提供多种表面处理。',
                        'es' => 'Nuestras tapas y accesorios completan las soluciones de envase de vidrio con funcionalidad, seguridad y coherencia visual. La gama incluye tapas multipieza de aluminio, cubiertas metálicas y de madera, conjuntos cuentagotas, tetinas de goma, tapas de plástico, pulverizadores, sprays nasales y sprays para garganta, en múltiples acabados.',
                        'ru' => 'Наши крышки и аксессуары завершают решения по стеклянной упаковке функциональностью, безопасностью и визуальной целостностью. Ассортимент включает алюминиевые комбинированные крышки, металлические и «деревянные» колпачки, пипеточные узлы, резиновые соски, пластиковые колпачки для пипеток, распылители, назальные и горловые спреи — в различных отделках.',
                        'fr' => 'Nos bouchons et accessoires complètent les solutions d’emballage en verre avec fonctionnalité, sécurité et cohérence visuelle. La gamme comprend bouchons aluminium multipièces, capsules métal et aspect bois, ensembles compte-gouttes, tétines caoutchouc, bouchons plastique, sprays, sprays nasaux et sprays de gorge — en de nombreuses finitions.',
                        'pl' => 'Nasze zakrętki i akcesoria dopełniają rozwiązania opakowań szklanych funkcjonalnością, bezpieczeństwem i spójnością wizualną. W ofercie: wieloczęściowe zakrętki aluminiowe, nakładki metalowe i drewnopodobne, zestawy kroplomierzy, smoczki gumowe, nakładki plastikowe, atomizery, spraye do nosa i gardła — w wielu wykończeniach.',
                        'de' => 'Unsere Verschlüsse und Zubehörteile vervollständigen Glasverpackungen mit Funktion, Sicherheit und visueller Einheit. Das Sortiment umfasst Aluminium-Mehrteilkappen, Metall- und Holzoptik-Deckel, Tropfeinsätze, Gummisauger, Kunststoff-Tropferkappen, Zerstäuber, Nasen- und Rachensprays — in zahlreichen Ausführungen.',
                        'he' => 'הפקקים והאביזרים שלנו משלימים פתרונות אריזת זכוכית בפונקציונליות, בטיחות ואחידות חזותית. המגוון כולל פקקי אלומיניום רב-חלקיים, מכסים ממתכת ובגימור עץ, מכלולי טפטפת, פטמות גומי, מכסי טפטפת מפלסטיק, תרסיסים, תרסיסי אף ותרסיסי גרון — במגוון גימורים.'],
            'specs' => ['sizes' => '13 – 70 mm', 'colors' => 'Gold, Silver, Black, Matt, Red, Wooden & more', 'neck' => 'PP / GPI / DIN', 'code' => 'RDX'],
        ],
    ];
}

function find_product(string $slug): ?array
{
    foreach (content_products() as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

/** Ürün teknik özellik tabloları (app/products-specs.php). Kodlar ve ölçüler orada düzenlenir. */
function content_specs(string $slug): array
{
    static $all = null;
    if ($all === null) {
        $file = __DIR__ . '/products-specs.php';
        $all = is_file($file) ? (require $file) : [];
    }
    return $all[$slug] ?? [];
}

/** Spec tablosu terimlerini (başlık/renk/caps etiket/açıklama) aktif dile çevirir; bilinmeyen terim/kod/sayı aynen döner. */
function spec_t(string $term): string
{
    static $map = null;
    if ($map === null) {
        $file = __DIR__ . '/spec-i18n.php';
        $map = is_file($file) ? (require $file) : [];
    }
    $l = lang();
    if ($l === 'en') return $term;
    return $map[$term][$l] ?? $term;
}

/** Quality sayfasi icerigi (handbook v5 — kalite kontrol asamalari + urun testleri). */
function content_quality(): array
{
    return [
        'lead' => ['en' => 'Quality controlled at every stage', 'zh' => '每一道工序都严格把控品质', 'es' => 'Calidad controlada en cada etapa', 'ru' => 'Контроль качества на каждом этапе', 'fr' => 'La qualité contrôlée à chaque étape', 'pl' => 'Jakość kontrolowana na każdym etapie', 'de' => 'Qualität auf jeder Stufe geprüft', 'he' => 'בקרת איכות בכל שלב'],
        'body' => ['en' => 'Every Redex bottle moves through a multi-stage quality system. It begins at the forming machine and ends with a final QC inspection; in between, an automatic camera photographs each bottle and checks its external and neck-thread dimensions, air bubbles and visual defects, while light and packaging inspections catch any remaining flaws. Non-conforming bottles are separated, and every defect type has its own AQL (Acceptable Quality Level) limit.', 'zh' => '每一只 Redex 瓶子都要经过多道质量管控。从成型机开始，到最终质检结束；其间，自动相机逐瓶拍照，检查外形与瓶口螺纹尺寸、气泡及外观缺陷，灯检与包装检验再排查其余瑕疵。不合格品被分离，每种缺陷都有各自的 AQL（可接受质量水平）限值。', 'es' => 'Cada botella Redex pasa por un sistema de calidad de varias etapas. Comienza en la máquina de conformado y termina con una inspección final de QC; entremedias, una cámara automática fotografía cada botella y comprueba sus dimensiones externas y de rosca del cuello, las burbujas de aire y los defectos visuales, mientras que las inspecciones de luz y de embalaje detectan cualquier defecto restante. Las botellas no conformes se separan y cada tipo de defecto tiene su propio límite AQL (nivel de calidad aceptable).', 'ru' => 'Каждый флакон Redex проходит многоступенчатую систему качества. Она начинается на формующей машине и заканчивается финальной проверкой QC; между ними автоматическая камера фотографирует каждый флакон и проверяет наружные размеры и размеры резьбы горловины, воздушные пузырьки и визуальные дефекты, а световой и упаковочный контроль выявляют оставшиеся изъяны. Несоответствующие флаконы отделяются, и для каждого типа дефекта установлен свой предел AQL (приемлемый уровень качества).', 'fr' => 'Chaque flacon Redex traverse un système qualité en plusieurs étapes. Il commence à la machine de formage et se termine par un contrôle qualité final ; entre les deux, une caméra automatique photographie chaque flacon et vérifie ses dimensions externes et de filetage du col, les bulles d’air et les défauts visuels, tandis que les contrôles à la lumière et à l’emballage détectent les défauts restants. Les flacons non conformes sont écartés et chaque type de défaut a sa propre limite AQL (niveau de qualité acceptable).', 'pl' => 'Każda butelka Redex przechodzi wieloetapowy system jakości. Zaczyna się na maszynie formującej, a kończy końcową kontrolą QC; pomiędzy nimi automatyczna kamera fotografuje każdą butelkę i sprawdza jej wymiary zewnętrzne oraz gwintu szyjki, pęcherze powietrza i wady wizualne, a kontrola świetlna i pakowania wychwytuje pozostałe usterki. Butelki niezgodne są oddzielane, a każdy typ wady ma własny limit AQL (akceptowalny poziom jakości).', 'de' => 'Jede Redex-Flasche durchläuft ein mehrstufiges Qualitätssystem. Es beginnt an der Formmaschine und endet mit einer finalen QC-Prüfung; dazwischen fotografiert eine automatische Kamera jede Flasche und prüft Außen- und Mündungsgewindemaße, Luftblasen und optische Fehler, während Durchlicht- und Verpackungsprüfung verbleibende Mängel erfassen. Nicht konforme Flaschen werden aussortiert, und jeder Fehlertyp hat seinen eigenen AQL-Grenzwert (Acceptable Quality Level).', 'he' => 'כל בקבוק של Redex עובר מערכת איכות רב-שלבית. היא מתחילה במכונת העיצוב ומסתיימת בבדיקת QC סופית; בין לבין, מצלמה אוטומטית מצלמת כל בקבוק ובודקת את מידותיו החיצוניות ואת מידות תבריג הצוואר, בועות אוויר ופגמים חזותיים, בעוד בדיקות אור ואריזה מאתרות פגמים שנותרו. בקבוקים שאינם תקינים מופרדים, ולכל סוג פגם יש גבול AQL משלו (רמת איכות מקובלת).'],
        'tests_title' => ['en' => 'Quality tests we perform', 'zh' => '我们开展的质量测试', 'es' => 'Ensayos de calidad que realizamos', 'ru' => 'Испытания качества, которые мы проводим', 'fr' => 'Les essais qualité que nous réalisons', 'pl' => 'Badania jakości, które wykonujemy', 'de' => 'Qualitätsprüfungen, die wir durchführen', 'he' => 'בדיקות האיכות שאנו מבצעים'],
        'tests_intro' => ['en' => 'Beyond in-line inspection, we carry out laboratory product tests to industry, national and relevant international standards:', 'zh' => '除在线检验外，我们还按行业、国家及相关国际标准进行实验室产品测试：', 'es' => 'Además de la inspección en línea, realizamos ensayos de producto en laboratorio según normas industriales, nacionales e internacionales pertinentes:', 'ru' => 'Помимо контроля на линии, мы проводим лабораторные испытания изделий по отраслевым, национальным и соответствующим международным стандартам:', 'fr' => 'Au-delà du contrôle en ligne, nous réalisons des essais produit en laboratoire selon les normes industrielles, nationales et internationales pertinentes :', 'pl' => 'Poza kontrolą na linii wykonujemy laboratoryjne badania produktu zgodnie z normami branżowymi, krajowymi i odpowiednimi międzynarodowymi:', 'de' => 'Über die Inline-Kontrolle hinaus führen wir Laborproduktprüfungen nach branchenbezogenen, nationalen und relevanten internationalen Normen durch:', 'he' => 'מעבר לבדיקה בקו הייצור, אנו מבצעים בדיקות מוצר במעבדה לפי תקנים תעשייתיים, לאומיים ובינלאומיים רלוונטיים:'],
        'tests' => [
            ['en' => 'Internal surface hydrolytic resistance test', 'zh' => '内表面耐水性测试', 'es' => 'Ensayo de resistencia hidrolítica de la superficie interna', 'ru' => 'Испытание гидролитической стойкости внутренней поверхности', 'fr' => 'Essai de résistance hydrolytique de la surface interne', 'pl' => 'Badanie odporności hydrolitycznej powierzchni wewnętrznej', 'de' => 'Prüfung der hydrolytischen Beständigkeit der inneren Oberfläche', 'he' => 'בדיקת עמידות הידרוליטית של פני השטח הפנימיים'],
            ['en' => 'Thermal shock test', 'zh' => '热震测试', 'es' => 'Ensayo de choque térmico', 'ru' => 'Испытание на термоудар', 'fr' => 'Essai de choc thermique', 'pl' => 'Badanie szoku termicznego', 'de' => 'Temperaturwechsel­beständigkeitsprüfung', 'he' => 'בדיקת הלם תרמי'],
            ['en' => 'Glass grain (particle) hydrolytic resistance test', 'zh' => '玻璃颗粒耐水性测试', 'es' => 'Ensayo de resistencia hidrolítica del granulado de vidrio (partículas)', 'ru' => 'Испытание гидролитической стойкости стеклянной крошки (частиц)', 'fr' => 'Essai de résistance hydrolytique du verre en grains (particules)', 'pl' => 'Badanie odporności hydrolitycznej rozdrobnionego szkła (cząstek)', 'de' => 'Prüfung der hydrolytischen Beständigkeit des Glasgrießes (Partikel)', 'he' => 'בדיקת עמידות הידרוליטית של גרגרי זכוכית (חלקיקים)'],
            ['en' => 'Stress (annealing) test', 'zh' => '应力（退火）测试', 'es' => 'Ensayo de tensiones (recocido)', 'ru' => 'Испытание на остаточные напряжения (отжиг)', 'fr' => 'Essai de contraintes (recuit)', 'pl' => 'Badanie naprężeń (odprężanie)', 'de' => 'Spannungsprüfung (Kühlung)', 'he' => 'בדיקת מאמצים (חישול)'],
            ['en' => 'Heavy metal release test', 'zh' => '重金属溶出测试', 'es' => 'Ensayo de liberación de metales pesados', 'ru' => 'Испытание на выделение тяжёлых металлов', 'fr' => 'Essai de libération de métaux lourds', 'pl' => 'Badanie uwalniania metali ciężkich', 'de' => 'Schwermetallabgabe-Prüfung', 'he' => 'בדיקת שחרור מתכות כבדות'],
        ],
        'note' => ['en' => 'Confirm the exact test scope you need with our technical team.', 'zh' => '请与我们的技术团队确认您所需的具体测试范围。', 'es' => 'Confirme con nuestro equipo técnico el alcance exacto de los ensayos que necesita.', 'ru' => 'Уточните необходимый объём испытаний у нашей технической команды.', 'fr' => 'Confirmez l’étendue exacte des essais dont vous avez besoin avec notre équipe technique.', 'pl' => 'Zakres potrzebnych badań potwierdź z naszym zespołem technicznym.', 'de' => 'Stimmen Sie den genauen Prüfumfang mit unserem technischen Team ab.', 'he' => 'אנא אשרו מול הצוות הטכני שלנו את היקף הבדיקות הנדרש לכם.'],
    ];
}

/**
 * Açık pozisyonlar. Pozisyon bilgileri verilince buraya eklenecek.
 */
function content_positions(): array
{
    return [];
}
