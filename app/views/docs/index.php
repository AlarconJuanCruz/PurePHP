<?php /* views/docs/index.php */ ?>
<style>
.doc-section{scroll-margin-top:80px}.doc-card{background:var(--fw-card);border:1px solid var(--fw-border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.doc-h2{font-size:1rem;font-weight:700;color:#f1f5f9;margin-bottom:.9rem;display:flex;align-items:center;gap:.5rem}
.doc-h3{font-size:.84rem;font-weight:700;color:#94a3b8;margin:1.1rem 0 .5rem;letter-spacing:.02em}
.doc-p{font-size:.84rem;color:#64748b;line-height:1.7;margin-bottom:.6rem}
.doc-p a{color:#a78bfa;text-decoration:none}.doc-p a:hover{text-decoration:underline}
.code-block{background:#080d1a;border:1px solid rgba(255,255,255,.07);border-radius:8px;padding:.9rem 1.1rem;font-family:'JetBrains Mono',monospace;font-size:.76rem;color:#94a3b8;line-height:1.7;overflow-x:auto;margin:.5rem 0 .9rem;white-space:pre}
.code-block .cm{color:#334155}.code-block .kw{color:#c084fc}.code-block .st{color:#86efac}.code-block .fn{color:#67e8f9}.code-block .cl{color:#fbbf24}
.inline-code{font-family:'JetBrains Mono',monospace;font-size:.78rem;background:rgba(124,58,237,.12);color:#c084fc;padding:.08rem .35rem;border-radius:4px}
.toc-link{display:block;font-size:.81rem;color:#475569;text-decoration:none;padding:.28rem .5rem;border-radius:6px;transition:.14s}
.toc-link:hover{background:rgba(255,255,255,.04);color:#94a3b8}
.toc-link.active-toc{color:#a78bfa;background:rgba(124,58,237,.1)}
.method-badge{font-family:'JetBrains Mono',monospace;font-size:.7rem;font-weight:700;padding:.12rem .38rem;border-radius:4px}
.method-get{background:rgba(16,185,129,.15);color:#34d399}
.method-post{background:rgba(245,158,11,.15);color:#fbbf24}
.method-delete{background:rgba(239,68,68,.15);color:#f87171}
.status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;flex-shrink:0}
</style>

<!-- Status bar -->
<div class="d-flex flex-wrap gap-3 mb-4">
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:160px">
    <span class="status-dot" style="background:<?= $dbConnected ? '#22c55e' : '#ef4444' ?>;box-shadow:0 0 6px <?= $dbConnected ? '#22c55e' : '#ef4444' ?>"></span>
    <span style="font-size:.8rem;color:#94a3b8"><?= e(__('docs.database')) ?></span>
    <span class="ms-auto" style="font-size:.78rem;color:<?= $dbConnected ? '#22c55e' : '#ef4444' ?>;font-weight:600">
      <?= $dbConnected ? e(__('docs.db_connected')) : e(__('docs.db_missing')) ?>
    </span>
  </div>
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:160px">
    <i class="bi bi-cpu text-info"></i>
    <span style="font-size:.8rem;color:#94a3b8"><?= e(__('docs.php_version')) ?></span>
    <span class="ms-auto inline-code"><?= e($phpVersion) ?></span>
  </div>
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:200px">
    <i class="bi bi-link-45deg text-primary"></i>
    <span style="font-size:.8rem;color:#94a3b8"><?= e(__('docs.base_url_lbl')) ?></span>
    <span class="ms-auto" style="font-size:.75rem;color:#a78bfa;font-family:'JetBrains Mono',monospace"><?= e($baseUrl) ?></span>
  </div>
</div>

<div class="row g-4">
  <!-- TOC -->
  <div class="col-lg-3 d-none d-lg-block">
    <div style="position:sticky;top:76px">
      <div class="fw-card py-2 px-2">
        <div style="font-size:.63rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#334155;padding:.3rem .4rem .6rem"><?= e(__('docs.contents')) ?></div>
        <a class="toc-link" href="#install"><?= e(__('docs.toc_install')) ?></a>
        <a class="toc-link" href="#database"><?= e(__('docs.toc_database')) ?></a>
        <a class="toc-link" href="#routing"><?= e(__('docs.toc_routing')) ?></a>
        <a class="toc-link" href="#controllers"><?= e(__('docs.toc_ctrl')) ?></a>
        <a class="toc-link" href="#views"><?= e(__('docs.toc_views')) ?></a>
        <a class="toc-link" href="#request"><?= e(__('docs.toc_request')) ?></a>
        <a class="toc-link" href="#helpers"><?= e(__('docs.toc_helpers')) ?></a>
        <a class="toc-link" href="#auth"><?= e(__('docs.toc_auth')) ?></a>
        <a class="toc-link" href="#db-layer"><?= e(__('docs.toc_db')) ?></a>
        <a class="toc-link" href="#lang"><?= e(__('docs.toc_lang')) ?></a>
        <a class="toc-link" href="#security"><?= e(__('docs.toc_security')) ?></a>
        <a class="toc-link" href="#structure"><?= e(__('docs.toc_structure')) ?></a>
      </div>
    </div>
  </div>

  <!-- Main docs content -->
  <div class="col-lg-9">

    <div class="doc-card doc-section" id="install">
      <div class="doc-h2"><i class="bi bi-download text-primary"></i> <?= e(__('docs.toc_install')) ?></div>
      <p class="doc-p">Pure PHP requires <strong>PHP 8.1+</strong> and Apache with <span class="inline-code">mod_rewrite</span> enabled (or Nginx).</p>
      <div class="doc-h3">Laragon (Windows)</div>
      <div class="code-block"><span class="cm"># 1. Copy project to C:\laragon\www\purephp\
# 2. Laragon auto-creates the vhost purephp.test
# 3. Visit http://purephp.test — installer starts automatically</span></div>
      <div class="doc-h3">Apache vhost (Linux)</div>
      <div class="code-block">&lt;VirtualHost *:80&gt;
    ServerName  purephp.test
    DocumentRoot /var/www/purephp
    &lt;Directory /var/www/purephp&gt;
        AllowOverride All
        Require all granted
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;</div>
      <div class="doc-h3">Nginx</div>
      <div class="code-block">server {
    listen 80;
    server_name purephp.test;
    root /var/www/purephp;
    index index.php;
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}</div>
    </div>

    <div class="doc-card doc-section" id="database">
      <div class="doc-h2"><i class="bi bi-database text-success"></i> <?= e(__('docs.toc_database')) ?></div>
      <p class="doc-p">The installer handles everything automatically. For manual setup:</p>
      <div class="code-block"><span class="cm"># Terminal:</span>
mysql -u root -p -e "CREATE DATABASE purephp CHARACTER SET utf8mb4;"
mysql -u root -p purephp &lt; database.sql</div>
      <div class="doc-h3"><?= e(__('docs.demo_accounts')) ?></div>
      <div class="table-responsive">
        <table class="table table-dark table-sm align-middle mb-0" style="font-size:.8rem">
          <thead><tr><th style="color:#475569"><?= e(__('common.email')) ?></th><th style="color:#475569"><?= e(__('common.role')) ?></th><th style="color:#475569"><?= e(__('common.status')) ?></th></tr></thead>
          <tbody>
            <tr><td class="text-white">admin@demo.com</td><td><span class="badge bg-primary-subtle text-primary-emphasis">Administrator</span></td><td><span class="badge bg-success-subtle text-success-emphasis"><?= e(__('common.active')) ?></span></td></tr>
            <tr><td class="text-white">alice@mail.com</td><td><span class="badge bg-primary-subtle text-primary-emphasis">Administrator</span></td><td><span class="badge bg-success-subtle text-success-emphasis"><?= e(__('common.active')) ?></span></td></tr>
            <tr><td class="text-white">bob@mail.com</td><td><span class="badge bg-info-subtle text-info-emphasis">Developer</span></td><td><span class="badge bg-success-subtle text-success-emphasis"><?= e(__('common.active')) ?></span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="doc-card doc-section" id="routing">
      <div class="doc-h2"><i class="bi bi-signpost-2 text-info"></i> <?= e(__('docs.toc_routing')) ?></div>
      <div class="code-block"><span class="cm">// app/config/routes.php</span>
$router-&gt;<span class="fn">get</span>(<span class="st">'/users'</span>,         <span class="st">'UsersController@index'</span>)-&gt;<span class="fn">name</span>(<span class="st">'users.index'</span>);
$router-&gt;<span class="fn">post</span>(<span class="st">'/users'</span>,        <span class="st">'UsersController@store'</span>);
$router-&gt;<span class="fn">get</span>(<span class="st">'/users/{id}'</span>,   <span class="st">'UsersController@show'</span>);

$router-&gt;<span class="fn">group</span>(<span class="st">'admin'</span>, <span class="kw">function</span>(<span class="cl">Router</span> $r) {
    $r-&gt;<span class="fn">get</span>(<span class="st">'/dashboard'</span>, <span class="st">'AdminController@dashboard'</span>);
});</div>
      <div class="doc-h3"><?= e(__('docs.available_routes')) ?></div>
      <div class="d-flex flex-column gap-1" style="font-size:.78rem;font-family:'JetBrains Mono',monospace">
        <?php
        $routes = [
          ['GET','/',             'Dashboard'],['GET','/users','User list'],['POST','/users','Create user'],
          ['POST','/users/{id}','Update user'],['POST','/users/{id}/delete','Delete user'],
          ['GET','/roles','Roles matrix'],['POST','/roles','Create role'],
          ['GET','/components','UI showcase'],['GET','/docs','Documentation'],
          ['GET','/api/stats','JSON stats'],['GET','/lang/{locale}','Switch language'],
          ['GET','/install','Installer'],['GET','/login','Login'],['POST','/login','Process login'],['GET','/logout','Logout'],
        ];
        foreach ($routes as [$m, $path, $desc]):
          $mc = $m==='GET'?'method-get':($m==='POST'?'method-post':'method-delete');
        ?>
        <div class="d-flex align-items-center gap-2 py-1" style="border-bottom:1px solid rgba(255,255,255,.04)">
          <span class="method-badge <?= $mc ?>"><?= $m ?></span>
          <span class="text-white"><?= e($path) ?></span>
          <span class="ms-auto text-secondary" style="font-family:'Space Grotesk',sans-serif"><?= e($desc) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="doc-card doc-section" id="controllers">
      <div class="doc-h2"><i class="bi bi-cpu text-warning"></i> <?= e(__('docs.toc_ctrl')) ?></div>
      <div class="code-block"><span class="cm">// app/controllers/ProductController.php</span>
<span class="kw">class</span> <span class="cl">ProductController</span> <span class="kw">extends</span> <span class="cl">Controller</span>
{
    <span class="kw">public function</span> <span class="fn">index</span>(<span class="cl">Request</span> $req): <span class="kw">void</span>
    {
        $products = <span class="cl">DB</span>::<span class="fn">fetchAll</span>(<span class="st">'SELECT * FROM products ORDER BY name'</span>);
        $this-&gt;<span class="fn">render</span>(<span class="st">'products/index'</span>, [<span class="st">'products'</span> =&gt; $products]);
    }

    <span class="kw">public function</span> <span class="fn">store</span>(<span class="cl">Request</span> $req): <span class="kw">void</span>
    {
        <span class="fn">verify_csrf</span>();
        $v = $req-&gt;<span class="fn">validate</span>([<span class="st">'name'</span> =&gt; <span class="st">'required|min:2'</span>]);
        <span class="kw">if</span> ($v[<span class="st">'errors'</span>]) { <span class="fn">flash</span>(<span class="st">'error'</span>, <span class="st">'Fix errors'</span>); $this-&gt;<span class="fn">redirect</span>(<span class="fn">url</span>(<span class="st">'/products'</span>)); }
        <span class="cl">DB</span>::<span class="fn">insert</span>(<span class="st">'products'</span>, $v[<span class="st">'data'</span>]);
        <span class="fn">flash</span>(<span class="st">'success'</span>, <span class="fn">__</span>(<span class="st">'products.created'</span>));
        $this-&gt;<span class="fn">redirect</span>(<span class="fn">url</span>(<span class="st">'/products'</span>));
    }
}</div>
    </div>

    <div class="doc-card doc-section" id="views">
      <div class="doc-h2"><i class="bi bi-eye text-success"></i> <?= e(__('docs.toc_views')) ?></div>
      <div class="code-block"><span class="cm">// app/views/products/index.php</span>
&lt;h2&gt;&lt;?= <span class="fn">e</span>(<span class="fn">__</span>(<span class="st">'products.title'</span>)) ?&gt;&lt;/h2&gt;
&lt;?php <span class="kw">foreach</span> ($products <span class="kw">as</span> $p): ?&gt;
  &lt;p&gt;&lt;?= <span class="fn">e</span>($p[<span class="st">'name'</span>]) ?&gt;&lt;/p&gt;
&lt;?php <span class="kw">endforeach</span>; ?&gt;

&lt;?php <span class="cl">View</span>::<span class="fn">start</span>(<span class="st">'scripts'</span>); ?&gt;
&lt;script&gt;
  <span class="cm">// bootstrap, $, Chart are already available here</span>
  console.log(<span class="st">'page loaded'</span>);
&lt;/script&gt;
&lt;?php <span class="cl">View</span>::<span class="fn">end</span>(); ?&gt;

<span class="cm">// Use a different layout (e.g. auth):</span>
$this-&gt;<span class="fn">render</span>(<span class="st">'auth/login'</span>, $data, <span class="st">'auth'</span>);</div>
    </div>

    <div class="doc-card doc-section" id="request">
      <div class="doc-h2"><i class="bi bi-arrow-down-circle text-info"></i> <?= e(__('docs.toc_request')) ?></div>
      <div class="code-block">$req-&gt;<span class="fn">input</span>(<span class="st">'name'</span>)           <span class="cm">// sanitised POST value</span>
$req-&gt;<span class="fn">query</span>(<span class="st">'page'</span>, 1)       <span class="cm">// GET with default</span>
$req-&gt;<span class="fn">json</span>(<span class="st">'key'</span>)             <span class="cm">// JSON body</span>
$req-&gt;<span class="fn">all</span>()                    <span class="cm">// all POST fields</span>

<span class="cm">// Validation rules: required|min:N|max:N|email|numeric|alpha</span>
$v = $req-&gt;<span class="fn">validate</span>([
    <span class="st">'name'</span>  =&gt; <span class="st">'required|min:2|max:100'</span>,
    <span class="st">'email'</span> =&gt; <span class="st">'required|email'</span>,
    <span class="st">'age'</span>   =&gt; <span class="st">'numeric'</span>,
]);
<span class="cm">// $v['data']   → sanitised values
// $v['errors'] → ['field' => ['message']]</span></div>
    </div>

    <div class="doc-card doc-section" id="helpers">
      <div class="doc-h2"><i class="bi bi-tools text-secondary"></i> <?= e(__('docs.toc_helpers')) ?></div>
      <div class="row g-2">
        <?php foreach ([
          ["url('/path')",    'Absolute URL: http://host/path'],
          ["e(\$value)",      'HTML-escape output (always use this)'],
          ["csrf_field()",    'Hidden CSRF input for forms'],
          ["verify_csrf()",   'Validate token in POST handlers'],
          ["flash('k','msg')",'Store one-time flash message'],
          ["get_flash('k')",  'Read + clear flash message'],
          ["auth()",          'Current user array or null'],
          ["requireAuth()",   'Redirect to /login if not logged in'],
          ["can('perm')",     'Check user permission slug'],
          ["isActive('/p')",  'Returns "active" if URI matches'],
          ["__(\'key\')",     'Translate a string key'],
          ["_a(\'key\')",     'Get a translation array'],
          ["localDate(\$d)",  'Format date for current locale'],
          ["dd(\$var)",       'Dump & die (debug)'],
        ] as [$fn, $desc]): ?>
        <div class="col-md-6">
          <div class="d-flex align-items-start gap-2 py-1" style="border-bottom:1px solid rgba(255,255,255,.04)">
            <code class="inline-code" style="font-size:.71rem;flex-shrink:0"><?= e($fn) ?></code>
            <span style="font-size:.78rem;color:#64748b"><?= e($desc) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="doc-card doc-section" id="auth">
      <div class="doc-h2"><i class="bi bi-shield-lock text-warning"></i> <?= e(__('docs.toc_auth')) ?></div>
      <div class="code-block"><span class="cm">// Protect a route</span>
<span class="fn">requireAuth</span>();

<span class="cm">// Check role / permission</span>
<span class="kw">if</span> (<span class="fn">hasRole</span>(<span class="st">'admin'</span>)) { <span class="cm">/* admin only */</span> }
<span class="kw">if</span> (!<span class="fn">can</span>(<span class="st">'users.delete'</span>)) $this-&gt;<span class="fn">abort</span>(403);

<span class="cm">// Current user data</span>
$user = <span class="fn">auth</span>();
<span class="cm">// Keys: id, name, email, role_id, role_name, role_slug, permissions[]</span></div>
    </div>

    <div class="doc-card doc-section" id="db-layer">
      <div class="doc-h2"><i class="bi bi-database text-primary"></i> <?= e(__('docs.toc_db')) ?></div>
      <div class="code-block"><span class="cl">DB</span>::<span class="fn">fetchAll</span>(<span class="st">'SELECT * FROM users WHERE status=?'</span>, [<span class="st">'active'</span>]);
<span class="cl">DB</span>::<span class="fn">fetch</span>(<span class="st">'SELECT * FROM users WHERE email=?'</span>, [$email]);
<span class="cl">DB</span>::<span class="fn">scalar</span>(<span class="st">'SELECT COUNT(*) FROM users'</span>);
<span class="cl">DB</span>::<span class="fn">insert</span>(<span class="st">'users'</span>, [<span class="st">'name'</span>=&gt;<span class="st">'Alice'</span>, <span class="st">'email'</span>=&gt;<span class="st">'alice@x.com'</span>]);  <span class="cm">// returns new ID</span>
<span class="cl">DB</span>::<span class="fn">update</span>(<span class="st">'users'</span>, [<span class="st">'status'</span>=&gt;<span class="st">'active'</span>], <span class="st">'id=?'</span>, [$id]);
<span class="cl">DB</span>::<span class="fn">delete</span>(<span class="st">'users'</span>, <span class="st">'id=?'</span>, [$id]);
<span class="cl">DB</span>::<span class="fn">query</span>(<span class="st">'UPDATE users SET last_login=NOW() WHERE id=?'</span>, [$id]);</div>
    </div>

    <div class="doc-card doc-section" id="lang">
      <div class="doc-h2"><i class="bi bi-translate text-info"></i> <?= e(__('docs.toc_lang')) ?></div>
      <div class="code-block"><span class="cm">// In any controller or view:</span>
<span class="fn">__</span>(<span class="st">'nav.dashboard'</span>)                    <span class="cm">// → 'Dashboard' / 'Panel'</span>
<span class="fn">__</span>(<span class="st">'users.created'</span>)                    <span class="cm">// → 'User created.' / 'Usuario creado.'</span>
<span class="fn">__</span>(<span class="st">'roles.has_users'</span>, [<span class="st">'n'</span>=&gt;3])         <span class="cm">// → ':n user(s)…' with replacement</span>
<span class="fn">_a</span>(<span class="st">'dashboard.days_short'</span>)              <span class="cm">// → ['Mon','Tue',...] / ['Lun','Mar',...]</span>
<span class="fn">localDate</span>(<span class="st">'2024-01-15'</span>)                <span class="cm">// → '2024-01-15' / '15/01/2024'</span>
<span class="fn">currentLocale</span>()                        <span class="cm">// → 'en' / 'es_AR'</span>

<span class="cm">// Switch language via URL:</span>
<span class="cm">// http://purephp.test/lang/es_AR</span>
<span class="cm">// http://purephp.test/lang/en</span>

<span class="cm">// Add a new language: copy app/lang/en.php → app/lang/fr.php</span>
<span class="cm">// Register in core/Lang.php → available() array</span></div>
    </div>

    <div class="doc-card doc-section" id="security">
      <div class="doc-h2"><i class="bi bi-lock-fill text-danger"></i> <?= e(__('docs.toc_security')) ?></div>
      <div class="row g-2">
        <?php foreach ([
          ['CSRF Protection',   'All forms use csrf_field(). verify_csrf() validates POST. Tokens compared with hash_equals().'],
          ['XSS Prevention',    'Always use e() to echo user data. Never echo $_POST/$_GET directly.'],
          ['SQL Injection',     'DB class uses PDO prepared statements exclusively. Never interpolate user input into SQL.'],
          ['Password Hashing',  'Passwords stored with password_hash(PASSWORD_BCRYPT). Verified with password_verify().'],
          ['Session Security',  'session_regenerate_id(true) on login. Cookies: httponly=true, samesite=Lax.'],
          ['.htaccess',         'Blocks access to /core/, /app/, .git. All requests routed through index.php.'],
        ] as [$title, $desc]): ?>
        <div class="col-md-6">
          <div class="fw-card py-2 px-3 h-100">
            <div style="font-size:.8rem;font-weight:600;color:#f1f5f9;margin-bottom:.3rem"><i class="bi bi-check-circle-fill text-success me-2" style="font-size:.75rem"></i><?= e($title) ?></div>
            <div style="font-size:.77rem;color:#475569;line-height:1.55"><?= e($desc) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="doc-card doc-section" id="structure">
      <div class="doc-h2"><i class="bi bi-folder2-open text-info"></i> <?= e(__('docs.toc_structure')) ?></div>
      <div class="code-block">purephp/
├── index.php                    <span class="cm">← Front controller (entry point)</span>
├── .htaccess                    <span class="cm">← URL rewriting + security</span>
├── database.sql                 <span class="cm">← Schema + seed data</span>
│
├── core/
│   ├── Router.php               <span class="cm">← Route registration & dispatch</span>
│   ├── Controller.php           <span class="cm">← Base controller</span>
│   ├── View.php                 <span class="cm">← Template renderer + section system</span>
│   ├── Request.php              <span class="cm">← Input sanitisation + validation</span>
│   ├── DB.php                   <span class="cm">← PDO wrapper</span>
│   ├── Lang.php                 <span class="cm">← Translation engine</span>
│   └── helpers.php              <span class="cm">← url(), e(), __(), auth(), flash()…</span>
│
└── app/
    ├── config/
    │   ├── routes.php           <span class="cm">← All route definitions</span>
    │   ├── database.php         <span class="cm">← DB credentials (auto-generated)</span>
    │   └── .installed           <span class="cm">← Installer lock file</span>
    ├── lang/
    │   ├── en.php               <span class="cm">← English translations</span>
    │   └── es_AR.php            <span class="cm">← Spanish Argentina translations</span>
    ├── controllers/
    │   ├── HomeController.php   <span class="cm">← Dashboard + real chart data</span>
    │   ├── UsersController.php  <span class="cm">← User CRUD</span>
    │   ├── RolesController.php  <span class="cm">← Role + permission CRUD</span>
    │   ├── AuthController.php   <span class="cm">← Login / logout</span>
    │   ├── LangController.php   <span class="cm">← Language switcher</span>
    │   ├── InstallController.php<span class="cm">← Installer wizard</span>
    │   └── DocsController.php
    └── views/
        ├── layouts/
        │   ├── main.php         <span class="cm">← Default layout (sidebar + topbar)</span>
        │   ├── auth.php         <span class="cm">← Minimal login layout</span>
        │   └── install.php      <span class="cm">← Installer wizard layout</span>
        ├── home/index.php       <span class="cm">← Dashboard (real DB charts)</span>
        ├── users/index.php      <span class="cm">← User management (DataTables)</span>
        ├── roles/index.php      <span class="cm">← Roles & permissions matrix</span>
        ├── components/index.php <span class="cm">← UI component showcase</span>
        ├── docs/index.php       <span class="cm">← This page</span>
        ├── install/*.php        <span class="cm">← Installer steps 1–4</span>
        ├── auth/login.php
        └── errors/error.php</div>
    </div>

  </div>
</div>

<?php View::start('scripts'); ?>
<script>
(function() {
  var sections = document.querySelectorAll('.doc-section');
  var links    = document.querySelectorAll('.toc-link');
  if (!links.length) return;
  var obs = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) {
      if (e.isIntersecting) {
        links.forEach(function(l) { l.classList.remove('active-toc'); });
        var a = document.querySelector('.toc-link[href="#'+e.target.id+'"]');
        if (a) a.classList.add('active-toc');
      }
    });
  }, { threshold: 0.25 });
  sections.forEach(function(s) { obs.observe(s); });
}());
</script>
<?php View::end(); ?>
