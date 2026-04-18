<?php

class UsersController extends Controller
{
    public function index(Request $request): void
    {
        requireAuth();

        try {
            $users = DB::fetchAll('
                SELECT u.*, r.name AS role_name, r.color AS role_color, r.slug AS role_slug
                FROM users u
                JOIN roles r ON r.id = u.role_id
                ORDER BY u.created_at DESC
            ');
            $roles      = DB::fetchAll('SELECT id, name, color FROM roles ORDER BY name');
            $dbConnected = true;
        } catch (\Throwable) {
            $users = []; $roles = []; $dbConnected = false;
        }

        $this->render('users/index', [
            'pageTitle'   => 'Users',
            'users'       => $users,
            'roles'       => $roles,
            'dbConnected' => $dbConnected,
        ]);
    }

    public function store(Request $request): void
    {
        requireAuth();
        verify_csrf();

        $v = $request->validate([
            'name'     => 'required|min:2|max:100',
            'email'    => 'required|email|max:150',
            'password' => 'required|min:8',
            'role_id'  => 'required|numeric',
            'status'   => 'required',
        ]);

        if (!empty($v['errors'])) {
            flash('error', implode(' ', array_merge(...array_values($v['errors']))));
            $this->redirect(url('/users'));
        }

        try {
            $exists = DB::fetch('SELECT id FROM users WHERE email = ?', [$v['data']['email']]);
            if ($exists) { flash('error', 'Email already registered.'); $this->redirect(url('/users')); }

            DB::insert('users', [
                'name'          => $v['data']['name'],
                'email'         => $v['data']['email'],
                'password_hash' => password_hash($v['data']['password'], PASSWORD_BCRYPT),
                'role_id'       => (int) $v['data']['role_id'],
                'status'        => in_array($_POST['status'], ['active','inactive','pending']) ? $_POST['status'] : 'pending',
            ]);
            flash('success', 'User created successfully.');
        } catch (\Throwable $e) {
            flash('error', 'Failed to create user: ' . $e->getMessage());
        }
        $this->redirect(url('/users'));
    }

    public function update(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();

        $id = (int) $id;
        $v  = $request->validate([
            'name'    => 'required|min:2|max:100',
            'email'   => 'required|email|max:150',
            'role_id' => 'required|numeric',
            'status'  => 'required',
        ]);

        if (!empty($v['errors'])) {
            flash('error', implode(' ', array_merge(...array_values($v['errors']))));
            $this->redirect(url('/users'));
        }

        try {
            $data = [
                'name'    => $v['data']['name'],
                'email'   => $v['data']['email'],
                'role_id' => (int) $v['data']['role_id'],
                'status'  => in_array($_POST['status'], ['active','inactive','pending']) ? $_POST['status'] : 'active',
            ];
            if (!empty($_POST['password'])) {
                $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }
            DB::update('users', $data, 'id = ?', [$id]);
            flash('success', 'User updated.');
        } catch (\Throwable $e) {
            flash('error', 'Update failed: ' . $e->getMessage());
        }
        $this->redirect(url('/users'));
    }

    public function destroy(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();

        $id = (int) $id;
        // Prevent self-deletion
        if ($id === (int)(auth()['id'] ?? 0)) {
            flash('error', 'You cannot delete your own account.');
            $this->redirect(url('/users'));
        }
        try {
            DB::delete('users', 'id = ?', [$id]);
            flash('success', 'User deleted.');
        } catch (\Throwable $e) {
            flash('error', 'Delete failed: ' . $e->getMessage());
        }
        $this->redirect(url('/users'));
    }
}
