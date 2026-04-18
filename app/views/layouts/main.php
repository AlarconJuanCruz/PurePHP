<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'App') ?> — Pure PHP</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

  <!-- ALL JS in <head> so view scripts can reference bootstrap/$ immediately -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

  <style>
    :root {
      --fw-sidebar-w : 262px;
      --fw-accent    : #7c3aed;
      --fw-accent2   : #06b6d4;
      --fw-surface   : #0f172a;
      --fw-card      : #1e293b;
      --fw-border    : rgba(255,255,255,.07);
    }
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: 'Space Grotesk', sans-serif; background: var(--fw-surface); min-height: 100vh; margin: 0; }
    code, pre, .mono { font-family: 'JetBrains Mono', monospace; }

    /* ── Sidebar ─────────────────────────────────────────────────── */
    .fw-sidebar {
      width: var(--fw-sidebar-w); background: #080d1a;
      border-right: 1px solid var(--fw-border);
      position: fixed; inset: 0 auto 0 0;
      display: flex; flex-direction: column;
      overflow-y: auto; z-index: 1040;
      transition: transform .28s cubic-bezier(.4,0,.2,1);
    }
    .fw-sidebar .brand {
      padding: 1.4rem 1.2rem .9rem;
      display: flex; align-items: center; gap: .55rem;
      font-size: 1.1rem; font-weight: 700; color: #f1f5f9;
      text-decoration: none; letter-spacing: -.3px; flex-shrink: 0;
    }
    .fw-sidebar .brand .dot {
      width: 9px; height: 9px; border-radius: 50%;
      background: var(--fw-accent); box-shadow: 0 0 10px var(--fw-accent);
      flex-shrink: 0;
    }
    .fw-sidebar .nav-label {
      padding: .45rem 1.1rem .1rem; margin-top: .4rem;
      font-size: .6rem; font-weight: 700; letter-spacing: .13em;
      color: #2d3f55; text-transform: uppercase;
    }
    .fw-sidebar .nav-link {
      display: flex; align-items: center; gap: .6rem;
      padding: .48rem .9rem; margin: 1px .45rem;
      color: #4e6480; font-size: .84rem; font-weight: 500;
      border-radius: 7px; text-decoration: none;
      transition: background .14s, color .14s;
    }
    .fw-sidebar .nav-link .bi { font-size: .9rem; flex-shrink: 0; }
    .fw-sidebar .nav-link:hover  { background: rgba(255,255,255,.045); color: #94a3b8; }
    .fw-sidebar .nav-link.active { background: rgba(124,58,237,.16); color: #a78bfa; font-weight: 600; }
    .fw-sidebar .nav-link .nav-badge {
      margin-left: auto; font-size: .6rem; font-weight: 700;
      padding: .1rem .38rem; border-radius: 20px;
      background: rgba(124,58,237,.22); color: #a78bfa;
    }
    .fw-sidebar .nav-link .ext-icon { margin-left: auto; font-size: .58rem; opacity: .3; }
    .fw-sidebar .sidebar-footer {
      margin-top: auto; padding: .9rem 1.1rem;
      border-top: 1px solid var(--fw-border);
      font-size: .72rem; color: #2d3f55; flex-shrink: 0;
    }

    /* ── Layout ──────────────────────────────────────────────────── */
    .fw-main  { margin-left: var(--fw-sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
    .fw-topbar {
      height: 58px; background: rgba(8,13,26,.88);
      backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
      border-bottom: 1px solid var(--fw-border);
      display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem;
      position: sticky; top: 0; z-index: 100;
    }
    .fw-topbar .page-title { font-size: .98rem; font-weight: 600; color: #f1f5f9; margin: 0; }
    .fw-content { padding: 1.7rem; flex: 1; }

    /* ── Cards ───────────────────────────────────────────────────── */
    .fw-card  { background: var(--fw-card); border: 1px solid var(--fw-border); border-radius: 12px; padding: 1.2rem; }
    .stat-card {
      background: var(--fw-card); border: 1px solid var(--fw-border);
      border-radius: 12px; padding: 1.2rem 1.4rem;
      transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.38); }
    .stat-icon { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .stat-value { font-size: 1.55rem; font-weight: 700; letter-spacing: -.5px; }
    .stat-label { font-size: .76rem; color: #64748b; font-weight: 500; }
    .stat-trend { font-size: .76rem; font-weight: 600; }

    /* ── DataTables dark ─────────────────────────────────────────── */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      background: #0f172a; border: 1px solid rgba(255,255,255,.1);
      color: #cbd5e1; border-radius: 6px; padding: .28rem .55rem;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { color: #64748b; font-size: .8rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      color: #64748b !important; border-radius: 6px !important; border: 1px solid transparent !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: rgba(255,255,255,.06) !important; color: #f1f5f9 !important; border-color: rgba(255,255,255,.08) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
      background: rgba(124,58,237,.22) !important; border-color: rgba(124,58,237,.4) !important; color: #a78bfa !important;
    }
    table.dataTable thead th { border-bottom: 1px solid rgba(255,255,255,.07) !important; }
    table.dataTable tbody tr { border-top: 1px solid rgba(255,255,255,.04) !important; }
    table.dataTable.hover tbody tr:hover > * { background: rgba(255,255,255,.022) !important; }

    /* ── Code blocks ─────────────────────────────────────────────── */
    .code-block {
      background: #0a0f1e; border: 1px solid rgba(255,255,255,.07);
      border-radius: 8px; padding: .9rem 1rem;
      font-family: 'JetBrains Mono', monospace; font-size: .76rem;
      color: #94a3b8; line-height: 1.65; overflow-x: auto; white-space: pre;
    }

    /* ── Scrollbar ───────────────────────────────────────────────── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #334155; }

    /* ── Mobile ──────────────────────────────────────────────────── */
    @media (max-width: 991.98px) {
      .fw-sidebar { transform: translateX(-100%); }
      .fw-sidebar.open { transform: translateX(0); box-shadow: 6px 0 32px rgba(0,0,0,.8); }
      .fw-main { margin-left: 0; }
    }
  </style>
</head>
<body>

<?php
// Use safe_parse_url — never passes null to trim
$_user        = auth();
$_currentPath = safe_parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'));
?>

<!-- ═══ Sidebar ══════════════════════════════════════════════════════════════ -->
<nav class="fw-sidebar" id="sidebar">

  <a href="<?= url('/') ?>" class="brand">
    <span class="dot"></span> Pure PHP
  </a>

  <!-- Main -->
  <div class="nav-label">Main</div>
  <a href="<?= url('/') ?>"           class="nav-link <?= isActive('/') ?>">
    <i class="bi bi-grid-1x2-fill"></i> Dashboard
  </a>
  <a href="<?= url('/users') ?>"      class="nav-link <?= isActive('/users', false) ?>">
    <i class="bi bi-people-fill"></i> Users
  </a>
  <a href="<?= url('/roles') ?>"      class="nav-link <?= isActive('/roles', false) ?>">
    <i class="bi bi-shield-fill-check"></i> Roles &amp; Permissions
  </a>

  <!-- Interface -->
  <div class="nav-label">Interface</div>
  <a href="<?= url('/components') ?>" class="nav-link <?= isActive('/components') ?>">
    <i class="bi bi-palette2"></i> Components
  </a>

  <!-- Developer -->
  <div class="nav-label">Developer</div>
  <a href="<?= url('/docs') ?>"       class="nav-link <?= isActive('/docs') ?>">
    <i class="bi bi-book-half"></i> Documentation
  </a>
  <a href="<?= url('/api/stats') ?>"  class="nav-link" target="_blank">
    <i class="bi bi-braces-asterisk"></i> GET /api/stats
    <i class="bi bi-box-arrow-up-right ext-icon"></i>
  </a>

  <!-- Account -->
  <div class="nav-label">Account</div>
  <?php if ($_user): ?>
  <a href="<?= url('/logout') ?>" class="nav-link"
     onclick="return confirm('Log out?')">
    <i class="bi bi-box-arrow-right"></i> Logout
  </a>
  <?php else: ?>
  <a href="<?= url('/login') ?>" class="nav-link <?= isActive('/login') ?>">
    <i class="bi bi-box-arrow-in-right"></i> Login
  </a>
  <?php endif; ?>

  <!-- Footer -->
  <div class="sidebar-footer">
    <?php if ($_user): ?>
    <div class="d-flex align-items-center gap-2 mb-2">
      <div style="width:26px;height:26px;border-radius:50%;background:rgba(124,58,237,.28);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#a78bfa;flex-shrink:0">
        <?= e(mb_strtoupper(mb_substr((string)($_user['name'] ?? 'U'), 0, 1))) ?>
      </div>
      <div>
        <div style="color:#64748b;font-size:.78rem;font-weight:600;line-height:1.2"><?= e((string)($_user['name'] ?? '')) ?></div>
        <div style="font-size:.68rem;color:#2d3f55"><?= e((string)($_user['role_name'] ?? '')) ?></div>
      </div>
    </div>
    <?php endif; ?>
    <div>Pure PHP <span class="mono" style="color:#2d3f55">v1.0</span></div>
    <div class="mt-1">PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?></div>
  </div>
</nav>

<!-- ═══ Main ═════════════════════════════════════════════════════════════════ -->
<div class="fw-main">

  <header class="fw-topbar">
    <button class="d-lg-none me-1" id="sidebarToggle"
            style="background:none;border:none;padding:0;cursor:pointer;line-height:1">
      <i class="bi bi-list fs-4 text-white"></i>
    </button>
    <h1 class="page-title"><?= e($pageTitle ?? 'Page') ?></h1>

    <?php if (isset($dbConnected) && $dbConnected === false): ?>
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle ms-2" style="font-size:.68rem">
      <i class="bi bi-exclamation-triangle me-1"></i>DB not connected
    </span>
    <?php endif; ?>

    <div class="ms-auto d-flex align-items-center gap-2">
      <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle" style="font-size:.66rem">
        <i class="bi bi-circle-fill me-1" style="font-size:.4rem"></i>Online
      </span>
      <?php if ($_user): ?>
      <div style="width:28px;height:28px;border-radius:50%;background:rgba(124,58,237,.28);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#a78bfa;flex-shrink:0;cursor:default"
           data-bs-toggle="tooltip"
           title="<?= e((string)($_user['name'] ?? '')) ?> — <?= e((string)($_user['role_name'] ?? '')) ?>">
        <?= e(mb_strtoupper(mb_substr((string)($_user['name'] ?? 'U'), 0, 1))) ?>
      </div>
      <?php endif; ?>
    </div>
  </header>

  <main class="fw-content">
    <?php
    $fSuccess = get_flash('success');
    $fError   = get_flash('error');
    if ($fSuccess): ?>
    <div class="alert alert-success alert-dismissible mb-3 py-2" role="alert" style="font-size:.85rem">
      <i class="bi bi-check-circle me-2"></i><?= e($fSuccess) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; if ($fError): ?>
    <div class="alert alert-danger alert-dismissible mb-3 py-2" role="alert" style="font-size:.85rem">
      <i class="bi bi-x-circle me-2"></i><?= e($fError) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php $this->slot('content'); ?>
  </main>
</div>

<script>
(function () {
  const toggle  = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', function(e) { e.stopPropagation(); sidebar.classList.toggle('open'); });
    document.addEventListener('click', function(e) {
      if (sidebar.classList.contains('open') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }
  // Init tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
    bootstrap.Tooltip.getOrCreateInstance(el);
  });
}());
</script>

<?php View::yield('scripts'); ?>
</body>
</html>
