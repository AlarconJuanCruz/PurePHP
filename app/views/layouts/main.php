<!DOCTYPE html>
<html lang="<?= e(currentLocale() === 'es_AR' ? 'es' : 'en') ?>" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? __('app_name')) ?> — <?= e(__('app_name')) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
  <style>
    :root{--fw-sidebar-w:265px;--fw-accent:#7c3aed;--fw-surface:#0f172a;--fw-card:#1e293b;--fw-border:rgba(255,255,255,.07)}
    *,*::before,*::after{box-sizing:border-box}
    body{font-family:'Space Grotesk',sans-serif;background:var(--fw-surface);min-height:100vh;margin:0}
    code,pre,.mono{font-family:'JetBrains Mono',monospace}
    .fw-sidebar{width:var(--fw-sidebar-w);background:#080d1a;border-right:1px solid var(--fw-border);position:fixed;inset:0 auto 0 0;display:flex;flex-direction:column;overflow-y:auto;z-index:1040;transition:transform .28s cubic-bezier(.4,0,.2,1)}
    .fw-sidebar .brand{padding:1.3rem 1.2rem .8rem;display:flex;align-items:center;gap:.55rem;font-size:1.05rem;font-weight:700;color:#f1f5f9;text-decoration:none;letter-spacing:-.3px;flex-shrink:0}
    .fw-sidebar .brand .dot{width:9px;height:9px;border-radius:50%;background:var(--fw-accent);box-shadow:0 0 10px var(--fw-accent);flex-shrink:0}
    .fw-sidebar .nav-label{padding:.4rem 1rem .1rem;margin-top:.35rem;font-size:.58rem;font-weight:700;letter-spacing:.14em;color:#2d3f55;text-transform:uppercase}
    .fw-sidebar .nav-link{display:flex;align-items:center;gap:.58rem;padding:.46rem .9rem;margin:1px .45rem;color:#4e6480;font-size:.83rem;font-weight:500;border-radius:7px;text-decoration:none;transition:background .14s,color .14s;white-space:nowrap}
    .fw-sidebar .nav-link .bi{font-size:.88rem;flex-shrink:0}
    .fw-sidebar .nav-link:hover{background:rgba(255,255,255,.04);color:#94a3b8}
    .fw-sidebar .nav-link.active{background:rgba(124,58,237,.16);color:#a78bfa;font-weight:600}
    .fw-sidebar .nav-link .ext-icon{margin-left:auto;font-size:.58rem;opacity:.3}
    .fw-sidebar .sidebar-footer{margin-top:auto;padding:.85rem 1rem;border-top:1px solid var(--fw-border);font-size:.7rem;color:#2d3f55;flex-shrink:0}
    .fw-main{margin-left:var(--fw-sidebar-w);min-height:100vh;display:flex;flex-direction:column}
    .fw-topbar{height:56px;background:rgba(8,13,26,.9);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--fw-border);display:flex;align-items:center;padding:0 1.4rem;gap:1rem;position:sticky;top:0;z-index:100}
    .fw-topbar .page-title{font-size:.95rem;font-weight:600;color:#f1f5f9;margin:0}
    .fw-content{padding:1.6rem;flex:1}
    .fw-card{background:var(--fw-card);border:1px solid var(--fw-border);border-radius:12px;padding:1.2rem}
    .stat-card{background:var(--fw-card);border:1px solid var(--fw-border);border-radius:12px;padding:1.2rem 1.4rem;transition:transform .2s,box-shadow .2s;text-decoration:none;display:block}
    .stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.38)}
    .stat-icon{width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem}
    .stat-value{font-size:1.5rem;font-weight:700;letter-spacing:-.5px}
    .stat-label{font-size:.75rem;color:#64748b;font-weight:500}
    /* DataTables */
    .dataTables_wrapper .dataTables_length select,.dataTables_wrapper .dataTables_filter input{background:#0f172a;border:1px solid rgba(255,255,255,.1);color:#cbd5e1;border-radius:6px;padding:.28rem .55rem}
    .dataTables_wrapper .dataTables_info,.dataTables_wrapper .dataTables_length label,.dataTables_wrapper .dataTables_filter label{color:#64748b;font-size:.8rem}
    .dataTables_wrapper .dataTables_paginate .paginate_button{color:#64748b !important;border-radius:6px !important;border:1px solid transparent !important}
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{background:rgba(255,255,255,.06) !important;color:#f1f5f9 !important;border-color:rgba(255,255,255,.08) !important}
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{background:rgba(124,58,237,.22) !important;border-color:rgba(124,58,237,.4) !important;color:#a78bfa !important}
    table.dataTable thead th{border-bottom:1px solid rgba(255,255,255,.07) !important}
    table.dataTable tbody tr{border-top:1px solid rgba(255,255,255,.04) !important}
    table.dataTable.hover tbody tr:hover>*{background:rgba(255,255,255,.022) !important}
    /* Editor textarea */
    .nota-editor{min-height:300px;font-family:inherit;font-size:.9rem;line-height:1.7;resize:vertical}
    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:#1e293b;border-radius:3px}
    ::-webkit-scrollbar-thumb:hover{background:#334155}
    .lang-btn{display:inline-flex;align-items:center;gap:.28rem;font-size:.7rem;padding:.2rem .5rem;border-radius:5px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);color:#475569;text-decoration:none;transition:.14s}
    .lang-btn:hover{background:rgba(255,255,255,.08);color:#94a3b8}
    .lang-btn.active-lang{background:rgba(124,58,237,.14);border-color:rgba(124,58,237,.3);color:#a78bfa}
    @media(max-width:991.98px){.fw-sidebar{transform:translateX(-100%)}.fw-sidebar.open{transform:translateX(0);box-shadow:6px 0 32px rgba(0,0,0,.8)}.fw-main{margin-left:0}}
  </style>
</head>
<body>
<?php $_user = auth(); ?>

<!-- Sidebar -->
<nav class="fw-sidebar" id="sidebar">
  <a href="<?= url('/') ?>" class="brand"><span class="dot"></span> <?= e(__('app_name')) ?></a>

  <div class="nav-label"><?= e(__('nav.section_main')) ?></div>
  <a href="<?= url('/') ?>"            class="nav-link <?= isActive('/') ?>"><i class="bi bi-grid-1x2-fill"></i> <?= e(__('nav.dashboard')) ?></a>
  <a href="<?= url('/users') ?>"       class="nav-link <?= isActive('/users',false) ?>"><i class="bi bi-people-fill"></i> <?= e(__('nav.users')) ?></a>
  <a href="<?= url('/roles') ?>"       class="nav-link <?= isActive('/roles',false) ?>"><i class="bi bi-shield-fill-check"></i> <?= e(__('nav.roles')) ?></a>

  <div class="nav-label"><?= e(__('nav.section_content')) ?></div>
  <?php
  // "Notas" link: active on /notas and /notas/{id}/edit but NOT on /notas/crear
  $_notasActive = (!isActive('/notas/crear') && isActive('/notas', false)) ? 'active' : '';
  ?>
  <a href="<?= url('/notas') ?>"       class="nav-link <?= $_notasActive ?>"><i class="bi bi-newspaper"></i> <?= e(__('nav.notas')) ?></a>
  <a href="<?= url('/notas/crear') ?>" class="nav-link <?= isActive('/notas/crear') ?>"><i class="bi bi-plus-circle"></i> <?= e(__('notas.new')) ?></a>
  <a href="<?= url('/categorias') ?>"  class="nav-link <?= isActive('/categorias',false) ?>"><i class="bi bi-tag-fill"></i> <?= e(__('nav.categorias')) ?></a>
  <a href="<?= url('/settings') ?>"    class="nav-link <?= isActive('/settings',false) ?>"><i class="bi bi-sliders"></i> <?= e(__('nav.settings')) ?></a>
  <a href="<?= url('/portal') ?>"      class="nav-link" target="_blank"><i class="bi bi-globe2"></i> <?= e(__('nav.portal')) ?><i class="bi bi-box-arrow-up-right ext-icon"></i></a>

  <div class="nav-label"><?= e(__('nav.section_interface')) ?></div>
  <a href="<?= url('/components') ?>"  class="nav-link <?= isActive('/components') ?>"><i class="bi bi-palette2"></i> <?= e(__('nav.components')) ?></a>

  <div class="nav-label"><?= e(__('nav.section_developer')) ?></div>
  <a href="<?= url('/docs') ?>"        class="nav-link <?= isActive('/docs') ?>"><i class="bi bi-book-half"></i> <?= e(__('nav.docs')) ?></a>
  <a href="<?= url('/api/stats') ?>"   class="nav-link" target="_blank"><i class="bi bi-braces-asterisk"></i> <?= e(__('nav.api_stats')) ?><i class="bi bi-box-arrow-up-right ext-icon"></i></a>

  <div class="nav-label"><?= e(__('nav.section_account')) ?></div>
  <?php if ($_user): ?>
  <a href="<?= url('/logout') ?>" class="nav-link" onclick="return confirm('<?= e(__('nav.logout_confirm')) ?>')"><i class="bi bi-box-arrow-right"></i> <?= e(__('nav.logout')) ?></a>
  <?php else: ?>
  <a href="<?= url('/login') ?>" class="nav-link <?= isActive('/login') ?>"><i class="bi bi-box-arrow-in-right"></i> <?= e(__('nav.login')) ?></a>
  <?php endif; ?>

  <div class="sidebar-footer">
    <?php if ($_user): ?>
    <div class="d-flex align-items-center gap-2 mb-2">
      <div style="width:26px;height:26px;border-radius:50%;background:rgba(124,58,237,.28);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#a78bfa;flex-shrink:0"><?= e(mb_strtoupper(mb_substr((string)($_user['name']??'U'),0,1))) ?></div>
      <div>
        <div style="color:#64748b;font-size:.76rem;font-weight:600;line-height:1.2"><?= e((string)($_user['name']??'')) ?></div>
        <div style="font-size:.66rem;color:#2d3f55"><?= e((string)($_user['role_name']??'')) ?></div>
      </div>
    </div>
    <?php endif; ?>
    <div><?= e(__('app_name')) ?> <span class="mono" style="color:#2d3f55"><?= e(__('version')) ?></span></div>
    <div class="d-flex gap-1 mt-2 flex-wrap">
      <?php foreach (Lang::available() as $loc => $label): ?>
      <a href="<?= url('/lang/'.$loc) ?>" class="lang-btn <?= currentLocale()===$loc?'active-lang':'' ?>"><?= e(Lang::flag($loc)) ?> <?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</nav>

<!-- Main -->
<div class="fw-main">
  <header class="fw-topbar">
    <button class="d-lg-none me-1" id="sidebarToggle" style="background:none;border:none;padding:0;cursor:pointer;line-height:1"><i class="bi bi-list fs-4 text-white"></i></button>
    <h1 class="page-title"><?= e($pageTitle??'') ?></h1>
    <?php
    // Only show badge when explicitly false (DB failed to connect)
    // Do NOT show when $dbConnected is true or not set
    if (isset($dbConnected) && $dbConnected === false): ?>
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2" style="font-size:.66rem">
      <i class="bi bi-exclamation-triangle me-1"></i><?= e(__('nav.db_not_connected')) ?>
    </span>
    <?php endif; ?>
    <div class="ms-auto d-flex align-items-center gap-2">
      <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle" style="font-size:.64rem">
        <i class="bi bi-circle-fill me-1" style="font-size:.4rem"></i><?= e(__('nav.online')) ?>
      </span>
      <?php if ($_user): ?>
      <div style="width:28px;height:28px;border-radius:50%;background:rgba(124,58,237,.28);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#a78bfa;cursor:default"
           data-bs-toggle="tooltip" title="<?= e((string)($_user['name']??'')) ?> — <?= e((string)($_user['role_name']??'')) ?>">
        <?= e(mb_strtoupper(mb_substr((string)($_user['name']??'U'),0,1))) ?>
      </div>
      <?php endif; ?>
    </div>
  </header>

  <main class="fw-content">
    <?php
    $fS = get_flash('success'); $fE = get_flash('error');
    if ($fS): ?><div class="alert alert-success alert-dismissible mb-3 py-2" style="font-size:.84rem"><i class="bi bi-check-circle me-2"></i><?= e($fS) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;
    if ($fE): ?><div class="alert alert-danger alert-dismissible mb-3 py-2" style="font-size:.84rem"><i class="bi bi-x-circle me-2"></i><?= e($fE) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php $this->slot('content'); ?>
  </main>
</div>

<script>
(function(){
  var t=document.getElementById('sidebarToggle'),s=document.getElementById('sidebar');
  if(t&&s){t.addEventListener('click',function(e){e.stopPropagation();s.classList.toggle('open')});document.addEventListener('click',function(e){if(s.classList.contains('open')&&!s.contains(e.target))s.classList.remove('open')})}
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){bootstrap.Tooltip.getOrCreateInstance(el)});
}());
</script>
<?php View::yield('scripts'); ?>
</body>
</html>
