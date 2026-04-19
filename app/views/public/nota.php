<?php /* views/public/nota.php */
$colorCss = ['primary'=>'cat-primary','success'=>'cat-success','danger'=>'cat-danger','info'=>'cat-info','warning'=>'cat-warning','secondary'=>'cat-secondary'];
$cc = $colorCss[$nota['cat_color']??'secondary'] ?? 'cat-secondary';
?>

<main class="container py-4">
  <div class="row g-4">

    <!-- ── Article ──────────────────────────────────────────────── -->
    <div class="col-lg-8">

      <!-- Breadcrumb -->
      <nav style="font-size:.78rem;margin-bottom:1rem">
        <a href="<?= url('/portal') ?>" style="color:var(--pub-gray)"><?= e(__('public.latest_news')) ?></a>
        <?php if ($nota['cat_nombre']): ?>
        <span class="mx-1" style="color:var(--pub-border)">›</span>
        <a href="<?= url('/portal/categoria/'.(string)$nota['cat_slug']) ?>" style="color:var(--pub-gray)"><?= e($nota['cat_nombre']) ?></a>
        <?php endif; ?>
      </nav>

      <!-- Category badge -->
      <?php if ($nota['cat_nombre']): ?>
      <a href="<?= url('/portal/categoria/'.(string)$nota['cat_slug']) ?>">
        <span class="cat-badge <?= e($cc) ?> mb-3 d-inline-block"><?= e($nota['cat_nombre']) ?></span>
      </a>
      <?php endif; ?>

      <!-- Title -->
      <h1 style="font-family:'Lora',serif;font-size:2rem;font-weight:700;line-height:1.25;margin-bottom:.75rem">
        <?= e($nota['titulo']) ?>
      </h1>

      <!-- Subtitle -->
      <?php if ($nota['subtitulo']): ?>
      <p style="font-size:1.15rem;color:var(--pub-gray);font-style:italic;margin-bottom:1rem;font-family:'Lora',serif">
        <?= e($nota['subtitulo']) ?>
      </p>
      <?php endif; ?>

      <!-- Meta -->
      <div class="d-flex align-items-center gap-2 flex-wrap mb-3" style="font-size:.8rem;color:var(--pub-gray);padding-bottom:.75rem;border-bottom:1px solid var(--pub-border)">
        <?php if ($settings['show_author']??true): ?>
        <span><i class="bi bi-person-circle me-1"></i><?= e($nota['autor_nombre']) ?></span>
        <span>·</span>
        <?php endif; ?>
        <?php if ($settings['show_date']??true): ?>
        <span><i class="bi bi-calendar3 me-1"></i><?= e(localDate(substr((string)$nota['published_at'],0,10))) ?></span>
        <span>·</span>
        <?php endif; ?>
        <span><i class="bi bi-eye me-1"></i><?= number_format((int)$nota['views']) ?> <?= e(__('notas.views_label')) ?></span>
        <div class="ms-auto d-flex gap-2">
          <a href="<?= url('/notas/'.(int)$nota['id'].'/edit') ?>" class="btn btn-sm btn-outline-secondary" style="font-size:.73rem">
            <i class="bi bi-pencil me-1"></i><?= e(__('common.edit')) ?>
          </a>
        </div>
      </div>

      <!-- Cover image -->
      <?php if ($nota['imagen_portada']): ?>
      <img src="<?= url($nota['imagen_portada']) ?>"
           class="w-100 mb-4" style="border-radius:8px;max-height:460px;object-fit:cover"
           alt="<?= e($nota['titulo']) ?>">
      <?php endif; ?>

      <!-- Body -->
      <div class="article-body">
        <?= $nota['cuerpo'] /* HTML already stored, render raw */ ?>
      </div>

      <!-- Gallery -->
      <?php if (!empty($imagenes)): ?>
      <div class="mt-4 pt-3" style="border-top:1px solid var(--pub-border)">
        <h5 style="font-family:'Lora',serif;font-size:1rem;margin-bottom:1rem"><?= e(__('notas.gallery_title')) ?></h5>
        <div class="row g-2">
          <?php foreach ($imagenes as $img): ?>
          <div class="col-6 col-md-4">
            <a href="<?= url($img['archivo']) ?>" target="_blank">
              <img src="<?= url($img['archivo']) ?>"
                   class="w-100" style="border-radius:6px;aspect-ratio:4/3;object-fit:cover"
                   alt="<?= e($img['titulo']??'') ?>">
            </a>
            <?php if ($img['titulo']): ?>
            <div style="font-size:.72rem;color:var(--pub-gray);margin-top:.25rem"><?= e($img['titulo']) ?></div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Share -->
      <div class="mt-4 pt-3 d-flex align-items-center gap-2 flex-wrap" style="border-top:1px solid var(--pub-border)">
        <span style="font-size:.82rem;font-weight:600;color:var(--pub-gray)"><?= e(__('public.share')) ?>:</span>
        <a href="https://wa.me/?text=<?= urlencode($nota['titulo'].' - '.url('/portal/nota/'.(string)$nota['slug'])) ?>"
           target="_blank" class="btn btn-sm" style="background:#25d366;color:#fff;font-size:.78rem">
          <i class="bi bi-whatsapp me-1"></i>WhatsApp
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($nota['titulo']) ?>&url=<?= urlencode(url('/portal/nota/'.(string)$nota['slug'])) ?>"
           target="_blank" class="btn btn-sm" style="background:#000;color:#fff;font-size:.78rem">
          <i class="bi bi-twitter-x me-1"></i>X
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('/portal/nota/'.(string)$nota['slug'])) ?>"
           target="_blank" class="btn btn-sm" style="background:#1877f2;color:#fff;font-size:.78rem">
          <i class="bi bi-facebook me-1"></i>Facebook
        </a>
      </div>

    </div>

    <!-- ── Sidebar ──────────────────────────────────────────────── -->
    <div class="col-lg-4">

      <!-- Related articles -->
      <?php if (!empty($relacionadas)): ?>
      <div class="sidebar-widget">
        <h6><?= e(__('public.related')) ?></h6>
        <?php foreach ($relacionadas as $rel): ?>
        <div class="d-flex gap-2 mb-3 pb-3" style="border-bottom:1px solid var(--pub-border)">
          <?php if ($rel['imagen_portada']): ?>
          <a href="<?= url('/portal/nota/'.(string)$rel['slug']) ?>" style="flex-shrink:0">
            <img src="<?= url($rel['imagen_portada']) ?>" style="width:64px;height:48px;border-radius:5px;object-fit:cover" alt="">
          </a>
          <?php endif; ?>
          <div style="min-width:0">
            <a href="<?= url('/portal/nota/'.(string)$rel['slug']) ?>"
               style="font-size:.83rem;font-weight:600;color:var(--pub-dark);line-height:1.3;display:block">
              <?= e(mb_substr($rel['titulo'],0,70)) ?>…
            </a>
            <div style="font-size:.72rem;color:var(--pub-gray);margin-top:.25rem">
              <?= e(localDate(substr((string)$rel['published_at'],0,10))) ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Back -->
      <div class="sidebar-widget">
        <a href="<?= url('/portal') ?>" class="btn btn-outline-secondary w-100" style="font-size:.82rem">
          <i class="bi bi-arrow-left me-1"></i><?= e(__('common.back')) ?>
        </a>
        <a href="<?= url('/notas/'.(int)$nota['id'].'/edit') ?>" class="btn btn-outline-primary w-100 mt-2" style="font-size:.82rem">
          <i class="bi bi-pencil me-1"></i><?= e(__('common.edit')) ?>
        </a>
      </div>

    </div>
  </div>
</main>
