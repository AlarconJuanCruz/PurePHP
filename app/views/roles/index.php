<?php /* views/roles/index.php */
// Group permissions by group_name for the matrix
$permGroups = [];
foreach ($permissions as $p) {
    $permGroups[$p['group_name']][] = $p;
}
$colorOptions = ['primary','secondary','success','danger','warning','info'];
?>

<?php if (!$dbConnected): ?>
<div class="alert alert-warning mb-3" style="font-size:.85rem">
  <i class="bi bi-database-exclamation me-2"></i>
  <strong>Database not connected.</strong>
  <a href="<?= url('/docs') ?>" class="alert-link ms-1">See setup docs →</a>
</div>
<?php endif; ?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <p class="text-secondary mb-0" style="font-size:.84rem">Define roles and control what each role can access.</p>
  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNewRole">
    <i class="bi bi-shield-plus me-1"></i>New Role
  </button>
</div>

<!-- Role cards -->
<div class="row g-3 mb-4">
  <?php foreach ($roles as $role):
    $c = $role['color'] ?? 'secondary';
  ?>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="fw-card h-100">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <span class="badge bg-<?= e($c) ?>-subtle text-<?= e($c) ?>-emphasis border border-<?= e($c) ?>-subtle mb-2">
            <?= e($role['slug']) ?>
          </span>
          <div class="text-white fw-semibold" style="font-size:.95rem"><?= e($role['name']) ?></div>
        </div>
        <div class="d-flex gap-1">
          <button class="btn btn-sm" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.2rem .42rem"
                  onclick="openEditRole(<?= (int)$role['id'] ?>,<?= json_encode($role['name']) ?>,<?= json_encode($role['description'] ?? '') ?>,<?= json_encode($role['color']) ?>,<?= json_encode(array_keys(array_filter($matrix[$role['id']] ?? []))) ?>)">
            <i class="bi bi-pencil" style="font-size:.78rem"></i>
          </button>
          <button class="btn btn-sm" style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.2rem .42rem"
                  onclick="openDeleteRole(<?= (int)$role['id'] ?>,<?= json_encode($role['name']) ?>)">
            <i class="bi bi-trash" style="font-size:.78rem"></i>
          </button>
        </div>
      </div>
      <p style="font-size:.78rem;color:#64748b;min-height:2.5rem;line-height:1.5"><?= e($role['description'] ?? '') ?></p>
      <div class="d-flex align-items-center justify-content-between mt-2 pt-2" style="border-top:1px solid var(--fw-border)">
        <span style="font-size:.75rem;color:#475569">
          <i class="bi bi-people me-1"></i><?= (int)$role['user_count'] ?> user<?= $role['user_count'] != 1 ? 's' : '' ?>
        </span>
        <span style="font-size:.75rem;color:#475569">
          <?= count($matrix[$role['id']] ?? []) ?> permissions
        </span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Permissions matrix -->
