<?php

/**
 * Pure PHP — Global helpers
 */

// ── safe_parse_url ─────────────────────────────────────────────────────────
// parse_url() returns null|false on malformed input (common on Windows/Laragon).
// This wrapper always returns a clean path string.
if (!function_exists('safe_parse_url')) {
    function safe_parse_url(string $raw): string {
        $path = parse_url($raw, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = '/';
        }
        // Normalise backslashes (Windows)
        $path = str_replace('\\', '/', $path);
        // Collapse accidental double-slashes
        $path = (string) preg_replace('#/{2,}#', '/', $path);
        return '/' . trim($path, '/') ?: '/';
    }
}

// ── url() ──────────────────────────────────────────────────────────────────
if (!function_exists('url')) {
    /**
     * Generate an absolute URL.
     * url('/users') → http://purephp.test/users
     * Works in vhosts AND subdirectory installs.
     */
    function url(string $path = ''): string {
        $base = rtrim((string) BASE_URL, '/');

        // Normalise path: always starts with /, no double slashes
        $path = '/' . ltrim($path, '/');
        $path = (string) preg_replace('#/{2,}#', '/', $path);

        return $base . $path;
    }
}

// ── e() ───────────────────────────────────────────────────────────────────
if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

// ── asset() ───────────────────────────────────────────────────────────────
if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('public/' . ltrim($path, '/'));
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
        $token = (string) ($_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $stored = (string) ($_SESSION['_csrf_token'] ?? '');
        if ($stored === '' || !hash_equals($stored, $token)) {
            http_response_code(419);
            exit('Invalid or expired CSRF token. Please go back and try again.');
        }
    }
}

// ── Session helpers ───────────────────────────────────────────────────────
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

// ── Auth helpers ──────────────────────────────────────────────────────────
if (!function_exists('auth')) {
    function auth(): ?array {
        return $_SESSION['_auth_user'] ?? null;
    }
}

if (!function_exists('isGuest')) {
    function isGuest(): bool { return auth() === null; }
}

if (!function_exists('requireAuth')) {
    function requireAuth(): void {
        if (isGuest()) {
            header('Location: ' . url('/login'));
            exit;
        }
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
    /**
     * Returns 'active' when current URI matches the given path(s).
     * Uses safe_parse_url so it never throws a deprecation on Windows.
     */
    function isActive(string|array $paths, bool $exact = true): string {
        $current = safe_parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'));
        // Normalise: no trailing slash except root
        $current = $current === '/' ? '/' : rtrim($current, '/');

        foreach ((array) $paths as $path) {
            $path = '/' . trim((string) $path, '/');
            $match = $exact
                ? ($current === $path)
                : str_starts_with($current, $path);
            if ($match) return 'active';
        }
        return '';
    }
}
