<?php

/**
 * View — renders PHP templates with layout + section support.
 *
 * Usage inside a controller:
 *   $this->render('home/index', ['title' => 'Home']);
 *
 * Template hierarchy:
 *   views/layouts/{layout}.php   ← wraps everything
 *   views/{template}.php         ← page content
 *
 * Section system (use in any view template):
 *   <?php View::start('scripts') ?>
 *     <script> ... </script>
 *   <?php View::end() ?>
 *
 * Layout yields a section:
 *   <?php View::yield('scripts') ?>
 */
class View
{
    /** Named content slots (content, scripts, etc.) */
    private array $slots = [];

    /** Static section buffer — shared across the render cycle */
    private static array  $sections       = [];
    private static string $activeSection  = '';

    /* ================================================================== */
    /* Instance API                                                         */
    /* ================================================================== */

    public function render(string $template, array $data = [], string $layout = 'main'): void
    {
        // Reset section buffer for each render
        self::$sections      = [];
        self::$activeSection = '';

        $templateFile = VIEWS_PATH . '/' . str_replace('.', '/', $template) . '.php';
        $layoutFile   = VIEWS_PATH . '/layouts/' . $layout . '.php';

        if (!file_exists($templateFile)) {
            throw new \RuntimeException("View not found: {$templateFile}");
        }

        // Render inner template → capture output (sections are captured here too)
        $content = $this->capture($templateFile, $data);

        $this->slots['content'] = $content;

        if ($layoutFile && file_exists($layoutFile)) {
            extract($data, EXTR_SKIP);
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /** Output a named slot inside a layout (instance method) */
    public function slot(string $name): void
    {
        echo $this->slots[$name] ?? '';
    }

    /* ================================================================== */
    /* Static section API (called from inside view templates)              */
    /* ================================================================== */

    /**
     * Start capturing a named section.
     * Call View::end() to close it.
     */
    public static function start(string $name): void
    {
        self::$activeSection = $name;
        ob_start();
    }

    /** Close the current section and store its content. */
    public static function end(): void
    {
        if (self::$activeSection === '') {
            ob_end_clean();
            return;
        }
        self::$sections[self::$activeSection] = (self::$sections[self::$activeSection] ?? '') . ob_get_clean();
        self::$activeSection = '';
    }

    /**
     * Output a named section inside a layout.
     * Typically used for 'scripts' or 'styles' slots.
     */
    public static function yield(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    /* ================================================================== */
    /* Utilities                                                            */
    /* ================================================================== */

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /* ------------------------------------------------------------------ */

    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }
}
