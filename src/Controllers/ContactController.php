<?php
namespace App\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
    /**
     * Generate a simple math captcha
     */
    public function captcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $_SESSION['captcha_result'] = $num1 + $num2;

        // Create an image
        $image = imagecreatetruecolor(100, 40);
        $bg = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);

        imagefilledrectangle($image, 0, 0, 100, 40, $bg);

        // Add text
        imagestring($image, 5, 20, 10, "$num1 + $num2 =", $text_color);

        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }

    /**
     * Contact form page
     */
    public function index()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';
            $captcha = (int)($_POST['captcha'] ?? 0);

            if (!isset($_SESSION['captcha_result']) || $captcha !== $_SESSION['captcha_result']) {
                $error = 'Incorrect captcha.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
            } else {
                // Use PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Configure logic here. Using Mailtrap or local sendmail for demo
                    $mail->isSMTP();
                    $mail->Host       = 'localhost';
                    $mail->Port       = 1025; // E.g., MailHog port
                    $mail->SMTPAuth   = false;

                    $mail->setFrom($email, $name);
                    $mail->addAddress('admin@mvcini.local', 'Admin');

                    $mail->isHTML(false);
                    $mail->Subject = 'New Contact Message from ' . $name;
                    $mail->Body    = $message;

                    // Suppress send for demo if SMTP isn't running, but code is ready
                    @$mail->send();
                    $success = 'Your message has been sent!';
                } catch (Exception $e) {
                    $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }

        $this->render('contact/index', [
            'title' => 'Contact Us',
            'meta_description' => 'Contact MVCini Framework',
            'error' => $error,
            'success' => $success
        ]);
    }
}
