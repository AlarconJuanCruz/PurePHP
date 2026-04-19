<?php /* views/notas/index.php */
$estadoColors = ['publicado'=>'success','borrador'=>'secondary','archivado'=>'warning'];
?>

<?php if (!$dbConnected): ?>
<div class="alert alert-warning mb-3" style="font-size:.84rem"><i class="bi bi-database-exclamation me-2"></i><?= e(__('notas.db_warning')) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <p class="text-secondary mb-0" style="font-size:.83rem"><?= e(__('notas.subtitle_ph')) ?></p>
  <div class="d-flex gap-2">
    <a href="<?= url('/portal') ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
      <i class="bi bi-globe2 me-1"></i><?= e(__('nav.portal')) ?>
    </a>
    <a href="<?= url('/notas/crear') ?>" class="btn btn-sm btn-primary">
      <i class="bi bi-plus-circle me-1"></i><?= e(__('notas.new')) ?>
    </a>
  </div>
</div>

<?php
$total     = count($notas);
$published = count(array_filter($notas, fn($n) => $n['estado'] === 'publicado'));
$draft     = count(array_filter($notas, fn($n) => $n['estado'] === 'borrador'));
$archived  = count(array_filter($notas, fn($n) => $n['estado'] === 'archivado'));
?>
<div class="row g-3 mb-4">
  <?php foreach ([
    [__('dashboard.total_notas'), $total,     'newspaper',     'primary'],
    [__('dashboard.published'),   $published, 'check-circle',  'success'],
    [__('dashboard.drafts'),      $draft,     'pencil-square', 'secondary'],
    [__('notas.archived_label'),  $archived,  'archive',       'warning'],
  ] as [$label, $val, $icon, $color]): ?>
  <div class="col-6 col-xl-3">
    <div class="fw-card d-flex align-items-center gap-3 py-3">
      <div class="stat-icon bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis flex-shrink-0 " style="border-radius:9px"><i class="bi bi-<?= e($icon) ?>"></i></div>
      <div>
        <div class="text-white fw-bold" style="font-size:1.25rem;line-height:1"><?= e($val) ?></div>
        <div style="font-size:.73rem;color:#64748b"><?= e($label) ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="fw-card">
  <div class="table-responsive">
    <table id="notasTable" class="table table-dark table-hover align-middle" style="font-size:.83rem;width:100%">
      <thead><tr style="border-color:rgba(255,255,255,.06)">
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_title')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_category')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_status')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_author')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_date')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('notas.col_views')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em;text-align:right"><?= e(__('notas.col_actions')) ?></th>
      </tr></thead>
      <tbody>
        <?php foreach ($notas as $nota):
          $sc = $estadoColors[$nota['estado']] ?? 'secondary';
        ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <?php if ($nota['imagen_portada']): ?>
              <img src="<?= url($nota['imagen_portada']) ?>" style="width:36px;height:36px;border-radius:5px;object-fit:cover;flex-shrink:0" alt="">
              <?php else: ?>
              <div style="width:36px;height:36px;border-radius:5px;background:rgba(124,58,237,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="bi bi-newspaper text-secondary" style="font-size:.8rem"></i></div>
              <?php endif; ?>
              <div>
                <div class="text-white" style="font-size:.82rem;font-weight:600;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                  <?php if ($nota['destacada']): ?><i class="bi bi-star-fill text-warning me-1" style="font-size:.7rem"></i><?php endif; ?>
                  <?= e((string)$nota['titulo']) ?>
                </div>
                <?php if ($nota['subtitulo']): ?>
                <div class="text-secondary" style="font-size:.73rem;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e((string)$nota['subtitulo']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td>
            <?php if ($nota['cat_nombre']): ?>
            <span class="badge bg-<?= e($nota['cat_color']??'secondary') ?>-subtle text-<?= e($nota['cat_color']??'secondary') ?>-emphasis border border-<?= e($nota['cat_color']??'secondary') ?>-subtle"><?= e($nota['cat_nombre']) ?></span>
            <?php else: ?>
            <span class="text-secondary" style="font-size:.78rem"><?= e(__('notas.no_category')) ?></span>
            <?php endif; ?>
          </td>
          <td><span class="badge bg-<?= e($sc) ?>-subtle text-<?= e($sc) ?>-emphasis border border-<?= e($sc) ?>-subtle"><?= e(__('notas.'.$nota['estado'])) ?></span></td>
          <td class="text-secondary"><?= e((string)$nota['autor_nombre']) ?></td>
          <td class="text-secondary"><?= e(localDate(substr((string)$nota['created_at'],0,10))) ?></td>
          <td class="text-secondary"><?= number_format((int)$nota['views']) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <?php if ($nota['estado'] === 'publicado'): ?>
              <a href="<?= url('/portal/nota/'.(string)$nota['slug']) ?>" class="btn btn-sm" target="_blank"
                 style="background:rgba(6,182,212,.07);border:1px solid rgba(6,182,212,.18);color:#67e8f9;padding:.22rem .44rem"
                 data-bs-toggle="tooltip" title="<?= e(__('notas.view_public')) ?>">
                <i class="bi bi-eye" style="font-size:.78rem"></i>
              </a>
              <?php endif; ?>
              <a href="<?= url('/notas/'.(int)$nota['id'].'/edit') ?>" class="btn btn-sm"
                 style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.22rem .44rem"
                 data-bs-toggle="tooltip" title="<?= e(__('common.edit')) ?>">
                <i class="bi bi-pencil" style="font-size:.78rem"></i>
              </a>
              <button class="btn btn-sm btn-delete-nota"
                      style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.22rem .44rem"
                      data-bs-toggle="tooltip" title="<?= e(__('common.delete')) ?>"
                      data-id="<?= (int)$nota['id'] ?>"
                      data-title="<?= e((string)$nota['titulo']) ?>">
                <i class="bi bi-trash" style="font-size:.78rem"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Delete modal -->
<div class="modal fade" id="modalDeleteNota" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <form id="deleteNotaForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center py-4 px-3">
          <div style="width:50px;height:50px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.35rem;color:#ef4444"><i class="bi bi-newspaper"></i></div>
          <h6 class="text-white mb-1"><?= e(__('notas.confirm_delete')) ?></h6>
          <p id="deleteNotaTitle" style="font-size:.82rem;color:#a78bfa;margin:.25rem 0 .75rem;font-weight:600"></p>
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i><?= e(__('common.yes_delete')) ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  $('#notasTable').DataTable({
    pageLength: 10, lengthMenu: [5,10,25,50], order: [[4,'desc']],
    columnDefs: [{orderable:false, targets:[6]}],
    language: {
      searchPlaceholder: <?= json_encode(__('notas.dt_search')) ?>,
      lengthMenu: <?= json_encode(__('notas.dt_show')) ?>,
      info: <?= json_encode(__('notas.dt_info')) ?>,
      paginate: {previous:'<i class="bi bi-chevron-left"></i>',next:'<i class="bi bi-chevron-right"></i>'}
    },
    drawCallback: function(){ document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){bootstrap.Tooltip.getOrCreateInstance(el)}); }
  });
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){bootstrap.Tooltip.getOrCreateInstance(el)});

  document.querySelectorAll('.btn-delete-nota').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.getElementById('deleteNotaForm').action = '<?= url('/notas') ?>/' + btn.dataset.id + '/delete';
      document.getElementById('deleteNotaTitle').textContent = btn.dataset.title;
      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDeleteNota')).show();
    });
  });
});
</script>
<?php View::end(); ?>
