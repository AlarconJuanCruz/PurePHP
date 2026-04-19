<?php /* views/home/index.php */ ?>

<!-- Stat cards -->
<div class="row g-3 mb-4">
  <?php foreach ($stats as $stat): ?>
  <div class="col-6 col-xl-3">
    <a href="<?= e($stat['link']) ?>" class="stat-card" style="text-decoration:none">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="stat-icon bg-<?= e($stat['color']) ?>-subtle text-<?= e($stat['color']) ?>-emphasis">
          <i class="bi bi-<?= e($stat['icon']) ?>"></i>
        </div>
        <i class="bi bi-arrow-up-right" style="color:rgba(255,255,255,.15);font-size:.85rem"></i>
      </div>
      <div class="stat-value text-white"><?= e((string)$stat['value']) ?></div>
      <div class="stat-label mt-1"><?= e((string)$stat['label']) ?></div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts row -->
<div class="row g-3 mb-4">
  <!-- Line: monthly registrations -->
  <div class="col-12 col-xl-8">
    <div class="fw-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h6 class="mb-0 fw-semibold text-white"><?= e(__('dashboard.registrations_title')) ?></h6>
          <small class="text-secondary"><?= e(__('dashboard.last_months')) ?></small>
        </div>
      </div>
      <canvas id="revenueChart" height="100"></canvas>
    </div>
  </div>
  <!-- Donut: users by role -->
  <div class="col-12 col-xl-4">
    <div class="fw-card h-100">
      <h6 class="mb-0 fw-semibold text-white"><?= e(__('dashboard.users_by_role')) ?></h6>
      <small class="text-secondary"><?= e(__('dashboard.total_dist')) ?></small>
      <canvas id="trafficChart" class="mt-3" height="155"></canvas>
      <?php if (!empty($byRole)): ?>
      <div class="mt-3 d-flex flex-column gap-1">
        <?php
        $colorMap = ['primary'=>'#7c3aed','info'=>'#06b6d4','success'=>'#10b981','secondary'=>'#475569','danger'=>'#ef4444','warning'=>'#f59e0b'];
        foreach ($byRole as $r):
          $hex = $colorMap[$r['color']] ?? '#7c3aed';
        ?>
        <div class="d-flex align-items-center justify-content-between" style="font-size:.78rem">
          <div class="d-flex align-items-center gap-2">
            <span style="width:7px;height:7px;border-radius:50%;background:<?= e($hex) ?>;display:inline-block;flex-shrink:0"></span>
            <span class="text-secondary"><?= e((string)$r['name']) ?></span>
          </div>
          <span class="text-white fw-semibold"><?= (int)$r['cnt'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Bar chart + Recent notas -->
<div class="row g-3 mb-4">
  <div class="col-12 col-xl-5">
    <div class="fw-card h-100">
      <h6 class="mb-0 fw-semibold text-white"><?= e(__('dashboard.weekly_signups')) ?></h6>
      <small class="text-secondary"><?= e(__('dashboard.daily_reg')) ?></small>
      <canvas id="signupChart" class="mt-3" height="140"></canvas>
    </div>
  </div>
  <div class="col-12 col-xl-7">
    <div class="fw-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-semibold text-white"><?= e(__('dashboard.recent_notas')) ?></h6>
        <a href="<?= url('/notas') ?>" class="btn btn-sm" style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);color:#a78bfa;font-size:.75rem">
          <?= e(__('common.view')) ?> →
        </a>
      </div>
      <?php if (!empty($recentNotas)): ?>
      <div class="d-flex flex-column gap-2">
        <?php
        $estadoColors = ['publicado'=>'success','borrador'=>'secondary','archivado'=>'warning'];
        foreach ($recentNotas as $nota):
          $sc = $estadoColors[$nota['estado']] ?? 'secondary';
        ?>
        <div class="d-flex align-items-center gap-3 py-2" style="border-bottom:1px solid rgba(255,255,255,.04)">
          <!-- Thumbnail -->
          <?php if ($nota['imagen_portada']): ?>
          <img src="<?= url($nota['imagen_portada']) ?>"
               style="width:44px;height:44px;border-radius:6px;object-fit:cover;flex-shrink:0" alt="">
          <?php else: ?>
          <div style="width:44px;height:44px;border-radius:6px;background:rgba(124,58,237,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-newspaper text-secondary" style="font-size:.9rem"></i>
          </div>
          <?php endif; ?>
          <!-- Info -->
          <div style="min-width:0;flex:1">
            <div class="text-white" style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              <?= e((string)$nota['titulo']) ?>
            </div>
            <div class="d-flex align-items-center gap-2 mt-1">
              <?php if ($nota['cat_nombre']): ?>
              <span class="badge bg-<?= e($nota['cat_color']??'secondary') ?>-subtle text-<?= e($nota['cat_color']??'secondary') ?>-emphasis" style="font-size:.62rem"><?= e($nota['cat_nombre']) ?></span>
              <?php endif; ?>
              <span class="badge bg-<?= e($sc) ?>-subtle text-<?= e($sc) ?>-emphasis" style="font-size:.62rem"><?= e(ucfirst($nota['estado'])) ?></span>
              <?php if ($nota['destacada']): ?>
              <i class="bi bi-star-fill text-warning" style="font-size:.7rem"></i>
              <?php endif; ?>
            </div>
          </div>
          <div class="text-secondary flex-shrink-0" style="font-size:.72rem;text-align:right">
            <div><?= e(localDate(substr((string)$nota['created_at'],0,10))) ?></div>
            <a href="<?= url('/notas/'.(int)$nota['id'].'/edit') ?>" class="text-secondary mt-1 d-block" style="font-size:.7rem">
              <i class="bi bi-pencil"></i>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="text-center py-4">
        <i class="bi bi-newspaper text-secondary" style="font-size:2rem"></i>
        <p class="text-secondary mt-2 mb-3" style="font-size:.84rem"><?= e(__('common.no_results')) ?></p>
        <a href="<?= url('/notas/crear') ?>" class="btn btn-sm btn-primary">
          <i class="bi bi-plus me-1"></i><?= e(__('notas.new')) ?>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Recent users -->
