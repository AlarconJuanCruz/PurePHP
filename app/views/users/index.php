<?php /* views/users/index.php */
$statusColors = ['active' => 'success', 'inactive' => 'secondary', 'pending' => 'warning'];
$roleColors   = ['admin' => 'primary', 'developer' => 'info', 'designer' => 'success', 'viewer' => 'secondary'];
?>

<?php if (!$dbConnected): ?>
<div class="alert alert-warning mb-3" style="font-size:.85rem">
  <i class="bi bi-database-exclamation me-2"></i>
  <strong>Database not connected.</strong> Configure <code>app/config/database.php</code> and import <code>database.sql</code>.
  <a href="<?= url('/docs') ?>" class="alert-link ms-1">See documentation →</a>
</div>
<?php endif; ?>

<!-- Header row -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <p class="text-secondary mb-0" style="font-size:.84rem">Manage all registered accounts.</p>
  <div class="d-flex gap-2">
    <a href="<?= url('/roles') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-shield-check me-1"></i>Manage Roles
    </a>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNewUser">
      <i class="bi bi-person-plus me-1"></i>Add User
    </button>
  </div>
</div>

<!-- Stat strip -->
<?php
$total    = count($users);
$active   = count(array_filter($users, fn($u) => $u['status'] === 'active'));
$inactive = count(array_filter($users, fn($u) => $u['status'] === 'inactive'));
$pending  = count(array_filter($users, fn($u) => $u['status'] === 'pending'));
$strips   = [
  ['Total',    $total,    'people-fill',     'primary',   ''],
  ['Active',   $active,   'person-check',    'success',   $total > 0 ? round($active/$total*100).'%' : '0%'],
  ['Inactive', $inactive, 'person-dash',     'secondary', ''],
  ['Pending',  $pending,  'hourglass-split', 'warning',   'Awaiting verify'],
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

<!-- DataTable -->
<div class="fw-card">
  <div class="table-responsive">
    <table id="usersTable" class="table table-dark table-hover align-middle" style="font-size:.84rem;width:100%">
      <thead>
        <tr style="border-color:rgba(255,255,255,.06)">
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">USER</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">EMAIL</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">ROLE</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">STATUS</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">JOINED</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em">LAST LOGIN</th>
          <th style="color:#334155;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-align:right">ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u):
          $sc   = $statusColors[$u['status']] ?? 'secondary';
          $rc   = $roleColors[$u['role_slug'] ?? ''] ?? 'secondary';
          $init = mb_strtoupper(mb_substr($u['name'], 0, 1));
          $lastLogin = $u['last_login'] ? date('Y-m-d', strtotime($u['last_login'])) : 'Never';
        ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:30px;height:30px;border-radius:50%;background:rgba(124,58,237,.22);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#a78bfa;flex-shrink:0">
                <?= e($init) ?>
              </div>
              <span class="text-white"><?= e($u['name']) ?></span>
            </div>
          </td>
          <td class="text-secondary"><?= e($u['email']) ?></td>
          <td>
            <span class="badge bg-<?= e($rc) ?>-subtle text-<?= e($rc) ?>-emphasis border border-<?= e($rc) ?>-subtle">
              <?= e($u['role_name'] ?? 'Unknown') ?>
            </span>
          </td>
          <td>
            <span class="badge bg-<?= e($sc) ?>-subtle text-<?= e($sc) ?>-emphasis border border-<?= e($sc) ?>-subtle">
              <i class="bi bi-circle-fill me-1" style="font-size:.38rem"></i><?= e(ucfirst($u['status'])) ?>
            </span>
          </td>
          <td class="text-secondary"><?= e(substr($u['created_at'] ?? '', 0, 10)) ?></td>
          <td class="text-secondary"><?= e($lastLogin) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm"
                      style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.22rem .44rem"
                      data-bs-toggle="tooltip" title="Edit"
                      onclick="openEdit(<?= (int)$u['id'] ?>,<?= json_encode($u['name']) ?>,<?= json_encode($u['email']) ?>,<?= (int)$u['role_id'] ?>,<?= json_encode($u['status']) ?>)">
                <i class="bi bi-pencil" style="font-size:.78rem"></i>
              </button>
              <button class="btn btn-sm"
                      style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.22rem .44rem"
                      data-bs-toggle="tooltip" title="Delete"
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


<!-- ════ MODAL: New User ════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalNewUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form action="<?= url('/users') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-person-plus text-primary me-2"></i>Add New User</h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Full Name</label>
              <input type="text" name="name" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Role</label>
              <select name="role_id" class="form-select" required>
                <?php foreach ($roles as $r): ?>
                <option value="<?= (int)$r['id'] ?>"><?= e($r['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Status</label>
              <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="pending" selected>Pending</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Password</label>
              <div class="input-group">
                <input type="password" name="password" id="newPwd" class="form-control" placeholder="Min. 8 characters" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('newPwd',this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ════ MODAL: Edit User ═══════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form id="editForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i>Edit User</h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Full Name</label>
              <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Email</label>
              <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Role</label>
              <select name="role_id" id="editRole" class="form-select">
                <?php foreach ($roles as $r): ?>
                <option value="<?= (int)$r['id'] ?>"><?= e($r['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Status</label>
              <select name="status" id="editStatus" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">New Password <span style="color:#475569">(leave blank to keep current)</span></label>
              <div class="input-group">
                <input type="password" name="password" id="editPwd" class="form-control" placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('editPwd',this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ════ MODAL: Delete ══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <form id="deleteForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center py-4 px-3">
          <div style="width:50px;height:50px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.35rem;color:#ef4444">
            <i class="bi bi-person-x-fill"></i>
          </div>
          <h6 class="text-white mb-1">Remove user?</h6>
          <p id="deleteUserLabel" style="font-size:.82rem;color:#a78bfa;margin:.25rem 0 .75rem;font-weight:600"></p>
          <p style="font-size:.79rem;color:#64748b;margin-bottom:1.4rem">This action is permanent and cannot be undone.</p>
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Remove</button>
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
    lengthMenu: [5, 10, 25, 50],
    order: [[4, 'desc']],
    columnDefs: [{ orderable: false, targets: [6] }],
    language: {
      searchPlaceholder: 'Search users…',
      lengthMenu: 'Show _MENU_ users',
      info: 'Showing _START_–_END_ of _TOTAL_ users',
      paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
    },
    drawCallback: () => document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el))
  });

  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el));
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
  const input = document.getElementById(id);
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
}
</script>
<?php View::end(); ?>
