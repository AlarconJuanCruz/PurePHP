<?php

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (!isGuest()) { $this->redirect(url('/')); }
        $this->render('auth/login', ['pageTitle' => 'Sign In', 'error' => null], 'auth');
    }

    public function login(Request $request): void
    {
        verify_csrf();

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->renderError('Email and password are required.');
            return;
        }

        try {
            $user = DB::fetch('
                SELECT u.*, r.name AS role_name, r.slug AS role_slug
                FROM users u
                JOIN roles r ON r.id = u.role_id
                WHERE u.email = ?
                LIMIT 1
            ', [$email]);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                usleep(300_000);
                $this->renderError('Invalid email or password.');
                return;
            }

            if ($user['status'] !== 'active') {
                $this->renderError('Your account is ' . $user['status'] . '. Contact an administrator.');
                return;
            }

            $perms = DB::fetchAll('
                SELECT p.slug FROM permissions p
                JOIN role_permissions rp ON rp.permission_id = p.id
                WHERE rp.role_id = ?
            ', [$user['role_id']]);

            session_regenerate_id(true);
            $_SESSION['_auth_user'] = [
                'id'          => $user['id'],
                'name'        => $user['name'],
                'email'       => $user['email'],
                'role_id'     => $user['role_id'],
                'role_name'   => $user['role_name'],
                'role_slug'   => $user['role_slug'],
                'permissions' => array_column($perms, 'slug'),
            ];

            DB::query('UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']]);
            flash('success', 'Welcome back, ' . $user['name'] . '!');
            $this->redirect(url('/'));

        } catch (\Throwable) {
            // Fallback demo mode when DB is not yet configured
            $demo = [
                'admin@demo.com' => ['id'=>0,'name'=>'Admin User','role_name'=>'Administrator','role_slug'=>'admin','role_id'=>1,'permissions'=>[]],
                'dev@demo.com'   => ['id'=>0,'name'=>'Dev User',  'role_name'=>'Developer',    'role_slug'=>'developer','role_id'=>2,'permissions'=>[]],
            ];
            $u = $demo[$email] ?? null;
            if ($u && $password === 'password') {
                session_regenerate_id(true);
                $_SESSION['_auth_user'] = array_merge($u, ['email' => $email]);
                flash('success', 'Welcome, ' . $u['name'] . '! (demo — DB not connected)');
                $this->redirect(url('/'));
            } else {
                $this->renderError('Invalid email or password.');
            }
        }
    }

    public function logout(Request $request): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirect(url('/login'));
    }

    private function renderError(string $msg): void
    {
        $this->render('auth/login', ['pageTitle' => 'Sign In', 'error' => $msg], 'auth');
    }
}
