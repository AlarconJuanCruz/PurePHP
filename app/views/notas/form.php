<?php /* views/notas/form.php */
$n         = $nota ?? [];
$titulo    = (string)($n['titulo']    ?? '');
$subtitulo = (string)($n['subtitulo'] ?? '');
$cuerpo    = (string)($n['cuerpo']    ?? '');
$extracto  = (string)($n['extracto'] ?? '');
$estado    = (string)($n['estado']    ?? 'borrador');
$catId     = (int)($n['categoria_id'] ?? 0);
$destacada = !empty($n['destacada']);
$notaId    = isset($n['id']) ? (int)$n['id'] : 0;
$imgPortada= (string)($n['imagen_portada'] ?? '');
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger mb-3 py-2" style="font-size:.84rem">
  <?php foreach ($errors as $err): ?><div><i class="bi bi-exclamation-circle me-1"></i><?= e($err) ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<form action="<?= $isEdit ? url('/notas/'.$notaId) : url('/notas') ?>" method="POST"
      enctype="multipart/form-data" id="notaForm">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?>
  <input type="hidden" name="_nota_id" value="<?= $notaId ?>">
  <?php endif; ?>

  <div class="row g-3">
    <!-- Left col: main content -->
    <div class="col-lg-8">

      <!-- Title -->
      <div class="fw-card mb-3">
        <div class="mb-3">
          <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('notas.titulo')) ?> <span class="text-danger">*</span></label>
          <input type="text" name="titulo" class="form-control" style="font-size:1.1rem;font-weight:600"
                 placeholder="<?= e(__('notas.titulo_ph')) ?>" value="<?= e($titulo) ?>" required>
        </div>
        <div>
          <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('notas.subtitulo')) ?></label>
          <input type="text" name="subtitulo" class="form-control" placeholder="<?= e(__('notas.subtitulo_ph')) ?>" value="<?= e($subtitulo) ?>">
        </div>
      </div>

      <!-- Body -->
      <div class="fw-card mb-3">
        <label class="form-label d-flex justify-content-between" style="font-size:.79rem;color:#94a3b8;font-weight:600">
          <?= e(__('notas.cuerpo')) ?>
          <span style="font-size:.7rem;color:#475569"><?= e(__('notas.html_hint')) ?></span>
        </label>
        <!-- Toolbar -->
        <div class="d-flex flex-wrap gap-1 mb-2 pb-2" style="border-bottom:1px solid rgba(255,255,255,.06)">
          <?php
          $tools = [
            ['bold',       'bi-type-bold',      'Bold',        '<strong>|</strong>'],
            ['italic',     'bi-type-italic',     'Italic',      '<em>|</em>'],
            ['h2',         'bi-type-h2',         'H2',          '<h2>|</h2>'],
            ['h3',         'bi-type-h3',         'H3',          '<h3>|</h3>'],
            ['link',       'bi-link-45deg',      'Link',        '<a href="">|</a>'],
            ['ul',         'bi-list-ul',         'List',        '<ul>\n  <li>|</li>\n</ul>'],
            ['p',          'bi-paragraph',       'Paragraph',   '<p>|</p>'],
            ['blockquote', 'bi-blockquote-left', 'Quote',       '<blockquote>|</blockquote>'],
          ];
          foreach ($tools as [$id, $icon, $label, $wrap]):
          ?>
          <button type="button" class="btn btn-sm" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);color:#94a3b8;padding:.2rem .5rem;font-size:.75rem"
                  data-wrap="<?= e(json_encode($wrap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)) ?>"
                  data-bs-toggle="tooltip" title="<?= e($label) ?>">
            <i class="bi <?= e($icon) ?>"></i>
          </button>
          <?php endforeach; ?>
        </div>
        <textarea name="cuerpo" id="cuerpoEditor" class="form-control nota-editor"
                  placeholder="<?= e(__('notas.body_ph')) ?>"><?= htmlspecialchars($cuerpo, ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>

      <!-- Extracto -->
      <div class="fw-card mb-3">
        <label class="form-label" style="font-size:.79rem;color:#94a3b8;font-weight:600"><?= e(__('notas.extracto')) ?></label>
        <textarea name="extracto" class="form-control" rows="3" placeholder="<?= e(__('notas.extracto_ph')) ?>"><?= e($extracto) ?></textarea>
      </div>

      <!-- Gallery (edit only) -->
      <?php if ($isEdit): ?>
      <div class="fw-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h6 class="mb-0 fw-semibold text-white" style="font-size:.88rem"><?= e(__('notas.gallery_title')) ?></h6>
            <small class="text-secondary"><?= e(__('notas.gallery_hint')) ?></small>
          </div>
          <label class="btn btn-sm btn-outline-primary" style="cursor:pointer">
            <i class="bi bi-plus me-1"></i><?= e(__('notas.add_image')) ?>
            <input type="file" id="galleryInput" accept="image/*" style="display:none">
          </label>
        </div>
        <div id="galleryGrid" class="row g-2">
          <?php foreach (($imagenes??[]) as $img): ?>
          <div class="col-4 col-md-3" id="gimg-<?= (int)$img['id'] ?>">
            <div style="position:relative;border-radius:8px;overflow:hidden;aspect-ratio:4/3">
              <img src="<?= url($img['archivo']) ?>" style="width:100%;height:100%;object-fit:cover" alt="<?= e((string)($img['titulo']??'')) ?>">
              <div style="position:absolute;inset:0;background:rgba(0,0,0,.45);opacity:0;transition:.2s;display:flex;align-items:center;justify-content:center;gap:.5rem" class="img-overlay">
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteGalleryImg(<?= (int)$img['id'] ?>)"
                        style="padding:.2rem .4rem;font-size:.75rem"><i class="bi bi-trash"></i></button>
              </div>
            </div>
            <?php if ($img['titulo']): ?><div style="font-size:.7rem;color:#475569;margin-top:.25rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($img['titulo']) ?></div><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <div id="galleryUploadStatus" class="mt-2" style="font-size:.78rem;display:none"></div>
      </div>
      <?php endif; ?>

    </div>

    <!-- Right col: meta -->
    <div class="col-lg-4">

      <!-- Publish box -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.88rem"><?= e(__('notas.estado')) ?></h6>
        <div class="mb-3">
          <select name="estado" class="form-select" id="estadoSelect">
            <option value="borrador"   <?= $estado==='borrador'  ?'selected':'' ?>><?= e(__('notas.borrador')) ?></option>
            <option value="publicado"  <?= $estado==='publicado' ?'selected':'' ?>><?= e(__('notas.publicado')) ?></option>
            <option value="archivado"  <?= $estado==='archivado' ?'selected':'' ?>><?= e(__('notas.archivado')) ?></option>
          </select>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="destacada" id="destacada" value="1" <?= $destacada ? 'checked' : '' ?>>
          <label class="form-check-label" for="destacada" style="font-size:.82rem;color:#94a3b8"><?= e(__('notas.destacada')) ?></label>
          <div style="font-size:.72rem;color:#334155;margin-top:.15rem"><?= e(__('notas.destacada_hint')) ?></div>
        </div>
        <div class="d-flex gap-2 mt-3">
          <button type="submit" class="btn btn-primary flex-grow-1" id="btnPublish">
            <i class="bi bi-check2 me-1"></i>
            <span id="btnPublishLabel"><?= e($estado === 'publicado' ? __('common.save_changes') : __('notas.save_draft')) ?></span>
          </button>
          <?php if ($isEdit && $nota['estado'] === 'publicado'): ?>
          <a href="<?= url('/portal/nota/'.(string)($nota['slug']??'')) ?>" class="btn btn-outline-info btn-sm" target="_blank"
             data-bs-toggle="tooltip" title="<?= e(__('notas.view_public')) ?>">
            <i class="bi bi-eye"></i>
          </a>
          <?php endif; ?>
        </div>
        <a href="<?= url('/notas') ?>" class="btn btn-outline-secondary w-100 mt-2" style="font-size:.82rem">
          <i class="bi bi-arrow-left me-1"></i><?= e(__('common.back')) ?>
        </a>
      </div>

      <!-- Category -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.88rem"><?= e(__('notas.categoria')) ?></h6>
        <select name="categoria_id" class="form-select">
          <option value=""><?= e(__('notas.no_category')) ?></option>
          <?php foreach ($categorias as $cat): ?>
          <option value="<?= (int)$cat['id'] ?>" <?= $catId === (int)$cat['id'] ? 'selected' : '' ?>>
            <?= e((string)$cat['nombre']) ?>
          </option>
          <?php endforeach; ?>
        </select>
        <a href="<?= url('/categorias') ?>" class="d-block mt-2" style="font-size:.75rem;color:#475569;text-decoration:none">
          <i class="bi bi-plus-circle me-1"></i><?= e(__('categorias.new')) ?>
        </a>
      </div>

      <!-- Cover image -->
      <div class="fw-card mb-3">
        <h6 class="fw-semibold text-white mb-3" style="font-size:.88rem"><?= e(__('notas.imagen_portada')) ?></h6>
        <?php if ($imgPortada): ?>
        <img src="<?= url($imgPortada) ?>" class="w-100 mb-2" style="border-radius:8px;object-fit:cover;max-height:160px" alt="">
        <div style="font-size:.72rem;color:#475569;margin-bottom:.5rem"><?= e(__('notas.cover_current')) ?></div>
        <?php endif; ?>
        <label class="form-label" style="font-size:.75rem;color:#64748b"><?= e(__('notas.cover_change')) ?></label>
        <input type="file" name="imagen_portada" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" id="coverInput">
        <div id="coverPreview" class="mt-2" style="display:none">
          <img id="coverPreviewImg" src="" class="w-100" style="border-radius:8px;max-height:120px;object-fit:cover">
        </div>
      </div>

    </div>
  </div>
</form>

<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toolbar buttons - read wrap template from data-wrap attribute
  document.querySelectorAll('[data-wrap]').forEach(function(btn) {
    btn.addEventListener('click', function() {
      insertWrap(JSON.parse(btn.dataset.wrap));
    });
  });

  // Tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){ bootstrap.Tooltip.getOrCreateInstance(el); });

  // Publish button label
  document.getElementById('estadoSelect').addEventListener('change', function() {
    var lbl = document.getElementById('btnPublishLabel');
    lbl.textContent = this.value === 'publicado'
      ? <?= json_encode(__('notas.save_publish')) ?>
      : <?= json_encode(__('notas.save_draft')) ?>;
  });

  // Cover image preview
  document.getElementById('coverInput').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('coverPreviewImg').src = e.target.result;
      document.getElementById('coverPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  });

  // Hover on gallery images
  document.querySelectorAll('.img-overlay').forEach(function(ov) {
    ov.parentElement.addEventListener('mouseenter', function(){ ov.style.opacity='1'; });
    ov.parentElement.addEventListener('mouseleave', function(){ ov.style.opacity='0'; });
  });

  <?php if ($isEdit): ?>
  // Gallery upload
  document.getElementById('galleryInput').addEventListener('change', function() {
    if (!this.files[0]) return;
    var fd = new FormData();
    fd.append('gallery_image', this.files[0]);
    var status = document.getElementById('galleryUploadStatus');
    status.style.display = 'block';
    status.style.color = '#94a3b8';
    status.textContent = <?= json_encode(__('common.loading')) ?>;

    fetch(<?= json_encode(url('/notas/'.$notaId.'/upload-image')) ?>, {method:'POST',body:fd})
      .then(function(r){ return r.json(); })
      .then(function(data) {
        if (data.ok) {
          var grid = document.getElementById('galleryGrid');
          var col  = document.createElement('div');
          col.className = 'col-4 col-md-3';
          col.innerHTML = '<div style="position:relative;border-radius:8px;overflow:hidden;aspect-ratio:4/3"><img src="'+data.url+'" style="width:100%;height:100%;object-fit:cover"></div>';
          grid.appendChild(col);
          status.style.color = '#22c55e';
          status.textContent = '✓ ' + <?= json_encode(__('common.done')) ?>;
          setTimeout(function(){ status.style.display='none'; }, 2000);
        } else {
          status.style.color = '#ef4444';
          status.textContent = data.message || <?= json_encode(__('notas.upload_fail')) ?>;
        }
      })
      .catch(function() {
        status.style.color = '#ef4444';
        status.textContent = <?= json_encode(__('notas.upload_fail')) ?>;
      });
    this.value = '';
  });
  <?php endif; ?>
});

function insertWrap(tpl) {
  var ta = document.getElementById('cuerpoEditor');
  var s  = ta.selectionStart, e = ta.selectionEnd;
  var sel = ta.value.substring(s, e) || '';
  var parts = tpl.split('|');
  var before = parts[0] || '';
  var after  = parts[1] || '';
  var newVal = ta.value.substring(0,s) + before + sel + after + ta.value.substring(e);
  ta.value = newVal;
  ta.selectionStart = s + before.length;
  ta.selectionEnd   = s + before.length + sel.length;
  ta.focus();
}

function deleteGalleryImg(imgId) {
  if (!confirm(<?= json_encode(__('notas.delete_image')) ?>)) return;
  var fd = new FormData();
  fd.append('_csrf_token', <?= json_encode(csrf_token()) ?>);
  fetch(<?= json_encode(url('/notas/0/delete-image')) ?>.replace('/0/', '/'+imgId+'/'), {method:'POST',body:fd})
    .then(function(r){ return r.json(); })
    .then(function(data){ if (data.ok) { var el=document.getElementById('gimg-'+imgId); if(el)el.remove(); } });
}
</script>
<?php View::end(); ?>
