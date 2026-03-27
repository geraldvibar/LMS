<?php

namespace App\Core;

class View
{
    protected static $layout = 'default';

    // Change layout (example: login layout)
    public static function setLayout($layout)
    {
        self::$layout = $layout;
    }

    // Render view
    public static function render($view, $data = [])
    {
        // Convert array keys to variables
        if (!empty($data)) {
            extract($data);
        }

        // Path of view file
        $viewFile = APP_PATH . 'Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View file not found.");
        }

        // Capture view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Path of layout file
        $layoutFile = APP_PATH . 'Views/Layout/' . self::$layout . '.php';

        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            // If layout not found, just show content
            echo $content;
        }

        // Reset layout back to default
        self::$layout = 'default';
    }
}
