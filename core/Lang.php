<?php

/**
 * Lang — Translation engine
 *
 * Usage:
 *   Lang::boot('es_AR');          // boot from session or default
 *   __('nav.dashboard')           // returns translated string
 *   __('users.greeting', ['name' => 'Ana'])  // with :placeholder replacement
 *   Lang::locale()                // 'en' | 'es_AR'
 *   Lang::available()             // ['en' => 'English', 'es_AR' => 'Español (AR)']
 *   Lang::flag()                  // emoji flag for current locale
 */
class Lang
{
    private static array  $t      = [];   // loaded translations
    private static string $locale = 'en';

    /* ── Boot ─────────────────────────────────────────────────────────── */

    public static function boot(string $locale = 'en'): void
    {
        $available = array_keys(self::available());
        $locale    = in_array($locale, $available, true) ? $locale : 'en';

        self::$locale = $locale;
        $file = APP_PATH . '/lang/' . $locale . '.php';

        if (file_exists($file)) {
            self::$t = require $file;
        } else {
            // Fallback to English
            $en = APP_PATH . '/lang/en.php';
            self::$t = file_exists($en) ? require $en : [];
        }
    }

    /* ── Translation ──────────────────────────────────────────────────── */

    /**
     * Get a translation by dot-notation key.
     * Supports :placeholder replacements.
     *
     * @param string               $key     e.g. 'nav.dashboard'
     * @param array<string,scalar> $replace e.g. ['name' => 'Alice']
     */
    public static function get(string $key, array $replace = []): string
    {
        $parts = explode('.', $key);
        $value = self::$t;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $key;   // key not found — return key as fallback
            }
            $value = $value[$part];
        }

        if (!is_string($value)) {
            return $key;
        }

        // Replace :placeholder tokens
        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, (string) $v, $value);
        }

        return $value;
    }

    /**
     * Get an array (e.g. month/day labels for charts).
     * Returns empty array if key is not an array.
     */
    public static function arr(string $key): array
    {
        $parts = explode('.', $key);
        $value = self::$t;
        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return [];
            }
            $value = $value[$part];
        }
        return is_array($value) ? $value : [];
    }

    /* ── Locale info ──────────────────────────────────────────────────── */

    public static function locale(): string { return self::$locale; }

    public static function available(): array
    {
        return [
            'en'    => 'English',
            'es_AR' => 'Español (AR)',
        ];
    }

    public static function flag(string $locale = null): string
    {
        $flags = ['en' => '🇺🇸', 'es_AR' => '🇦🇷'];
        return $flags[$locale ?? self::$locale] ?? '🌐';
    }

    /* ── Date / number formatting ─────────────────────────────────────── */

    /**
     * Format a date string according to locale.
     * en: Y-m-d  |  es_AR: d/m/Y
     */
    public static function date(string $dateStr): string
    {
        if (!$dateStr || $dateStr === 'Never' || $dateStr === 'Nunca') {
            return self::get('common.never');
        }
        try {
            $ts     = strtotime($dateStr);
            $format = self::$locale === 'en' ? 'Y-m-d' : 'd/m/Y';
            return $ts ? date($format, $ts) : $dateStr;
        } catch (\Throwable) {
            return $dateStr;
        }
    }
}
