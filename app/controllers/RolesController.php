<?php

class RolesController extends Controller
{
    public function index(Request $request): void
    {
        requireAuth();
        try {
            $roles = DB::fetchAll('
                SELECT r.*,
                       COUNT(DISTINCT u.id) AS user_count
                FROM roles r
                LEFT JOIN users u ON u.role_id = r.id
                GROUP BY r.id
                ORDER BY r.id
            ');
            $permissions = DB::fetchAll('SELECT * FROM permissions ORDER BY group_name, name');
            $rolePerms   = DB::fetchAll('SELECT role_id, permission_id FROM role_permissions');

            // Build a lookup: $matrix[role_id][permission_id] = true
            $matrix = [];
            foreach ($rolePerms as $rp) {
                $matrix[$rp['role_id']][$rp['permission_id']] = true;
            }
            $dbConnected = true;
        } catch (\Throwable) {
            $roles = []; $permissions = []; $matrix = []; $dbConnected = false;
        }

        $this->render('roles/index', [
            'pageTitle'   => 'Roles & Permissions',
            'roles'       => $roles,
            'permissions' => $permissions,
            'matrix'      => $matrix,
            'dbConnected' => $dbConnected,
        ]);
    }

    public function store(Request $request): void
    {
        requireAuth();
        verify_csrf();

        $v = $request->validate([
            'name'        => 'required|min:2|max:60',
            'description' => 'max:255',
        ]);

        if (!empty($v['errors'])) {
            flash('error', implode(' ', array_merge(...array_values($v['errors']))));
            $this->redirect(url('/roles'));
        }

        try {
            $slug    = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $v['data']['name']));
            $color   = in_array($_POST['color'] ?? '', ['primary','secondary','success','danger','warning','info']) ? $_POST['color'] : 'secondary';
            $roleId  = DB::insert('roles', [
                'name'        => $v['data']['name'],
                'slug'        => $slug,
                'description' => $v['data']['description'] ?? '',
                'color'       => $color,
            ]);
            $this->syncPermissions($roleId, $_POST['permissions'] ?? []);
            flash('success', 'Role created.');
        } catch (\Throwable $e) {
            flash('error', 'Failed: ' . $e->getMessage());
        }
        $this->redirect(url('/roles'));
    }

    public function update(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();

        $id = (int) $id;
        $v  = $request->validate([
            'name'        => 'required|min:2|max:60',
            'description' => 'max:255',
        ]);

        if (!empty($v['errors'])) {
            flash('error', implode(' ', array_merge(...array_values($v['errors']))));
            $this->redirect(url('/roles'));
        }

        try {
            $color = in_array($_POST['color'] ?? '', ['primary','secondary','success','danger','warning','info']) ? $_POST['color'] : 'secondary';
            DB::update('roles', [
                'name'        => $v['data']['name'],
                'description' => $v['data']['description'] ?? '',
                'color'       => $color,
            ], 'id = ?', [$id]);
            $this->syncPermissions($id, $_POST['permissions'] ?? []);
            flash('success', 'Role updated.');
        } catch (\Throwable $e) {
            flash('error', 'Update failed: ' . $e->getMessage());
        }
        $this->redirect(url('/roles'));
    }

    public function destroy(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();

        $id = (int) $id;
        try {
            $count = (int) DB::scalar('SELECT COUNT(*) FROM users WHERE role_id = ?', [$id]);
            if ($count > 0) {
                flash('error', "Cannot delete: {$count} user(s) still assigned to this role.");
                $this->redirect(url('/roles'));
            }
            DB::delete('roles', 'id = ?', [$id]);
            flash('success', 'Role deleted.');
        } catch (\Throwable $e) {
            flash('error', 'Delete failed: ' . $e->getMessage());
        }
        $this->redirect(url('/roles'));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function syncPermissions(int $roleId, array $permissionIds): void
    {
        DB::query('DELETE FROM role_permissions WHERE role_id = ?', [$roleId]);
        foreach ($permissionIds as $permId) {
            $permId = (int) $permId;
            if ($permId > 0) {
                DB::query(
                    'INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?,?)',
                    [$roleId, $permId]
                );
            }
        }
    }
}
