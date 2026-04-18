<?php

/**
 * Router — maps URI patterns to controller actions.
 *
 * Supports : GET, POST, PUT, PATCH, DELETE
 * Params   : /users/{id}
 * Named    : ->name('route.name')
 * Groups   : $router->group('prefix', fn($r) => ...)
 */
class Router
{
    private array  $routes          = [];
    private array  $named           = [];
    private string $prefix          = '';

    /* ── Registration ──────────────────────────────────────────────────── */

    public function get(string $uri, string $action): self    { return $this->addRoute('GET',    $uri, $action); }
    public function post(string $uri, string $action): self   { return $this->addRoute('POST',   $uri, $action); }
    public function put(string $uri, string $action): self    { return $this->addRoute('PUT',    $uri, $action); }
    public function patch(string $uri, string $action): self  { return $this->addRoute('PATCH',  $uri, $action); }
    public function delete(string $uri, string $action): self { return $this->addRoute('DELETE', $uri, $action); }

    public function group(string $prefix, callable $callback): void
    {
        $prev         = $this->prefix;
        $this->prefix = $prev . '/' . trim($prefix, '/');
        $callback($this);
        $this->prefix = $prev;
    }

    public function name(string $name): self
    {
        $last = array_key_last($this->routes);
        if ($last !== null) {
            $this->routes[$last]['name'] = $name;
            $this->named[$name]          = &$this->routes[$last];
        }
        return $this;
    }

    /* ── Dispatch ──────────────────────────────────────────────────────── */

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        // Method spoofing (_method field in POST forms)
        if ($method === 'POST' && !empty($_POST['_method'])) {
            $method = strtoupper((string) $_POST['_method']);
        }

        $uri = $this->currentUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $params = [];
            if ($this->matches($route['uri'], $uri, $params)) {
                $this->call($route['action'], $params);
                return;
            }
        }

        $this->abort(404);
    }

    /* ── Internals ─────────────────────────────────────────────────────── */

    private function addRoute(string $method, string $uri, string $action): self
    {
        $fullUri        = trim($this->prefix . '/' . ltrim($uri, '/'), '/');
        $this->routes[] = [
            'method' => $method,
            'uri'    => $fullUri ?: '/',
            'action' => $action,
            'name'   => null,
        ];
        return $this;
    }

    /**
     * Resolve the current request path.
     *
     * On Windows / Laragon, REQUEST_URI can be anything —
     * parse_url() may return null, false, or a path with backslashes.
     * We guard every step with explicit casts and fallbacks.
     */
    private function currentUri(): string
    {
        $raw  = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';

        // parse_url returns null|false on malformed input — always fall back to '/'
        $path = parse_url($raw, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        // Normalise: forward-slashes only, no trailing slash (except root)
        $path = str_replace('\\', '/', $path);
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    private function matches(string $routeUri, string $requestUri, array &$params): bool
    {
        $routeUri = '/' . trim($routeUri, '/');
        if ($routeUri !== '/') {
            $routeUri = rtrim($routeUri, '/');
        }

        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#u';

        if (preg_match($pattern, $requestUri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }
        return false;
    }

    private function call(string $action, array $params): void
    {
        [$controllerName, $methodName] = explode('@', $action, 2);

        if (!class_exists($controllerName)) {
            $this->abort(500, "Controller '{$controllerName}' not found.");
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            $this->abort(500, "Method '{$methodName}' not found in '{$controllerName}'.");
            return;
        }

        $request = new Request();
        call_user_func_array(
            [$controller, $methodName],
            array_merge([$request], array_values($params))
        );
    }

    private function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $titles  = [404 => 'Not Found', 500 => 'Server Error', 403 => 'Forbidden'];
        $title   = $titles[$code] ?? 'Error';
        if ($message === '') {
            $message = $code === 404
                ? 'The page you requested could not be found.'
                : 'An internal error occurred.';
        }
        require VIEWS_PATH . '/errors/error.php';
        exit;
    }
}
