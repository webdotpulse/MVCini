<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\I18n;

class AdminController extends Controller
{
    /**
     * Check if user is logged in and is admin
     */
    private function checkAuth()
    {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/auth/login');
        }
    }

    /**
     * Admin Dashboard - List editable files
     */
    public function index()
    {
        $this->checkAuth();

        $directories = [
            'Models' => __DIR__ . '/../Models/',
            'Views' => __DIR__ . '/../Views/',
            'Controllers' => __DIR__ . '/../Controllers/'
        ];

        $filesList = [];
        foreach ($directories as $type => $dir) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    // Get relative path for easier display and editing
                    $relativePath = str_replace(__DIR__ . '/../', '', $file->getPathname());
                    $filesList[$type][] = $relativePath;
                }
            }
        }

        $this->render('admin/index', ['filesList' => $filesList, 'title' => I18n::get('admin')]);
    }

    /**
     * Create a new file
     */
    public function create()
    {
        $this->checkAuth();

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();

            $type = $_POST['type'] ?? '';
            $filename = $_POST['filename'] ?? '';
            $content = $_POST['content'] ?? '';

            if (empty($type) || empty($filename)) {
                $error = 'Type and filename are required.';
            } else {
                $dir = '';
                switch ($type) {
                    case 'Model': $dir = __DIR__ . '/../Models/'; break;
                    case 'View': $dir = __DIR__ . '/../Views/'; break;
                    case 'Controller': $dir = __DIR__ . '/../Controllers/'; break;
                }

                if (empty($dir)) {
                    $error = 'Invalid type.';
                } else {
                    // Sanitize filename to prevent path traversal
                    $filename = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $filename);
                    if (strpos($filename, '..') !== false) {
                        $error = 'Invalid filename.';
                    } else {
                        if (!str_ends_with($filename, '.php')) {
                            $filename .= '.php';
                        }

                        $fullPath = $dir . $filename;

                        // Ensure directory exists for views (e.g., 'item/new_view.php')
                        $fileDir = dirname($fullPath);
                        if (!is_dir($fileDir)) {
                            mkdir($fileDir, 0777, true);
                        }

                        if (file_exists($fullPath)) {
                            $error = 'File already exists.';
                        } else {
                            file_put_contents($fullPath, $content);
                            $this->redirect('/admin/index');
                        }
                    }
                }
            }
        }

        $this->render('admin/create', [
            'error' => $error,
            'title' => 'Create File'
        ]);
    }

    /**
     * Edit a file
     */
    public function edit()
    {
        $this->checkAuth();

        $file = $_GET['file'] ?? '';
        if (empty($file)) {
            $this->redirect('/admin/index');
        }

        // Basic security check to prevent directory traversal outside src/
        if (strpos($file, '..') !== false) {
            die('Invalid file path.');
        }

        $fullPath = realpath(__DIR__ . '/../' . $file);

        // Ensure the path is within the src directory
        $srcPath = realpath(__DIR__ . '/../');
        if ($fullPath === false || strpos($fullPath, $srcPath) !== 0) {
            die('Invalid file path.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $content = $_POST['content'] ?? '';
            file_put_contents($fullPath, $content);
            $this->redirect('/admin/index');
        }

        $content = file_get_contents($fullPath);
        $this->render('admin/edit', ['file' => $file, 'content' => $content, 'title' => 'Edit ' . $file]);
    }
}
