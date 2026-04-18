<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Install') ?> — Pure PHP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    :root { --accent: #7c3aed; --accent2: #06b6d4; }
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'Space Grotesk', sans-serif;
      background: #080d1a;
      min-height: 100vh;
      display: flex; flex-direction: column;
      color: #94a3b8;
      position: relative; overflow-x: hidden;
    }
    /* animated background */
    body::before {
      content: '';
      position: fixed; inset: 0; pointer-events: none; z-index: 0;
      background:
        radial-gradient(ellipse 700px 500px at 10% 20%, rgba(124,58,237,.12) 0%, transparent 70%),
        radial-gradient(ellipse 500px 400px at 90% 80%, rgba(6,182,212,.08) 0%, transparent 70%);
    }

    .install-wrap {
      position: relative; z-index: 1;
      max-width: 680px; width: 100%; margin: 0 auto;
      padding: 2.5rem 1rem 3rem;
      display: flex; flex-direction: column; gap: 1.5rem;
    }

    /* Brand header */
    .install-brand {
      display: flex; align-items: center; gap: .6rem;
      font-size: 1.15rem; font-weight: 700; color: #f1f5f9;
      text-decoration: none; justify-content: center;
    }
    .install-brand .dot {
      width: 10px; height: 10px; border-radius: 50%;
      background: var(--accent); box-shadow: 0 0 12px var(--accent);
    }

    /* Step indicator */
    .steps {
      display: flex; align-items: center; justify-content: center; gap: 0;
    }
    .step-item {
      display: flex; flex-direction: column; align-items: center; gap: .3rem;
      position: relative; flex: 1; max-width: 120px;
    }
    .step-item:not(:last-child)::after {
      content: '';
      position: absolute; top: 16px; left: calc(50% + 18px);
      width: calc(100% - 36px); height: 2px;
      background: rgba(255,255,255,.08);
      z-index: 0;
    }
    .step-item:not(:last-child).done::after { background: var(--accent); opacity: .6; }
    .step-num {
      width: 32px; height: 32px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: .78rem; font-weight: 700; position: relative; z-index: 1;
      border: 2px solid rgba(255,255,255,.1); color: #475569; background: #0f172a;
      transition: .2s;
    }
    .step-item.active .step-num {
      background: var(--accent); border-color: var(--accent);
      color: #fff; box-shadow: 0 0 16px rgba(124,58,237,.5);
    }
    .step-item.done .step-num {
      background: rgba(124,58,237,.2); border-color: rgba(124,58,237,.4); color: #a78bfa;
    }
    .step-label { font-size: .67rem; color: #334155; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
    .step-item.active .step-label { color: #a78bfa; }
    .step-item.done  .step-label  { color: #6d28d9; }

    /* Card */
    .install-card {
      background: rgba(30,41,59,.7);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 16px; padding: 2rem;
      backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    }
    .install-card h2 {
      font-size: 1.25rem; font-weight: 700; color: #f1f5f9; margin-bottom: .35rem;
    }
    .install-card p.subtitle {
      font-size: .84rem; color: #475569; margin-bottom: 1.5rem;
    }

    /* Form elements */
    .form-label { font-size: .79rem; color: #94a3b8; font-weight: 500; margin-bottom: .3rem; }
    .form-control, .form-select {
      background: rgba(15,23,42,.8) !important;
      border: 1px solid rgba(255,255,255,.1) !important;
      color: #f1f5f9 !important; border-radius: 8px !important;
      padding: .62rem .85rem !important; font-size: .88rem;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
      border-color: rgba(124,58,237,.6) !important;
      box-shadow: 0 0 0 3px rgba(124,58,237,.15) !important;
    }
    .form-control::placeholder { color: #334155; }

    .btn-install {
      background: var(--accent); border: none;
      color: #fff; font-weight: 600; font-size: .9rem;
      padding: .7rem 1.8rem; border-radius: 9px;
      cursor: pointer; transition: opacity .15s, box-shadow .2s, transform .1s;
    }
    .btn-install:hover { opacity: .9; box-shadow: 0 6px 20px rgba(124,58,237,.4); }
    .btn-install:active { transform: scale(.98); }
    .btn-install:disabled { opacity: .5; cursor: not-allowed; }

    .btn-back {
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.1);
      color: #64748b; font-weight: 500; font-size: .88rem;
      padding: .68rem 1.4rem; border-radius: 9px;
      cursor: pointer; text-decoration: none;
      transition: background .15s, color .15s;
      display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn-back:hover { background: rgba(255,255,255,.08); color: #94a3b8; }

    /* Check list */
    .check-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.05);
      font-size: .85rem;
    }
    .check-row:last-child { border-bottom: none; }
    .check-row .label { color: #94a3b8; }
    .check-row .val   { font-size: .78rem; font-family: 'JetBrains Mono', monospace; }
    .check-ok   { color: #22c55e; }
    .check-fail { color: #ef4444; }

    /* DB test result */
    #dbTestResult {
      padding: .6rem .85rem; border-radius: 8px; font-size: .82rem; display: none;
    }
    #dbTestResult.ok   { background: rgba(34,197,94,.1);  border: 1px solid rgba(34,197,94,.2);  color: #4ade80; }
    #dbTestResult.fail { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.2);  color: #f87171; }

    /* Password strength */
    .pwd-strength { height: 3px; border-radius: 2px; margin-top: .4rem; transition: width .3s, background .3s; width: 0; }

    /* Success checkmark animation */
    @keyframes popIn { 0%{transform:scale(0);opacity:0} 70%{transform:scale(1.15)} 100%{transform:scale(1);opacity:1} }
    .success-icon { animation: popIn .5s ease forwards; }
  </style>
</head>
<body>
  <div class="install-wrap">

    <!-- Brand -->
    <a href="<?= url('/install') ?>" class="install-brand">
      <span class="dot"></span> Pure PHP
    </a>

    <!-- Step indicator -->
    <?php
    $currentStep = $step ?? 1;
    $stepDefs    = [
      1 => 'Welcome',
      2 => 'Database',
      3 => 'Account',
      4 => 'Done',
    ];
    ?>
    <div class="steps">
      <?php foreach ($stepDefs as $n => $label): ?>
      <div class="step-item <?= $n === $currentStep ? 'active' : ($n < $currentStep ? 'done' : '') ?>">
        <div class="step-num">
          <?php if ($n < $currentStep): ?>
            <i class="bi bi-check"></i>
          <?php else: ?>
            <?= $n ?>
          <?php endif; ?>
        </div>
        <span class="step-label"><?= e($label) ?></span>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Page content -->
    <?php $this->slot('content'); ?>

    <p style="text-align:center;font-size:.72rem;color:#1e293b">
      Pure PHP v1.0 — Installation Wizard
    </p>
  </div>
  <?php View::yield('scripts'); ?>
</body>
</html>
