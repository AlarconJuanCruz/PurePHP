<?php /* views/categorias/index.php */
$colorOptions = ['primary','secondary','success','danger','warning','info'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <p class="text-secondary mb-0" style="font-size:.83rem"><?= e(__('notas.subtitle_ph')) ?></p>
  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNewCat">
    <i class="bi bi-plus-circle me-1"></i><?= e(__('categorias.new')) ?>
  </button>
</div>

<div class="fw-card">
  <div class="table-responsive">
    <table class="table table-dark table-hover align-middle mb-0" style="font-size:.84rem">
      <thead><tr style="border-color:rgba(255,255,255,.06)">
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('categorias.col_name')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('categorias.col_slug')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('categorias.col_color')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('categorias.col_notas')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em"><?= e(__('categorias.col_order')) ?></th>
        <th style="color:#334155;font-size:.67rem;font-weight:700;letter-spacing:.07em;text-align:right"><?= e(__('categorias.col_actions')) ?></th>
      </tr></thead>
      <tbody>
        <?php foreach ($cats as $cat): ?>
        <tr>
          <td>
            <div class="text-white fw-semibold"><?= e((string)$cat['nombre']) ?></div>
            <?php if ($cat['descripcion']): ?>
            <div class="text-secondary" style="font-size:.74rem"><?= e((string)$cat['descripcion']) ?></div>
            <?php endif; ?>
          </td>
          <td><code class="text-secondary" style="font-size:.76rem"><?= e((string)$cat['slug']) ?></code></td>
          <td><span class="badge bg-<?= e($cat['color']) ?>-subtle text-<?= e($cat['color']) ?>-emphasis border border-<?= e($cat['color']) ?>-subtle"><?= ucfirst($cat['color']) ?></span></td>
          <td>
            <a href="<?= url('/notas') ?>" class="text-white" style="font-size:.82rem;text-decoration:none">
              <?= (int)$cat['nota_count'] ?> <span class="text-secondary" style="font-size:.74rem"><?= e(__('nav.notas')) ?></span>
            </a>
          </td>
          <td class="text-secondary"><?= (int)$cat['orden'] ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm btn-edit-cat"
                      style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.22rem .44rem"
                      data-id="<?= (int)$cat['id'] ?>"
                      data-nombre="<?= e((string)$cat['nombre']) ?>"
                      data-desc="<?= e((string)($cat['descripcion']??'')) ?>"
                      data-color="<?= e($cat['color']) ?>"
                      data-orden="<?= (int)$cat['orden'] ?>">
                <i class="bi bi-pencil" style="font-size:.78rem"></i>
              </button>
              <button class="btn btn-sm btn-delete-cat"
                      style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.22rem .44rem"
                      data-id="<?= (int)$cat['id'] ?>"
                      data-nombre="<?= e((string)$cat['nombre']) ?>">
                <i class="bi bi-trash" style="font-size:.78rem"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($cats)): ?>
        <tr><td colspan="6" class="text-center text-secondary py-4"><?= e(__('common.no_results')) ?></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: New -->
<div class="modal fade" id="modalNewCat" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form action="<?= url('/categorias') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-tag text-primary me-2"></i><?= e(__('categorias.new')) ?></h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('categorias.nombre')) ?></label>
              <input type="text" name="nombre" class="form-control" placeholder="<?= e(__('categorias.nombre_ph')) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.description')) ?></label>
              <input type="text" name="descripcion" class="form-control" placeholder="<?= e(__('categorias.desc_ph')) ?>">
            </div>
            <div class="col-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.color')) ?></label>
              <select name="color" class="form-select">
                <?php foreach ($colorOptions as $co): ?>
                <option value="<?= e($co) ?>"><?= ucfirst($co) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.order')) ?> <span style="color:#475569"><?= e(__('categorias.order_hint')) ?></span></label>
              <input type="number" name="orden" class="form-control" value="0" min="0">
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i><?= e(__('categorias.new')) ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Edit -->
<div class="modal fade" id="modalEditCat" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form id="editCatForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i><?= e(__('categorias.edit')) ?></h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('categorias.nombre')) ?></label>
              <input type="text" name="nombre" id="editCatNombre" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.description')) ?></label>
              <input type="text" name="descripcion" id="editCatDesc" class="form-control">
            </div>
            <div class="col-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.color')) ?></label>
              <select name="color" id="editCatColor" class="form-select">
                <?php foreach ($colorOptions as $co): ?>
                <option value="<?= e($co) ?>"><?= ucfirst($co) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.order')) ?></label>
              <input type="number" name="orden" id="editCatOrden" class="form-control" min="0">
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i><?= e(__('common.save_changes')) ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Delete -->
<div class="modal fade" id="modalDeleteCat" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <form id="deleteCatForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center py-4 px-3">
          <div style="width:50px;height:50px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.35rem;color:#ef4444"><i class="bi bi-tag"></i></div>
          <h6 class="text-white mb-1"><?= e(__('categorias.confirm_delete')) ?></h6>
          <p id="deleteCatNombre" style="font-size:.82rem;color:#a78bfa;margin:.25rem 0 1.4rem;font-weight:600"></p>
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
  document.querySelectorAll('.btn-edit-cat').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('editCatForm').action = '<?= url('/categorias') ?>/' + btn.dataset.id;
      document.getElementById('editCatNombre').value = btn.dataset.nombre;
      document.getElementById('editCatDesc').value   = btn.dataset.desc;
      document.getElementById('editCatColor').value  = btn.dataset.color;
      document.getElementById('editCatOrden').value  = btn.dataset.orden;
      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditCat')).show();
    });
  });
  document.querySelectorAll('.btn-delete-cat').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.getElementById('deleteCatForm').action = '<?= url('/categorias') ?>/' + btn.dataset.id + '/delete';
      document.getElementById('deleteCatNombre').textContent = btn.dataset.nombre;
      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDeleteCat')).show();
    });
  });
});
</script>
<?php View::end(); ?>
