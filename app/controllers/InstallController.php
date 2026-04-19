<?php

class InstallController extends Controller
{
    private const LOCK_FILE = APP_PATH . '/config/.installed';

    /* ── Step 1: Welcome ─────────────────────────────────────────────────── */
    public function index(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        $checks = $this->systemChecks();
        $this->render('install/index', [
            'pageTitle'  => __('install.s1_title'),
            'checks'     => $checks,
            'canProceed' => !in_array(false, array_column($checks, 'ok'), true),
            'step'       => 1,
        ], 'install');
    }

    /* ── Step 2: Database config ─────────────────────────────────────────── */
    public function database(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        $this->render('install/database', [
            'pageTitle' => __('install.s2_title'),
            'step'      => 2,
        ], 'install');
    }

    /* ── AJAX: Test connection ────────────────────────────────────────────── */
    public function testDb(Request $request): void
    {
        $host = trim((string)($_POST['db_host'] ?? 'localhost'));
        $port = (int)($_POST['db_port']          ?? 3306);
        $name = trim((string)($_POST['db_name'] ?? ''));
        $user = trim((string)($_POST['db_user'] ?? ''));
        $pass = (string)($_POST['db_pass']        ?? '');

        try {
            // Connect WITHOUT dbname first to test credentials
            $pdo = new PDO(
                "mysql:host={$host};port={$port};charset=utf8mb4",
                $user, $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
            );
            // Try to create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->json(['ok' => true, 'message' => __('install.conn_ok', ['name' => $name])]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'message' => __('install.conn_fail', ['msg' => htmlspecialchars($e->getMessage())])]);
        }
    }

    /* ── Step 3: Account (GET — show form) ───────────────────────────────── */
    public function account(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        if (empty($_SESSION['_install']['db_host'])) { $this->redirect(url('/install/database')); }
        $this->render('install/account', [
            'pageTitle' => __('install.s3_title'),
            'step'      => 3,
            'errors'    => [],
        ], 'install');
    }

