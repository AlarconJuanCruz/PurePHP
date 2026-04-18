<?php

/**
 * Pure PHP — Front Controller
 */

define('ROOT_PATH', __DIR__);
define('APP_PATH',  ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');

// ── BASE_URL (Windows / Linux / Laragon / vhost safe) ─────────────────────
(function () {
    $scheme = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
        ? 'https' : 'http';

    $host = rtrim((string)($_SERVER['HTTP_HOST'] ?? 'localhost'), '/');

    // On Windows, SCRIPT_NAME may use backslashes; dirname may return '\' or '.'
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir    = str_replace('\\', '/', dirname($script));

    // Normalise: '/' , '\' and '.' all mean "application is at the domain root"
    if ($dir === '/' || $dir === '\\' || $dir === '.' || $dir === '') {
        $dir = '';
    } else {
        $dir = '/' . trim($dir, '/');
    }

    define('BASE_URL', $scheme . '://' . $host . $dir);
})();

// ── Autoloader ────────────────────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    foreach ([ROOT_PATH . '/core/', ROOT_PATH . '/app/controllers/'] as $base) {
        $file = $base . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

// ── Session ───────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_name('purephp_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ── Helpers ───────────────────────────────────────────────────────────────
require_once ROOT_PATH . '/core/helpers.php';

// ── Installer gate ────────────────────────────────────────────────────────
// If the app has not been installed yet, redirect every request to /install
// EXCEPT requests that are already going to /install
$_installedFlag = APP_PATH . '/config/.installed';
$_rawPath       = safe_parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'));

if (!file_exists($_installedFlag) && !str_starts_with($_rawPath, '/install')) {
    header('Location: ' . url('/install'));
    exit;
}

// ── Dispatch ──────────────────────────────────────────────────────────────
$router = new Router();
require_once ROOT_PATH . '/app/config/routes.php';
$router->dispatch();
