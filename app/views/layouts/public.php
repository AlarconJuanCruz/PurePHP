<!DOCTYPE html>
<html lang="<?= e(currentLocale() === 'es_AR' ? 'es' : 'en') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? ($settings['site_name'] ?? 'Noticias')) ?></title>
  <meta name="description" content="<?= e($settings['site_description'] ?? '') ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <?php if (file_exists(ROOT_PATH.'/public/favicon.ico')): ?>
  <link rel="icon" type="image/x-icon" href="<?= url('public/favicon.ico') ?>">
  <?php endif; ?>
  <?php if (file_exists(ROOT_PATH.'/public/css/custom.css')): ?>
  <link rel="stylesheet" href="<?= url('public/css/custom.css') ?>">
  <?php endif; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php
  // Dynamic primary color from settings
  $pubColor = preg_match('/^#[0-9a-fA-F]{3,6}$/', $settings['primary_color'] ?? '') ? $settings['primary_color'] : '#dc2626';
  ?>
  <style>
    :root{--pub-accent:<?= e($pubColor) ?>;--pub-dark:#111827;--pub-gray:#6b7280;--pub-light:#f9fafb;--pub-border:#e5e7eb}
    *,*::before,*::after{box-sizing:border-box}
    body{font-family:'Inter',sans-serif;background:#fff;color:var(--pub-dark);margin:0}
    h1,h2,h3,h4,h5{font-family:'Lora',serif}
    a{text-decoration:none;color:inherit}
    .pub-header{background:#fff;border-bottom:3px solid var(--pub-accent);position:sticky;top:0;z-index:100}
    .pub-brand{font-family:'Lora',serif;font-size:1.6rem;font-weight:700;color:var(--pub-dark);letter-spacing:-.5px}
    .pub-brand span{color:var(--pub-accent)}
    .pub-nav a{color:var(--pub-gray);font-size:.84rem;font-weight:500;padding:.35rem .65rem;border-radius:5px;transition:.15s}
    .pub-nav a:hover{background:var(--pub-light);color:var(--pub-dark)}
    .pub-nav a.active{color:var(--pub-accent);font-weight:600}
    .cat-badge{display:inline-block;font-size:.7rem;font-weight:700;padding:.18rem .55rem;border-radius:3px;text-transform:uppercase;letter-spacing:.05em}
    .cat-primary{background:#dbeafe;color:#1d4ed8}
    .cat-success{background:#dcfce7;color:#15803d}
    .cat-danger {background:#fee2e2;color:#b91c1c}
    .cat-info   {background:#cffafe;color:#0e7490}
    .cat-warning{background:#fef9c3;color:#a16207}
    .cat-secondary{background:#f3f4f6;color:#4b5563}
    .nota-card{border:none;border-radius:0;border-bottom:1px solid var(--pub-border);padding:1.25rem 0;transition:.15s}
    .nota-card:last-child{border-bottom:none}
    .nota-card:hover .nota-title{color:var(--pub-accent)}
    .nota-title{font-size:1.1rem;font-weight:700;line-height:1.35;transition:color .15s}
    .nota-excerpt{font-size:.87rem;color:var(--pub-gray);line-height:1.6;margin-top:.35rem}
    .nota-meta{font-size:.75rem;color:var(--pub-gray);margin-top:.5rem}
    .nota-img{border-radius:6px;object-fit:cover;width:100%;aspect-ratio:16/9}
    .nota-img-sq{border-radius:6px;object-fit:cover;width:80px;height:80px;flex-shrink:0}
    .featured-card{position:relative;border-radius:10px;overflow:hidden;display:block;aspect-ratio:16/9}
    .featured-card img{width:100%;height:100%;object-fit:cover}
    .featured-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 50%);padding:1.25rem}
    .featured-overlay .feat-cat{margin-bottom:.4rem}
    .featured-overlay .feat-title{font-size:1.2rem;font-weight:700;color:#fff;line-height:1.3;font-family:'Lora',serif}
    .featured-overlay .feat-meta{font-size:.75rem;color:rgba(255,255,255,.7);margin-top:.35rem}
    .sidebar-widget{border:1px solid var(--pub-border);border-radius:8px;padding:1.25rem;margin-bottom:1.25rem}
    .sidebar-widget h6{font-family:'Lora',serif;font-size:.95rem;font-weight:700;border-bottom:2px solid var(--pub-accent);padding-bottom:.4rem;margin-bottom:.9rem}
    .pub-footer{background:var(--pub-dark);color:#9ca3af;padding:2rem 0;margin-top:3rem;font-size:.82rem}
    .pub-footer a{color:#9ca3af;transition:.15s}
    .pub-footer a:hover{color:#fff}
    .article-body{font-family:'Lora',serif;font-size:1.05rem;line-height:1.85;color:#1f2937}
    .article-body p{margin-bottom:1.25rem}
    .article-body h2{font-size:1.4rem;margin:2rem 0 .75rem;color:var(--pub-dark)}
    .article-body h3{font-size:1.2rem;margin:1.5rem 0 .5rem;color:var(--pub-dark)}
    .article-body blockquote{border-left:4px solid var(--pub-accent);padding:.75rem 1.25rem;margin:1.5rem 0;color:var(--pub-gray);font-style:italic;background:var(--pub-light)}
    .article-body img{max-width:100%;border-radius:6px;margin:1rem 0}
    .pagination-pub .page-link{border-radius:5px;margin:0 2px;border-color:var(--pub-border);color:var(--pub-dark);font-size:.85rem}
    .pagination-pub .page-link:hover{background:var(--pub-accent);border-color:var(--pub-accent);color:#fff}
    .pagination-pub .active .page-link{background:var(--pub-accent);border-color:var(--pub-accent)}
  </style>
</head>
<body>

<!-- Header -->
<header class="pub-header">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= url('/portal') ?>" class="pub-brand">
        <?php
        $logoH = trim((string)($settings['logo_header_path'] ?? ''));
        if ($logoH && file_exists(ROOT_PATH . '/' . ltrim($logoH, '/'))):
        ?>
        <img src="<?= url($logoH) ?>" alt="<?= e($settings['site_name'] ?? '') ?>"
             style="max-height:48px;max-width:200px;object-fit:contain">
        <?php else:
          $sn = $settings['site_name'] ?? 'Pure PHP';
          $parts = explode(' ', $sn, 2);
          echo e($parts[0]);
          if (isset($parts[1])) echo ' <span>' . e($parts[1]) . '</span>';
        endif; ?>
      </a>
      <nav class="pub-nav d-none d-md-flex align-items-center gap-1" id="catNav">
        <?php
        // Try to load categories for nav
        try {
            $navCats = DB::fetchAll("SELECT nombre,slug FROM categorias ORDER BY orden LIMIT 6");
        } catch(\Throwable){ $navCats = []; }
        foreach ($navCats as $nc):
        ?>
        <a href="<?= url('/portal/categoria/'.(string)$nc['slug']) ?>"><?= e((string)$nc['nombre']) ?></a>
        <?php endforeach; ?>
      </nav>
      <div class="d-flex align-items-center gap-2">
        <a href="<?= url('/') ?>" class="btn btn-sm btn-outline-danger" style="font-size:.75rem">
          <i class="bi bi-person-fill me-1"></i><?= e(__('public.go_admin')) ?>
        </a>
        <!-- Language -->
        <div class="d-flex gap-1">
          <?php foreach (Lang::available() as $loc => $lbl): ?>
          <a href="<?= url('/lang/'.$loc) ?>" style="font-size:.72rem;color:<?= currentLocale()===$loc?'var(--pub-accent)':'var(--pub-gray)'?>;text-decoration:none"><?= e(Lang::flag($loc)) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Content -->
<?php $this->slot('content'); ?>

<!-- Footer -->
<footer class="pub-footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-3 mb-md-0">
        <?php
        $logoF = trim((string)($settings['logo_footer_path'] ?? ''));
        if ($logoF && file_exists(ROOT_PATH . '/' . ltrim($logoF, '/'))):
        ?>
        <a href="<?= url('/portal') ?>">
          <img src="<?= url($logoF) ?>" alt="<?= e($settings['site_name'] ?? '') ?>"
               style="max-height:40px;max-width:180px;object-fit:contain;filter:brightness(0) invert(1);opacity:.8;margin-bottom:.5rem">
        </a>
        <?php else: ?>
        <div class="pub-brand" style="font-size:1.2rem;color:#fff;margin-bottom:.5rem">
          <?= e($settings['site_name'] ?? 'Pure PHP News') ?>
        </div>
        <?php endif; ?>
        <p style="color:#6b7280;font-size:.8rem;margin:0"><?= e($settings['site_tagline'] ?? '') ?></p>
      </div>
      <div class="col-md-4 mb-3 mb-md-0">
        <div style="font-weight:600;color:#d1d5db;margin-bottom:.6rem;font-size:.84rem"><?= e(__('public.categories')) ?></div>
        <?php foreach ($navCats ?? [] as $nc): ?>
        <a href="<?= url('/portal/categoria/'.(string)$nc['slug']) ?>" class="d-block mb-1" style="font-size:.8rem"><?= e((string)$nc['nombre']) ?></a>
        <?php endforeach; ?>
      </div>
      <div class="col-md-4">
        <a href="<?= url('/') ?>" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem"><?= e(__('public.go_admin')) ?></a>
      </div>
    </div>
    <hr style="border-color:rgba(255,255,255,.1);margin:1.5rem 0 1rem">
    <div style="text-align:center;font-size:.75rem">Powered by Pure PHP v1.0</div>
  </div>
</footer>

<?php View::yield('scripts'); ?>
</body>
</html>
