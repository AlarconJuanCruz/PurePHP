<?php /* views/settings/index.php */
$s = $settings;   // short alias
$get = fn(string $k, string $d='') => (string)($s[$k] ?? $d);
?>

<p class="text-secondary mb-4" style="font-size:.84rem"><?= e(__('settings.subtitle')) ?></p>

<form action="<?= url('/settings') ?>" method="POST" id="settingsForm">
  <?= csrf_field() ?>
  <div class="row g-3">

    <!-- ── Left column ──────────────────────────────────────────── -->
    <div class="col-lg-8">

      <!-- Identity -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.9rem;border-left:3px solid var(--fw-accent);padding-left:.6rem">
          <i class="bi bi-globe2 me-2 text-primary"></i><?= e(__('settings.section_identity')) ?>
        </h6>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.site_name')) ?></label>
            <input type="text" name="site_name" class="form-control"
                   value="<?= e($get('site_name','Pure PHP News')) ?>"
                   placeholder="Mi Portal de Noticias">
            <div class="form-text" style="font-size:.72rem;color:#334155"><?= e(__('settings.site_name_hint')) ?></div>
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.site_tagline')) ?></label>
            <input type="text" name="site_tagline" class="form-control"
                   value="<?= e($get('site_tagline')) ?>"
                   placeholder="Las noticias más importantes">
            <div class="form-text" style="font-size:.72rem;color:#334155"><?= e(__('settings.site_tagline_hint')) ?></div>
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.site_description')) ?></label>
            <textarea name="site_description" class="form-control" rows="3"
                      placeholder="Descripción para Google y redes sociales…" maxlength="160"><?= e($get('site_description')) ?></textarea>
            <div class="form-text" style="font-size:.72rem;color:#334155"><?= e(__('settings.site_desc_hint')) ?></div>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.primary_color')) ?></label>
            <div class="d-flex gap-2 align-items-center">
              <input type="color" name="primary_color" class="form-control form-control-color"
                     value="<?= e($get('primary_color','#dc2626')) ?>" style="width:48px;height:38px;padding:2px">
              <input type="text" id="colorHex" class="form-control"
                     value="<?= e($get('primary_color','#dc2626')) ?>" placeholder="#dc2626"
                     style="font-family:'JetBrains Mono',monospace;font-size:.82rem">
            </div>
            <div class="form-text" style="font-size:.72rem;color:#334155"><?= __('settings.primary_color_hint') ?></div>
          </div>
        </div>
      </div>

      <!-- Content settings -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.9rem;border-left:3px solid var(--fw-accent);padding-left:.6rem">
          <i class="bi bi-newspaper me-2 text-info"></i><?= e(__('settings.section_content')) ?>
        </h6>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.notas_per_page')) ?></label>
            <input type="number" name="notas_per_page" class="form-control"
                   value="<?= e($get('notas_per_page','12')) ?>" min="1" max="50">
          </div>
          <div class="col-md-4 d-flex align-items-center">
            <div class="form-check form-switch mt-3">
              <input class="form-check-input" type="checkbox" name="show_author" id="showAuthor"
                     value="1" <?= $get('show_author','1') === '1' ? 'checked' : '' ?>>
              <label class="form-check-label" for="showAuthor" style="font-size:.82rem;color:#94a3b8">
                <?= e(__('settings.show_author')) ?>
              </label>
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-center">
            <div class="form-check form-switch mt-3">
              <input class="form-check-input" type="checkbox" name="show_date" id="showDate"
                     value="1" <?= $get('show_date','1') === '1' ? 'checked' : '' ?>>
              <label class="form-check-label" for="showDate" style="font-size:.82rem;color:#94a3b8">
                <?= e(__('settings.show_date')) ?>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Files & assets -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.9rem;border-left:3px solid var(--fw-accent);padding-left:.6rem">
          <i class="bi bi-image me-2 text-success"></i><?= e(__('settings.section_files')) ?>
        </h6>
        <div class="row g-3">

          <!-- Favicon -->
          <div class="col-12">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.favicon')) ?></label>
            <div class="d-flex align-items-center gap-3">
              <div style="width:40px;height:40px;border:1px solid rgba(255,255,255,.1);border-radius:6px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.03)">
                <?php if (file_exists(ROOT_PATH.'/public/favicon.ico')): ?>
                <img src="<?= url('public/favicon.ico') ?>" style="width:28px;height:28px" alt="favicon">
                <?php else: ?>
                <i class="bi bi-image text-secondary" style="font-size:1.1rem"></i>
                <?php endif; ?>
              </div>
              <div style="font-size:.8rem;color:#64748b"><?= __('settings.favicon_hint') ?></div>
            </div>
          </div>

          <!-- Logo paths -->
          <div class="col-12">
            <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('settings.logo_header')) ?></label>
            <div style="font-size:.75rem;color:#475569;margin-bottom:.4rem"><?= __('settings.logo_hint') ?></div>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-1 mb-1"><span style="font-size:.73rem;color:#475569"><?= e(__('settings.logo_header_path')) ?></span></div>
                <input type="text" name="logo_header_path" class="form-control"
                       value="<?= e($get('logo_header_path')) ?>"
                       placeholder="public/logo.png"
                       style="font-family:'JetBrains Mono',monospace;font-size:.78rem">
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-1 mb-1"><span style="font-size:.73rem;color:#475569"><?= e(__('settings.logo_footer_path')) ?></span></div>
                <input type="text" name="logo_footer_path" class="form-control"
                       value="<?= e($get('logo_footer_path')) ?>"
                       placeholder="public/logo.png"
                       style="font-family:'JetBrains Mono',monospace;font-size:.78rem">
              </div>
            </div>
            <div class="form-text" style="font-size:.72rem;color:#334155"><?= __('settings.logo_path_hint') ?></div>
          </div>

        </div>
      </div>

    </div>

    <!-- ── Right column ──────────────────────────────────────────── -->
    <div class="col-lg-4">

      <!-- Save box -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.88rem"><?= e(__('common.save')) ?></h6>
        <!-- Live preview of site name -->
        <div class="mb-3 p-3" style="background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.18);border-radius:8px">
          <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#475569;margin-bottom:.35rem">
            <?= e(__('public.go_admin')) ?> preview
          </div>
          <div id="siteNamePreview" style="font-family:'Lora',Georgia,serif;font-weight:700;font-size:1.1rem;color:#f1f5f9">
            <?= e($get('site_name','Pure PHP News')) ?>
          </div>
          <div id="taglinePreview" style="font-size:.76rem;color:#64748b;margin-top:.2rem">
            <?= e($get('site_tagline','')) ?>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-check2 me-1"></i><?= e(__('common.save_changes')) ?>
        </button>
        <a href="<?= url('/portal') ?>" class="btn btn-outline-secondary w-100 mt-2" target="_blank" style="font-size:.82rem">
          <i class="bi bi-eye me-1"></i><?= e(__('nav.portal')) ?>
        </a>
      </div>

      <!-- Advanced / code hints -->
      <div class="fw-card">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.88rem">
          <i class="bi bi-code-slash me-1 text-secondary"></i><?= e(__('settings.section_code')) ?>
        </h6>
        <p style="font-size:.79rem;color:#475569;margin-bottom:.75rem"><?= e(__('settings.code_hint_title')) ?></p>
        <?php foreach (Lang::arr('settings.code_files') as [$file, $desc]): ?>
        <div class="mb-2 pb-2" style="border-bottom:1px solid rgba(255,255,255,.05)">
          <code style="font-size:.72rem;color:#c084fc;background:rgba(124,58,237,.1);padding:.1rem .35rem;border-radius:4px;display:block;margin-bottom:.2rem"><?= e($file) ?></code>
          <span style="font-size:.76rem;color:#475569"><?= e($desc) ?></span>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</form>

<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Sync color picker ↔ text input
  var colorPicker = document.querySelector('input[name="primary_color"]');
  var colorHex    = document.getElementById('colorHex');
  if (colorPicker && colorHex) {
    colorPicker.addEventListener('input', function() { colorHex.value = this.value; });
    colorHex.addEventListener('input', function() {
      if (/^#[0-9a-fA-F]{3,6}$/.test(this.value)) colorPicker.value = this.value;
    });
  }

  // Live preview of site name / tagline
  var nameInput    = document.querySelector('input[name="site_name"]');
  var tagInput     = document.querySelector('input[name="site_tagline"]');
  var namePrev     = document.getElementById('siteNamePreview');
  var tagPrev      = document.getElementById('taglinePreview');
  if (nameInput && namePrev) {
    nameInput.addEventListener('input', function() { namePrev.textContent = this.value || 'Pure PHP News'; });
  }
  if (tagInput && tagPrev) {
    tagInput.addEventListener('input', function() { tagPrev.textContent = this.value; });
  }
});
</script>
<?php View::end(); ?>
