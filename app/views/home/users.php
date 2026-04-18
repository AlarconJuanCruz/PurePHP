<?php /* views/home/users.php */ ?>

<!-- Header actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-secondary mb-0" style="font-size:.85rem">Manage and monitor all registered accounts.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i>Export</button>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNewUser">
      <i class="bi bi-person-plus me-1"></i>Add User
    </button>
  </div>
</div>

<!-- Stat strip -->
<div class="row g-3 mb-4">
  <?php
  $strips = [
    ['Total Users',    '48',  'people-fill',     'primary',   '+4 this week'],
    ['Active',         '34',  'person-check',    'success',   '70.8%'],
    ['Inactive',       '9',   'person-dash',     'secondary', '18.7%'],
    ['Pending',        '5',   'hourglass-split', 'warning',   'Awaiting verify'],
  ];
  foreach ($strips as [$label, $val, $icon, $color, $sub]): ?>
  <div class="col-6 col-xl-3">
    <div class="fw-card d-flex align-items-center gap-3 py-3">
      <div class="stat-icon bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis" style="width:40px;height:40px;border-radius:10px;flex-shrink:0">
        <i class="bi bi-<?= e($icon) ?>"></i>
      </div>
      <div>
        <div class="text-white fw-bold" style="font-size:1.3rem;line-height:1"><?= e($val) ?></div>
        <div style="font-size:.75rem;color:#64748b"><?= e($label) ?></div>
        <div style="font-size:.7rem;color:#475569"><?= e($sub) ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- DataTable card -->
<div class="fw-card">
  <div class="table-responsive">
    <table id="usersTable" class="table table-dark table-hover align-middle" style="font-size:.85rem;width:100%">
      <thead>
        <tr style="border-color:rgba(255,255,255,.07)">
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">USER</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">EMAIL</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">ROLE</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">STATUS</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">JOINED</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em">LAST LOGIN</th>
          <th style="color:#475569;font-size:.7rem;font-weight:700;letter-spacing:.07em;text-align:right">ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $users = [
          ['Alice Martin',   'alice@mail.com',    'Admin',     'active',   '2024-01-12', '2025-04-10'],
          ['Bob Chen',       'bob@mail.com',      'Developer', 'active',   '2024-02-28', '2025-04-09'],
          ['Carol Diaz',     'carol@mail.com',    'Designer',  'inactive', '2024-03-05', '2025-03-20'],
          ['David Kim',      'david@mail.com',    'Viewer',    'pending',  '2024-04-01', 'Never'],
          ['Eva Müller',     'eva@mail.com',      'Developer', 'active',   '2024-04-10', '2025-04-11'],
          ['Frank Santos',   'frank@mail.com',    'Developer', 'active',   '2024-05-01', '2025-04-08'],
          ['Grace Lee',      'grace@mail.com',    'Designer',  'active',   '2024-05-15', '2025-04-07'],
          ['Hiro Tanaka',    'hiro@mail.com',     'Developer', 'inactive', '2024-06-02', '2025-02-14'],
          ['Isabel Torres',  'isabel@mail.com',   'Admin',     'active',   '2024-06-20', '2025-04-11'],
          ['James Wright',   'james@mail.com',    'Viewer',    'pending',  '2024-07-04', 'Never'],
          ['Kate Brown',     'kate@mail.com',     'Developer', 'active',   '2024-07-18', '2025-04-10'],
          ['Liam Nguyen',    'liam@mail.com',     'Viewer',    'inactive', '2024-08-01', '2025-01-30'],
          ['Maria Rossi',    'maria@mail.com',    'Designer',  'active',   '2024-08-15', '2025-04-09'],
          ['Nathan Osei',    'nathan@mail.com',   'Developer', 'active',   '2024-09-01', '2025-04-11'],
          ['Olivia Park',    'olivia@mail.com',   'Viewer',    'pending',  '2024-09-20', 'Never'],
          ['Pedro Alves',    'pedro@mail.com',    'Developer', 'active',   '2024-10-05', '2025-04-06'],
          ['Quinn Adams',    'quinn@mail.com',    'Designer',  'active',   '2024-10-22', '2025-04-05'],
          ['Rachel Moore',   'rachel@mail.com',   'Admin',     'active',   '2024-11-01', '2025-04-10'],
          ['Samuel Clark',   'samuel@mail.com',   'Developer', 'inactive', '2024-11-18', '2025-03-01'],
          ['Tina Hoffman',   'tina@mail.com',     'Viewer',    'active',   '2024-12-03', '2025-04-04'],
        ];
        $roles = ['Admin' => 'primary','Developer' => 'info','Designer' => 'success','Viewer' => 'secondary'];
        $statusColors = ['active' => 'success','inactive' => 'secondary','pending' => 'warning'];
        $avatarColors = ['Admin' => '#7c3aed','Developer' => '#06b6d4','Designer' => '#10b981','Viewer' => '#475569'];

        foreach ($users as $u): ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:32px;height:32px;border-radius:50%;background:<?= e($avatarColors[$u[2]] ?? '#334155') ?>30;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:<?= e($avatarColors[$u[2]] ?? '#94a3b8') ?>;flex-shrink:0;border:1px solid <?= e($avatarColors[$u[2]] ?? '#334155') ?>25">
                <?= e(mb_strtoupper(mb_substr($u[0], 0, 1))) ?>
              </div>
              <span class="text-white"><?= e($u[0]) ?></span>
            </div>
          </td>
          <td class="text-secondary"><?= e($u[1]) ?></td>
          <td>
            <span class="badge bg-<?= e($roles[$u[2]] ?? 'secondary') ?>-subtle text-<?= e($roles[$u[2]] ?? 'secondary') ?>-emphasis border border-<?= e($roles[$u[2]] ?? 'secondary') ?>-subtle">
              <?= e($u[2]) ?>
            </span>
          </td>
          <td>
            <span class="badge bg-<?= e($statusColors[$u[3]]) ?>-subtle text-<?= e($statusColors[$u[3]]) ?>-emphasis border border-<?= e($statusColors[$u[3]]) ?>-subtle">
              <i class="bi bi-circle-fill me-1" style="font-size:.4rem"></i><?= e(ucfirst($u[3])) ?>
            </span>
          </td>
          <td class="text-secondary"><?= e($u[4]) ?></td>
          <td class="text-secondary"><?= e($u[5]) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm" style="background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.2);color:#67e8f9;padding:.22rem .45rem"
                      data-bs-toggle="tooltip" title="View profile">
                <i class="bi bi-eye" style="font-size:.8rem"></i>
              </button>
              <button class="btn btn-sm" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#94a3b8;padding:.22rem .45rem"
                      data-bs-toggle="tooltip" title="Edit user"
                      onclick="openEditModal('<?= e(addslashes($u[0])) ?>','<?= e($u[1]) ?>','<?= e($u[2]) ?>','<?= e($u[3]) ?>')">
                <i class="bi bi-pencil" style="font-size:.8rem"></i>
              </button>
              <button class="btn btn-sm" style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.18);color:#f87171;padding:.22rem .45rem"
                      data-bs-toggle="tooltip" title="Delete user"
                      onclick="openDeleteModal('<?= e(addslashes($u[0])) ?>')">
                <i class="bi bi-trash" style="font-size:.8rem"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>


