<?php

class SettingsController extends Controller
{
    private const ALLOWED_KEYS = [
        'site_name', 'site_tagline', 'site_description',
        'notas_per_page', 'show_author', 'show_date',
        'logo_header_path', 'logo_footer_path', 'primary_color',
    ];

    public function index(Request $request): void
    {
        requireAuth();
        try {
            $rows = DB::fetchAll('SELECT `key`, `value` FROM settings');
            $settings = [];
            foreach ($rows as $r) { $settings[$r['key']] = $r['value']; }
            $dbConnected = true;
        } catch (\Throwable) {
            $settings = []; $dbConnected = false;
        }
        $this->render('settings/index', [
            'pageTitle'   => __('settings.title'),
            'settings'    => $settings,
            'dbConnected' => $dbConnected,
        ]);
    }

    public function update(Request $request): void
    {
        requireAuth();
        verify_csrf();

        try {
            foreach (self::ALLOWED_KEYS as $key) {
                $value = trim((string)($_POST[$key] ?? ''));

                // Sanitize specific fields
                if ($key === 'primary_color') {
                    // Allow only valid hex color
                    $value = preg_match('/^#[0-9a-fA-F]{3,6}$/', $value) ? $value : '#dc2626';
                }
                if ($key === 'notas_per_page') {
                    $value = (string)max(1, min(50, (int)$value));
                }
                if (in_array($key, ['show_author','show_date'])) {
                    $value = !empty($_POST[$key]) ? '1' : '0';
                }

                // Upsert
                DB::query(
                    'INSERT INTO settings (`key`, `value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?',
                    [$key, $value, $value]
                );
            }
            flash('success', __('settings.saved'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }

        $this->redirect(url('/settings'));
    }
}
