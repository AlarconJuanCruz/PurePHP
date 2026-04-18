<?php /* views/components/index.php */ ?>

<style>
  .section-title { font-size:.63rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#475569; margin-bottom:1rem; }
</style>

<!-- ─── MODALS ──────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Modals / Popups</div>
  <div class="d-flex flex-wrap gap-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBasic">
      <i class="bi bi-window me-2"></i>Basic Modal
    </button>
    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
      <i class="bi bi-pencil-square me-2"></i>Form Modal
    </button>
    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalConfirm">
      <i class="bi bi-exclamation-triangle me-2"></i>Confirm Dialog
    </button>
    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalChart">
      <i class="bi bi-bar-chart me-2"></i>Chart Modal
    </button>
    <button class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettings">
      <i class="bi bi-layout-sidebar-reverse me-2"></i>Offcanvas
    </button>
    <button class="btn btn-outline-success" id="btnShowToast">
      <i class="bi bi-bell me-2"></i>Toast Notification
    </button>
  </div>
</div>

<!-- ─── BUTTONS ─────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Button Variants</div>
  <div class="d-flex flex-wrap gap-2 mb-3">
    <button class="btn btn-primary">Primary</button>
    <button class="btn btn-secondary">Secondary</button>
    <button class="btn btn-success">Success</button>
    <button class="btn btn-danger">Danger</button>
    <button class="btn btn-warning text-dark">Warning</button>
    <button class="btn btn-info">Info</button>
    <button class="btn btn-dark border border-secondary-subtle">Dark</button>
    <button class="btn btn-link">Link</button>
  </div>
  <div class="d-flex flex-wrap gap-2 mb-3">
    <button class="btn btn-outline-primary">Outline Primary</button>
    <button class="btn btn-outline-secondary">Outline Secondary</button>
    <button class="btn btn-outline-success">Outline Success</button>
    <button class="btn btn-outline-danger">Outline Danger</button>
    <button class="btn btn-outline-info">Outline Info</button>
    <button class="btn btn-outline-warning">Outline Warning</button>
  </div>
  <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <button class="btn btn-primary btn-lg">Large</button>
    <button class="btn btn-primary">Default</button>
    <button class="btn btn-primary btn-sm">Small</button>
    <button class="btn btn-primary" disabled>Disabled</button>
    <button class="btn btn-primary" id="btnLoading">
      <span class="spinner-border spinner-border-sm me-2 d-none" id="loadSpinner"></span>
      <span id="loadText">Click to Load</span>
    </button>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <button class="btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
    <button class="btn btn-success"><i class="bi bi-check-circle me-2"></i>Confirm</button>
    <button class="btn btn-danger"><i class="bi bi-trash me-2"></i>Delete</button>
    <button class="btn btn-dark border border-secondary-subtle rounded-circle" style="width:38px;height:38px;padding:0">
      <i class="bi bi-heart-fill text-danger"></i>
    </button>
    <button class="btn btn-dark border border-secondary-subtle rounded-circle" style="width:38px;height:38px;padding:0">
      <i class="bi bi-star-fill text-warning"></i>
    </button>
    <div class="btn-group">
      <button class="btn btn-primary">Split</button>
      <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
      <ul class="dropdown-menu dropdown-menu-dark">
        <li><a class="dropdown-item" href="#">Action one</a></li>
        <li><a class="dropdown-item" href="#">Action two</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- ─── BADGES ───────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Badges &amp; Chips</div>
  <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <span class="badge bg-primary">Primary</span>
    <span class="badge bg-secondary">Secondary</span>
    <span class="badge bg-success">Success</span>
    <span class="badge bg-danger">Danger</span>
    <span class="badge bg-warning text-dark">Warning</span>
    <span class="badge bg-info">Info</span>
    <span class="badge rounded-pill bg-primary">Pill Badge</span>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle px-2 py-1">Subtle Primary</span>
    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-2 py-1">Subtle Success</span>
    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-2 py-1">Subtle Danger</span>
    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Subtle Warning</span>
    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">Subtle Info</span>
  </div>
</div>

<!-- ─── ALERTS ──────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Alerts</div>
  <div class="d-flex flex-column gap-2">
    <div class="alert alert-primary mb-0"><i class="bi bi-info-circle me-2"></i><strong>Info:</strong> This is an informational alert.</div>
    <div class="alert alert-success mb-0"><i class="bi bi-check-circle me-2"></i><strong>Success!</strong> Changes saved.</div>
    <div class="alert alert-warning mb-0"><i class="bi bi-exclamation-triangle me-2"></i><strong>Warning:</strong> Please review before continuing.</div>
    <div class="alert alert-danger alert-dismissible mb-0">
      <i class="bi bi-x-octagon me-2"></i><strong>Error:</strong> Something went wrong.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
</div>

<!-- ─── PROGRESS & SPINNERS ──────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Progress &amp; Spinners</div>
  <div class="d-flex flex-column gap-2 mb-4">
    <div class="d-flex justify-content-between mb-1" style="font-size:.8rem"><span>Storage</span><span class="text-secondary">72%</span></div>
    <div class="progress mb-2" style="height:7px"><div class="progress-bar bg-primary" style="width:72%"></div></div>
    <div class="progress mb-2" style="height:7px"><div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:45%"></div></div>
    <div class="progress" style="height:9px">
      <div class="progress-bar bg-primary" style="width:30%"></div>
      <div class="progress-bar bg-info" style="width:20%"></div>
      <div class="progress-bar bg-success" style="width:15%"></div>
    </div>
  </div>
  <div class="d-flex flex-wrap gap-3 align-items-center">
    <div class="spinner-border text-primary"></div>
    <div class="spinner-border text-success"></div>
    <div class="spinner-border text-danger"></div>
    <div class="spinner-grow text-warning"></div>
    <div class="spinner-grow text-info"></div>
    <div class="spinner-border spinner-border-sm text-secondary"></div>
  </div>
</div>

<!-- ─── CHARTS ───────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Charts (Chart.js 4)</div>
  <div class="row g-3">
    <div class="col-md-6">
      <div style="background:rgba(255,255,255,.025);border:1px solid var(--fw-border);border-radius:10px;padding:1rem">
        <div style="font-size:.78rem;font-weight:600;color:#94a3b8;margin-bottom:.75rem">Radar — Team Skills</div>
        <canvas id="radarChart" height="200"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div style="background:rgba(255,255,255,.025);border:1px solid var(--fw-border);border-radius:10px;padding:1rem">
        <div style="font-size:.78rem;font-weight:600;color:#94a3b8;margin-bottom:.75rem">Polar Area — Distribution</div>
        <canvas id="polarChart" height="200"></canvas>
      </div>
    </div>
    <div class="col-12">
      <div style="background:rgba(255,255,255,.025);border:1px solid var(--fw-border);border-radius:10px;padding:1rem">
        <div style="font-size:.78rem;font-weight:600;color:#94a3b8;margin-bottom:.75rem">Mixed — Bar + Line</div>
        <canvas id="mixedChart" height="80"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ─── CARDS GRID ────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Cards</div>
  <div class="row g-3">
    <?php
    $cards = [
      ['Design System',   'Consistent tokens, spacing, and components across all interfaces.',   'Design',  'primary', 'palette2'],
      ['API Integration', 'RESTful endpoints with JSON responses and session-based auth.',        'Backend', 'success', 'braces'],
      ['Performance',     '< 100ms TTFB, edge caching and optimised SQL with prepared statements.','DevOps', 'info',    'lightning'],
    ];
    foreach ($cards as [$title, $desc, $tag, $color, $icon]): ?>
    <div class="col-md-4">
      <div class="h-100" style="background:rgba(255,255,255,.025);border:1px solid var(--fw-border);border-radius:12px;padding:1.25rem;transition:border-color .2s"
           onmouseover="this.style.borderColor='rgba(124,58,237,.45)'"
           onmouseout="this.style.borderColor='rgba(255,255,255,.07)'">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="stat-icon bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis" style="width:40px;height:40px;border-radius:10px">
            <i class="bi bi-<?= e($icon) ?>"></i>
          </div>
          <span class="badge bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis border border-<?= e($color) ?>-subtle"><?= e($tag) ?></span>
        </div>
        <div class="fw-semibold text-white mb-1" style="font-size:.9rem"><?= e($title) ?></div>
        <div style="font-size:.8rem;color:#64748b;line-height:1.55"><?= e($desc) ?></div>
        <a href="#" class="btn btn-sm btn-outline-<?= e($color) ?> mt-3">Learn more <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ─── TABLE ────────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Bootstrap Table</div>
  <div class="table-responsive">
    <table class="table table-dark table-hover align-middle mb-0" style="font-size:.85rem">
      <thead>
        <tr style="border-color:rgba(255,255,255,.07)">
          <th style="color:#475569;font-size:.72rem;letter-spacing:.06em;font-weight:600">#</th>
          <th style="color:#475569;font-size:.72rem;letter-spacing:.06em;font-weight:600">NAME</th>
          <th style="color:#475569;font-size:.72rem;letter-spacing:.06em;font-weight:600">STATUS</th>
          <th style="color:#475569;font-size:.72rem;letter-spacing:.06em;font-weight:600">ROLE</th>
          <th style="color:#475569;font-size:.72rem;letter-spacing:.06em;font-weight:600">JOINED</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rows = [
          ['Alice Martin',  'active',   'Admin',     '2024-01-12'],
          ['Bob Chen',      'active',   'Developer', '2024-02-28'],
          ['Carol Diaz',    'inactive', 'Designer',  '2024-03-05'],
          ['David Kim',     'pending',  'Viewer',    '2024-04-01'],
          ['Eva Müller',    'active',   'Developer', '2024-04-10'],
        ];
        $statusColors = ['active' => 'success', 'inactive' => 'secondary', 'pending' => 'warning'];
        foreach ($rows as $i => $r): ?>
        <tr>
          <td class="text-secondary"><?= $i + 1 ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:28px;height:28px;border-radius:50%;background:rgba(124,58,237,.28);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#a78bfa">
                <?= e(mb_substr($r[0], 0, 1)) ?>
              </div>
              <?= e($r[0]) ?>
            </div>
          </td>
          <td>
            <span class="badge bg-<?= $statusColors[$r[1]] ?>-subtle text-<?= $statusColors[$r[1]] ?>-emphasis border border-<?= $statusColors[$r[1]] ?>-subtle">
              <?= e(ucfirst($r[1])) ?>
            </span>
          </td>
          <td class="text-secondary"><?= e($r[2]) ?></td>
          <td class="text-secondary"><?= e($r[3]) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);padding:.25rem .5rem">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#f87171;padding:.25rem .5rem">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ─── ACCORDION ────────────────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Accordion</div>
  <div class="accordion" id="faqAccordion">
    <?php
    $faqs = [
      ['How does the router work?',    'The Router maps URI patterns to Controller@method pairs via regex. Params like {id} are extracted and injected into the controller method automatically.'],
      ['How are views rendered?',      'The View class captures template output with PHP output buffering, then injects it into the layout via named slots. The scripts section renders after all JS libs are loaded.'],
      ['Is CSRF protection built in?', 'Yes — use csrf_field() in forms and call verify_csrf() in POST handlers. Tokens are stored in the session and compared with hash_equals() to prevent timing attacks.'],
    ];
    foreach ($faqs as $i => [$q, $a]): ?>
    <div class="accordion-item" style="background:transparent;border-color:rgba(255,255,255,.07)">
      <h2 class="accordion-header">
        <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>"
                type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>"
                style="background:rgba(255,255,255,.03);color:#f1f5f9;font-size:.875rem">
          <?= e($q) ?>
        </button>
      </h2>
      <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
        <div class="accordion-body text-secondary" style="font-size:.85rem"><?= e($a) ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ─── TOOLTIPS & POPOVERS ──────────────────────────────────────────────── -->
