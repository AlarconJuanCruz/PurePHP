<?php /* views/install/account.php — Step 3 */ ?>
<div class="install-card">
  <h2><?= e(__('install.s3_title')) ?></h2>
  <p class="subtitle"><?= e(__('install.s3_sub')) ?></p>

  <?php if (!empty($errors)): ?>
  <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:.75rem .9rem;font-size:.82rem;color:#f87171;margin-bottom:1.25rem">
    <?php foreach ($errors as $err): ?>
    <div><i class="bi bi-exclamation-circle me-2"></i><?= e($err) ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form action="<?= url('/install/run') ?>" method="POST" id="accountForm">
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label"><?= e(__('install.admin_name')) ?></label>
        <input type="text" name="admin_name" class="form-control"
               value="<?= e((string)($_POST['admin_name'] ?? 'Administrator')) ?>"
               placeholder="John Doe" required>
      </div>
      <div class="col-12">
        <label class="form-label"><?= e(__('install.admin_email')) ?></label>
        <input type="email" name="admin_email" class="form-control"
               value="<?= e((string)($_POST['admin_email'] ?? '')) ?>"
               placeholder="admin@yourdomain.com" required>
      </div>
      <div class="col-12">
        <label class="form-label"><?= e(__('install.admin_pass')) ?></label>
        <div style="position:relative">
          <input type="password" name="admin_pass" id="adminPwd" class="form-control"
                 placeholder="<?= e(__('users.min_8')) ?>" required minlength="8"
                 oninput="updateStrength(this.value)">
          <button type="button" onclick="togglePwd()"
                  style="position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#475569;cursor:pointer;padding:0">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
        <div class="pwd-strength" id="pwdBar"></div>
        <div style="font-size:.72rem;margin-top:.3rem;display:none" id="pwdHint"></div>
      </div>
      <div class="col-12">
        <label class="form-label"><?= e(__('install.admin_pass_c')) ?></label>
        <input type="password" name="admin_pass_confirm" id="adminPwdConfirm" class="form-control"
               placeholder="<?= e(__('install.admin_pass_ph') ?? '••••••••') ?>"
               required oninput="checkMatch()">
        <div style="font-size:.72rem;margin-top:.3rem;display:none" id="matchMsg"></div>
      </div>
    </div>

    <!-- Admin note -->
    <div style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.18);border-radius:8px;padding:.65rem .9rem;font-size:.79rem;color:#a78bfa;margin:1.25rem 0 .75rem">
      <i class="bi bi-info-circle me-2"></i><?= __('install.admin_note') ?>
    </div>

    <!-- Demo data toggle -->
    <div style="background:rgba(6,182,212,.07);border:1px solid rgba(6,182,212,.18);border-radius:8px;padding:.85rem 1rem;margin-bottom:1.25rem">
      <div class="d-flex align-items-start gap-3">
        <div class="form-check form-switch mt-1 mb-0" style="flex-shrink:0">
          <input class="form-check-input" type="checkbox" name="include_demo" id="includeDemo"
                 role="switch" checked style="cursor:pointer;width:2.2rem;height:1.1rem">
        </div>
        <div>
          <label for="includeDemo" style="font-size:.84rem;font-weight:600;color:#67e8f9;cursor:pointer;display:block">
            <?= e(__('install.demo_data_label')) ?>
          </label>
          <div style="font-size:.76rem;color:#0891b2;margin-top:.2rem"><?= e(__('install.demo_data_hint')) ?></div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <a href="<?= url('/install/database') ?>" class="btn-back">
        <i class="bi bi-arrow-left"></i> <?= e(__('common.back')) ?>
      </a>
      <button type="submit" class="btn-install" id="installBtn">
        <span id="installText"><?= e(__('install.install_btn')) ?></span>
        <span class="spinner-border spinner-border-sm ms-2 d-none" id="installSpinner"></span>
      </button>
    </div>
  </form>
</div>

<?php View::start('scripts'); ?>
<script>
var pwdLevels = [
  {w:'0%',   bg:'transparent', label:''},
  {w:'25%',  bg:'#ef4444', label:<?= json_encode(__('install.pwd_weak')) ?>},
  {w:'50%',  bg:'#f59e0b', label:<?= json_encode(__('install.pwd_fair')) ?>},
  {w:'75%',  bg:'#3b82f6', label:<?= json_encode(__('install.pwd_good')) ?>},
  {w:'90%',  bg:'#22c55e', label:<?= json_encode(__('install.pwd_strong')) ?>},
  {w:'100%', bg:'#10b981', label:<?= json_encode(__('install.pwd_very_strong')) ?>},
];

function togglePwd() {
  var i = document.getElementById('adminPwd');
  var e = document.getElementById('eyeIcon');
  var t = i.type === 'text';
  i.type = t ? 'password' : 'text';
  e.className = t ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function updateStrength(val) {
  var bar  = document.getElementById('pwdBar');
  var hint = document.getElementById('pwdHint');
  var score = 0;
  if (val.length >= 8)           score++;
  if (val.length >= 12)          score++;
  if (/[A-Z]/.test(val))        score++;
  if (/[0-9]/.test(val))        score++;
  if (/[^a-zA-Z0-9]/.test(val)) score++;
  var l = pwdLevels[Math.min(score, 5)];
  bar.style.width      = l.w;
  bar.style.background = l.bg;
  hint.style.display   = l.label ? 'block' : 'none';
  hint.style.color     = l.bg;
  hint.textContent     = l.label;
}

function checkMatch() {
  var pwd  = document.getElementById('adminPwd').value;
  var conf = document.getElementById('adminPwdConfirm').value;
  var msg  = document.getElementById('matchMsg');
  if (!conf) { msg.style.display = 'none'; return; }
  msg.style.display = 'block';
  if (pwd === conf) {
    msg.style.color = '#22c55e';
    msg.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + <?= json_encode(__('install.pwd_match')) ?>;
  } else {
    msg.style.color = '#ef4444';
    msg.innerHTML = '<i class="bi bi-x-circle me-1"></i>' + <?= json_encode(__('install.pwd_no_match')) ?>;
  }
}

document.getElementById('accountForm').addEventListener('submit', function(e) {
  var pwd  = document.getElementById('adminPwd').value;
  var conf = document.getElementById('adminPwdConfirm').value;
  if (pwd !== conf) {
    e.preventDefault();
    alert(<?= json_encode(__('install.pwd_no_match')) ?>);
    return;
  }
  document.getElementById('installText').textContent = <?= json_encode(__('install.installing')) ?>;
  document.getElementById('installSpinner').classList.remove('d-none');
  document.getElementById('installBtn').disabled = true;
});
</script>
<?php View::end(); ?>
