<?php
namespace App\Core;

class Controller
{
    /**
     * Enforce CSRF token verification for POST requests
     */
    protected function requireCsrf()
    {
        if (!Security::verifyCsrf()) {
            header('HTTP/1.0 403 Forbidden');
            die('CSRF token validation failed.');
        }
    }

    /**
     * Render a view
     *
     * @param string $view View name (e.g., 'item/index')
     * @param array $data Data to pass to the view
     */
    protected function render(string $view, array $data = [])
    {
        // Inject CSRF token to views by default
        $data['csrf_token'] = Security::csrfToken();

        // Extract data so keys become variables
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Start output buffering
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Require layout if it exists
            $layoutFile = __DIR__ . '/../Views/layout.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                echo $content;
            }
        } else {
            die("View {$viewFile} not found!");
        }
    }

    /**
     * Render JSON response for AJAX
     *
     * @param mixed $data Data to encode
     * @param int $statusCode HTTP status code
     */
    protected function jsonResponse($data, int $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a specific URL
     */
    protected function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }
}
