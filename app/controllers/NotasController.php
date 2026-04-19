<?php

class NotasController extends Controller
{
    private const UPLOAD_DIR  = ROOT_PATH . '/public/uploads/notas/';
    private const ALLOWED_EXT = ['jpg','jpeg','png','gif','webp'];
    private const MAX_SIZE    = 5 * 1024 * 1024; // 5 MB

    /* ── List ─────────────────────────────────────────────────────────────── */
    public function index(Request $request): void
    {
        requireAuth();
        try {
            $notas = DB::fetchAll("
                SELECT n.*, c.nombre AS cat_nombre, c.color AS cat_color, u.name AS autor_nombre
                FROM notas n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                JOIN users u ON u.id = n.user_id
                ORDER BY n.created_at DESC
            ");
            $dbConnected = true;
        } catch (\Throwable) {
            $notas = []; $dbConnected = false;
        }
        $this->render('notas/index', [
            'pageTitle'   => __('notas.title'),
            'notas'       => $notas,
            'dbConnected' => $dbConnected,
        ]);
    }

    /* ── Create form ──────────────────────────────────────────────────────── */
    public function create(Request $request): void
    {
        requireAuth();
        $categorias = $this->getCategorias();
        $this->render('notas/form', [
            'pageTitle'  => __('notas.new'),
            'nota'       => null,
            'categorias' => $categorias,
            'errors'     => [],
            'isEdit'     => false,
        ]);
    }

    /* ── Store ────────────────────────────────────────────────────────────── */
    public function store(Request $request): void
    {
        requireAuth();
        verify_csrf();

        $titulo    = trim((string)($_POST['titulo']     ?? ''));
        $subtitulo = trim((string)($_POST['subtitulo']  ?? ''));
        $cuerpo    = (string)($_POST['cuerpo']           ?? '');
        $extracto  = trim((string)($_POST['extracto']   ?? ''));
        $estado    = in_array($_POST['estado'] ?? '', ['borrador','publicado','archivado'])
                     ? $_POST['estado'] : 'borrador';
        $destacada = !empty($_POST['destacada']) ? 1 : 0;
        $catId     = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;
        $userId    = (int)(auth()['id'] ?? 1);

        $errors = [];
        if (mb_strlen($titulo) < 3) $errors[] = __('notas.val_titulo');

        if ($errors) {
            $this->render('notas/form', [
                'pageTitle'  => __('notas.new'),
                'nota'       => $_POST,
                'categorias' => $this->getCategorias(),
                'errors'     => $errors,
                'isEdit'     => false,
            ]);
            return;
        }

        $slug  = $this->makeSlug($titulo);
        $imagen = $this->handleUpload();

        try {
            $id = DB::insert('notas', [
                'titulo'         => $titulo,
                'subtitulo'      => $subtitulo,
                'slug'           => $slug,
                'cuerpo'         => $cuerpo,
                'extracto'       => $extracto,
                'imagen_portada' => $imagen,
                'estado'         => $estado,
                'destacada'      => $destacada,
                'categoria_id'   => $catId,
                'user_id'        => $userId,
                'published_at'   => $estado === 'publicado' ? date('Y-m-d H:i:s') : null,
            ]);
            flash('success', __('notas.created'));
            $this->redirect(url('/notas/' . $id . '/edit'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            $this->redirect(url('/notas'));
        }
    }

    /* ── Edit form ────────────────────────────────────────────────────────── */
    public function edit(Request $request, string $id): void
    {
        requireAuth();
        try {
            $nota = DB::fetch('SELECT * FROM notas WHERE id = ?', [(int)$id]);
            if (!$nota) { flash('error', __('notas.not_found')); $this->redirect(url('/notas')); }
            $imagenes = DB::fetchAll('SELECT * FROM nota_imagenes WHERE nota_id = ? ORDER BY orden', [(int)$id]);
        } catch (\Throwable) {
            flash('error', __('notas.not_found'));
            $this->redirect(url('/notas'));
        }
        $this->render('notas/form', [
            'pageTitle'  => __('notas.edit'),
            'nota'       => $nota,
            'imagenes'   => $imagenes ?? [],
            'categorias' => $this->getCategorias(),
            'errors'     => [],
            'isEdit'     => true,
        ]);
    }

    /* ── Update ───────────────────────────────────────────────────────────── */
    public function update(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();
        $id = (int)$id;

        $titulo    = trim((string)($_POST['titulo']    ?? ''));
        $subtitulo = trim((string)($_POST['subtitulo'] ?? ''));
        $cuerpo    = (string)($_POST['cuerpo']          ?? '');
        $extracto  = trim((string)($_POST['extracto']  ?? ''));
        $estado    = in_array($_POST['estado'] ?? '', ['borrador','publicado','archivado'])
                     ? $_POST['estado'] : 'borrador';
        $destacada = !empty($_POST['destacada']) ? 1 : 0;
        $catId     = !empty($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : null;

        $errors = [];
        if (mb_strlen($titulo) < 3) $errors[] = __('notas.val_titulo');

        if ($errors) {
            $this->render('notas/form', [
                'pageTitle'  => __('notas.edit'),
                'nota'       => array_merge(['id' => $id], $_POST),
                'imagenes'   => DB::fetchAll('SELECT * FROM nota_imagenes WHERE nota_id=? ORDER BY orden', [$id]),
                'categorias' => $this->getCategorias(),
                'errors'     => $errors,
                'isEdit'     => true,
            ]);
            return;
        }

        try {
            $existing = DB::fetch('SELECT estado, imagen_portada, published_at FROM notas WHERE id = ?', [$id]);
            $imagen   = $this->handleUpload($existing['imagen_portada'] ?? null);

            $data = [
                'titulo'         => $titulo,
                'subtitulo'      => $subtitulo,
                'cuerpo'         => $cuerpo,
                'extracto'       => $extracto,
                'imagen_portada' => $imagen,
                'estado'         => $estado,
                'destacada'      => $destacada,
                'categoria_id'   => $catId,
            ];

            // Set published_at only when first publishing
            if ($estado === 'publicado' && $existing['estado'] !== 'publicado' && !$existing['published_at']) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            DB::update('notas', $data, 'id = ?', [$id]);
            flash('success', __('notas.updated'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect(url('/notas/' . $id . '/edit'));
    }

    /* ── Delete ───────────────────────────────────────────────────────────── */
    public function destroy(Request $request, string $id): void
    {
        requireAuth();
        verify_csrf();
        $id = (int)$id;
        try {
            // Delete cover image file
            $nota = DB::fetch('SELECT imagen_portada FROM notas WHERE id = ?', [$id]);
            if ($nota && $nota['imagen_portada']) {
                $file = self::UPLOAD_DIR . basename($nota['imagen_portada']);
                if (file_exists($file)) @unlink($file);
            }
            DB::delete('notas', 'id = ?', [$id]);
            flash('success', __('notas.deleted'));
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        $this->redirect(url('/notas'));
    }

    /* ── AJAX: Upload gallery image ───────────────────────────────────────── */
    public function uploadImage(Request $request, string $id): void
    {
        requireAuth();
        $id     = (int)$id;
        $result = $this->handleUpload(null, 'gallery_image');

        if (!$result) {
            $this->json(['ok' => false, 'message' => __('notas.upload_fail')]);
            return;
        }

        try {
            DB::insert('nota_imagenes', [
                'nota_id' => $id,
                'archivo' => $result,
                'titulo'  => trim((string)($_POST['titulo'] ?? '')),
                'orden'   => (int)DB::scalar('SELECT COUNT(*)+1 FROM nota_imagenes WHERE nota_id=?', [$id]),
            ]);
            $this->json(['ok' => true, 'url' => url('public/uploads/notas/' . basename($result))]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    /* ── AJAX: Delete gallery image ───────────────────────────────────────── */
    public function deleteImage(Request $request, string $imgId): void
    {
        requireAuth();
        verify_csrf();
        try {
            $img = DB::fetch('SELECT * FROM nota_imagenes WHERE id = ?', [(int)$imgId]);
            if ($img) {
                $file = self::UPLOAD_DIR . basename($img['archivo']);
                if (file_exists($file)) @unlink($file);
                DB::delete('nota_imagenes', 'id = ?', [(int)$imgId]);
            }
            $this->json(['ok' => true]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    /* ── Helpers ──────────────────────────────────────────────────────────── */

    private function getCategorias(): array
    {
        try {
            return DB::fetchAll('SELECT id, nombre, color FROM categorias ORDER BY orden, nombre');
        } catch (\Throwable) {
            return [];
        }
    }

    private function makeSlug(string $title): string
    {
        $slug = mb_strtolower($title);
        $slug = str_replace(['á','é','í','ó','ú','ü','ñ','ä','ö'], ['a','e','i','o','u','u','n','a','o'], $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $slug = substr($slug, 0, 200);

        // Ensure uniqueness
        $base  = $slug;
        $i     = 1;
        $notaId = (int)($_POST['_nota_id'] ?? 0);
        while (true) {
            $existing = DB::fetch(
                'SELECT id FROM notas WHERE slug = ?' . ($notaId ? ' AND id != ?' : ''),
                $notaId ? [$slug, $notaId] : [$slug]
            );
            if (!$existing) break;
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    /**
     * Handle a file upload.
     * @param string|null $existing  Current file path (to keep if no new upload)
     * @param string      $field     Input name (default: 'imagen_portada')
     */
    private function handleUpload(?string $existing = null, string $field = 'imagen_portada'): ?string
    {
        if (empty($_FILES[$field]['name'])) {
            return $existing; // No new upload, keep existing
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) return $existing;
        if ($file['size'] > self::MAX_SIZE) return $existing;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXT, true)) return $existing;

        // Validate MIME (basic)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($mime, $allowedMimes, true)) return $existing;

        // Create upload directory if needed
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0775, true);
        }

        $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest     = self::UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) return $existing;

        // Delete old file if replacing
        if ($existing) {
            $old = self::UPLOAD_DIR . basename($existing);
            if (file_exists($old)) @unlink($old);
        }

        return 'public/uploads/notas/' . $filename;
    }
}
