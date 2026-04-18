<?php /* views/install/complete.php — Step 4 */ ?>

<div class="install-card" style="text-align:center">

  <!-- Animated checkmark -->
  <div class="success-icon" style="width:72px;height:72px;border-radius:50%;background:rgba(34,197,94,.12);border:2px solid rgba(34,197,94,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2rem;color:#22c55e">
    <i class="bi bi-check-lg"></i>
  </div>

  <h2 style="margin-bottom:.4rem">Installation Complete!</h2>
  <p class="subtitle" style="margin-bottom:1.75rem">
    Pure PHP has been successfully installed. Your database is configured and your admin account is ready.
  </p>

  <!-- Summary -->
  <div style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:1.1rem 1.3rem;text-align:left;margin-bottom:1.75rem">
    <div style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#334155;margin-bottom:.85rem">
      What was installed
    </div>
    <?php
    $items = [
      ['Database schema',          'roles, permissions, users tables'],
      ['Demo data',                '4 roles, 11 permissions, 20 seed users'],
      ['Admin account',            'Your credentials from Step 3'],
      ['Configuration files',      'app/config/database.php, .installed'],
    ];
    foreach ($items as [$label, $value]):
    ?>
    <div style="display:flex;justify-content:space-between;align-items:center;padding:.38rem 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:.82rem">
      <span style="color:#94a3b8;display:flex;align-items:center;gap:.5rem">
        <i class="bi bi-check-circle-fill text-success" style="font-size:.75rem"></i>
        <?= e($label) ?>
      </span>
      <span style="color:#475569;font-size:.77rem"><?= e($value) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Security notice -->
  <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:8px;padding:.65rem .9rem;font-size:.79rem;color:#fbbf24;text-align:left;margin-bottom:1.75rem">
    <i class="bi bi-shield-exclamation me-2"></i>
    <strong>Security:</strong> For production, delete or restrict access to the <code style="color:#f59e0b">database.sql</code> file.
    The installer will not run again — it's locked by <code style="color:#f59e0b">app/config/.installed</code>.
  </div>

  <a href="<?= url('/login') ?>" class="btn-install" style="display:inline-flex;align-items:center;gap:.5rem;font-size:1rem;padding:.8rem 2rem;text-decoration:none">
    <i class="bi bi-box-arrow-in-right"></i>
    Go to Login
  </a>

  <div style="margin-top:1rem;font-size:.75rem;color:#1e293b">
    <a href="<?= url('/docs') ?>" style="color:#334155;text-decoration:none">Read the documentation</a>
  </div>
</div>
