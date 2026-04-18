<?php /* views/auth/login.php */ ?>

<div class="auth-card">

  <!-- Brand -->
  <a href="<?= url('/') ?>" class="auth-brand">
    <span class="dot"></span>
    Pure PHP
  </a>

  <h2 style="font-size:1.2rem;font-weight:700;color:#f1f5f9;margin-bottom:.25rem;text-align:center">Welcome back</h2>
  <p style="font-size:.82rem;color:#475569;text-align:center;margin-bottom:1.5rem">Sign in to your account</p>

  <!-- Error banner -->
  <?php if (!empty($error)): ?>
  <div class="error-banner">
    <i class="bi bi-exclamation-circle me-2"></i><?= e($error) ?>
  </div>
  <?php endif; ?>

  <!-- Demo credentials notice -->
  <div style="background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:8px;padding:.6rem .85rem;font-size:.78rem;color:#a78bfa;margin-bottom:1.25rem">
    <i class="bi bi-info-circle me-1"></i>
    Demo: <strong>admin@demo.com</strong> / <strong>password</strong>
  </div>

  <form action="<?= url('/login') ?>" method="POST" id="loginForm" novalidate>
    <?= csrf_field() ?>

    <div class="mb-3">
      <label class="form-label" for="email">Email address</label>
      <div class="input-icon-wrapper">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= e($_POST['email'] ?? '') ?>"
               placeholder="you@example.com" autocomplete="email" required>
      </div>
    </div>

    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <label class="form-label mb-0" for="password">Password</label>
        <a href="#" style="font-size:.75rem;color:#7c3aed;text-decoration:none">Forgot?</a>
      </div>
      <div class="input-icon-wrapper">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" class="form-control" id="password" name="password"
               placeholder="••••••••" autocomplete="current-password" required>
        <button type="button" class="toggle-pwd" id="togglePwd">
          <i class="bi bi-eye" id="eyeIcon"></i>
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="form-check" style="margin:0">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember" style="font-size:.8rem;color:#64748b">Remember me</label>
      </div>
    </div>

    <button type="submit" class="btn-auth" id="submitBtn">
      <span id="submitText">Sign in</span>
      <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
    </button>
  </form>

  <div class="divider">or continue with</div>

  <div class="row g-2">
    <div class="col-6">
      <button class="w-100 d-flex align-items-center justify-content-center gap-2"
              style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:.6rem;cursor:pointer;color:#94a3b8;font-size:.82rem;font-weight:500;font-family:'Space Grotesk',sans-serif;transition:.15s"
              onmouseover="this.style.background='rgba(255,255,255,.07)'"
              onmouseout="this.style.background='rgba(255,255,255,.04)'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Google
      </button>
    </div>
    <div class="col-6">
      <button class="w-100 d-flex align-items-center justify-content-center gap-2"
              style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:.6rem;cursor:pointer;color:#94a3b8;font-size:.82rem;font-weight:500;font-family:'Space Grotesk',sans-serif;transition:.15s"
              onmouseover="this.style.background='rgba(255,255,255,.07)'"
              onmouseout="this.style.background='rgba(255,255,255,.04)'">
        <i class="bi bi-github" style="font-size:1rem;color:#f1f5f9"></i>
        GitHub
      </button>
    </div>
  </div>

  <p style="text-align:center;font-size:.78rem;color:#475569;margin-top:1.5rem;margin-bottom:0">
    Don't have an account?
    <a href="#" style="color:#7c3aed;text-decoration:none;font-weight:600">Sign up</a>
  </p>
</div>

<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // Toggle password visibility
  const toggleBtn = document.getElementById('togglePwd');
  const pwdInput  = document.getElementById('password');
  const eyeIcon   = document.getElementById('eyeIcon');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const isText = pwdInput.type === 'text';
      pwdInput.type = isText ? 'password' : 'text';
      eyeIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
  }

  // Loading state on submit
  const form       = document.getElementById('loginForm');
  const submitBtn  = document.getElementById('submitBtn');
  const submitText = document.getElementById('submitText');
  const spinner    = document.getElementById('submitSpinner');
  if (form) {
    form.addEventListener('submit', () => {
      submitBtn.disabled  = true;
      submitText.textContent = 'Signing in…';
      spinner.classList.remove('d-none');
    });
  }

});
</script>
<?php View::end(); ?>
