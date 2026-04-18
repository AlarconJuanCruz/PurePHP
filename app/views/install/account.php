<?php /* views/install/account.php — Step 3 */ ?>

<div class="install-card">
  <h2>Create Admin Account</h2>
  <p class="subtitle">
    Set up your administrator credentials. You'll use these to log in after installation.
  </p>

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
        <label class="form-label">Full Name</label>
        <input type="text" name="admin_name" class="form-control"
               value="<?= e($_POST['admin_name'] ?? 'Administrator') ?>" placeholder="John Doe" required>
      </div>
      <div class="col-12">
        <label class="form-label">Email Address</label>
        <input type="email" name="admin_email" class="form-control"
               value="<?= e($_POST['admin_email'] ?? '') ?>" placeholder="admin@yourdomain.com" required>
      </div>
      <div class="col-12">
        <label class="form-label">Password</label>
        <div style="position:relative">
          <input type="password" name="admin_pass" id="adminPwd" class="form-control"
                 placeholder="Min. 8 characters" required minlength="8"
                 oninput="updateStrength(this.value)">
          <button type="button"
                  onclick="togglePwd()"
                  style="position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#475569;cursor:pointer;padding:0">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </button>
        </div>
        <div class="pwd-strength" id="pwdBar"></div>
        <div style="font-size:.72rem;color:#334155;margin-top:.3rem" id="pwdHint"></div>
      </div>
      <div class="col-12">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="admin_pass_confirm" id="adminPwdConfirm" class="form-control"
               placeholder="Repeat password" required oninput="checkMatch()">
        <div style="font-size:.72rem;margin-top:.3rem;display:none" id="matchMsg"></div>
      </div>
    </div>

    <div style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.18);border-radius:8px;padding:.65rem .9rem;font-size:.79rem;color:#a78bfa;margin:1.25rem 0">
      <i class="bi bi-info-circle me-2"></i>
      This account will have <strong>Administrator</strong> access with full permissions.
      Demo seed accounts are also imported from <code style="color:#c084fc">database.sql</code>.
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <a href="<?= url('/install/database') ?>" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back
      </a>
      <button type="submit" class="btn-install" id="installBtn">
        <span id="installText">Install Pure PHP</span>
        <span class="spinner-border spinner-border-sm ms-2 d-none" id="installSpinner"></span>
      </button>
    </div>
  </form>
</div>

<?php View::start('scripts'); ?>
<script>
function togglePwd() {
  const input = document.getElementById('adminPwd');
  const icon  = document.getElementById('eyeIcon');
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  icon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function updateStrength(val) {
  const bar  = document.getElementById('pwdBar');
  const hint = document.getElementById('pwdHint');
  let score = 0;
  if (val.length >= 8)  score++;
  if (val.length >= 12) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^a-zA-Z0-9]/.test(val)) score++;

  const levels = [
    { w: '0%',   bg: 'transparent', label: '' },
    { w: '25%',  bg: '#ef4444', label: 'Weak' },
    { w: '50%',  bg: '#f59e0b', label: 'Fair' },
    { w: '75%',  bg: '#3b82f6', label: 'Good' },
    { w: '90%',  bg: '#22c55e', label: 'Strong' },
    { w: '100%', bg: '#10b981', label: 'Very Strong' },
  ];
  const level = levels[Math.min(score, 5)];
  bar.style.width      = level.w;
  bar.style.background = level.bg;
  hint.style.display   = level.label ? 'block' : 'none';
  hint.style.color     = level.bg;
  hint.textContent     = level.label;
}

function checkMatch() {
  const pwd  = document.getElementById('adminPwd').value;
  const conf = document.getElementById('adminPwdConfirm').value;
  const msg  = document.getElementById('matchMsg');
  if (!conf) { msg.style.display = 'none'; return; }
  msg.style.display = 'block';
  if (pwd === conf) {
    msg.style.color = '#22c55e';
    msg.innerHTML   = '<i class="bi bi-check-circle me-1"></i>Passwords match';
  } else {
    msg.style.color = '#ef4444';
    msg.innerHTML   = '<i class="bi bi-x-circle me-1"></i>Passwords do not match';
  }
}

document.getElementById('accountForm').addEventListener('submit', function(e) {
  const pwd  = document.getElementById('adminPwd').value;
  const conf = document.getElementById('adminPwdConfirm').value;
  if (pwd !== conf) {
    e.preventDefault();
    alert('Passwords do not match.');
    return;
  }
  document.getElementById('installText').textContent = 'Installing…';
  document.getElementById('installSpinner').classList.remove('d-none');
  document.getElementById('installBtn').disabled = true;
});
</script>
<?php View::end(); ?>
