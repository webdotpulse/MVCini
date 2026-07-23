<?php
namespace App\Core;

class I18n
{
    private static array $translations = [];
    private static string $lang = 'en';

    /**
     * Initialize I18n with a specific language
     */
    public static function init(string $lang = 'en')
    {
        // Check session for language preference
        if (\App\Core\Session::has('lang')) {
            self::$lang = \App\Core\Session::get('lang');
        } else {
            self::$lang = $lang;
            \App\Core\Session::set('lang', self::$lang);
        }

        self::$translations = [];
    }

    /**
     * Set language
     */
    public static function setLang(string $lang)
    {
        self::$lang = $lang;
        \App\Core\Session::set('lang', $lang);
        self::$translations = [];
    }

    /**
     * Load translation domain file
     */
    private static function loadDomain(string $domain)
    {
        if (isset(self::$translations[$domain])) {
            return;
        }

        $file = __DIR__ . '/../i18n/' . self::$lang . '/' . $domain . '.php';
        if (file_exists($file)) {
            self::$translations[$domain] = require $file;
        } else {
            // Fallback to empty translations for this domain
            self::$translations[$domain] = [];
        }
    }

    /**
     * Translate a key
     */
    public static function get(string $key): string
    {
        $domain = 'global';
        $translationKey = $key;

        if (strpos($key, '.') !== false) {
            list($domain, $translationKey) = explode('.', $key, 2);
        }

        self::loadDomain($domain);

        return self::$translations[$domain][$translationKey] ?? $key;
    }
}
