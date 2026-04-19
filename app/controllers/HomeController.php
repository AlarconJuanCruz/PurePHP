<?php

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        requireAuth();

        // ── Each section has its own try/catch so one failure doesn't kill all
        $dbConnected  = false;
        $totalUsers   = 0; $activeUsers = 0; $pendingUsers = 0; $totalRoles = 4;
        $totalNotas   = 0; $publishedNotas = 0; $draftNotas = 0;
        $byRole       = []; $roleLabels = []; $roleCounts = []; $roleColors = [];
        $monthlyLabels = []; $monthlyCounts = [];
        $days7Labels   = []; $days7Counts = [];
        $recentNotas   = [];
        $recentUsers   = [];

        // ── 1: Basic counts ───────────────────────────────────────────────
        try {
            $totalUsers    = (int)DB::scalar('SELECT COUNT(*) FROM users');
            $activeUsers   = (int)DB::scalar("SELECT COUNT(*) FROM users WHERE status='active'");
            $pendingUsers  = (int)DB::scalar("SELECT COUNT(*) FROM users WHERE status='pending'");
            $totalRoles    = (int)DB::scalar('SELECT COUNT(*) FROM roles');
            $dbConnected   = true;
        } catch (\Throwable) {}

        // ── 2: Notas counts ───────────────────────────────────────────────
        try {
            $totalNotas     = (int)DB::scalar('SELECT COUNT(*) FROM notas');
            $publishedNotas = (int)DB::scalar("SELECT COUNT(*) FROM notas WHERE estado='publicado'");
            $draftNotas     = (int)DB::scalar("SELECT COUNT(*) FROM notas WHERE estado='borrador'");
        } catch (\Throwable) {}

        // ── 3: Monthly user registrations — safe query, no alias GROUP BY ──
        try {
            $localMonths12 = Lang::arr('dashboard.months_12')
                             ?: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

            // Simple query: group by year+month numeric (no alias in GROUP BY)
            $monthlyRaw = DB::fetchAll("
                SELECT
                    YEAR(created_at)  AS yr,
                    MONTH(created_at) AS mo,
                    COUNT(*)          AS cnt
                FROM users
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY YEAR(created_at), MONTH(created_at)
                ORDER BY yr ASC, mo ASC
            ");

            $monthMap = [];
            foreach ($monthlyRaw as $row) {
                $key = sprintf('%04d-%02d', $row['yr'], $row['mo']);
                $monthMap[$key] = (int)$row['cnt'];
            }

            $monthlyLabels = [];
            $monthlyCounts = [];
            for ($i = 11; $i >= 0; $i--) {
                $ts            = strtotime("-{$i} months");
                $key           = date('Y-m', $ts);
                $moIdx         = (int)date('n', $ts) - 1; // 0-based
                $monthlyLabels[] = $localMonths12[$moIdx] ?? date('M', $ts);
                $monthlyCounts[] = $monthMap[$key] ?? 0;
            }
        } catch (\Throwable) {
            $localMonths12  = Lang::arr('dashboard.months_12')
                              ?: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $monthlyLabels  = $localMonths12;
            $monthlyCounts  = array_fill(0, 12, 0);
        }

        // ── 4: Users by role ──────────────────────────────────────────────
        try {
            $byRole = DB::fetchAll("
                SELECT r.name, r.color, COUNT(u.id) AS cnt
                FROM roles r
                LEFT JOIN users u ON u.role_id = r.id
                GROUP BY r.id, r.name, r.color
                ORDER BY cnt DESC
            ");
            $colorMap = ['primary'=>'#7c3aed','info'=>'#06b6d4','success'=>'#10b981',
                         'secondary'=>'#475569','danger'=>'#ef4444','warning'=>'#f59e0b'];
            $roleLabels = array_column($byRole, 'name');
            $roleCounts = array_map(fn($r) => (int)$r['cnt'], $byRole);
            $roleColors = array_map(fn($r) => $colorMap[$r['color']] ?? '#7c3aed', $byRole);
        } catch (\Throwable) {}

        // ── 5: Last 7 days user registrations ─────────────────────────────
        try {
            $localDays = Lang::arr('dashboard.days_short')
                         ?: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

            $days7Raw = DB::fetchAll("
                SELECT
                    DATE(created_at)  AS day,
                    COUNT(*)          AS cnt
                FROM users
                WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY DATE(created_at)
                ORDER BY day ASC
            ");
            $days7Map = [];
            foreach ($days7Raw as $row) {
                $days7Map[$row['day']] = (int)$row['cnt'];
            }
            $days7Labels = [];
            $days7Counts = [];
            for ($i = 6; $i >= 0; $i--) {
                $date   = date('Y-m-d', strtotime("-{$i} days"));
                $dow    = (int)date('w', strtotime($date)); // 0=Sun
                $moIdx  = ($dow + 6) % 7;                  // Mon=0
                $days7Labels[] = $localDays[$moIdx] ?? date('D', strtotime($date));
                $days7Counts[] = $days7Map[$date] ?? 0;
            }
        } catch (\Throwable) {
            $localDays   = Lang::arr('dashboard.days_short') ?: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
            $days7Labels = $localDays;
            $days7Counts = array_fill(0, 7, 0);
        }

        // ── 6: Recent notas ───────────────────────────────────────────────
        try {
            $recentNotas = DB::fetchAll("
                SELECT n.id, n.titulo, n.estado, n.destacada, n.created_at,
                       n.imagen_portada, c.nombre AS cat_nombre, c.color AS cat_color,
                       u.name AS autor_nombre
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                JOIN users u ON u.id = n.user_id
                ORDER BY n.created_at DESC
                LIMIT 6
            ");
        } catch (\Throwable) {}

        // ── 7: Recent user signups ────────────────────────────────────────
        try {
            $recentUsers = DB::fetchAll("
                SELECT u.name, u.email, u.created_at, r.name AS role_name
                FROM users u
                JOIN roles r ON r.id = u.role_id
                ORDER BY u.created_at DESC
                LIMIT 5
            ");
        } catch (\Throwable) {}

        $this->render('home/index', [
            'pageTitle'       => __('dashboard.title'),
            'dbConnected'     => $dbConnected,
            // Stat cards
            'stats' => [
                ['label' => __('dashboard.total_users'),  'value' => number_format($totalUsers),   'icon' => 'people-fill',    'color' => 'primary', 'link' => url('/users')],
                ['label' => __('dashboard.total_notas'),  'value' => number_format($totalNotas),   'icon' => 'newspaper',      'color' => 'info',    'link' => url('/notas')],
                ['label' => __('dashboard.published'),    'value' => number_format($publishedNotas),'icon' => 'check-circle',  'color' => 'success', 'link' => url('/notas')],
                ['label' => __('dashboard.drafts'),       'value' => $draftNotas,                  'icon' => 'pencil-square',  'color' => 'warning', 'link' => url('/notas')],
            ],
            // Charts
            'byRole'        => $byRole,
            'monthlyLabels' => $monthlyLabels,
            'monthlyCounts' => $monthlyCounts,
            'roleLabels'    => $roleLabels,
            'roleCounts'    => $roleCounts,
            'roleColors'    => $roleColors,
            'days7Labels'   => $days7Labels,
            'days7Counts'   => $days7Counts,
            // Tables
            'recentNotas'   => $recentNotas,
            'recentUsers'   => $recentUsers,
        ]);
    }

    public function stats(Request $request): void
    {
        try {
            $data = [
                'users'     => (int)DB::scalar('SELECT COUNT(*) FROM users'),
                'notas'     => (int)DB::scalar('SELECT COUNT(*) FROM notas'),
                'published' => (int)DB::scalar("SELECT COUNT(*) FROM notas WHERE estado='publicado'"),
            ];
        } catch (\Throwable $e) {
            $data = ['error' => 'DB error: ' . $e->getMessage()];
        }
        $this->json(['success' => true, 'data' => $data, 'timestamp' => date('c')]);
    }
}
