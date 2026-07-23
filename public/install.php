<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'] ?? '127.0.0.1';
    $dbname = $_POST['db_name'] ?? 'mvcini';
    $user = $_POST['db_user'] ?? 'root';
    $pass = $_POST['db_pass'] ?? '';

    $email_host = $_POST['email_host'] ?? 'smtp.example.com';
    $email_port = $_POST['email_port'] ?? '587';
    $email_user = $_POST['email_user'] ?? '';
    $email_pass = $_POST['email_pass'] ?? '';
    $email_from_address = $_POST['email_from_address'] ?? 'noreply@example.com';
    $email_from_name = $_POST['email_from_name'] ?? 'MVCini';

    $error = null;
    try {
        // Connect without database to create it if it doesn't exist
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");

        // Read and execute database.sql
        $sqlFile = __DIR__ . '/../database.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            if ($sql) {
                // Remove comments and execute
                $sql = preg_replace('/--.*$/m', '', $sql);
                // Split queries by semicolon and execute them one by one
                $queries = explode(';', $sql);
                foreach ($queries as $query) {
                    $query = trim($query);
                    if (!empty($query)) {
                        $pdo->exec($query);
                    }
                }
            }
        }

        // Generate app_secret
        $appSecret = bin2hex(random_bytes(32));

        // Create config file content
        $configContent = "<?php\n\nreturn [\n" .
            "    'db' => [\n" .
            "        'host' => '" . addslashes($host) . "',\n" .
            "        'dbname' => '" . addslashes($dbname) . "',\n" .
            "        'charset' => 'utf8mb4',\n" .
            "        'user' => '" . addslashes($user) . "',\n" .
            "        'pass' => '" . addslashes($pass) . "',\n" .
            "    ],\n" .
            "    'email' => [\n" .
            "        'host' => '" . addslashes($email_host) . "',\n" .
            "        'port' => '" . addslashes($email_port) . "',\n" .
            "        'user' => '" . addslashes($email_user) . "',\n" .
            "        'pass' => '" . addslashes($email_pass) . "',\n" .
            "        'from_address' => '" . addslashes($email_from_address) . "',\n" .
            "        'from_name' => '" . addslashes($email_from_name) . "',\n" .
            "    ],\n" .
            "    'default_lang' => 'en',\n" .
            "    'app_secret' => '" . addslashes($appSecret) . "',\n" .
            "];\n";

        // Write config file
        $configFile = __DIR__ . '/../config/config.php';
        file_put_contents($configFile, $configContent);

        // Delete self
        unlink(__FILE__);

        // Redirect
        header('Location: /');
        exit;
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVCini Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Welcome to MVCini Installer</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="/install.php">
            <div class="mb-4">
                <label for="db_host" class="block text-gray-700 text-sm font-bold mb-2">Database Host</label>
                <input type="text" id="db_host" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? '127.0.0.1') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="db_name" class="block text-gray-700 text-sm font-bold mb-2">Database Name</label>
                <input type="text" id="db_name" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? 'mvcini') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="db_user" class="block text-gray-700 text-sm font-bold mb-2">Database User</label>
                <input type="text" id="db_user" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? 'root') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-6">
                <label for="db_pass" class="block text-gray-700 text-sm font-bold mb-2">Database Password</label>
                <input type="password" id="db_pass" name="db_pass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <h2 class="text-xl font-bold mb-4 mt-6 text-gray-800">Email Settings</h2>

            <div class="mb-4">
                <label for="email_host" class="block text-gray-700 text-sm font-bold mb-2">SMTP Host</label>
                <input type="text" id="email_host" name="email_host" value="<?= htmlspecialchars($_POST['email_host'] ?? 'smtp.example.com') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="email_port" class="block text-gray-700 text-sm font-bold mb-2">SMTP Port</label>
                <input type="text" id="email_port" name="email_port" value="<?= htmlspecialchars($_POST['email_port'] ?? '587') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="email_user" class="block text-gray-700 text-sm font-bold mb-2">SMTP User</label>
                <input type="text" id="email_user" name="email_user" value="<?= htmlspecialchars($_POST['email_user'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="email_pass" class="block text-gray-700 text-sm font-bold mb-2">SMTP Password</label>
                <input type="password" id="email_pass" name="email_pass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="email_from_address" class="block text-gray-700 text-sm font-bold mb-2">From Address</label>
                <input type="text" id="email_from_address" name="email_from_address" value="<?= htmlspecialchars($_POST['email_from_address'] ?? 'noreply@example.com') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-6">
                <label for="email_from_name" class="block text-gray-700 text-sm font-bold mb-2">From Name</label>
                <input type="text" id="email_from_name" name="email_from_name" value="<?= htmlspecialchars($_POST['email_from_name'] ?? 'MVCini') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Install MVCini
                </button>
            </div>
        </form>
    </div>
</body>
</html>