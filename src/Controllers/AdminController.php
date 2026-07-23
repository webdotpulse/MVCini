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

        $this->render('admin/index', [
            'filesList' => $filesList,
            'title' => I18n::get('admin.title'),
            'meta_description' => I18n::get('admin.meta_description'),
            'meta_keywords' => I18n::get('admin.meta_keywords')
        ]);
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

    /**
     * Translations Management
     */
    public function translations()
    {
        $this->checkAuth();

        $i18nDir = __DIR__ . '/../i18n/';
        $dirs = array_diff(scandir($i18nDir), ['.', '..']);

        $languages = [];
        foreach ($dirs as $dir) {
            if (is_dir($i18nDir . $dir)) {
                $languages[] = $dir;
            }
        }

        $this->render('admin/translations', ['languages' => $languages, 'title' => 'Manage Translations']);
    }

    public function translationsDomains($lang = null)
    {
        $this->checkAuth();

        if (!$lang) {
            $this->redirect('/admin/translations');
        }

        // Sanitize lang to prevent path traversal
        $lang = preg_replace('/[^a-zA-Z0-9_-]/', '', $lang);
        $langDir = __DIR__ . "/../i18n/{$lang}/";

        if (!is_dir($langDir)) {
            $this->redirect('/admin/translations');
        }

        $files = array_diff(scandir($langDir), ['.', '..']);
        $domains = [];
        foreach ($files as $file) {
            if (str_ends_with($file, '.php')) {
                $domains[] = str_replace('.php', '', $file);
            }
        }

        $this->render('admin/translations_domains', ['lang' => $lang, 'domains' => $domains, 'title' => 'Manage Domains: ' . strtoupper($lang)]);
    }

    public function editTranslation($lang = null, $domain = null)
    {
        $this->checkAuth();

        if (!$lang || !$domain) {
            $this->redirect('/admin/translations');
        }

        // Sanitize lang and domain to prevent path traversal
        $lang = preg_replace('/[^a-zA-Z0-9_-]/', '', $lang);
        $domain = preg_replace('/[^a-zA-Z0-9_-]/', '', $domain);
        $filePath = __DIR__ . "/../i18n/{$lang}/{$domain}.php";

        if (!file_exists($filePath)) {
            $this->redirect('/admin/translations');
        }

        $translations = require $filePath;
        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $keys = $_POST['keys'] ?? [];
            $values = $_POST['values'] ?? [];

            if (count($keys) === count($values)) {
                $newTranslations = array_combine($keys, $values);

                // Remove empty keys
                $newTranslations = array_filter($newTranslations, function($k) { return !empty($k); }, ARRAY_FILTER_USE_KEY);

                $content = "<?php\nreturn " . var_export($newTranslations, true) . ";\n";
                if (file_put_contents($filePath, $content) !== false) {
                    $success = 'Translations saved successfully.';
                    $translations = $newTranslations;
                } else {
                    $error = 'Failed to save translations.';
                }
            } else {
                $error = 'Invalid data submitted.';
            }
        }

        $this->render('admin/edit_translation', [
            'lang' => $lang,
            'domain' => $domain,
            'translations' => $translations,
            'title' => 'Edit Translations: ' . strtoupper($lang) . ' / ' . $domain,
            'success' => $success,
            'error' => $error
        ]);
    }
}
