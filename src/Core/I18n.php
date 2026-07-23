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
        if (isset($_SESSION['lang'])) {
            self::$lang = $_SESSION['lang'];
        } else {
            self::$lang = $lang;
            $_SESSION['lang'] = self::$lang;
        }

        self::loadTranslations(self::$lang);
    }

    /**
     * Set language
     */
    public static function setLang(string $lang)
    {
        self::$lang = $lang;
        $_SESSION['lang'] = $lang;
        self::loadTranslations($lang);
    }

    /**
     * Load translation file
     */
    private static function loadTranslations(string $lang)
    {
        $file = __DIR__ . '/../i18n/' . $lang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        } else {
            // Fallback to empty translations or default
            self::$translations = [];
        }
    }

    /**
     * Translate a key
     */
    public static function get(string $key): string
    {
        return self::$translations[$key] ?? $key;
    }
}