<div class="fw-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h6 class="mb-0 fw-semibold text-white">Permissions Matrix</h6>
      <small class="text-secondary">Read-only overview — edit permissions via the role card.</small>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-dark align-middle mb-0" style="font-size:.82rem">
      <thead>
        <tr style="border-color:rgba(255,255,255,.06)">
          <th style="color:#475569;font-size:.68rem;font-weight:700;letter-spacing:.07em;min-width:160px">PERMISSION</th>
          <?php foreach ($roles as $role): ?>
          <th style="color:#475569;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-align:center">
            <span class="badge bg-<?= e($role['color'] ?? 'secondary') ?>-subtle text-<?= e($role['color'] ?? 'secondary') ?>-emphasis">
              <?= e($role['name']) ?>
            </span>
          </th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($permGroups as $group => $groupPerms): ?>
        <tr style="border-color:rgba(255,255,255,.04)">
          <td colspan="<?= count($roles) + 1 ?>"
              style="background:rgba(124,58,237,.06);color:#6d28d9;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.4rem 1rem">
            <?= e($group) ?>
          </td>
        </tr>
        <?php foreach ($groupPerms as $perm): ?>
        <tr style="border-color:rgba(255,255,255,.04)">
          <td class="text-secondary" style="font-size:.82rem"><?= e($perm['name']) ?></td>
          <?php foreach ($roles as $role): ?>
          <td style="text-align:center">
            <?php if (!empty($matrix[$role['id']][$perm['id']])): ?>
            <i class="bi bi-check-circle-fill text-success" style="font-size:.95rem"></i>
            <?php else: ?>
            <i class="bi bi-dash" style="color:#2d3f55;font-size:.95rem"></i>
            <?php endif; ?>
          </td>
          <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<!-- ════ MODAL: New Role ════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalNewRole" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form action="<?= url('/roles') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-shield-plus text-primary me-2"></i>New Role</h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Role Name</label>
              <input type="text" name="name" class="form-control" placeholder="e.g. Moderator" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Color</label>
              <select name="color" class="form-select">
                <?php foreach ($colorOptions as $co): ?>
                <option value="<?= e($co) ?>"><?= ucfirst($co) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Description</label>
              <input type="text" name="description" class="form-control" placeholder="Brief description of this role">
            </div>
          </div>
          <!-- Permissions checkboxes -->
          <div style="font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;margin-bottom:.75rem">Permissions</div>
          <?php foreach ($permGroups as $group => $groupPerms): ?>
          <div class="mb-3">
            <div style="font-size:.72rem;color:#6d28d9;font-weight:700;margin-bottom:.4rem"><?= e($group) ?></div>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($groupPerms as $perm): ?>
              <label class="d-flex align-items-center gap-1" style="font-size:.8rem;color:#94a3b8;cursor:pointer">
                <input type="checkbox" name="permissions[]" value="<?= (int)$perm['id'] ?>" class="form-check-input mt-0" style="flex-shrink:0">
                <?= e($perm['name']) ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create Role</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ════ MODAL: Edit Role ═══════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEditRole" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <form id="editRoleForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i>Edit Role</h5>
          <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Role Name</label>
              <input type="text" name="name" id="editRoleName" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Color</label>
              <select name="color" id="editRoleColor" class="form-select">
                <?php foreach ($colorOptions as $co): ?>
                <option value="<?= e($co) ?>"><?= ucfirst($co) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:.79rem;color:#94a3b8">Description</label>
              <input type="text" name="description" id="editRoleDesc" class="form-control">
            </div>
          </div>
          <div style="font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;margin-bottom:.75rem">Permissions</div>
          <?php foreach ($permGroups as $group => $groupPerms): ?>
          <div class="mb-3">
            <div style="font-size:.72rem;color:#6d28d9;font-weight:700;margin-bottom:.4rem"><?= e($group) ?></div>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($groupPerms as $perm): ?>
              <label class="d-flex align-items-center gap-1" style="font-size:.8rem;color:#94a3b8;cursor:pointer">
                <input type="checkbox" name="permissions[]" value="<?= (int)$perm['id'] ?>"
                       class="form-check-input mt-0 edit-perm-cb" data-perm-id="<?= (int)$perm['id'] ?>" style="flex-shrink:0">
                <?= e($perm['name']) ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ════ MODAL: Delete Role ═════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDeleteRole" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <form id="deleteRoleForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center py-4 px-3">
          <div style="width:50px;height:50px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.35rem;color:#ef4444">
            <i class="bi bi-shield-x"></i>
          </div>
          <h6 class="text-white mb-1">Delete role?</h6>
          <p id="deleteRoleLabel" style="font-size:.82rem;color:#a78bfa;margin:.25rem 0 .75rem;font-weight:600"></p>
          <p style="font-size:.79rem;color:#64748b;margin-bottom:1.4rem">
            This role will be permanently removed. Roles with assigned users cannot be deleted.
          </p>
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Delete</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<?php View::start('scripts'); ?>
<script>
function openEditRole(id, name, desc, color, permIds) {
  document.getElementById('editRoleForm').action = '<?= url('/roles') ?>/' + id;
  document.getElementById('editRoleName').value  = name;
  document.getElementById('editRoleDesc').value  = desc;
  document.getElementById('editRoleColor').value = color;
  // Reset then check the right boxes
  document.querySelectorAll('.edit-perm-cb').forEach(cb => {
    cb.checked = permIds.map(Number).includes(Number(cb.dataset.permId));
  });
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditRole')).show();
}

function openDeleteRole(id, name) {
  document.getElementById('deleteRoleForm').action = '<?= url('/roles') ?>/' + id + '/delete';
  document.getElementById('deleteRoleLabel').textContent = name;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDeleteRole')).show();
}
</script>
<?php View::end(); ?>
