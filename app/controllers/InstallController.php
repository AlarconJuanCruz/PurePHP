<?php

/**
 * InstallController
 *
 * Multi-step installer wizard.
 * Writes app/config/database.php and app/config/.installed on completion.
 */
class InstallController extends Controller
{
    private const LOCK_FILE = APP_PATH . '/config/.installed';
    private const STEPS     = ['welcome', 'database', 'account', 'complete'];

    /* ── Step 1: Welcome + system check ─────────────────────────────────── */
    public function index(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) {
            $this->redirect(url('/'));
        }

        $checks = $this->systemChecks();
        $this->render('install/index', [
            'pageTitle' => 'Install — Welcome',
            'checks'    => $checks,
            'canProceed'=> !in_array(false, array_column($checks, 'ok'), true),
            'step'      => 1,
        ], 'install');
    }

    /* ── Step 2: Database config ─────────────────────────────────────────── */
    public function database(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }

        $this->render('install/database', [
            'pageTitle' => 'Install — Database',
            'step'      => 2,
        ], 'install');
    }

    /* ── AJAX: Test DB connection ────────────────────────────────────────── */
    public function testDb(Request $request): void
    {
        $host   = trim((string)($_POST['db_host']     ?? 'localhost'));
        $port   = (int)($_POST['db_port']              ?? 3306);
        $name   = trim((string)($_POST['db_name']     ?? ''));
        $user   = trim((string)($_POST['db_user']     ?? ''));
        $pass   = (string)($_POST['db_pass']           ?? '');

        try {
            $pdo = new PDO(
                "mysql:host={$host};port={$port};charset=utf8mb4",
                $user, $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
            );
            // Try to create DB if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$name}`");
            $this->json(['ok' => true, 'message' => "Connected to MySQL and database <strong>{$name}</strong> is ready."]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'message' => 'Connection failed: ' . htmlspecialchars($e->getMessage())]);
        }
    }

    /* ── Step 3: Admin account (GET — session must have DB config) ──────── */
    public function account(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        if (empty($_SESSION['_install']['db_host'])) {
            $this->redirect(url('/install/database'));
        }
        $this->render('install/account', [
            'pageTitle' => 'Install — Admin Account',
            'step'      => 3,
            'errors'    => [],
        ], 'install');
    }

    /* ── Step 3: Save DB config from Step 2 → then show account form ────── */
    public function saveDb(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        // Store the DB fields in session
        $_SESSION['_install'] = [
            'db_host'   => trim((string)($_POST['db_host']   ?? 'localhost')),
            'db_port'   => (int)($_POST['db_port']            ?? 3306),
            'db_name'   => trim((string)($_POST['db_name']   ?? 'purephp')),
            'db_user'   => trim((string)($_POST['db_user']   ?? 'root')),
            'db_pass'   => (string)($_POST['db_pass']         ?? ''),
            'site_name' => trim((string)($_POST['site_name'] ?? 'Pure PHP')),
        ];
        $this->render('install/account', [
            'pageTitle' => 'Install — Admin Account',
            'step'      => 3,
            'errors'    => [],
        ], 'install');
    }

    /* ── POST: Run installation ──────────────────────────────────────────── */
    public function run(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }

        $cfg   = $_SESSION['_install'] ?? [];
        $name  = trim((string)($_POST['admin_name']  ?? ''));
        $email = strtolower(trim((string)($_POST['admin_email'] ?? '')));
        $pass  = (string)($_POST['admin_pass'] ?? '');

        // Validation
        $errors = [];
        if (empty($cfg['db_host'])) $errors[] = 'Database configuration is missing. Please go back to step 2.';
        if (strlen($name)  < 2)     $errors[] = 'Admin name must be at least 2 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Admin email is not valid.';
        if (strlen($pass)  < 8)     $errors[] = 'Password must be at least 8 characters.';

        if ($errors) {
            $this->render('install/account', [
                'pageTitle' => 'Install — Admin Account',
                'step'      => 3,
                'errors'    => $errors,
            ], 'install');
            return;
        }

        // 1 — Write database.php
        $this->writeDatabaseConfig($cfg);

        // 2 — Run SQL schema
        try {
            $pdo = new PDO(
                "mysql:host={$cfg['db_host']};port={$cfg['db_port']};dbname={$cfg['db_name']};charset=utf8mb4",
                $cfg['db_user'], $cfg['db_pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->runSql($pdo);

            // 3 — Create admin user (update or insert)
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $existing = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $existing->execute([$email]);
            if ($existing->fetch()) {
                $pdo->prepare('UPDATE users SET name=?, password_hash=?, role_id=1, status="active" WHERE email=?')
                    ->execute([$name, $hash, $email]);
            } else {
                $pdo->prepare('INSERT INTO users (name, email, password_hash, role_id, status) VALUES (?,?,?,1,"active")')
                    ->execute([$name, $email, $hash]);
            }

            // 4 — Write lock file
            file_put_contents(self::LOCK_FILE, date('Y-m-d H:i:s') . "\n");

            // 5 — Clear install session data
            unset($_SESSION['_install']);

            $this->redirect(url('/install/complete'));

        } catch (\Throwable $e) {
            $this->render('install/account', [
                'pageTitle' => 'Install — Admin Account',
                'step'      => 3,
                'errors'    => ['Installation failed: ' . $e->getMessage()],
            ], 'install');
        }
    }

    /* ── Step 4: Complete ────────────────────────────────────────────────── */
    public function complete(Request $request): void
    {
        if (!file_exists(self::LOCK_FILE)) { $this->redirect(url('/install')); }

        $this->render('install/complete', [
            'pageTitle' => 'Installation Complete',
            'step'      => 4,
        ], 'install');
    }

    /* ── Helpers ─────────────────────────────────────────────────────────── */

    private function systemChecks(): array
    {
        return [
            ['label' => 'PHP 8.1 or higher',        'ok' => version_compare(PHP_VERSION, '8.1.0', '>='), 'value' => PHP_VERSION],
            ['label' => 'PDO extension',             'ok' => extension_loaded('pdo'),                     'value' => extension_loaded('pdo') ? 'Enabled' : 'Missing'],
            ['label' => 'PDO MySQL driver',          'ok' => extension_loaded('pdo_mysql'),               'value' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Missing'],
            ['label' => 'Mbstring extension',        'ok' => extension_loaded('mbstring'),                'value' => extension_loaded('mbstring') ? 'Enabled' : 'Missing'],
            ['label' => 'app/config/ is writable',   'ok' => is_writable(APP_PATH . '/config'),           'value' => is_writable(APP_PATH . '/config') ? 'Writable' : 'Not writable'],
            ['label' => 'database.sql exists',       'ok' => file_exists(ROOT_PATH . '/database.sql'),    'value' => file_exists(ROOT_PATH . '/database.sql') ? 'Found' : 'Not found'],
        ];
    }

    private function writeDatabaseConfig(array $cfg): void
    {
        $host = addslashes($cfg['db_host']     ?? 'localhost');
        $name = addslashes($cfg['db_name']     ?? 'purephp');
        $user = addslashes($cfg['db_user']     ?? 'root');
        $pass = addslashes($cfg['db_pass']     ?? '');
        $port = (int)($cfg['db_port']          ?? 3306);

        $content = <<<PHP
<?php
// Auto-generated by Pure PHP Installer — <?= date('Y-m-d H:i:s') ?>

return [
    'host'     => '{$host}',
    'port'     => {$port},
    'database' => '{$name}',
    'username' => '{$user}',
    'password' => '{$pass}',
    'charset'  => 'utf8mb4',
];
PHP;
        file_put_contents(APP_PATH . '/config/database.php', $content);
    }

    private function runSql(PDO $pdo): void
    {
        $sql = file_get_contents(ROOT_PATH . '/database.sql');
        if (!$sql) return;

        // Remove comments and split on semicolons
        $sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
            if ($stmt !== '') {
                try { $pdo->exec($stmt); } catch (\Throwable) { /* skip already-exists errors */ }
            }
        }
    }
}
