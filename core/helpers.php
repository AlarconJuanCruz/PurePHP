<?php

/**
 * Pure PHP — Global helpers
 */

// ── safe_parse_url ────────────────────────────────────────────────────────
if (!function_exists('safe_parse_url')) {
    function safe_parse_url(string $raw): string {
        $path = parse_url($raw, PHP_URL_PATH);
        if (!is_string($path) || $path === '') { $path = '/'; }
        $path = str_replace('\\', '/', $path);
        $path = (string) preg_replace('#/{2,}#', '/', $path);
        return '/' . trim($path, '/') ?: '/';
    }
}

// ── url() ─────────────────────────────────────────────────────────────────
if (!function_exists('url')) {
    function url(string $path = ''): string {
        $base = rtrim((string) BASE_URL, '/');
        $path = '/' . ltrim($path, '/');
        $path = (string) preg_replace('#/{2,}#', '/', $path);
        return $base . $path;
    }
}

// ── asset() ───────────────────────────────────────────────────────────────
if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('public/' . ltrim($path, '/'));
    }
}

// ── e() ───────────────────────────────────────────────────────────────────
if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

// ── __() — Translation helper ─────────────────────────────────────────────
if (!function_exists('__')) {
    /**
     * Translate a dot-notation key.
     *
     * @param string               $key     e.g. 'nav.dashboard'
     * @param array<string,scalar> $replace e.g. ['name' => 'Alice']
     */
    function __(string $key, array $replace = []): string {
        return Lang::get($key, $replace);
    }
}

// ── _a() — Translation array ──────────────────────────────────────────────
if (!function_exists('_a')) {
    /** Get a translation array (e.g. month labels). */
    function _a(string $key): array {
        return Lang::arr($key);
    }
}

// ── lang helpers ──────────────────────────────────────────────────────────
if (!function_exists('switchLang')) {
    function switchLang(string $locale): void {
        $_SESSION['_locale'] = $locale;
        Lang::boot($locale);
    }
}

if (!function_exists('currentLocale')) {
    function currentLocale(): string {
        return Lang::locale();
    }
}

if (!function_exists('localDate')) {
    /** Format a date string according to current locale. */
    function localDate(?string $date): string {
        return Lang::date((string) $date);
    }
}

// ── dd() ──────────────────────────────────────────────────────────────────
if (!function_exists('dd')) {
    function dd(mixed ...$values): never {
        header('Content-Type: text/html; charset=UTF-8');
        foreach ($values as $v) {
            echo '<pre style="background:#1a1a2e;color:#e2e8f0;padding:1rem;border-radius:6px;font-size:13px;overflow:auto">';
            var_dump($v);
            echo '</pre>';
        }
        exit;
    }
}

// ── CSRF ──────────────────────────────────────────────────────────────────
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): void {
        $token  = (string) ($_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $stored = (string) ($_SESSION['_csrf_token'] ?? '');
        if ($stored === '' || !hash_equals($stored, $token)) {
            http_response_code(419);
            exit('Invalid or expired CSRF token. Please go back and try again.');
        }
    }
}

// ── Session / flash ───────────────────────────────────────────────────────
if (!function_exists('flash')) {
    function flash(string $key, string $message): void {
        $_SESSION['_flash'][$key] = $message;
    }
}

if (!function_exists('get_flash')) {
    function get_flash(string $key): ?string {
        $msg = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }
}

if (!function_exists('session')) {
    function session(?string $key = null, mixed $default = null): mixed {
        return $key === null ? $_SESSION : ($_SESSION[$key] ?? $default);
    }
}

// ── Auth ──────────────────────────────────────────────────────────────────
if (!function_exists('auth')) {
    function auth(): ?array { return $_SESSION['_auth_user'] ?? null; }
}

if (!function_exists('isGuest')) {
    function isGuest(): bool { return auth() === null; }
}

if (!function_exists('requireAuth')) {
    function requireAuth(): void {
        if (isGuest()) { header('Location: ' . url('/login')); exit; }
    }
}

if (!function_exists('hasRole')) {
    function hasRole(string $slug): bool {
        return (auth()['role_slug'] ?? '') === $slug;
    }
}

if (!function_exists('can')) {
    function can(string $permission): bool {
        return in_array($permission, auth()['permissions'] ?? [], true);
    }
}

// ── isActive() ────────────────────────────────────────────────────────────
if (!function_exists('isActive')) {
    function isActive(string|array $paths, bool $exact = true): string {
        $current = safe_parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'));
        $current = $current === '/' ? '/' : rtrim($current, '/');
        foreach ((array) $paths as $path) {
            $path = '/' . trim((string) $path, '/');
            if ($exact ? ($current === $path) : str_starts_with($current, $path)) {
                return 'active';
            }
        }
        return '';
    }
}