<div class="fw-card mb-4">
  <div class="section-title">Tooltips &amp; Popovers</div>
  <div class="d-flex flex-wrap gap-3 align-items-center">
    <button class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top!">Tooltip Top</button>
    <button class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Right side!">Tooltip Right</button>
    <button class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Bottom tooltip">Tooltip Bottom</button>
    <button class="btn btn-outline-primary"
            data-bs-toggle="popover"
            data-bs-placement="top"
            data-bs-title="Popover Title"
            data-bs-content="This is richer popover content with more space for explanation.">Popover</button>
    <button class="btn btn-outline-info"
            data-bs-toggle="popover"
            data-bs-trigger="focus"
            data-bs-title="Focus Popover"
            data-bs-content="Click elsewhere to dismiss this popover.">Focus Dismiss</button>
  </div>
</div>

<!-- ─── FORMS ────────────────────────────────────────────────────────────── -->
<div class="fw-card">
  <div class="section-title">Form Elements</div>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Text Input</label>
      <input type="text" class="form-control" placeholder="Enter value…">
    </div>
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Select</label>
      <select class="form-select">
        <option>Choose option…</option>
        <option>Option A</option>
        <option>Option B</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Valid input</label>
      <input type="email" class="form-control is-valid" value="user@example.com">
      <div class="valid-feedback">Looks good!</div>
    </div>
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Invalid input</label>
      <input type="text" class="form-control is-invalid" value="bad_value!!">
      <div class="invalid-feedback">Please provide a valid value.</div>
    </div>
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Range Slider</label>
      <input type="range" class="form-range" min="0" max="100" value="65">
    </div>
    <div class="col-md-6">
      <label class="form-label" style="font-size:.8rem;color:#94a3b8">Toggles</label>
      <div class="d-flex gap-3 mt-1">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" checked>
          <label class="form-check-label" style="font-size:.8rem">Enabled</label>
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox">
          <label class="form-check-label" style="font-size:.8rem">Disabled</label>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control" placeholder="Search…">
        <button class="btn btn-primary">Search</button>
      </div>
    </div>
  </div>
