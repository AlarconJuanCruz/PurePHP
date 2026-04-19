<?php

class PublicController extends Controller
{
    private function getSiteSettings(): array
    {
        try {
            $rows = DB::fetchAll('SELECT `key`, `value` FROM settings');
            $s = [];
            foreach ($rows as $r) { $s[$r['key']] = $r['value']; }
            return $s;
        } catch (\Throwable) {
            return [
                'site_name'    => 'Pure PHP News',
                'site_tagline' => 'Noticias',
                'notas_per_page' => 12,
            ];
        }
    }

    public function home(Request $request): void
    {
        $settings = $this->getSiteSettings();
        $page     = max(1, (int)($request->query('page', 1)));
        $perPage  = (int)($settings['notas_per_page'] ?? 12);
        $offset   = ($page - 1) * $perPage;

        try {
            $destacadas = DB::fetchAll("
                SELECT n.*, c.nombre AS cat_nombre, c.color AS cat_color,
                       c.slug AS cat_slug, u.name AS autor_nombre
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                JOIN users u ON u.id = n.user_id
                WHERE n.estado = 'publicado' AND n.destacada = 1
                ORDER BY n.published_at DESC
                LIMIT 3
            ");

            $total = (int)DB::scalar("SELECT COUNT(*) FROM notas WHERE estado='publicado'");
            $notas = DB::fetchAll("
                SELECT n.*, c.nombre AS cat_nombre, c.color AS cat_color,
                       c.slug AS cat_slug, u.name AS autor_nombre
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                JOIN users u ON u.id = n.user_id
                WHERE n.estado = 'publicado'
                ORDER BY n.published_at DESC
                LIMIT {$perPage} OFFSET {$offset}
            ");

            $categorias = DB::fetchAll("
                SELECT c.*, COUNT(n.id) AS cnt
                FROM categorias c
                JOIN notas n ON n.categoria_id = c.id AND n.estado = 'publicado'
                GROUP BY c.id ORDER BY cnt DESC LIMIT 8
            ");

        } catch (\Throwable) {
            $destacadas = []; $notas = []; $categorias = []; $total = 0;
        }

        $this->render('public/home', [
            'pageTitle'   => $settings['site_name'] ?? 'Noticias',
            'settings'    => $settings,
            'destacadas'  => $destacadas,
            'notas'       => $notas,
            'categorias'  => $categorias,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => (int)ceil($total / max(1, $perPage)),
        ], 'public');
    }

    public function nota(Request $request, string $slug): void
    {
        $settings = $this->getSiteSettings();
        try {
            $nota = DB::fetch("
                SELECT n.*, c.nombre AS cat_nombre, c.color AS cat_color, c.slug AS cat_slug,
                       u.name AS autor_nombre
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                JOIN users u ON u.id = n.user_id
                WHERE n.slug = ? AND n.estado = 'publicado'
            ", [$slug]);

            if (!$nota) { $this->abort(404); }

            // Increment views
            DB::query('UPDATE notas SET views = views + 1 WHERE id = ?', [$nota['id']]);

            $imagenes = DB::fetchAll('SELECT * FROM nota_imagenes WHERE nota_id = ? ORDER BY orden', [$nota['id']]);

            $relacionadas = DB::fetchAll("
                SELECT n.titulo, n.slug, n.imagen_portada, n.published_at,
                       c.nombre AS cat_nombre, c.color AS cat_color
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.estado = 'publicado' AND n.id != ? AND n.categoria_id = ?
                ORDER BY n.published_at DESC LIMIT 3
            ", [$nota['id'], $nota['categoria_id']]);

        } catch (\Throwable) {
            $this->abort(404);
        }

        $this->render('public/nota', [
            'pageTitle'   => $nota['titulo'],
            'settings'    => $settings,
            'nota'        => $nota,
            'imagenes'    => $imagenes ?? [],
            'relacionadas'=> $relacionadas ?? [],
        ], 'public');
    }

    public function categoria(Request $request, string $slug): void
    {
        $settings = $this->getSiteSettings();
        $page    = max(1, (int)($request->query('page', 1)));
        $perPage = 12;
        $offset  = ($page - 1) * $perPage;

        try {
            $cat = DB::fetch('SELECT * FROM categorias WHERE slug = ?', [$slug]);
            if (!$cat) { $this->abort(404); }

            $total = (int)DB::scalar(
                "SELECT COUNT(*) FROM notas WHERE estado='publicado' AND categoria_id=?",
                [$cat['id']]
            );
            $notas = DB::fetchAll("
                SELECT n.*, u.name AS autor_nombre
                FROM notas n
                JOIN users u ON u.id = n.user_id
                WHERE n.estado = 'publicado' AND n.categoria_id = ?
                ORDER BY n.published_at DESC
                LIMIT {$perPage} OFFSET {$offset}
            ", [$cat['id']]);

            $categorias = DB::fetchAll("
                SELECT c.*, COUNT(n.id) AS cnt
                FROM categorias c
                JOIN notas n ON n.categoria_id = c.id AND n.estado = 'publicado'
                GROUP BY c.id ORDER BY cnt DESC LIMIT 8
            ");

        } catch (\Throwable) {
            $this->abort(404);
        }

        $this->render('public/categoria', [
            'pageTitle'   => $cat['nombre'],
            'settings'    => $settings,
            'cat'         => $cat,
            'notas'       => $notas ?? [],
            'categorias'  => $categorias ?? [],
            'total'       => $total ?? 0,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => (int)ceil(($total ?? 0) / $perPage),
        ], 'public');
    }
}
