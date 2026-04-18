<?php /* views/home/index.php */ ?>

<!-- Stats row -->
<div class="row g-3 mb-4">
  <?php foreach ($stats as $stat): ?>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="stat-icon bg-<?= e($stat['color']) ?>-subtle text-<?= e($stat['color']) ?>-emphasis">
          <i class="bi bi-<?= e($stat['icon']) ?>"></i>
        </div>
        <span class="stat-trend <?= str_starts_with($stat['trend'], '+') ? 'text-success' : 'text-danger' ?>">
          <i class="bi bi-arrow-<?= str_starts_with($stat['trend'], '+') ? 'up' : 'down' ?>-short"></i>
          <?= e($stat['trend']) ?>
        </span>
      </div>
      <div class="stat-value text-white"><?= e($stat['value']) ?></div>
      <div class="stat-label mt-1"><?= e($stat['label']) ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts row -->
<div class="row g-3 mb-4">
  <div class="col-12 col-xl-8">
    <div class="fw-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h6 class="mb-0 fw-semibold text-white">Revenue Overview</h6>
          <small class="text-secondary">Last 7 months</small>
        </div>
        <div class="d-flex gap-1">
          <button class="btn btn-sm px-2 py-1" style="background:rgba(124,58,237,.2);color:#a78bfa;border:1px solid rgba(124,58,237,.3);border-radius:6px;font-size:.75rem">7M</button>
          <button class="btn btn-sm px-2 py-1" style="background:rgba(255,255,255,.04);color:#64748b;border:1px solid rgba(255,255,255,.07);border-radius:6px;font-size:.75rem">1Y</button>
        </div>
      </div>
      <canvas id="revenueChart" height="95"></canvas>
    </div>
  </div>

  <div class="col-12 col-xl-4">
    <div class="fw-card h-100">
      <h6 class="mb-0 fw-semibold text-white">Traffic Sources</h6>
      <small class="text-secondary">This month</small>
      <canvas id="trafficChart" class="mt-3" height="160"></canvas>
      <div class="mt-3 d-flex flex-column gap-2">
        <?php
        $sources = [
          ['Organic',  '41%', '#7c3aed'],
          ['Referral', '28%', '#06b6d4'],
          ['Direct',   '19%', '#10b981'],
          ['Social',   '12%', '#f59e0b'],
        ];
        foreach ($sources as [$label, $pct, $color]): ?>
        <div class="d-flex align-items-center justify-content-between" style="font-size:.8rem">
          <div class="d-flex align-items-center gap-2">
            <span style="width:8px;height:8px;border-radius:50%;background:<?= e($color) ?>;display:inline-block;flex-shrink:0"></span>
            <span class="text-secondary"><?= e($label) ?></span>
          </div>
          <span class="text-white fw-semibold"><?= e($pct) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Bar + activity -->
<div class="row g-3">
  <div class="col-12 col-xl-6">
    <div class="fw-card">
      <h6 class="mb-0 fw-semibold text-white">Weekly Signups</h6>
      <small class="text-secondary">New registrations per day</small>
      <canvas id="signupChart" class="mt-3" height="130"></canvas>
    </div>
  </div>

  <div class="col-12 col-xl-6">
    <div class="fw-card">
      <h6 class="mb-3 fw-semibold text-white">Recent Activity</h6>
      <div class="d-flex flex-column gap-3">
        <?php
        $activities = [
          ['person-plus','primary','New user <strong>alice@mail.com</strong> registered','2m ago'],
          ['credit-card','success','Payment of <strong>$240</strong> received','18m ago'],
          ['bug','danger','Error reported in <strong>API /users</strong>','1h ago'],
          ['arrow-repeat','warning','Deployment <strong>v2.4.1</strong> completed','3h ago'],
          ['chat-dots','info','New support ticket <strong>#4821</strong>','5h ago'],
        ];
        foreach ($activities as [$icon, $color, $text, $time]): ?>
        <div class="d-flex align-items-start gap-3">
          <div class="stat-icon bg-<?= e($color) ?>-subtle text-<?= e($color) ?>-emphasis flex-shrink-0"
               style="width:34px;height:34px;border-radius:8px;font-size:.9rem">
            <i class="bi bi-<?= e($icon) ?>"></i>
          </div>
          <div style="font-size:.82rem">
            <div class="text-white"><?= $text ?></div>
            <div class="text-secondary mt-1"><?= e($time) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php View::start('scripts'); ?>
<script>
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = 'rgba(255,255,255,.06)';
Chart.defaults.font.family = "'Space Grotesk', sans-serif";

new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: ['Oct','Nov','Dec','Jan','Feb','Mar','Apr'],
    datasets: [{
      label: 'Revenue', data: [52000,61000,78000,69000,85000,91000,94210],
      borderColor: '#7c3aed', backgroundColor: 'rgba(124,58,237,.1)',
      borderWidth: 2.5, pointBackgroundColor: '#7c3aed', pointRadius: 4, tension: .4, fill: true,
    },{
      label: 'Target', data: [55000,62000,72000,74000,80000,88000,92000],
      borderColor: '#06b6d4', borderWidth: 2, borderDash: [6,4], pointRadius: 0, tension: .4,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'top', labels: { boxWidth: 12, useBorderRadius: true, borderRadius: 3 } } },
    scales: {
      y: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { callback: v => '$'+(v/1000).toFixed(0)+'k' } },
      x: { grid: { display: false } }
    }
  }
});

new Chart(document.getElementById('trafficChart'), {
  type: 'doughnut',
  data: {
    labels: ['Organic','Referral','Direct','Social'],
    datasets: [{ data: [41,28,19,12], backgroundColor: ['#7c3aed','#06b6d4','#10b981','#f59e0b'], borderWidth: 0, hoverOffset: 8 }]
  },
  options: { cutout: '72%', plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('signupChart'), {
  type: 'bar',
  data: {
    labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
    datasets: [{
      label: 'Signups', data: [148,210,193,267,312,189,95],
      backgroundColor: 'rgba(124,58,237,.65)', borderColor: '#7c3aed',
      borderWidth: 1, borderRadius: 6, hoverBackgroundColor: '#7c3aed',
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,.04)' } }, x: { grid: { display: false } } }
  }
});
</script>
<?php View::end(); ?>
