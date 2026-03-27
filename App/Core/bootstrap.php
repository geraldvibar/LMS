<?php

// Load the initialization file (contains constants like APP_PATH)
require_once dirname(__DIR__) . '/Config/init.php';

/*
|--------------------------------------------------------------------------
| Autoload Function
|--------------------------------------------------------------------------
| This automatically loads PHP classes when they are used.
| No need to manually require files like:
| require_once 'UsersController.php';
|
| It follows a simple PSR-4 style structure.
*/

spl_autoload_register(function ($class) {

    // Only load classes that start with "App\"
    // This prevents loading unrelated classes
    if (strpos($class, 'App\\') !== 0) {
        return;
    }

    // Remove the "App\" part from the namespace
    // Example:
    // App\Controllers\UsersController
    // becomes:
    // Controllers\UsersController
    $class = substr($class, 4);

    // Convert namespace backslashes "\" into folder slashes "/"
    // Example:
    // Controllers\UsersController
    // becomes:
    // Controllers/UsersController
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Build the full file path
    // Example result:
    // App/Controllers/UsersController.php
    $file = APP_PATH . $class . '.php';

    // If the file exists, load it
    if (file_exists($file)) {
        require_once $file;
    }
});
