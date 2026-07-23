<?php
namespace App\Core;

class Security
{
    /**
     * Generate and retrieve CSRF Token
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF Token
     */
    public static function verifyCsrf(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Helper to encrypt cookie values using OpenSSL
     */
    private static function getSecretKey(): string
    {
        // Typically read from config. For demo, we use a static string if not found
        global $config;
        return $config['app_secret'] ?? 'defaU1t_S3cr3t_K3y_For_MVCini_Demo!@#';
    }

    public static function setEncryptedCookie(string $name, string $value, int $expires)
    {
        $key = self::getSecretKey();
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($value, 'aes-256-cbc', $key, 0, $iv);
        // Concatenate IV and ciphertext, encode in base64
        $payload = base64_encode($iv . $encrypted);

        setcookie($name, $payload, $expires, '/', '', false, true); // HttpOnly
    }

    public static function getEncryptedCookie(string $name): ?string
    {
        if (!isset($_COOKIE[$name])) {
            return null;
        }

        $payload = base64_decode($_COOKIE[$name]);
        if ($payload === false) {
            return null;
        }

        $ivLen = openssl_cipher_iv_length('aes-256-cbc');
        if (strlen($payload) <= $ivLen) {
            return null;
        }

        $iv = substr($payload, 0, $ivLen);
        $encrypted = substr($payload, $ivLen);
        $key = self::getSecretKey();

        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
        return $decrypted !== false ? $decrypted : null;
    }

    /**
     * Prevent session hijacking by validating IP/UserAgent
     */
    public static function enforceSessionSecurity()
    {
        $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

        if (isset($_SESSION['session_fingerprint'])) {
            if ($_SESSION['session_fingerprint'] !== $fingerprint) {
                session_unset();
                session_destroy();
                session_start();
            }
        } else {
            $_SESSION['session_fingerprint'] = $fingerprint;
        }
    }
}
