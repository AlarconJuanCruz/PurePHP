<?php

/**
 * Base Controller
 *
 * All controllers extend this class.
 */
abstract class Controller
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Render a view with an optional layout.
     *
     * @param string $template  e.g. 'home/index'
     * @param array  $data      Variables injected into the view
     * @param string $layout    Layout file under views/layouts/
     */
    protected function render(string $template, array $data = [], string $layout = 'main'): void
    {
        $this->view->render($template, $data, $layout);
    }

    /**
     * Return a JSON response.
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Redirect to a URL.
     */
    protected function redirect(string $url, int $status = 302): never
    {
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }

    /**
     * Abort with HTTP error.
     */
    protected function abort(int $code, string $message = ''): never
    {
        http_response_code($code);
        exit($message);
    }
}
