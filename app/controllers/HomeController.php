<?php

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        // Try DB, fall back to static demo data if not configured
        try {
            $totalUsers    = (int) DB::scalar('SELECT COUNT(*) FROM users');
            $activeUsers   = (int) DB::scalar("SELECT COUNT(*) FROM users WHERE status='active'");
            $pendingUsers  = (int) DB::scalar("SELECT COUNT(*) FROM users WHERE status='pending'");
            $totalRoles    = (int) DB::scalar('SELECT COUNT(*) FROM roles');
            $dbConnected   = true;
        } catch (\Throwable) {
            $totalUsers = 20; $activeUsers = 14; $pendingUsers = 3; $totalRoles = 4;
            $dbConnected = false;
        }

        $this->render('home/index', [
            'pageTitle'   => 'Dashboard',
            'dbConnected' => $dbConnected,
            'stats' => [
                ['label' => 'Total Users',    'value' => number_format($totalUsers),  'icon' => 'people-fill',     'trend' => '+8.2%',  'color' => 'primary'],
                ['label' => 'Active Users',   'value' => number_format($activeUsers), 'icon' => 'person-check',    'trend' => '+5.1%',  'color' => 'success'],
                ['label' => 'Pending',        'value' => $pendingUsers,               'icon' => 'hourglass-split', 'trend' => '-1',     'color' => 'warning'],
                ['label' => 'Roles',          'value' => $totalRoles,                 'icon' => 'shield-check',    'trend' => '+'.$totalRoles, 'color' => 'info'],
            ],
        ]);
    }

    public function stats(Request $request): void
    {
        try {
            $data = [
                'users'   => (int) DB::scalar('SELECT COUNT(*) FROM users'),
                'active'  => (int) DB::scalar("SELECT COUNT(*) FROM users WHERE status='active'"),
                'roles'   => (int) DB::scalar('SELECT COUNT(*) FROM roles'),
            ];
        } catch (\Throwable $e) {
            $data = ['error' => 'DB not configured: ' . $e->getMessage()];
        }
        $this->json(['success' => true, 'data' => $data, 'timestamp' => date('c')]);
    }
}