</div>


<!-- ════════════════════ MODALS HTML ════════════════════════════════════════ -->

<!-- Basic -->
<div class="modal fade" id="modalBasic" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <div class="modal-header border-0">
        <h5 class="modal-title"><i class="bi bi-info-circle text-primary me-2"></i>Basic Modal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-secondary" style="font-size:.9rem">
        <p>Standard Bootstrap 5 modal. Supports any content: text, images, forms, or tables.</p>
        <p class="mb-0">Trigger with <code class="text-warning">data-bs-toggle="modal"</code>.</p>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Form modal -->
<div class="modal fade" id="modalForm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <div class="modal-header border-0">
        <h5 class="modal-title"><i class="bi bi-pencil-square text-info me-2"></i>Edit User</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label" style="font-size:.8rem;color:#94a3b8">Full Name</label>
          <input type="text" class="form-control" value="Alice Martin">
        </div>
        <div class="mb-3">
          <label class="form-label" style="font-size:.8rem;color:#94a3b8">Email</label>
          <input type="email" class="form-control" value="alice@example.com">
        </div>
        <div class="mb-0">
          <label class="form-label" style="font-size:.8rem;color:#94a3b8">Role</label>
          <select class="form-select">
            <option selected>Admin</option><option>Developer</option><option>Viewer</option>
          </select>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirm -->
