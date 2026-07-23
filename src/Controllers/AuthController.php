<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Security;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
    private int $maxFailedAttempts = 5;
    private int $lockoutTime = 900; // 15 minutes

    public function login()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $identifier = $_POST['identifier'] ?? '';
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $user = User::findByUsernameOrEmail($identifier);

            if ($user) {
                // Throttling check
                if ($user['failed_attempts'] >= $this->maxFailedAttempts) {
                    $lastFailed = strtotime($user['last_failed_login']);
                    if (time() - $lastFailed < $this->lockoutTime) {
                        $error = 'Too many failed attempts. Try again later.';
                    } else {
                        User::resetFailedAttempts((int)$user['id']);
                    }
                }

                if (empty($error)) {
                    if (password_verify($password, $user['password'])) {
                        if ($user['is_verified'] == 0 && $user['role'] !== 'admin') {
                            $error = 'Please verify your email address.';
                        } else {
                            // Success
                            User::resetFailedAttempts((int)$user['id']);
                            session_regenerate_id(true);
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['role'] = $user['role'];

                            if ($remember) {
                                $token = bin2hex(random_bytes(32));
                                $expires = time() + (30 * 24 * 60 * 60); // 30 days
                                User::saveRememberToken((int)$user['id'], $token, date('Y-m-d H:i:s', $expires));
                                Security::setEncryptedCookie('remember_me', $user['id'] . ':' . $token, $expires);
                            }

                            $this->redirect('/');
                        }
                    } else {
                        User::incrementFailedAttempts((int)$user['id']);
                        $error = 'Invalid credentials.';
                    }
                }
            } else {
                $error = 'Invalid credentials.';
            }
        }

        $this->render('auth/login', ['error' => $error, 'title' => 'Login']);
    }

    public function register()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (User::findByUsernameOrEmail($email) || User::findByUsernameOrEmail($username)) {
                $error = 'Username or email already exists.';
            } else {
                $token = bin2hex(random_bytes(32));
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $userId = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'verification_token' => $token
                ]);

                // Mock sending email
                $verifyLink = "http://localhost:8000/auth/verify?token=$token&email=" . urlencode($email);
                file_put_contents('/tmp/mail.log', "Send to: $email\nVerify link: $verifyLink\n", FILE_APPEND);

                $success = 'Registration successful! Please check your email to verify your account.';
            }
        }

        $this->render('auth/register', ['error' => $error, 'success' => $success, 'title' => 'Register']);
    }

    public function verify()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        $user = User::findByUsernameOrEmail($email);
        if ($user && $user['verification_token'] === $token) {
            User::update((int)$user['id'], [
                'is_verified' => 1,
                'verification_token' => null
            ]);
            echo "Account verified! <a href='/auth/login'>Login here</a>";
            exit;
        }
        echo "Invalid verification link.";
        exit;
    }

    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            User::clearRememberTokens($_SESSION['user_id']);
        }
        setcookie('remember_me', '', time() - 3600, '/');
        session_unset();
        session_destroy();
        $this->redirect('/');
    }

    public function forgot()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $email = $_POST['email'] ?? '';

            $user = User::findByUsernameOrEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

                User::update((int)$user['id'], [
                    'reset_token' => $token,
                    'reset_token_expires' => $expires
                ]);

                // Mock sending email
                $resetLink = "http://localhost:8000/auth/reset?token=$token&email=" . urlencode($email);
                file_put_contents('/tmp/mail.log', "Send reset to: $email\nReset link: $resetLink\n", FILE_APPEND);
            }

            // Always show success to prevent email enumeration
            $success = 'If an account exists with that email, a password reset link has been sent.';
        }

        $this->render('auth/forgot', ['error' => $error, 'success' => $success, 'title' => 'Forgot Password']);
    }

    public function reset()
    {
        $token = $_GET['token'] ?? ($_POST['token'] ?? '');
        $email = $_GET['email'] ?? ($_POST['email'] ?? '');

        $error = '';
        $success = '';

        $user = User::findByUsernameOrEmail($email);

        if (!$user || $user['reset_token'] !== $token || strtotime($user['reset_token_expires']) < time()) {
            die('Invalid or expired reset token.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $password = $_POST['password'] ?? '';

            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } else {
                User::update((int)$user['id'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'reset_token' => null,
                    'reset_token_expires' => null
                ]);
                $success = 'Password successfully reset. You can now <a href="/auth/login">login</a>.';
            }
        }

        $this->render('auth/reset', [
            'token' => $token,
            'email' => $email,
            'error' => $error,
            'success' => $success,
            'title' => 'Reset Password'
        ]);
    }

    /**
     * Remember me check to run globally or in a middleware-like fashion
     */
    public static function checkRememberMe()
    {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
            $payload = Security::getEncryptedCookie('remember_me');
            if ($payload) {
                list($userId, $token) = explode(':', $payload, 2);
                $user = User::find((int)$userId);
                if ($user && User::verifyRememberToken((int)$userId, $token)) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                }
            }
        }
    }
}
