<?php /* views/public/categoria.php */
$colorCss = ['primary'=>'cat-primary','success'=>'cat-success','danger'=>'cat-danger','info'=>'cat-info','warning'=>'cat-warning','secondary'=>'cat-secondary'];
$cc = $colorCss[$cat['color']??'secondary'] ?? 'cat-secondary';
?>

<main class="container py-4">

  <!-- Category header -->
  <div class="mb-4 pb-3" style="border-bottom:3px solid var(--pub-accent)">
    <div class="d-flex align-items-center gap-3">
      <span class="cat-badge <?= e($cc) ?>" style="font-size:.9rem;padding:.3rem .7rem"><?= e($cat['nombre']) ?></span>
      <div>
        <h1 style="font-size:1.5rem;font-family:'Lora',serif;font-weight:700;margin:0"><?= e($cat['nombre']) ?></h1>
        <?php if ($cat['descripcion']): ?>
        <p style="font-size:.84rem;color:var(--pub-gray);margin:.25rem 0 0"><?= e($cat['descripcion']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <p style="font-size:.82rem;color:var(--pub-gray);margin-bottom:1.5rem"><?= $total ?> <?= e(__('public.all_news')) ?></p>

      <?php if (empty($notas)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-newspaper" style="font-size:2.5rem;opacity:.3"></i>
        <p class="mt-3"><?= e(__('public.no_articles')) ?></p>
      </div>
      <?php else: ?>
      <?php foreach ($notas as $nota): ?>
      <div class="nota-card">
        <div class="row g-3 align-items-start">
          <div class="col">
            <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>">
              <h2 class="nota-title"><?= e($nota['titulo']) ?></h2>
            </a>
            <?php if ($nota['extracto']): ?>
            <p class="nota-excerpt"><?= e(mb_substr($nota['extracto'],0,150)) ?>…</p>
            <?php endif; ?>
            <div class="nota-meta d-flex align-items-center gap-2 flex-wrap">
              <span><i class="bi bi-person me-1"></i><?= e($nota['autor_nombre']) ?></span>
              <span>·</span>
              <span><?= e(localDate(substr((string)$nota['published_at'],0,10))) ?></span>
              <span class="ms-auto">
                <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>" class="text-danger" style="font-size:.78rem;font-weight:600"><?= e(__('common.read_more')) ?> →</a>
              </span>
            </div>
          </div>
          <?php if ($nota['imagen_portada']): ?>
          <div class="col-auto">
            <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>">
              <img src="<?= url($nota['imagen_portada']) ?>" class="nota-img-sq" alt="">
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if ($totalPages > 1): ?>
      <nav class="mt-4">
        <ul class="pagination pagination-pub justify-content-center">
          <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="<?= url('/portal/categoria/'.(string)$cat['slug'].'?page='.($page-1)) ?>"><?= e(__('public.prev_page')) ?></a></li>
          <?php endif; ?>
          <?php for ($p=max(1,$page-2); $p<=min($totalPages,$page+2); $p++): ?>
          <li class="page-item <?= $p===$page?'active':'' ?>"><a class="page-link" href="<?= url('/portal/categoria/'.(string)$cat['slug'].'?page='.$p) ?>"><?= $p ?></a></li>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
          <li class="page-item"><a class="page-link" href="<?= url('/portal/categoria/'.(string)$cat['slug'].'?page='.($page+1)) ?>"><?= e(__('public.next_page')) ?></a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <?php if (!empty($categorias)): ?>
      <div class="sidebar-widget">
        <h6><?= e(__('public.categories')) ?></h6>
        <?php
        $colorCss2 = ['primary'=>'cat-primary','success'=>'cat-success','danger'=>'cat-danger','info'=>'cat-info','warning'=>'cat-warning','secondary'=>'cat-secondary'];
        foreach ($categorias as $c2):
          $cc2 = $colorCss2[$c2['color']??'secondary'] ?? 'cat-secondary';
        ?>
        <a href="<?= url('/portal/categoria/'.(string)$c2['slug']) ?>"
           class="d-flex align-items-center justify-content-between py-1"
           style="border-bottom:1px solid var(--pub-border);font-size:.86rem;color:var(--pub-dark)">
          <span class="cat-badge <?= e($cc2) ?>"><?= e($c2['nombre']) ?></span>
          <span style="font-size:.75rem;color:var(--pub-gray);font-weight:600"><?= (int)$c2['cnt'] ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="sidebar-widget">
        <a href="<?= url('/portal') ?>" class="btn btn-outline-secondary w-100" style="font-size:.82rem">
          <i class="bi bi-arrow-left me-1"></i><?= e(__('public.latest_news')) ?>
        </a>
      </div>
    </div>
  </div>
</main>
