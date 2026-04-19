<?php /* views/public/home.php */
$colorCss = [
    'primary'  => 'cat-primary',
    'success'  => 'cat-success',
    'danger'   => 'cat-danger',
    'info'     => 'cat-info',
    'warning'  => 'cat-warning',
    'secondary'=> 'cat-secondary',
];
?>

<main class="container py-4">
  <div class="row g-4">

    <!-- ── Main column ──────────────────────────────────────────── -->
    <div class="col-lg-8">

      <!-- Featured section -->
      <?php if (!empty($destacadas)): ?>
      <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="mb-0" style="font-family:'Lora',serif;font-weight:700;font-size:1rem;border-left:3px solid var(--pub-accent);padding-left:.6rem">
            <?= e(__('public.featured')) ?>
          </h5>
        </div>

        <?php if (count($destacadas) >= 1): $f = $destacadas[0]; ?>
        <div class="row g-3">
          <!-- Main featured -->
          <div class="col-md-7">
            <a href="<?= url('/portal/nota/'.(string)$f['slug']) ?>" class="featured-card">
              <?php if ($f['imagen_portada']): ?>
              <img src="<?= url($f['imagen_portada']) ?>" alt="<?= e($f['titulo']) ?>">
              <?php else: ?>
              <div style="background:linear-gradient(135deg,#1e293b,#0f172a);width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-newspaper" style="font-size:3rem;color:rgba(255,255,255,.2)"></i>
              </div>
              <?php endif; ?>
              <div class="featured-overlay d-flex flex-column justify-content-end">
                <?php if ($f['cat_nombre']): ?>
                <div class="feat-cat">
                  <span class="cat-badge <?= e($colorCss[$f['cat_color']??'secondary']??'cat-secondary') ?>"><?= e($f['cat_nombre']) ?></span>
                </div>
                <?php endif; ?>
                <div class="feat-title"><?= e($f['titulo']) ?></div>
                <div class="feat-meta">
                  <?= e(__('public.published_on')) ?> <?= e(localDate(substr((string)$f['published_at'],0,10))) ?>
                </div>
              </div>
            </a>
          </div>
          <!-- Secondary featured -->
          <div class="col-md-5">
            <?php for ($i=1; $i<count($destacadas) && $i<3; $i++): $sf=$destacadas[$i]; ?>
            <a href="<?= url('/portal/nota/'.(string)$sf['slug']) ?>" class="featured-card mb-3" style="aspect-ratio:16/7">
              <?php if ($sf['imagen_portada']): ?>
              <img src="<?= url($sf['imagen_portada']) ?>" alt="<?= e($sf['titulo']) ?>">
              <?php else: ?>
              <div style="background:linear-gradient(135deg,#374151,#1f2937);width:100%;height:100%"></div>
              <?php endif; ?>
              <div class="featured-overlay d-flex flex-column justify-content-end">
                <div class="feat-title" style="font-size:1rem"><?= e($sf['titulo']) ?></div>
                <div class="feat-meta"><?= e(localDate(substr((string)$sf['published_at'],0,10))) ?></div>
              </div>
            </a>
            <?php endfor; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Latest articles -->
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0" style="font-family:'Lora',serif;font-weight:700;font-size:1rem;border-left:3px solid var(--pub-accent);padding-left:.6rem">
          <?= e(__('public.latest_news')) ?>
        </h5>
        <span class="text-muted" style="font-size:.78rem"><?= $total ?> <?= e(__('public.all_news')) ?></span>
      </div>

      <?php if (empty($notas)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-newspaper" style="font-size:2.5rem;opacity:.3"></i>
        <p class="mt-3"><?= e(__('public.no_articles')) ?></p>
        <a href="<?= url('/notas/crear') ?>" class="btn btn-sm btn-danger"><?= e(__('notas.new')) ?></a>
      </div>
      <?php else: ?>
      <?php foreach ($notas as $nota):
        $cc = $colorCss[$nota['cat_color']??'secondary'] ?? 'cat-secondary';
      ?>
      <div class="nota-card">
        <div class="row g-3 align-items-start">
          <div class="col">
            <?php if ($nota['cat_nombre']): ?>
            <a href="<?= url('/portal/categoria/' . (string)($nota['cat_slug'] ?? '')) ?>" class="cat-badge <?= e($cc) ?> mb-2 d-inline-block"><?= e($nota['cat_nombre']) ?></a>
            <?php endif; ?>
            <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>">
              <h2 class="nota-title"><?= e($nota['titulo']) ?></h2>
            </a>
            <?php if ($nota['extracto']): ?>
            <p class="nota-excerpt"><?= e(mb_substr($nota['extracto'],0,160)) ?>…</p>
            <?php endif; ?>
            <div class="nota-meta d-flex align-items-center gap-2 flex-wrap">
              <?php if ($settings['show_author']??true): ?>
              <span><i class="bi bi-person me-1"></i><?= e($nota['autor_nombre']) ?></span>
              <span>·</span>
              <?php endif; ?>
              <?php if ($settings['show_date']??true): ?>
              <span><i class="bi bi-calendar3 me-1"></i><?= e(localDate(substr((string)$nota['published_at'],0,10))) ?></span>
              <?php endif; ?>
              <?php if ($nota['views']>0): ?>
              <span>·</span>
              <span><i class="bi bi-eye me-1"></i><?= number_format($nota['views']) ?></span>
              <?php endif; ?>
              <span class="ms-auto">
                <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>" class="text-danger" style="font-size:.78rem;font-weight:600">
                  <?= e(__('common.read_more')) ?> →
                </a>
              </span>
            </div>
          </div>
          <?php if ($nota['imagen_portada']): ?>
          <div class="col-auto">
            <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>">
              <img src="<?= url($nota['imagen_portada']) ?>" class="nota-img-sq" alt="<?= e($nota['titulo']) ?>">
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
      <nav class="mt-4">
        <ul class="pagination pagination-pub justify-content-center">
          <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="<?= url('/portal?page='.($page-1)) ?>"><?= e(__('public.prev_page')) ?></a></li>
          <?php endif; ?>
          <?php for ($p=max(1,$page-2); $p<=min($totalPages,$page+2); $p++): ?>
          <li class="page-item <?= $p===$page?'active':'' ?>"><a class="page-link" href="<?= url('/portal?page='.$p) ?>"><?= $p ?></a></li>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
          <li class="page-item"><a class="page-link" href="<?= url('/portal?page='.($page+1)) ?>"><?= e(__('public.next_page')) ?></a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- ── Sidebar ──────────────────────────────────────────────── -->
    <div class="col-lg-4">

      <!-- Categories widget -->
      <?php if (!empty($categorias)): ?>
      <div class="sidebar-widget">
        <h6><?= e(__('public.categories')) ?></h6>
        <div class="d-flex flex-column gap-2">
          <?php foreach ($categorias as $cat):
            $cc2 = $colorCss[$cat['color']??'secondary'] ?? 'cat-secondary';
          ?>
          <a href="<?= url('/portal/categoria/'.(string)$cat['slug']) ?>"
             class="d-flex align-items-center justify-content-between py-1"
             style="border-bottom:1px solid var(--pub-border);font-size:.86rem;color:var(--pub-dark)">
            <div class="d-flex align-items-center gap-2">
              <span class="cat-badge <?= e($cc2) ?>"><?= e($cat['nombre']) ?></span>
            </div>
            <span style="font-size:.75rem;color:var(--pub-gray);font-weight:600"><?= (int)$cat['cnt'] ?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Admin link -->
      <div class="sidebar-widget" style="background:#fff9f9;border-color:#fecaca">
        <h6 style="border-color:#dc2626"><?= e(__('public.go_admin')) ?></h6>
        <p style="font-size:.82rem;color:var(--pub-gray);margin-bottom:.75rem">
          <?= e(__('public.go_admin_desc')) ?>
        </p>
        <a href="<?= url('/') ?>" class="btn btn-sm btn-danger w-100">
          <i class="bi bi-speedometer2 me-1"></i><?= e(__('public.go_admin')) ?>
        </a>
      </div>

    </div>
  </div>
</main>
