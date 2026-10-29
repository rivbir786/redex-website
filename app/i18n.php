<?php
/** Çok dillilik: dil tespiti ve çeviri fonksiyonu t(). */

function i18n_detect(string $uriLang = ''): string
{
    $langs = array_keys(cfg('languages'));
    if ($uriLang && in_array($uriLang, $langs, true)) {
        return $uriLang;
    }
    return cfg('default_lang');
}

function load_lang(string $l): array
{
    static $cache = [];
    if (isset($cache[$l])) return $cache[$l];
    $file = __DIR__ . '/lang/' . $l . '.php';
    $cache[$l] = is_file($file) ? require $file : [];
    return $cache[$l];
}

/**
 * Çeviri. Anahtar yoksa önce varsayılan dile, o da yoksa anahtarın kendisine düşer.
 * t('nav.products') gibi noktalı anahtarlar desteklenir.
 */
function t(string $key, array $vars = []): string
{
    $val = lang_get(load_lang(lang()), $key);
    if ($val === null && lang() !== cfg('default_lang')) {
        $val = lang_get(load_lang(cfg('default_lang')), $key);
    }
    if ($val === null) $val = $key;
    foreach ($vars as $k => $v) {
        $val = str_replace('{' . $k . '}', $v, $val);
    }
    return $val;
}

function lang_get(array $arr, string $key)
{
    if (array_key_exists($key, $arr)) return $arr[$key];
    $parts = explode('.', $key);
    $cur = $arr;
    foreach ($parts as $p) {
        if (is_array($cur) && array_key_exists($p, $cur)) {
            $cur = $cur[$p];
        } else {
            return null;
        }
    }
    return is_string($cur) ? $cur : null;
}
