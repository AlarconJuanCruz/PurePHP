<?php /* views/install/index.php — Step 1 */ ?>
<div class="install-card">
  <h2><?= e(__('install.s1_title')) ?></h2>
  <p class="subtitle"><?= e(__('install.s1_sub')) ?></p>

  <div style="margin-bottom:1.5rem">
    <?php foreach ($checks as $check): ?>
    <div class="check-row">
      <span class="label"><?= e($check['label']) ?></span>
      <div class="d-flex align-items-center gap-2">
        <span class="val <?= $check['ok'] ? 'check-ok' : 'check-fail' ?>"><?= e($check['value']) ?></span>
        <i class="bi bi-<?= $check['ok'] ? 'check-circle-fill check-ok' : 'x-circle-fill check-fail' ?>"></i>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if ($canProceed): ?>
  <div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:.7rem .9rem;font-size:.82rem;color:#4ade80;margin-bottom:1.5rem">
    <i class="bi bi-check-circle-fill me-2"></i><?= e(__('install.all_ok')) ?>
  </div>
  <div class="d-flex justify-content-end">
    <a href="<?= url('/install/database') ?>" class="btn-install">
      <?= e(__('common.continue')) ?> <i class="bi bi-arrow-right ms-1"></i>
    </a>
  </div>
  <?php else: ?>
  <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:.7rem .9rem;font-size:.82rem;color:#f87171;margin-bottom:1.5rem">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e(__('install.fix_issues')) ?>
  </div>
  <div class="d-flex justify-content-end">
    <button class="btn-install" disabled><?= e(__('common.continue')) ?> <i class="bi bi-arrow-right ms-1"></i></button>
  </div>
  <?php endif; ?>
</div>