<!-- ═══ MODAL: New User ══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalNewUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <div class="modal-header border-0">
        <h5 class="modal-title"><i class="bi bi-person-plus text-primary me-2"></i>Add New User</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Full Name</label>
            <input type="text" class="form-control" placeholder="John Doe">
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Email Address</label>
            <input type="email" class="form-control" placeholder="john@example.com">
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Role</label>
            <select class="form-select">
              <option>Viewer</option><option>Developer</option><option>Designer</option><option>Admin</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Status</label>
            <select class="form-select">
              <option>active</option><option>pending</option><option>inactive</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Temporary Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="newPwd" placeholder="Min. 8 characters">
              <button class="btn btn-outline-secondary" onclick="togglePwd('newPwd',this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Create User</button>
      </div>
    </div>
  </div>
</div>

<!-- ═══ MODAL: Edit User ═════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <div class="modal-header border-0">
        <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i>Edit User</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Full Name</label>
            <input type="text" class="form-control" id="editName">
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Email</label>
            <input type="email" class="form-control" id="editEmail">
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Role</label>
            <select class="form-select" id="editRole">
              <option>Admin</option><option>Developer</option><option>Designer</option><option>Viewer</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:.8rem;color:#94a3b8">Status</label>
            <select class="form-select" id="editStatus">
              <option>active</option><option>inactive</option><option>pending</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- ═══ MODAL: Delete Confirm ════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.35)">
      <div class="modal-body text-center py-4 px-3">
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.4rem;color:#ef4444">
          <i class="bi bi-person-x-fill"></i>
        </div>
        <h6 class="text-white mb-1">Remove user?</h6>
        <p id="deleteUserName" style="font-size:.82rem;color:#7c3aed;margin:.25rem 0 .75rem;font-weight:600"></p>
        <p style="font-size:.8rem;color:#64748b;margin-bottom:1.5rem">This user will be permanently deleted and cannot be recovered.</p>
        <div class="d-flex gap-2 justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-trash me-1"></i>Remove</button>
        </div>
      </div>
    </div>
  </div>
</div>


<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // Init DataTable
  const table = $('#usersTable').DataTable({
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    order: [[4, 'desc']],
    columnDefs: [
      { orderable: false, targets: [6] }
    ],
    language: {
      search:         '<i class="bi bi-search me-1"></i>',
      searchPlaceholder: 'Search users…',
      lengthMenu:     'Show _MENU_ users',
      info:           'Showing _START_–_END_ of _TOTAL_ users',
      paginate: {
        previous: '<i class="bi bi-chevron-left"></i>',
        next:     '<i class="bi bi-chevron-right"></i>'
      }
    },
    drawCallback: function () {
      // Re-init tooltips on each draw (DataTables moves DOM nodes)
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        bootstrap.Tooltip.getOrCreateInstance(el);
      });
    }
  });

  // Tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el));

});

function openEditModal(name, email, role, status) {
  document.getElementById('editName').value   = name;
  document.getElementById('editEmail').value  = email;
  document.getElementById('editRole').value   = role;
  document.getElementById('editStatus').value = status;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditUser')).show();
}

function openDeleteModal(name) {
  document.getElementById('deleteUserName').textContent = name;
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
