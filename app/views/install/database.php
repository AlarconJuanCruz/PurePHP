<?php /* views/install/database.php — Step 2 */ ?>

<div class="install-card">
  <h2>Database Configuration</h2>
  <p class="subtitle">
    Enter your MySQL connection details. The installer will create the database and import the schema automatically.
  </p>

  <div class="row g-3 mb-3">
    <div class="col-8">
      <label class="form-label">Database Host</label>
      <input type="text" id="db_host" class="form-control" value="localhost" placeholder="localhost">
    </div>
    <div class="col-4">
      <label class="form-label">Port</label>
      <input type="number" id="db_port" class="form-control" value="3306" placeholder="3306">
    </div>
    <div class="col-12">
      <label class="form-label">Database Name</label>
      <input type="text" id="db_name" class="form-control" value="purephp" placeholder="purephp">
      <div style="font-size:.73rem;color:#334155;margin-top:.3rem">
        <i class="bi bi-info-circle me-1"></i>The database will be created automatically if it doesn't exist.
      </div>
    </div>
    <div class="col-6">
      <label class="form-label">Username</label>
      <input type="text" id="db_user" class="form-control" value="root" placeholder="root">
    </div>
    <div class="col-6">
      <label class="form-label">Password</label>
      <input type="password" id="db_pass" class="form-control" placeholder="(blank for no password)">
    </div>
    <div class="col-12">
      <label class="form-label">Site Name</label>
      <input type="text" id="site_name" class="form-control" value="Pure PHP" placeholder="My App">
    </div>
  </div>

  <!-- Test result banner -->
  <div id="dbTestResult" class="mb-3"></div>

  <div class="d-flex justify-content-between align-items-center mt-2">
    <a href="<?= url('/install') ?>" class="btn-back">
      <i class="bi bi-arrow-left"></i> Back
    </a>
    <div class="d-flex gap-2">
      <button type="button" class="btn-back" id="btnTestDb" onclick="testConnection()">
        <i class="bi bi-plug me-1"></i> Test Connection
      </button>
      <button type="button" class="btn-install" id="btnNext" onclick="goNext()" disabled>
        Continue <i class="bi bi-arrow-right ms-1"></i>
      </button>
    </div>
  </div>
</div>

<!-- Hidden form that posts to /install/account -->
<form id="dbForm" action="<?= url('/install/account') ?>" method="GET" style="display:none">
</form>

<?php View::start('scripts'); ?>
<script>
let dbVerified = false;

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
  const btn    = document.getElementById('btnTestDb');
  const result = document.getElementById('dbTestResult');
  const fields = getDbFields();

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Testing…';
  result.style.display = 'none';
  result.className     = '';

  const body = new URLSearchParams(fields);

  try {
    const res  = await fetch('<?= url('/install/test-db') ?>', { method: 'POST', body });
    const data = await res.json();

    result.style.display = 'block';
    if (data.ok) {
      result.className  = 'ok';
      result.innerHTML  = '<i class="bi bi-check-circle-fill me-2"></i>' + data.message;
      dbVerified = true;
      // Store in sessionStorage for form submission
      sessionStorage.setItem('_install_db', JSON.stringify(fields));
      document.getElementById('btnNext').disabled = false;
    } else {
      result.className = 'fail';
      result.innerHTML = '<i class="bi bi-x-circle-fill me-2"></i>' + data.message;
      dbVerified = false;
      document.getElementById('btnNext').disabled = true;
    }
  } catch (e) {
    result.style.display = 'block';
    result.className     = 'fail';
    result.innerHTML     = '<i class="bi bi-x-circle-fill me-2"></i>Request failed. Check your server is running.';
    dbVerified           = false;
    document.getElementById('btnNext').disabled = true;
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="bi bi-plug me-1"></i> Test Connection';
}

function goNext() {
  if (!dbVerified) return;
  const fields = getDbFields();
  // Build URL with DB params so the controller can pick them up
  const params = new URLSearchParams(fields);
  // We use a hidden form to POST to /install/account
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '<?= url('/install/account') ?>';
  for (const [k,v] of Object.entries(fields)) {
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = k; inp.value = v;
    form.appendChild(inp);
  }
  document.body.appendChild(form);
  form.submit();
}

// Allow Enter key to trigger test
document.addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && !dbVerified) testConnection();
});
</script>
<?php View::end(); ?>
