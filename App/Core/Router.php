<?php

namespace App\Core;

class Router
{
	/*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    | If no controller or method is provided in the URL,
    | the system will use these defaults.
    */
	protected $controller = 'Auth';
	protected $method = 'login';
	protected $params = [];


	/*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    | This runs automatically when Router is created.
    | It decides:
    | 1. Which controller to use
    | 2. Which method to call
    | 3. What parameters to pass
    */
	public function __construct()
	{
		// Break the URL into parts
		$url = $this->parseUrl();
		
        if (!isset($_SESSION['user_id'])) {
            // Allow only auth routes without login
            $publicControllers = ['Auth'];
            $requestedController = !empty($url[0]) ? ucfirst($url[0]) : 'Auth';

            if (!in_array($requestedController, $publicControllers)) {
                $this->controller = 'Auth';
                $this->method     = 'login';
            } else {
                $this->controller = $requestedController;
                $this->method     = !empty($url[1])
                    ? $url[1]
                    : ($requestedController === 'Auth' ? 'login' : 'index');
                $this->params     = array_slice($url, 2);
            }
        } else {
            if (!empty($url[0])) $this->controller = ucfirst($url[0]);
            if (!empty($url[1])) $this->method     = $url[1];
            $this->params = array_slice($url, 2);
        }
	

		/*
        |--------------------------------------------------------------------------
        | Build Full Controller Class Name
        |--------------------------------------------------------------------------
        | Example:
        | If controller = Users
        | It becomes:
        | App\Controllers\UsersController
        */
		$controllerClass = "App\\Controllers\\{$this->controller}Controller";

		// Check if controller class exists
		if (!class_exists($controllerClass)) {
			$this->abort("Controller not found.");
		}

		// Create controller object
		$controllerObject = new $controllerClass();

		// Check if method exists inside the controller
		if (!method_exists($controllerObject, $this->method)) {
			$this->abort("Method not found.");
		}

		/*
        |--------------------------------------------------------------------------
        | Call the Controller Method
        |--------------------------------------------------------------------------
        | call_user_func_array allows passing parameters dynamically.
        */
		call_user_func_array(
			[$controllerObject, $this->method],
			$this->params
		);
	}


	/*
    |--------------------------------------------------------------------------
    | parseUrl()
    |--------------------------------------------------------------------------
    | Reads the URL from:
    | index.php?url=Users/edit/5
    |
    | Converts it into an array:
    | ['Users', 'edit', '5']
    */
	protected function parseUrl()
	{
		if (!empty($_GET['url'])) {
			// Remove query string before parsing
			$url = strtok($_GET['url'], '?');
			return array_values(
				explode('/', trim($url, '/'))
			);
		}

		return [];
	}


	/*
    |--------------------------------------------------------------------------
    | abort()
    |--------------------------------------------------------------------------
    | Shows a simple 404 error if something is not found.
    */
	protected function abort($message)
	{
		http_response_code(404);
		echo "<h1>404 Not Found</h1><p>{$message}</p>";
		exit;
	}

	
}
