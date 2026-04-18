<?php /* views/docs/index.php */ ?>

<style>
.doc-section { scroll-margin-top: 80px; }
.doc-card { background: var(--fw-card); border: 1px solid var(--fw-border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
.doc-h2 { font-size: 1rem; font-weight: 700; color: #f1f5f9; margin-bottom: .9rem; display: flex; align-items: center; gap: .5rem; }
.doc-h3 { font-size: .84rem; font-weight: 700; color: #94a3b8; margin: 1.1rem 0 .5rem; letter-spacing: .02em; }
.doc-p  { font-size: .84rem; color: #64748b; line-height: 1.7; margin-bottom: .6rem; }
.doc-p a { color: #a78bfa; text-decoration: none; }
.doc-p a:hover { text-decoration: underline; }
.code-block { background: #080d1a; border: 1px solid rgba(255,255,255,.07); border-radius: 8px; padding: .9rem 1.1rem; font-family: 'JetBrains Mono', monospace; font-size: .76rem; color: #94a3b8; line-height: 1.7; overflow-x: auto; margin: .5rem 0 .9rem; white-space: pre; }
.code-block .cm  { color: #334155; }
.code-block .kw  { color: #c084fc; }
.code-block .st  { color: #86efac; }
.code-block .fn  { color: #67e8f9; }
.code-block .cl  { color: #fbbf24; }
.inline-code { font-family: 'JetBrains Mono', monospace; font-size: .78rem; background: rgba(124,58,237,.12); color: #c084fc; padding: .08rem .35rem; border-radius: 4px; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.toc-link { display: block; font-size: .81rem; color: #475569; text-decoration: none; padding: .28rem .5rem; border-radius: 6px; transition: .14s; }
.toc-link:hover { background: rgba(255,255,255,.04); color: #94a3b8; }
.toc-link.active-toc { color: #a78bfa; background: rgba(124,58,237,.1); }
.method-badge { font-family: 'JetBrains Mono', monospace; font-size: .7rem; font-weight: 700; padding: .12rem .38rem; border-radius: 4px; }
.method-get    { background: rgba(16,185,129,.15); color: #34d399; }
.method-post   { background: rgba(245,158,11,.15);  color: #fbbf24; }
.method-delete { background: rgba(239,68,68,.15);   color: #f87171; }
</style>

<!-- Status bar -->
<div class="d-flex flex-wrap gap-3 mb-4">
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:160px">
    <span class="status-dot" style="background:<?= $dbConnected ? '#22c55e' : '#ef4444' ?>;box-shadow:0 0 6px <?= $dbConnected ? '#22c55e' : '#ef4444' ?>"></span>
    <span style="font-size:.8rem;color:#94a3b8">Database</span>
    <span class="ms-auto" style="font-size:.78rem;color:<?= $dbConnected ? '#22c55e' : '#ef4444' ?>;font-weight:600"><?= $dbConnected ? 'Connected' : 'Not Connected' ?></span>
  </div>
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:160px">
    <i class="bi bi-cpu text-info"></i>
    <span style="font-size:.8rem;color:#94a3b8">PHP Version</span>
    <span class="ms-auto inline-code"><?= e($phpVersion) ?></span>
  </div>
  <div class="fw-card py-2 px-3 d-flex align-items-center gap-2" style="flex:1;min-width:200px">
    <i class="bi bi-link-45deg text-primary"></i>
    <span style="font-size:.8rem;color:#94a3b8">Base URL</span>
    <span class="ms-auto" style="font-size:.75rem;color:#a78bfa;font-family:'JetBrains Mono',monospace"><?= e($baseUrl) ?></span>
  </div>
</div>

<div class="row g-4">
  <!-- TOC (sticky) -->
  <div class="col-lg-3 d-none d-lg-block">
    <div style="position:sticky;top:76px">
      <div class="fw-card py-2 px-2">
        <div style="font-size:.63rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#334155;padding:.3rem .4rem .6rem">Contents</div>
        <a class="toc-link" href="#install">Installation</a>
        <a class="toc-link" href="#database">Database Setup</a>
        <a class="toc-link" href="#routing">Routing</a>
        <a class="toc-link" href="#controllers">Controllers</a>
        <a class="toc-link" href="#views">Views & Layouts</a>
        <a class="toc-link" href="#request">Request & Validation</a>
        <a class="toc-link" href="#helpers">Helper Functions</a>
        <a class="toc-link" href="#auth">Auth & Sessions</a>
        <a class="toc-link" href="#db-layer">DB Layer</a>
        <a class="toc-link" href="#security">Security</a>
        <a class="toc-link" href="#structure">File Structure</a>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <div class="col-lg-9">

    <!-- ── Installation ─────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="install">
      <div class="doc-h2"><i class="bi bi-download text-primary"></i> Installation</div>
      <p class="doc-p">Pure PHP requires <strong>PHP 8.1+</strong> and Apache with <span class="inline-code">mod_rewrite</span> enabled (or Nginx with equivalent rewrite rules).</p>

      <div class="doc-h3">1 — Apache vhost</div>
      <div class="code-block"><span class="cm"># /etc/apache2/sites-available/purephp.test.conf</span>
&lt;VirtualHost *:80&gt;
    ServerName  purephp.test
    DocumentRoot /var/www/purephp

    &lt;Directory /var/www/purephp&gt;
        AllowOverride All
        Require all granted
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;</div>

      <div class="doc-h3">2 — Nginx (alternative)</div>
      <div class="code-block">server {
    listen 80;
    server_name purephp.test;
    root /var/www/purephp;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}</div>
        include fastcgi_params;
    }
}</div>

      <div class="doc-h3">3 — Hosts file</div>
      <div class="code-block"><span class="cm"># /etc/hosts  (or C:\Windows\System32\drivers\etc\hosts on Windows)</span>
127.0.0.1   purephp.test</div>

      <p class="doc-p">That's it — no Composer, no npm. Open <a href="<?= url('/') ?>"><?= e($baseUrl) ?></a> in your browser.</p>
    </div>

    <!-- ── Database ─────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="database">
      <div class="doc-h2"><i class="bi bi-database text-success"></i> Database Setup</div>
      <p class="doc-p">The framework ships with a complete SQL schema and demo seed data in <span class="inline-code">database.sql</span>.</p>

      <div class="doc-h3">1 — Create database & import</div>
      <div class="code-block"><span class="cm"># From the terminal:</span>
mysql -u root -p -e "CREATE DATABASE purephp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p purephp &lt; database.sql

<span class="cm"># Or via phpMyAdmin / TablePlus: import database.sql directly.</span></div>

      <div class="doc-h3">2 — Edit credentials</div>
      <div class="code-block"><span class="cm">// app/config/database.php</span>
<span class="kw">return</span> [
    <span class="st">'host'</span>     =&gt; <span class="st">'localhost'</span>,
    <span class="st">'database'</span> =&gt; <span class="st">'purephp'</span>,
    <span class="st">'username'</span> =&gt; <span class="st">'root'</span>,
    <span class="st">'password'</span> =&gt; <span class="st">''</span>,
];</div>

      <div class="doc-h3">Demo accounts (all passwords: <span class="inline-code">password</span>)</div>
      <div class="table-responsive">
        <table class="table table-dark table-sm align-middle mb-0" style="font-size:.8rem">
          <thead><tr><th style="color:#475569">Email</th><th style="color:#475569">Role</th><th style="color:#475569">Status</th></tr></thead>
          <tbody>
            <tr><td class="text-white">admin@demo.com</td><td><span class="badge bg-primary-subtle text-primary-emphasis">Administrator</span></td><td><span class="badge bg-success-subtle text-success-emphasis">Active</span></td></tr>
            <tr><td class="text-white">alice@mail.com</td><td><span class="badge bg-primary-subtle text-primary-emphasis">Administrator</span></td><td><span class="badge bg-success-subtle text-success-emphasis">Active</span></td></tr>
            <tr><td class="text-white">bob@mail.com</td><td><span class="badge bg-info-subtle text-info-emphasis">Developer</span></td><td><span class="badge bg-success-subtle text-success-emphasis">Active</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ── Routing ──────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="routing">
      <div class="doc-h2"><i class="bi bi-signpost-2 text-info"></i> Routing</div>
      <p class="doc-p">All routes are defined in <span class="inline-code">app/config/routes.php</span>. The Router maps URI patterns to <span class="inline-code">Controller@method</span> pairs.</p>

      <div class="code-block"><span class="cm">// Basic HTTP methods</span>
$router-&gt;<span class="fn">get</span>(<span class="st">'/users'</span>,         <span class="st">'UsersController@index'</span>)-&gt;<span class="fn">name</span>(<span class="st">'users.index'</span>);
$router-&gt;<span class="fn">post</span>(<span class="st">'/users'</span>,        <span class="st">'UsersController@store'</span>);
$router-&gt;<span class="fn">delete</span>(<span class="st">'/users/{id}'</span>, <span class="st">'UsersController@destroy'</span>);

<span class="cm">// Route parameters  →  /users/42</span>
$router-&gt;<span class="fn">get</span>(<span class="st">'/users/{id}'</span>,    <span class="st">'UsersController@show'</span>);

<span class="cm">// Grouped prefix  →  /admin/settings, /admin/logs</span>
$router-&gt;<span class="fn">group</span>(<span class="st">'admin'</span>, <span class="kw">function</span>(<span class="cl">Router</span> $r) {
    $r-&gt;<span class="fn">get</span>(<span class="st">'/settings'</span>, <span class="st">'AdminController@settings'</span>);
    $r-&gt;<span class="fn">get</span>(<span class="st">'/logs'</span>,     <span class="st">'AdminController@logs'</span>);
});</div>

      <div class="doc-h3">Available routes in this project</div>
      <div class="d-flex flex-column gap-1" style="font-size:.78rem;font-family:'JetBrains Mono',monospace">
        <?php
        $routes = [
          ['GET',  '/',               'Dashboard'],
          ['GET',  '/users',          'User list (DataTables)'],
          ['POST', '/users',          'Create user'],
          ['POST', '/users/{id}',     'Update user'],
          ['POST', '/users/{id}/delete','Delete user'],
          ['GET',  '/roles',          'Roles & permissions matrix'],
          ['POST', '/roles',          'Create role'],
          ['POST', '/roles/{id}',     'Update role'],
          ['POST', '/roles/{id}/delete','Delete role'],
          ['GET',  '/components',     'UI component showcase'],
          ['GET',  '/docs',           'Documentation (this page)'],
          ['GET',  '/api/stats',      'JSON stats endpoint'],
          ['GET',  '/login',          'Login page'],
          ['POST', '/login',          'Process login'],
          ['GET',  '/logout',         'Destroy session'],
        ];
        foreach ($routes as [$m, $path, $desc]):
          $mc = $m === 'GET' ? 'method-get' : ($m === 'POST' ? 'method-post' : 'method-delete');
        ?>
        <div class="d-flex align-items-center gap-2 py-1" style="border-bottom:1px solid rgba(255,255,255,.04)">
          <span class="method-badge <?= $mc ?>"><?= $m ?></span>
          <span class="text-white"><?= e($path) ?></span>
          <span class="ms-auto text-secondary" style="font-family:'Space Grotesk',sans-serif"><?= e($desc) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Controllers ──────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="controllers">
      <div class="doc-h2"><i class="bi bi-cpu text-warning"></i> Controllers</div>
      <p class="doc-p">Create a file in <span class="inline-code">app/controllers/</span>. Extend <span class="inline-code">Controller</span>. The first argument of every method is always a <span class="inline-code">Request</span> object; route params follow.</p>

      <div class="code-block"><span class="cm">// app/controllers/ProductController.php</span>
<span class="kw">class</span> <span class="cl">ProductController</span> <span class="kw">extends</span> <span class="cl">Controller</span>
{
    <span class="kw">public function</span> <span class="fn">index</span>(<span class="cl">Request</span> $req): <span class="kw">void</span>
    {
        $products = <span class="cl">DB</span>::<span class="fn">fetchAll</span>(<span class="st">'SELECT * FROM products ORDER BY name'</span>);
        $this-&gt;<span class="fn">render</span>(<span class="st">'products/index'</span>, [<span class="st">'products'</span> =&gt; $products]);
    }

    <span class="kw">public function</span> <span class="fn">show</span>(<span class="cl">Request</span> $req, <span class="kw">string</span> $id): <span class="kw">void</span>
    {
        $product = <span class="cl">DB</span>::<span class="fn">fetch</span>(<span class="st">'SELECT * FROM products WHERE id = ?'</span>, [$id]);
        <span class="kw">if</span> (!$product) $this-&gt;<span class="fn">abort</span>(404);
        $this-&gt;<span class="fn">render</span>(<span class="st">'products/show'</span>, compact(<span class="st">'product'</span>));
    }

    <span class="kw">public function</span> <span class="fn">store</span>(<span class="cl">Request</span> $req): <span class="kw">void</span>
    {
        <span class="fn">verify_csrf</span>();
        $v = $req-&gt;<span class="fn">validate</span>([<span class="st">'name'</span> =&gt; <span class="st">'required|min:2'</span>, <span class="st">'price'</span> =&gt; <span class="st">'required|numeric'</span>]);
        <span class="kw">if</span> ($v[<span class="st">'errors'</span>]) { <span class="fn">flash</span>(<span class="st">'error'</span>, <span class="st">'Fix validation errors'</span>); $this-&gt;<span class="fn">redirect</span>(<span class="fn">url</span>(<span class="st">'/products'</span>)); }
        <span class="cl">DB</span>::<span class="fn">insert</span>(<span class="st">'products'</span>, $v[<span class="st">'data'</span>]);
        <span class="fn">flash</span>(<span class="st">'success'</span>, <span class="st">'Product created!'</span>);
        $this-&gt;<span class="fn">redirect</span>(<span class="fn">url</span>(<span class="st">'/products'</span>));
    }
}</div>

      <div class="doc-h3">Controller methods</div>
      <div class="d-flex flex-column gap-1" style="font-size:.8rem">
        <?php foreach ([
          ['render($template, $data, $layout)', 'Render a view inside a layout'],
          ['json($data, $status)',              'Return a JSON response and exit'],
          ['redirect($url, $status)',           'HTTP redirect and exit'],
          ['abort($code, $message)',            'Send HTTP error response'],
        ] as [$sig, $desc]): ?>
        <div class="d-flex align-items-start gap-2 py-1" style="border-bottom:1px solid rgba(255,255,255,.04)">
          <code class="inline-code" style="flex-shrink:0"><?= e($sig) ?></code>
          <span class="text-secondary ms-2"><?= e($desc) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Views ────────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="views">
      <div class="doc-h2"><i class="bi bi-eye text-success"></i> Views &amp; Layouts</div>
      <p class="doc-p">Views are plain PHP files under <span class="inline-code">app/views/</span>. They render inside a layout. Push page-specific scripts using the section API so they always run after Bootstrap is loaded.</p>

      <div class="code-block"><span class="cm">// In your view template (app/views/products/index.php):</span>

&lt;h2&gt;Products&lt;/h2&gt;
&lt;?php <span class="kw">foreach</span> ($products <span class="kw">as</span> $p): ?&gt;
  &lt;p&gt;&lt;?= <span class="fn">e</span>($p[<span class="st">'name'</span>]) ?&gt;&lt;/p&gt;
&lt;?php <span class="kw">endforeach</span>; ?&gt;

&lt;?php <span class="cl">View</span>::<span class="fn">start</span>(<span class="st">'scripts'</span>); ?&gt;
&lt;script&gt;
  <span class="cm">// bootstrap, $, Chart are already available here</span>
  <span class="kw">new</span> bootstrap.Tooltip(document.body);
&lt;/script&gt;
&lt;?php <span class="cl">View</span>::<span class="fn">end</span>(); ?&gt;</div>

      <div class="doc-h3">Custom layout</div>
      <div class="code-block"><span class="cm">// Render with a different layout:</span>
$this-&gt;<span class="fn">render</span>(<span class="st">'auth/login'</span>, [<span class="st">'pageTitle'</span> =&gt; <span class="st">'Login'</span>], <span class="st">'auth'</span>);
<span class="cm">// → loads app/views/layouts/auth.php</span>
<span class="cm">// Omit 3rd param to use 'main' (default).</span></div>
    </div>

    <!-- ── Request ──────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="request">
      <div class="doc-h2"><i class="bi bi-arrow-down-circle text-info"></i> Request &amp; Validation</div>
      <div class="code-block"><span class="kw">public function</span> <span class="fn">store</span>(<span class="cl">Request</span> $req): <span class="kw">void</span>
{
    $name  = $req-&gt;<span class="fn">input</span>(<span class="st">'name'</span>);        <span class="cm">// sanitised POST</span>
    $page  = $req-&gt;<span class="fn">query</span>(<span class="st">'page'</span>, 1);     <span class="cm">// GET with default</span>
    $body  = $req-&gt;<span class="fn">json</span>(<span class="st">'key'</span>);          <span class="cm">// JSON body</span>
    $all   = $req-&gt;<span class="fn">all</span>();                 <span class="cm">// all POST fields</span>
    $isXhr = $req-&gt;<span class="fn">isAjax</span>();              <span class="cm">// AJAX / fetch?</span>

    <span class="cm">// Validation  — rules: required|min:N|max:N|email|numeric|alpha</span>
    $v = $req-&gt;<span class="fn">validate</span>([
        <span class="st">'name'</span>  =&gt; <span class="st">'required|min:2|max:100'</span>,
        <span class="st">'email'</span> =&gt; <span class="st">'required|email'</span>,
        <span class="st">'age'</span>   =&gt; <span class="st">'numeric'</span>,
    ]);

    <span class="kw">if</span> (!<span class="fn">empty</span>($v[<span class="st">'errors'</span>])) {
        <span class="fn">flash</span>(<span class="st">'error'</span>, <span class="st">'Validation failed.'</span>);
        $this-&gt;<span class="fn">redirect</span>(<span class="fn">url</span>(<span class="st">'/products'</span>));
    }
    <span class="cm">// $v['data'] contains sanitised values</span>
}</div>
    </div>

    <!-- ── Helpers ──────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="helpers">
      <div class="doc-h2"><i class="bi bi-tools text-secondary"></i> Helper Functions</div>
      <div class="row g-2">
        <?php foreach ([
          ['url(\'/path\')',       'Absolute URL: http://host/path'],
          ['e($value)',            'HTML-escape output (always use this)'],
          ['csrf_field()',         'Hidden CSRF input for forms'],
          ['verify_csrf()',        'Validate token — call in POST handlers'],
          ['flash(\'key\',\'msg\')','Store one-time flash message'],
          ['get_flash(\'key\')',   'Read + clear flash message'],
          ['auth()',               'Current user array or null'],
          ['isGuest()',            'True if not logged in'],
          ['requireAuth()',        'Redirect to /login if not authed'],
          ['can(\'perm.slug\')',   'Check user permission'],
          ['isActive(\'/path\')', 'Returns "active" if current URI matches'],
          ['dd($var)',             'Dump & die (debug)'],
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

    <!-- ── Auth ─────────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="auth">
      <div class="doc-h2"><i class="bi bi-shield-lock text-warning"></i> Auth &amp; Sessions</div>
      <div class="code-block"><span class="cm">// Protect any route — add at top of controller method:</span>
<span class="fn">requireAuth</span>();

<span class="cm">// Check role:</span>
<span class="kw">if</span> (<span class="fn">hasRole</span>(<span class="st">'admin'</span>)) { <span class="cm">/* admin-only code */</span> }

<span class="cm">// Check permission:</span>
<span class="kw">if</span> (!<span class="fn">can</span>(<span class="st">'users.delete'</span>)) $this-&gt;<span class="fn">abort</span>(403);

<span class="cm">// Current user data:</span>
$user = <span class="fn">auth</span>();
<span class="cm">// Keys: id, name, email, role_id, role_name, role_slug, permissions[]</span>

<span class="cm">// Session is started in index.php — just use $_SESSION directly or:</span>
$_SESSION[<span class="st">'my_key'</span>] = <span class="st">'value'</span>;
<span class="fn">session</span>(<span class="st">'my_key'</span>);  <span class="cm">// read via helper</span></div>
    </div>

    <!-- ── DB Layer ──────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="db-layer">
      <div class="doc-h2"><i class="bi bi-database text-primary"></i> DB Layer</div>
      <p class="doc-p">The <span class="inline-code">DB</span> class is a thin PDO wrapper. It uses prepared statements for all queries, preventing SQL injection by default.</p>
      <div class="code-block"><span class="cm">// Fetch multiple rows</span>
$users = <span class="cl">DB</span>::<span class="fn">fetchAll</span>(<span class="st">'SELECT * FROM users WHERE status = ?'</span>, [<span class="st">'active'</span>]);

<span class="cm">// Fetch one row (or null)</span>
$user = <span class="cl">DB</span>::<span class="fn">fetch</span>(<span class="st">'SELECT * FROM users WHERE email = ?'</span>, [$email]);

<span class="cm">// Single scalar value</span>
$count = <span class="cl">DB</span>::<span class="fn">scalar</span>(<span class="st">'SELECT COUNT(*) FROM users'</span>);

<span class="cm">// INSERT — returns new ID</span>
$id = <span class="cl">DB</span>::<span class="fn">insert</span>(<span class="st">'users'</span>, [<span class="st">'name'</span> =&gt; <span class="st">'Alice'</span>, <span class="st">'email'</span> =&gt; <span class="st">'alice@x.com'</span>]);

<span class="cm">// UPDATE — returns affected rows</span>
<span class="cl">DB</span>::<span class="fn">update</span>(<span class="st">'users'</span>, [<span class="st">'status'</span> =&gt; <span class="st">'inactive'</span>], <span class="st">'id = ?'</span>, [$id]);

<span class="cm">// DELETE</span>
<span class="cl">DB</span>::<span class="fn">delete</span>(<span class="st">'users'</span>, <span class="st">'id = ?'</span>, [$id]);

<span class="cm">// Raw prepared query</span>
<span class="cl">DB</span>::<span class="fn">query</span>(<span class="st">'UPDATE users SET last_login = NOW() WHERE id = ?'</span>, [$id]);</div>
    </div>

    <!-- ── Security ─────────────────────────────────────────────── -->
    <div class="doc-card doc-section" id="security">
      <div class="doc-h2"><i class="bi bi-lock-fill text-danger"></i> Security Notes</div>
      <div class="row g-2">
        <?php foreach ([
          ['CSRF Protection',     'All forms use csrf_field(). verify_csrf() checks POST requests. Tokens are session-stored and compared with hash_equals().'],
          ['XSS Prevention',      'Always use e() to echo untrusted data. The View also HTML-encodes by default.'],
          ['SQL Injection',       'DB class uses PDO prepared statements exclusively. Never interpolate user input into SQL strings.'],
          ['Password Hashing',    'Passwords stored with password_hash(PASSWORD_BCRYPT). Verification via password_verify(). Never store plain text.'],
          ['Session Security',    'session_regenerate_id(true) on login. Session cookies: httponly=true, samesite=Lax.'],
          ['.htaccess',           'Blocks direct access to /core/, /app/, and .git. All requests funneled through index.php.'],
        ] as [$title, $desc]): ?>
        <div class="col-md-6">
          <div class="fw-card py-2 px-3 h-100">
            <div style="font-size:.8rem;font-weight:600;color:#f1f5f9;margin-bottom:.3rem">
              <i class="bi bi-check-circle-fill text-success me-2" style="font-size:.75rem"></i><?= e($title) ?>
            </div>
            <div style="font-size:.77rem;color:#475569;line-height:1.55"><?= e($desc) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── File structure ───────────────────────────────────────── -->
    <div class="doc-card doc-section" id="structure">
      <div class="doc-h2"><i class="bi bi-folder2-open text-info"></i> File Structure</div>
      <div class="code-block">purephp/
├── index.php                    <span class="cm">← Front controller (entry point)</span>
├── .htaccess                    <span class="cm">← URL rewriting + security</span>
├── database.sql                 <span class="cm">← Schema + seed data</span>
│
├── core/                        <span class="cm">← Framework internals</span>
│   ├── Router.php               <span class="cm">← Route registration &amp; dispatch</span>
│   ├── Controller.php           <span class="cm">← Base controller (render/json/redirect)</span>
│   ├── View.php                 <span class="cm">← Template renderer + section system</span>
│   ├── Request.php              <span class="cm">← Input sanitisation + validation</span>
│   ├── DB.php                   <span class="cm">← PDO wrapper</span>
│   └── helpers.php              <span class="cm">← url(), e(), csrf_*(), auth(), flash()…</span>
│
└── app/
    ├── config/
    │   ├── routes.php           <span class="cm">← All route definitions</span>
    │   └── database.php         <span class="cm">← DB credentials</span>
    │
    ├── controllers/
    │   ├── HomeController.php
    │   ├── UsersController.php
    │   ├── RolesController.php
    │   ├── AuthController.php
    │   ├── ComponentsController.php
    │   └── DocsController.php
    │
    └── views/
        ├── layouts/
        │   ├── main.php         <span class="cm">← Default layout (sidebar + topbar)</span>
        │   └── auth.php         <span class="cm">← Minimal centered layout for login</span>
        ├── home/index.php       <span class="cm">← Dashboard (charts)</span>
        ├── users/index.php      <span class="cm">← User management (DataTables)</span>
        ├── roles/index.php      <span class="cm">← Roles &amp; permissions matrix</span>
        ├── components/index.php <span class="cm">← UI component showcase</span>
        ├── docs/index.php       <span class="cm">← This page</span>
        ├── auth/login.php       <span class="cm">← Login form</span>
        └── errors/error.php     <span class="cm">← 404 / 500 error page</span></div>
    </div>

  </div><!-- /col -->
</div><!-- /row -->

<?php View::start('scripts'); ?>
<script>
// Highlight active TOC link on scroll
const sections = document.querySelectorAll('.doc-section');
const tocLinks  = document.querySelectorAll('.toc-link');
if (tocLinks.length) {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        tocLinks.forEach(l => l.classList.remove('active-toc'));
        const active = document.querySelector('.toc-link[href="#' + entry.target.id + '"]');
        if (active) active.classList.add('active-toc');
      }
    });
  }, { threshold: 0.35 });
  sections.forEach(s => obs.observe(s));
}
</script>
<?php View::end(); ?>
