<!DOCTYPE html>
<html lang="<?= (isset($_SESSION['_locale']) && $_SESSION['_locale'] === 'es_AR') ? 'es' : 'en' ?>" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($code) ? (int)$code : 500 ?> — Pure PHP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
  <style>
    body{font-family:'Space Grotesk',sans-serif;background:#0a0f1e;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
    .error-code{font-family:'JetBrains Mono',monospace;font-size:7rem;font-weight:600;line-height:1;background:linear-gradient(135deg,#7c3aed,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
  </style>
</head>
<body>
  <div class="text-center px-3">
    <div class="error-code"><?= isset($code) ? (int)$code : 500 ?></div>
    <?php
    // Translate without requiring Lang to be loaded (error pages load early)
    $locale  = $_SESSION['_locale'] ?? 'en';
    $isEs    = ($locale === 'es_AR');
    $titles  = [
      404 => $isEs ? 'No encontrado'      : 'Not Found',
      500 => $isEs ? 'Error del servidor' : 'Server Error',
      403 => $isEs ? 'Acceso denegado'    : 'Forbidden',
    ];
    $msgs    = [
      404 => $isEs ? 'La página que solicitaste no pudo encontrarse.'  : 'The page you requested could not be found.',
      500 => $isEs ? 'Ocurrió un error interno.'                        : 'An internal error occurred.',
      403 => $isEs ? 'No tenés permisos para acceder a esta página.'    : 'You do not have permission to access this page.',
    ];
    $errCode   = isset($code) ? (int)$code : 500;
    $errTitle  = $titles[$errCode]  ?? ($isEs ? 'Error' : 'Error');
    $errMsg    = !empty($message)   ? $message : ($msgs[$errCode] ?? '');
    $backLabel = $isEs ? '← Volver al inicio' : '← Back to home';
    ?>
    <h2 class="text-white mt-2 mb-1"><?= htmlspecialchars($errTitle) ?></h2>
    <p class="text-secondary mb-4" style="max-width:400px;margin:0 auto"><?= htmlspecialchars($errMsg) ?></p>
    <a href="<?php
      // Minimal url() without requiring helpers
      $base = rtrim(dirname(str_replace('\\','/',$_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/.');
      echo htmlspecialchars(($base === '' || $base === '/') ? '/' : $base . '/');
    ?>" class="btn btn-primary"><?= htmlspecialchars($backLabel) ?></a>
  </div>
</body>
</html>
