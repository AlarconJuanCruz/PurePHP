<?php

class CategoriasController extends Controller
{
    public function index(Request $request): void
    {
        requireAuth();
        try {
            $cats = DB::fetchAll("
                SELECT c.*, COUNT(n.id) AS nota_count
                FROM categorias c
                LEFT JOIN notas n ON n.categoria_id = c.id
                GROUP BY c.id
                ORDER BY c.orden, c.nombre
            ");
            $dbConnected = true;
        } catch (\Throwable) {
            $cats = []; $dbConnected = false;
        }
        $this->render('categorias/index', [
            'pageTitle'   => __('categorias.title'),
            'cats'        => $cats,
            'dbConnected' => $dbConnected,
        ]);
    }

    public function store(Request $request): void
    {
        requireAuth();
        verify_csrf();
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        if (mb_strlen($nombre) < 2) { flash('error', __('categorias.val_nombre')); $this->redirect(url('/categorias')); }
        $slug  = $this->makeSlug($nombre);
        $color = in_array($_POST['color'] ?? '', ['primary','secondary','success','danger','warning','info']) ? $_POST['color'] : 'primary';
        try {
            DB::insert('categorias', [
                'nombre'      => $nombre,
                'slug'        => $slug,
                'descripcion' => trim((string)($_POST['descripcion'] ?? '')),
                'color'       => $color,
                'orden'       => (int)($_POST['orden'] ?? 0),
            ]);
            flash('success', __('categorias.created'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect(url('/categorias'));
    }

    public function update(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();
        $id    = (int)$id;
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        if (mb_strlen($nombre) < 2) { flash('error', __('categorias.val_nombre')); $this->redirect(url('/categorias')); }
        $color = in_array($_POST['color'] ?? '', ['primary','secondary','success','danger','warning','info']) ? $_POST['color'] : 'primary';
        try {
            DB::update('categorias', [
                'nombre'      => $nombre,
                'descripcion' => trim((string)($_POST['descripcion'] ?? '')),
                'color'       => $color,
                'orden'       => (int)($_POST['orden'] ?? 0),
            ], 'id = ?', [$id]);
            flash('success', __('categorias.updated'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect(url('/categorias'));
    }

    public function destroy(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();
        $id = (int)$id;
        try {
            $count = (int)DB::scalar('SELECT COUNT(*) FROM notas WHERE categoria_id = ?', [$id]);
            if ($count > 0) {
                flash('error', __('categorias.has_notas', ['n' => $count]));
                $this->redirect(url('/categorias'));
            }
            DB::delete('categorias', 'id = ?', [$id]);
            flash('success', __('categorias.deleted'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect(url('/categorias'));
    }

    private function makeSlug(string $s): string
    {
        $s = mb_strtolower($s);
        $s = str_replace(['á','é','í','ó','ú','ü','ñ'], ['a','e','i','o','u','u','n'], $s);
        $s = preg_replace('/[^a-z0-9]+/', '-', $s);
        return trim($s, '-');
    }
}