<div class="modal fade" id="modalConfirm" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(239,68,68,.3)">
      <div class="modal-body text-center py-4">
        <div style="width:54px;height:54px;border-radius:50%;background:rgba(239,68,68,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;color:#ef4444">
          <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h6 class="text-white mb-1">Delete record?</h6>
        <p style="font-size:.82rem;color:#64748b;margin-bottom:1.5rem">This cannot be undone. The record will be permanently removed.</p>
        <div class="d-flex gap-2 justify-content-center">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-trash me-1"></i>Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart modal -->
<div class="modal fade" id="modalChart" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="background:#1e293b;border:1px solid rgba(255,255,255,.1)">
      <div class="modal-header border-0">
        <h5 class="modal-title"><i class="bi bi-bar-chart-fill text-info me-2"></i>Sales Report</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <canvas id="modalSalesChart" height="100"></canvas>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-outline-info"><i class="bi bi-download me-1"></i>Export CSV</button>
      </div>
    </div>
  </div>
</div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" id="offcanvasSettings" tabindex="-1"
     style="background:#1e293b;border-left:1px solid rgba(255,255,255,.08);width:300px">
  <div class="offcanvas-header" style="border-bottom:1px solid rgba(255,255,255,.07)">
    <h6 class="offcanvas-title text-white mb-0"><i class="bi bi-sliders me-2"></i>Settings</h6>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <?php
    $settings = [
      ['Appearance', [['Dark Mode','checked'],['Compact Sidebar','']]],
      ['Notifications', [['Email Alerts','checked'],['Push Notifications','checked'],['Weekly Report','']]],
    ];
    foreach ($settings as [$section, $items]): ?>
    <div class="mb-4">
      <div style="font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;margin-bottom:.75rem"><?= e($section) ?></div>
      <?php foreach ($items as [$label, $checked]): ?>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span style="font-size:.85rem;color:#94a3b8"><?= e($label) ?></span>
        <div class="form-check form-switch mb-0">
          <input class="form-check-input" type="checkbox" <?= $checked ?>>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <button class="btn btn-primary w-100">Save Settings</button>
  </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="liveToast" class="toast" role="alert"
       style="background:#1e293b;border:1px solid rgba(124,58,237,.4);min-width:280px">
    <div class="toast-header" style="background:rgba(124,58,237,.1);border-bottom:1px solid rgba(124,58,237,.2)">
      <span style="width:8px;height:8px;border-radius:50%;background:#a78bfa;display:inline-block" class="me-2"></span>
      <strong class="me-auto text-white" style="font-size:.85rem">Notification</strong>
      <small class="text-secondary">just now</small>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body d-flex align-items-center gap-2" style="font-size:.85rem">
      <i class="bi bi-check-circle-fill text-success"></i>
      <span class="text-white">Action completed successfully!</span>
    </div>
  </div>
