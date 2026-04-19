<?php /* views/users/index.php */
$statusColors = ['active' => 'success', 'inactive' => 'secondary', 'pending' => 'warning'];
$roleColors   = ['admin' => 'primary', 'developer' => 'info', 'designer' => 'success', 'viewer' => 'secondary'];
?>

<?php if (!$dbConnected): ?>
<div class="alert alert-warning mb-3" style="font-size:.85rem">
  <i class="bi bi-database-exclamation me-2"></i>
  <strong><?= e(__('users.db_warning')) ?></strong>
  <a href="<?= url('/docs') ?>" class="alert-link ms-1"><?= e(__('users.see_docs')) ?></a>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <p class="text-secondary mb-0" style="font-size:.84rem"><?= e(__('users.subtitle')) ?></p>
  <div class="d-flex gap-2">
    <a href="<?= url('/roles') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-shield-check me-1"></i><?= e(__('users.manage_roles')) ?>
    </a>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNewUser">
      <i class="bi bi-person-plus me-1"></i><?= e(__('users.add_user')) ?>
    </button>
  </div>
</div>

<?php
$total    = count($users);
$active   = count(array_filter($users, fn($u) => $u['status'] === 'active'));
$inactive = count(array_filter($users, fn($u) => $u['status'] === 'inactive'));
$pending  = count(array_filter($users, fn($u) => $u['status'] === 'pending'));
$strips   = [
  [__('users.total'),    $total,    'people-fill',     'primary',   ''],
  [__('users.active'),   $active,   'person-check',    'success',   $total > 0 ? round($active/$total*100).'%' : '0%'],
  [__('users.inactive'), $inactive, 'person-dash',     'secondary', ''],
  [__('users.pending'),  $pending,  'hourglass-split', 'warning',   __('users.awaiting')],
];
?>
<div class="row g-3 mb-4">
  <?php foreach ($strips as [$label, $val, $icon, $color, $sub]): ?>
  <div class="col-6 col-xl-3">
    <div class="fw-card d-flex align-items-center gap-3 py-3">
      <div class="stat-icon bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis flex-shrink-0">
        <i class="bi bi-<?= e($icon) ?>"></i>
      </div>
      <div>
        <div class="text-white fw-bold" style="font-size:1.3rem;line-height:1"><?= e($val) ?></div>
        <div style="font-size:.74rem;color:#64748b"><?= e($label) ?></div>
        <?php if ($sub): ?><div style="font-size:.68rem;color:#475569"><?= e($sub) ?></div><?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="fw-card">
  <div class="table-responsive">
    <table id="usersTable" class="table table-dark table-hover align-middle" style="font-size:.84rem;width:100%">
      <thead>
        <tr style="border-color:rgba(255,255,255,.06)">
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_user')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_email')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_role')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_status')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_joined')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_last_login')) ?></th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-align:right"><?= e(__('users.col_actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u):
          $sc   = $statusColors[$u['status']] ?? 'secondary';
          $rc   = $roleColors[$u['role_slug'] ?? ''] ?? 'secondary';
          $init = mb_strtoupper(mb_substr((string)$u['name'], 0, 1));
          $ll   = $u['last_login'] ? localDate($u['last_login']) : __('common.never');
        ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:30px;height:30px;border-radius:50%;background:rgba(124,58,237,.22);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#a78bfa;flex-shrink:0"><?= e($init) ?></div>
              <span class="text-white"><?= e((string)$u['name']) ?></span>
            </div>
          </td>
          <td class="text-secondary"><?= e((string)$u['email']) ?></td>
          <td><span class="badge bg-<?= e($rc) ?>-subtle text-<?= e($rc) ?>-emphasis border border-<?= e($rc) ?>-subtle"><?= e((string)($u['role_name'] ?? '')) ?></span></td>
          <td><span class="badge bg-<?= e($sc) ?>-subtle text-<?= e($sc) ?>-emphasis border border-<?= e($sc) ?>-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.38rem"></i><?= e(ucfirst((string)$u['status'])) ?></span></td>
          <td class="text-secondary"><?= e(localDate(substr((string)($u['created_at'] ?? ''), 0, 10))) ?></td>
          <td class="text-secondary"><?= e($ll) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm"
                      style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.22rem .44rem"
                      data-bs-toggle="tooltip" title="<?= e(__('common.edit')) ?>"
                      onclick="openEdit(<?= (int)$u['id'] ?>,<?= json_encode($u['name']) ?>,<?= json_encode($u['email']) ?>,<?= (int)$u['role_id'] ?>,<?= json_encode($u['status']) ?>)">
                <i class="bi bi-pencil" style="font-size:.78rem"></i>
              </button>
              <button class="btn btn-sm"
                      style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.22rem .44rem"
                      data-bs-toggle="tooltip" title="<?= e(__('common.delete')) ?>"
                      onclick="openDelete(<?= (int)$u['id'] ?>,<?= json_encode($u['name']) ?>)">
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

