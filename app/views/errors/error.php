<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $code ?? 500 ?> — Error</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
  <style>
    body { background: #0a0f1e; font-family: 'Space Grotesk', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .error-code { font-family: 'JetBrains Mono', monospace; font-size: 7rem; font-weight: 600; line-height: 1; background: linear-gradient(135deg, #7c3aed, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  </style>
</head>
<body>
  <div class="text-center px-3">
    <div class="error-code"><?= $code ?? 500 ?></div>
    <h2 class="text-white mt-2 mb-1"><?= e($title ?? 'Error') ?></h2>
    <p class="text-secondary mb-4" style="max-width:400px;margin:0 auto"><?= e($message ?? '') ?></p>
    <a href="/" class="btn btn-primary">← Back to home</a>
  </div>
</body>
</html>
