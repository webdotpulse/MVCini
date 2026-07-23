<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    private function checkAuth()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
    }

    public function index()
    {
        $this->checkAuth();
        $user = User::find($_SESSION['user_id']);

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();

            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $newFilename = uniqid('avatar_') . '.' . $ext;
                    $destPath = $uploadDir . $newFilename;

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destPath)) {
                        User::update((int)$user['id'], ['avatar' => $newFilename]);
                        $user['avatar'] = $newFilename; // Update local for display
                        $success = 'Avatar updated successfully.';
                    } else {
                        $error = 'Failed to save uploaded file.';
                    }
                } else {
                    $error = 'Invalid file type. Only JPG, PNG, and GIF allowed.';
                }
            }
        }

        // Determine avatar URL (Local vs Gravatar)
        $avatarUrl = '';
        if (!empty($user['avatar'])) {
            $avatarUrl = '/uploads/avatars/' . $user['avatar'];
        } else {
            // Gravatar fallback
            $hash = md5(strtolower(trim($user['email'])));
            $avatarUrl = "https://www.gravatar.com/avatar/$hash?d=mp&s=150";
        }

        $this->render('profile/index', [
            'title' => 'User Profile',
            'user' => $user,
            'avatarUrl' => $avatarUrl,
            'error' => $error,
            'success' => $success
        ]);
    }
}