    /* ── Step 3: Account (POST from DB form — save DB config then show form) */
    public function saveDb(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }
        $_SESSION['_install'] = [
            'db_host'   => trim((string)($_POST['db_host']   ?? 'localhost')),
            'db_port'   => (int)($_POST['db_port']            ?? 3306),
            'db_name'   => trim((string)($_POST['db_name']   ?? 'purephp')),
            'db_user'   => trim((string)($_POST['db_user']   ?? 'root')),
            'db_pass'   => (string)($_POST['db_pass']         ?? ''),
            'site_name' => trim((string)($_POST['site_name'] ?? 'Pure PHP')),
        ];
        $this->render('install/account', [
            'pageTitle' => __('install.s3_title'),
            'step'      => 3,
            'errors'    => [],
        ], 'install');
    }

    /* ── Step 3: Run installation ────────────────────────────────────────── */
    public function run(Request $request): void
    {
        if (file_exists(self::LOCK_FILE)) { $this->redirect(url('/')); }

        $cfg         = $_SESSION['_install'] ?? [];
        $adminName   = trim((string)($_POST['admin_name']  ?? ''));
        $adminEmail  = strtolower(trim((string)($_POST['admin_email'] ?? '')));
        $adminPass   = (string)($_POST['admin_pass'] ?? '');
        $includeDemo = !empty($_POST['include_demo']); // checkbox

        // Validation
        $errors = [];
        if (empty($cfg['db_host']))                          $errors[] = __('install.val_db_missing');
        if (mb_strlen($adminName)  < 2)                      $errors[] = __('install.val_name');
        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) $errors[] = __('install.val_email');
        if (mb_strlen($adminPass)  < 8)                      $errors[] = __('install.val_pass');

        if ($errors) {
            $this->render('install/account', [
                'pageTitle' => __('install.s3_title'),
                'step'      => 3,
                'errors'    => $errors,
            ], 'install');
            return;
        }

        try {
            // ── 1: Create DB if not exists (connect WITHOUT dbname) ───────────
            $pdoInit = new PDO(
                "mysql:host={$cfg['db_host']};port={$cfg['db_port']};charset=utf8mb4",
                $cfg['db_user'], $cfg['db_pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdoInit->exec(
                "CREATE DATABASE IF NOT EXISTS `{$cfg['db_name']}` " .
                "CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
            unset($pdoInit);

            // ── 2: Connect WITH dbname ────────────────────────────────────────
            $pdo = new PDO(
                "mysql:host={$cfg['db_host']};port={$cfg['db_port']};dbname={$cfg['db_name']};charset=utf8mb4",
                $cfg['db_user'], $cfg['db_pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // ── 3: Run SQL schema (+ optionally demo data) ────────────────────
            $sqlFile = ROOT_PATH . '/database.sql';
            if (!file_exists($sqlFile)) {
                throw new \RuntimeException('database.sql not found in project root.');
            }
            $this->runSqlFile($pdo, $sqlFile, $includeDemo);

            // ── 4: Create admin user ──────────────────────────────────────────
            $hash = password_hash($adminPass, PASSWORD_BCRYPT);
            $existing = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $existing->execute([$adminEmail]);
            if ($existing->fetch()) {
                $pdo->prepare('UPDATE users SET name=?,password_hash=?,role_id=1,status="active" WHERE email=?')
                    ->execute([$adminName, $hash, $adminEmail]);
            } else {
                $pdo->prepare(
                    'INSERT INTO users (name,email,password_hash,role_id,status) VALUES (?,?,?,1,"active")'
                )->execute([$adminName, $adminEmail, $hash]);
            }

            // ── 5: Write config file ──────────────────────────────────────────
            $this->writeDatabaseConfig($cfg);

            // ── 6: Write lock file with metadata ─────────────────────────────
            $meta = json_encode([
                'installed_at' => date('Y-m-d H:i:s'),
                'demo_data'    => $includeDemo,
                'admin_email'  => $adminEmail,
            ]);
            file_put_contents(self::LOCK_FILE, $meta . "\n");

            // ── 7: Store flag for complete view ───────────────────────────────
            $_SESSION['_install_demo'] = $includeDemo;
            unset($_SESSION['_install']);

            $this->redirect(url('/install/complete'));

        } catch (\Throwable $e) {
            $this->render('install/account', [
                'pageTitle' => __('install.s3_title'),
                'step'      => 3,
                'errors'    => [__('install.fail', ['msg' => $e->getMessage()])],
            ], 'install');
        }
    }

    /* ── Step 4: Complete ────────────────────────────────────────────────── */
    public function complete(Request $request): void
    {
        if (!file_exists(self::LOCK_FILE)) { $this->redirect(url('/install')); }
        $demoInstalled = $_SESSION['_install_demo'] ?? true;
        unset($_SESSION['_install_demo']);
        $this->render('install/complete', [
            'pageTitle'     => __('install.s4_title'),
            'step'          => 4,
            'demoInstalled' => $demoInstalled,
        ], 'install');
    }

    /* ── System checks ───────────────────────────────────────────────────── */
    private function systemChecks(): array
    {
        return [
            ['label' => __('install.chk_php'),       'ok' => version_compare(PHP_VERSION, '8.1.0', '>='), 'value' => PHP_VERSION],
            ['label' => __('install.chk_pdo'),        'ok' => extension_loaded('pdo'),        'value' => extension_loaded('pdo')        ? __('install.chk_enabled')   : __('install.chk_missing')],
            ['label' => __('install.chk_pdo_mysql'),  'ok' => extension_loaded('pdo_mysql'),  'value' => extension_loaded('pdo_mysql')  ? __('install.chk_enabled')   : __('install.chk_missing')],
            ['label' => __('install.chk_mbstring'),   'ok' => extension_loaded('mbstring'),   'value' => extension_loaded('mbstring')   ? __('install.chk_enabled')   : __('install.chk_missing')],
            ['label' => __('install.chk_writable'),   'ok' => is_writable(APP_PATH.'/config'), 'value' => is_writable(APP_PATH.'/config') ? __('install.chk_writable_y') : __('install.chk_writable_n')],
            ['label' => __('install.chk_sql'),        'ok' => file_exists(ROOT_PATH.'/database.sql'), 'value' => file_exists(ROOT_PATH.'/database.sql') ? __('install.chk_found') : __('install.chk_not_found')],
        ];
    }

    /* ── SQL file runner ─────────────────────────────────────────────────── */
    /**
     * Robust SQL statement splitter and executor.
     *
     * Fixes vs simple explode(';'):
     * - Uses a state machine to ignore semicolons inside string literals
     * - Skips CREATE DATABASE / USE statements (handled above via DSN)
     * - Uses IF NOT EXISTS so re-running is safe
     * - Optionally skips INSERT INTO `users` for a clean install
     */
    private function runSqlFile(PDO $pdo, string $file, bool $includeData = true): void
    {
        $sql = (string)file_get_contents($file);

        // Remove block comments /* ... */
        $sql = (string)preg_replace('/\/\*.*?\*\//s', '', $sql);

        $statements = $this->splitSql($sql);

        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt === '') continue;

            // Skip CREATE DATABASE and USE — we handled that already
            if (preg_match('/^(CREATE\s+DATABASE|USE\s+)/i', $stmt)) continue;

            // Skip user demo data if user wants a clean install
            if (!$includeData && preg_match('/^INSERT\s+INTO\s+`?users`?/i', $stmt)) continue;

            try {
                $pdo->exec($stmt);
            } catch (\PDOException $e) {
                // '42S01' = Table already exists — safe to ignore
                // '42000' with code 1007 = DB already exists — safe to ignore
                $ignoreCodes = ['42S01'];
                $msg = $e->getMessage();
                if (!in_array($e->getCode(), $ignoreCodes, true)
                    && !str_contains($msg, 'already exists')
                    && !str_contains($msg, 'Duplicate entry')
                ) {
                    throw $e;
                }
            }
        }
    }

    /**
     * State-machine SQL splitter.
     * Correctly handles semicolons inside quoted strings and backtick identifiers.
     */
    private function splitSql(string $sql): array
    {
        $statements = [];
        $current    = '';
        $inString   = false;
        $quote      = '';
        $len        = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $c = $sql[$i];

            // Escape sequences inside strings
            if ($inString && $c === '\\') {
                $current .= $c . ($sql[++$i] ?? '');
                continue;
            }

            // Toggle string / backtick mode
            if (!$inString && ($c === "'" || $c === '"' || $c === '`')) {
                $inString = true;
                $quote    = $c;
                $current .= $c;
                continue;
            }
            if ($inString && $c === $quote) {
                $inString = false;
                $current .= $c;
                continue;
            }

            // Line comment (-- ...) — skip to end of line
            if (!$inString && $c === '-' && isset($sql[$i + 1]) && $sql[$i + 1] === '-') {
                while ($i < $len && $sql[$i] !== "\n") {
                    $i++;
                }
                continue;
            }

            // Statement delimiter
            if (!$inString && $c === ';') {
                $stmt = trim($current);
                if ($stmt !== '') {
                    $statements[] = $stmt;
                }
                $current = '';
                continue;
            }

            $current .= $c;
        }

        // Trailing statement without semicolon
        $last = trim($current);
        if ($last !== '') {
            $statements[] = $last;
        }

        return $statements;
    }

    /* ── Write database.php config ───────────────────────────────────────── */
    private function writeDatabaseConfig(array $cfg): void
    {
        $host = str_replace("'", "\\'", $cfg['db_host']  ?? 'localhost');
        $name = str_replace("'", "\\'", $cfg['db_name']  ?? 'purephp');
        $user = str_replace("'", "\\'", $cfg['db_user']  ?? 'root');
        $pass = str_replace("'", "\\'", $cfg['db_pass']  ?? '');
        $port = (int)($cfg['db_port'] ?? 3306);
        $now  = date('Y-m-d H:i:s');

        $content = <<<PHP
<?php
// Auto-generated by Pure PHP Installer on {$now}
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
}
