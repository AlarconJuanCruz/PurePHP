<?php

/**
 * Request — wraps incoming HTTP data with safe accessors.
 */
class Request
{
    public readonly string $method;
    public readonly string $uri;
    public readonly string $ip;

    public function __construct()
    {
        $this->method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        // parse_url can return null/false on Windows / Laragon — always cast to string
        $raw       = (string)($_SERVER['REQUEST_URI'] ?? '/');
        $parsedUri = parse_url($raw, PHP_URL_PATH);
        $this->uri = is_string($parsedUri) && $parsedUri !== '' ? $parsedUri : '/';

        $this->ip  = $this->resolveIp();
    }

    /* ── Input accessors ───────────────────────────────────────────────── */

    /** Sanitised GET parameter */
    public function query(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    /** Sanitised POST parameter */
    public function input(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    /** All POST parameters (sanitised) */
    public function all(): array
    {
        return array_map([$this, 'sanitize'], $_POST);
    }

    /** JSON body parameter */
    public function json(string $key = null, mixed $default = null): mixed
    {
        static $body = null;
        if ($body === null) {
            $raw  = (string) file_get_contents('php://input');
            $body = json_decode($raw, true) ?? [];
        }
        return $key === null ? $body : ($body[$key] ?? $default);
    }

    /** True if request is XMLHttpRequest / fetch with JSON accept */
    public function isAjax(): bool
    {
        return (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest')
            || str_contains(($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
    }

    /* ── Validation ────────────────────────────────────────────────────── */

    /**
     * Validate POST inputs.
     * Rules: required | min:N | max:N | email | numeric | alpha
     * Returns ['data' => [...], 'errors' => [...]]
     */
    public function validate(array $rules): array
    {
        $errors = [];
        $data   = [];

        foreach ($rules as $field => $ruleString) {
            $value = $this->sanitize((string)($_POST[$field] ?? ''));

            foreach (explode('|', $ruleString) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

                $err = match ($name) {
                    'required' => $value === ''                         ? "{$field} is required."                        : null,
                    'min'      => mb_strlen($value) < (int)$param       ? "{$field} must be at least {$param} chars."    : null,
                    'max'      => mb_strlen($value) > (int)$param       ? "{$field} may not exceed {$param} chars."      : null,
                    'email'    => !filter_var($value, FILTER_VALIDATE_EMAIL) ? "{$field} must be a valid email."         : null,
                    'numeric'  => $value !== '' && !is_numeric($value)  ? "{$field} must be numeric."                    : null,
                    'alpha'    => $value !== '' && !ctype_alpha($value)  ? "{$field} must contain only letters."         : null,
                    default    => null,
                };

                if ($err !== null) {
                    $errors[$field][] = $err;
                }
            }

            $data[$field] = $value;
        }

        return ['data' => $data, 'errors' => $errors];
    }

    /* ── Internals ─────────────────────────────────────────────────────── */

    private function sanitize(mixed $value): string
    {
        if (is_array($value)) {
            return '';   // arrays must be handled explicitly
        }
        return trim(htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }

    private function resolveIp(): string
    {
        foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                return trim(explode(',', (string)$_SERVER[$key])[0]);
            }
        }
        return '0.0.0.0';
    }
}
