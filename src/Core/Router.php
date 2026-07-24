<?php
namespace App\Core;

class Router
{
    /**
     * Dispatch the request to the appropriate controller and method
     */
    public function dispatch($url)
    {
        // Remove query string variables
        $url = explode('?', $url)[0];

        // Remove trailing slash
        $url = rtrim($url, '/');

        // Parse URL
        $parts = explode('/', trim($url, '/'));

        // Default controller and method
        $controllerName = 'HomeController'; // Default to Home
        $methodName = 'index';
        $params = [];

        if (!empty($parts[0])) {
            // Capitalize first letter and append 'Controller'
            $controllerName = ucfirst(strtolower($parts[0])) . 'Controller';
        }

        if (!empty($parts[1])) {
            $methodName = strtolower($parts[1]);
        }

        if (count($parts) > 2) {
            $params = array_slice($parts, 2);
        }

        // Namespace for controllers
        $controllerClass = '\\App\\Controllers\\' . $controllerName;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (is_callable([$controller, $methodName])) {
                call_user_func_array([$controller, $methodName], $params);
            } else if (method_exists($controller, 'index') && !empty($parts[1])) {
                // Fallback to index method and treat $parts[1] as a parameter
                array_unshift($params, $parts[1]);
                call_user_func_array([$controller, 'index'], $params);
            } else {
                $this->sendNotFound();
            }
        } else {
            $this->sendNotFound();
        }
    }

    private function sendNotFound()
    {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        exit;
    }
}