<?php if (!empty($recentUsers)): ?>
<div class="fw-card">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0 fw-semibold text-white"><?= e(__('dashboard.recent_users')) ?></h6>
    <a href="<?= url('/users') ?>" class="btn btn-sm" style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);color:#a78bfa;font-size:.75rem"><?= e(__('common.view')) ?> →</a>
  </div>
  <div class="table-responsive">
    <table class="table table-dark table-hover align-middle mb-0" style="font-size:.82rem">
      <thead><tr style="border-color:rgba(255,255,255,.06)">
        <th style="color:#334155;font-size:.66rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_user')) ?></th>
        <th style="color:#334155;font-size:.66rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_role')) ?></th>
        <th style="color:#334155;font-size:.66rem;font-weight:700;letter-spacing:.07em"><?= e(__('users.col_joined')) ?></th>
      </tr></thead>
      <tbody>
        <?php foreach ($recentUsers as $u): ?>
        <tr><td>
          <div class="d-flex align-items-center gap-2">
            <div style="width:28px;height:28px;border-radius:50%;background:rgba(124,58,237,.2);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#a78bfa;flex-shrink:0"><?= e(mb_strtoupper(mb_substr((string)$u['name'],0,1))) ?></div>
            <div>
              <div class="text-white"><?= e((string)$u['name']) ?></div>
              <div class="text-secondary" style="font-size:.74rem"><?= e((string)$u['email']) ?></div>
            </div>
          </div>
        </td>
        <td><span class="badge bg-secondary-subtle text-secondary-emphasis"><?= e((string)$u['role_name']) ?></span></td>
        <td class="text-secondary"><?= e(localDate(substr((string)$u['created_at'],0,10))) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php View::start('scripts'); ?>
<script>
Chart.defaults.color='#64748b';
Chart.defaults.borderColor='rgba(255,255,255,.06)';
Chart.defaults.font.family="'Space Grotesk', sans-serif";

var ml=<?= json_encode($monthlyLabels,JSON_UNESCAPED_UNICODE) ?>;
var mc=<?= json_encode($monthlyCounts) ?>;
var rl=<?= json_encode($roleLabels,JSON_UNESCAPED_UNICODE) ?>;
var rc=<?= json_encode($roleCounts) ?>;
var rco=<?= json_encode($roleColors) ?>;
var d7l=<?= json_encode($days7Labels,JSON_UNESCAPED_UNICODE) ?>;
var d7c=<?= json_encode($days7Counts) ?>;
if(!rco.length) rco=['#7c3aed','#06b6d4','#10b981','#f59e0b'];

new Chart(document.getElementById('revenueChart'),{
  type:'line',
  data:{labels:ml,datasets:[{label:<?= json_encode(__('dashboard.registrations')) ?>,data:mc,borderColor:'#7c3aed',backgroundColor:'rgba(124,58,237,.1)',borderWidth:2.5,pointBackgroundColor:'#7c3aed',pointRadius:4,tension:.4,fill:true}]},
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(255,255,255,.04)'},beginAtZero:true,ticks:{precision:0}},x:{grid:{display:false}}}}
});
new Chart(document.getElementById('trafficChart'),{
  type:'doughnut',
  data:{labels:rl.length?rl:['No data'],datasets:[{data:rc.length?rc:[1],backgroundColor:rco,borderWidth:0,hoverOffset:8}]},
  options:{cutout:'72%',plugins:{legend:{display:false}}}
});
new Chart(document.getElementById('signupChart'),{
  type:'bar',
  data:{labels:d7l,datasets:[{label:<?= json_encode(__('dashboard.registrations')) ?>,data:d7c,backgroundColor:'rgba(124,58,237,.65)',borderColor:'#7c3aed',borderWidth:1,borderRadius:6,hoverBackgroundColor:'#7c3aed'}]},
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'rgba(255,255,255,.04)'},ticks:{precision:0}},x:{grid:{display:false}}}}
});
</script>
<?php View::end(); ?>
