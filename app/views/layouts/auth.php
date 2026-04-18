<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Login') ?> — Pure PHP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root { --fw-accent: #7c3aed; }
    * { box-sizing: border-box; }
    body {
      font-family: 'Space Grotesk', sans-serif;
      background: #0a0f1e;
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 1.5rem;
      position: relative; overflow: hidden;
    }
    /* Animated background blobs */
    body::before, body::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: .18;
      pointer-events: none;
    }
    body::before {
      width: 520px; height: 520px;
      background: radial-gradient(circle, #7c3aed, transparent);
      top: -120px; left: -120px;
      animation: drift1 12s ease-in-out infinite alternate;
    }
    body::after {
      width: 400px; height: 400px;
      background: radial-gradient(circle, #06b6d4, transparent);
      bottom: -80px; right: -80px;
      animation: drift2 15s ease-in-out infinite alternate;
    }
    @keyframes drift1 { to { transform: translate(40px, 60px); } }
    @keyframes drift2 { to { transform: translate(-30px, -50px); } }

    .auth-card {
      background: rgba(30,41,59,.75);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 20px;
      padding: 2.25rem 2rem;
      width: 100%; max-width: 400px;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      position: relative; z-index: 1;
      box-shadow: 0 32px 64px rgba(0,0,0,.4);
    }
    .auth-brand {
      display: flex; align-items: center; justify-content: center; gap: .5rem;
      font-size: 1.1rem; font-weight: 700; color: #f1f5f9;
      margin-bottom: 1.75rem; text-decoration: none;
    }
    .auth-brand .dot {
      width: 9px; height: 9px; border-radius: 50%;
      background: var(--fw-accent); box-shadow: 0 0 10px var(--fw-accent);
    }
    .form-control {
      background: rgba(15,23,42,.7) !important;
      border: 1px solid rgba(255,255,255,.1) !important;
      color: #f1f5f9 !important;
      border-radius: 8px !important;
      padding: .65rem .85rem !important;
      font-size: .9rem;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
      border-color: rgba(124,58,237,.6) !important;
      box-shadow: 0 0 0 3px rgba(124,58,237,.15) !important;
    }
    .form-control::placeholder { color: #475569; }
    .form-label { font-size: .8rem; color: #94a3b8; margin-bottom: .35rem; font-weight: 500; }
    .btn-auth {
      width: 100%; padding: .7rem; border-radius: 8px;
      background: var(--fw-accent);
      border: none; color: #fff; font-weight: 600; font-size: .9rem;
      cursor: pointer;
      transition: opacity .15s, transform .1s, box-shadow .2s;
    }
    .btn-auth:hover { opacity: .92; box-shadow: 0 6px 20px rgba(124,58,237,.4); }
    .btn-auth:active { transform: scale(.98); }
    .divider { display: flex; align-items: center; gap: .75rem; color: #334155; font-size: .75rem; margin: 1.25rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.07); }
    .input-icon-wrapper { position: relative; }
    .input-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: #475569; font-size: .9rem; pointer-events: none; }
    .input-icon-wrapper .form-control { padding-left: 2.4rem !important; }
    .toggle-pwd { position: absolute; right: .85rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #475569; cursor: pointer; padding: 0; font-size: .9rem; }
    .toggle-pwd:hover { color: #94a3b8; }
    .error-banner { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); border-radius: 8px; padding: .65rem .85rem; font-size: .82rem; color: #f87171; margin-bottom: 1.25rem; }
  </style>
</head>
<body>
  <?php $this->slot('content'); ?>
  <?php View::yield('scripts'); ?>
</body>
</html>