</div>

<!-- ════════════════════ SCRIPTS (after Bootstrap loads) ════════════════════ -->
<?php View::start('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

  Chart.defaults.color = '#64748b';
  Chart.defaults.borderColor = 'rgba(255,255,255,.06)';
  Chart.defaults.font.family = "'Space Grotesk', sans-serif";

  // Radar
  new Chart(document.getElementById('radarChart'), {
    type: 'radar',
    data: {
      labels: ['Frontend','Backend','DevOps','Security','Testing','Design'],
      datasets: [
        { label: 'Team A', data: [85,90,70,75,65,60], borderColor: '#7c3aed', backgroundColor: 'rgba(124,58,237,.2)', pointBackgroundColor: '#7c3aed' },
        { label: 'Team B', data: [70,75,90,80,85,50], borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,.15)', pointBackgroundColor: '#06b6d4' }
      ]
    },
    options: { scales: { r: { grid: { color: 'rgba(255,255,255,.07)' }, pointLabels: { font: { size: 11 } }, ticks: { display: false } } } }
  });

  // Polar
  new Chart(document.getElementById('polarChart'), {
    type: 'polarArea',
    data: {
      labels: ['PHP','JavaScript','CSS','SQL','DevOps'],
      datasets: [{ data: [85,78,65,72,55], backgroundColor: ['rgba(124,58,237,.7)','rgba(6,182,212,.7)','rgba(16,185,129,.7)','rgba(245,158,11,.7)','rgba(239,68,68,.7)'], borderWidth: 0 }]
    },
    options: { scales: { r: { grid: { color: 'rgba(255,255,255,.07)' }, ticks: { display: false } } }, plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } } }
  });

  // Mixed
  new Chart(document.getElementById('mixedChart'), {
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
      datasets: [
        { type: 'bar',  label: 'Orders',    data: [320,480,390,560,490,620,710,580,650,720,800,940], backgroundColor: 'rgba(124,58,237,.5)', borderRadius: 5, order: 2 },
        { type: 'line', label: 'Avg Value', data: [42,51,45,58,52,67,74,62,69,78,85,92], borderColor: '#06b6d4', borderWidth: 2, pointRadius: 3, tension: .4, yAxisID: 'y2', order: 1 }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y:  { grid: { color: 'rgba(255,255,255,.04)' }, beginAtZero: true },
        y2: { position: 'right', grid: { display: false }, ticks: { callback: v => '$'+v } },
        x:  { grid: { display: false } }
      },
      plugins: { legend: { labels: { boxWidth: 12 } } }
    }
  });

  // Chart modal (lazy)
  document.getElementById('modalChart').addEventListener('shown.bs.modal', function () {
    if (this._chartInited) return;
    this._chartInited = true;
    new Chart(document.getElementById('modalSalesChart'), {
      type: 'bar',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [
          { label: 'Sales',   data: [12000,18500,14200,21000,19800,24600], backgroundColor: 'rgba(124,58,237,.7)', borderRadius: 6 },
          { label: 'Returns', data: [800,1200,900,1400,1100,1600],         backgroundColor: 'rgba(239,68,68,.6)',  borderRadius: 6 }
        ]
      },
      options: {
        responsive: true,
        scales: { y: { ticks: { callback: v => '$'+(v/1000)+'k' } }, x: { grid: { display: false } } }
      }
    });
  });

  // Tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el));

  // Popovers
  document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => bootstrap.Popover.getOrCreateInstance(el));

  // Toast
  document.getElementById('btnShowToast').addEventListener('click', () => {
    bootstrap.Toast.getOrCreateInstance(document.getElementById('liveToast')).show();
  });

  // Loading button demo
  const btn = document.getElementById('btnLoading');
  btn.addEventListener('click', () => {
    document.getElementById('loadSpinner').classList.remove('d-none');
    document.getElementById('loadText').textContent = 'Loading…';
    btn.disabled = true;
    setTimeout(() => {
      document.getElementById('loadSpinner').classList.add('d-none');
      document.getElementById('loadText').textContent = 'Done!';
      btn.classList.replace('btn-primary','btn-success');
      setTimeout(() => {
        document.getElementById('loadText').textContent = 'Click to Load';
        btn.classList.replace('btn-success','btn-primary');
        btn.disabled = false;
      }, 1800);
    }, 2200);
  });

});
</script>
<?php View::end(); ?>