<!-- Modal: New User -->
<div class="modal fade" id="modalNewUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form action="<?= url('/users') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-person-plus text-primary me-2"></i><?= e(__('users.new_user')) ?></h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('users.full_name')) ?></label>
              <input type="text" name="name" class="form-control" placeholder="<?= e(__('users.full_name_ph')) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('users.col_email')) ?></label>
              <input type="email" name="email" class="form-control" placeholder="<?= e(__('users.email_ph')) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.role')) ?></label>
              <select name="role_id" class="form-select" required>
                <?php foreach ($roles as $r): ?>
                <option value="<?= (int)$r['id'] ?>"><?= e((string)$r['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.status')) ?></label>
              <select name="status" class="form-select">
                <option value="active"><?= e(__('common.active')) ?></option>
                <option value="pending" selected><?= e(__('common.pending')) ?></option>
                <option value="inactive"><?= e(__('common.inactive')) ?></option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('users.temp_password')) ?></label>
              <div class="input-group">
                <input type="password" name="password" id="newPwd" class="form-control" placeholder="<?= e(__('users.min_8')) ?>" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('newPwd',this)"><i class="bi bi-eye"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i><?= e(__('users.create_user')) ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form id="editForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i><?= e(__('users.edit_user')) ?></h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('users.full_name')) ?></label>
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('users.col_email')) ?></label>
              <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.role')) ?></label>
              <select name="role_id" id="editRole" class="form-select">
                <?php foreach ($roles as $r): ?>
                <option value="<?= (int)$r['id'] ?>"><?= e((string)$r['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8"><?= e(__('common.status')) ?></label>
              <select name="status" id="editStatus" class="form-select">
                <option value="active"><?= e(__('common.active')) ?></option>
                <option value="inactive"><?= e(__('common.inactive')) ?></option>
                <option value="pending"><?= e(__('common.pending')) ?></option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">
                <?= e(__('common.new_password')) ?> <span style="color:#475569"><?= e(__('users.password_hint')) ?></span>
              </label>
              <div class="input-group">
                <input type="password" name="password" id="editPwd" class="form-control" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('editPwd',this)"><i class="bi bi-eye"></i></button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i><?= e(__('users.save_changes')) ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Delete -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <form id="deleteForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center py-4 px-3">
          <div style="width:50px;height:50px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.35rem;color:#ef4444"><i class="bi bi-person-x-fill"></i></div>
          <h6 class="text-white mb-1"><?= e(__('users.delete_user')) ?></h6>
          <p id="deleteUserLabel" style="font-size:.82rem;color:#a78bfa;margin:.25rem 0 .75rem;font-weight:600"></p>
          <p style="font-size:.79rem;color:#64748b;margin-bottom:1.4rem"><?= e(__('users.delete_confirm')) ?></p>
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('common.cancel')) ?></button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i><?= e(__('users.remove')) ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  $('#usersTable').DataTable({
    pageLength: 10,
    lengthMenu: [5,10,25,50],
    order: [[4,'desc']],
    columnDefs: [{ orderable: false, targets: [6] }],
    language: {
      searchPlaceholder: <?= json_encode(__('users.dt_search')) ?>,
      lengthMenu: <?= json_encode(__('users.dt_show')) ?>,
      info: <?= json_encode(__('users.dt_info')) ?>,
      paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
    },
    drawCallback: function() {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){ bootstrap.Tooltip.getOrCreateInstance(el); });
    }
  });
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el){ bootstrap.Tooltip.getOrCreateInstance(el); });
});

function openEdit(id, name, email, roleId, status) {
  document.getElementById('editForm').action = '<?= url('/users') ?>/' + id;
  document.getElementById('editName').value   = name;
  document.getElementById('editEmail').value  = email;
  document.getElementById('editRole').value   = roleId;
  document.getElementById('editStatus').value = status;
  document.getElementById('editPwd').value    = '';
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditUser')).show();
}

function openDelete(id, name) {
  document.getElementById('deleteForm').action = '<?= url('/users') ?>/' + id + '/delete';
  document.getElementById('deleteUserLabel').textContent = name;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDeleteUser')).show();
}

function togglePwd(id, btn) {
  var input = document.getElementById(id);
  var isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
<?php View::end(); ?>
