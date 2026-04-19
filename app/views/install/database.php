<?php /* views/install/database.php — Step 2 */ ?>
<div class="install-card">
  <h2><?= e(__('install.s2_title')) ?></h2>
  <p class="subtitle"><?= e(__('install.s2_sub')) ?></p>

  <div class="row g-3 mb-3">
    <div class="col-8">
      <label class="form-label"><?= e(__('install.db_host')) ?></label>
      <input type="text" id="db_host" class="form-control" value="localhost">
    </div>
    <div class="col-4">
      <label class="form-label"><?= e(__('install.db_port')) ?></label>
      <input type="number" id="db_port" class="form-control" value="3306">
    </div>
    <div class="col-12">
      <label class="form-label"><?= e(__('install.db_name')) ?></label>
      <input type="text" id="db_name" class="form-control" value="purephp">
      <div style="font-size:.73rem;color:#334155;margin-top:.3rem">
        <i class="bi bi-info-circle me-1"></i><?= e(__('install.db_name_hint')) ?>
      </div>
    </div>
    <div class="col-6">
      <label class="form-label"><?= e(__('install.db_user')) ?></label>
      <input type="text" id="db_user" class="form-control" value="root">
    </div>
    <div class="col-6">
      <label class="form-label"><?= e(__('install.db_pass')) ?></label>
      <input type="password" id="db_pass" class="form-control" placeholder="<?= e(__('install.db_pass_ph')) ?>">
    </div>
    <div class="col-12">
      <label class="form-label"><?= e(__('install.site_name')) ?></label>
      <input type="text" id="site_name" class="form-control" value="Pure PHP">
    </div>
  </div>

  <div id="dbTestResult" class="mb-3"></div>

  <div class="d-flex justify-content-between align-items-center mt-2">
    <a href="<?= url('/install') ?>" class="btn-back">
      <i class="bi bi-arrow-left"></i> <?= e(__('common.back')) ?>
    </a>
    <div class="d-flex gap-2">
      <button type="button" class="btn-back" id="btnTestDb">
        <span id="testIcon"><i class="bi bi-plug me-1"></i></span>
        <span id="testText"><?= e(__('install.test_conn')) ?></span>
      </button>
      <button type="button" class="btn-install" id="btnNext" onclick="goNext()" disabled>
        <?= e(__('common.continue')) ?> <i class="bi bi-arrow-right ms-1"></i>
      </button>
    </div>
  </div>
</div>

<?php View::start('scripts'); ?>
<script>
var dbVerified = false;
var testBtn    = document.getElementById('btnTestDb');
var testText   = document.getElementById('testText');
var testResult = document.getElementById('dbTestResult');

function getDbFields() {
  return {
    db_host:   document.getElementById('db_host').value.trim(),
    db_port:   document.getElementById('db_port').value.trim(),
    db_name:   document.getElementById('db_name').value.trim(),
    db_user:   document.getElementById('db_user').value.trim(),
    db_pass:   document.getElementById('db_pass').value,
    site_name: document.getElementById('site_name').value.trim(),
  };
}

async function testConnection() {
  testBtn.disabled  = true;
  testText.textContent = <?= json_encode(__('install.testing')) ?>;
  testResult.style.display = 'none';

  try {
    var res  = await fetch(<?= json_encode(url('/install/test-db')) ?>, {
      method: 'POST',
      body: new URLSearchParams(getDbFields())
    });
    var data = await res.json();
    testResult.style.display = 'block';
    testResult.className = data.ok ? 'ok' : 'fail';
    testResult.innerHTML = (data.ok ? '<i class="bi bi-check-circle-fill me-2"></i>' : '<i class="bi bi-x-circle-fill me-2"></i>') + data.message;
    dbVerified = data.ok;
    document.getElementById('btnNext').disabled = !data.ok;
  } catch(e) {
    testResult.style.display = 'block';
    testResult.className = 'fail';
    testResult.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>' + <?= json_encode(__('install.req_fail')) ?>;
    dbVerified = false;
  }

  testBtn.disabled = false;
  testText.textContent = <?= json_encode(__('install.test_conn')) ?>;
}

testBtn.addEventListener('click', testConnection);
document.addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && !dbVerified) testConnection();
});

function goNext() {
  if (!dbVerified) return;
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = <?= json_encode(url('/install/account')) ?>;
  var fields = getDbFields();
  for (var k in fields) {
    var inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = k; inp.value = fields[k];
    form.appendChild(inp);
  }
  document.body.appendChild(form);
  form.submit();
}
</script>
<?php View::end(); ?>
