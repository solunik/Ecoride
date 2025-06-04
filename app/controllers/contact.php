<?php
namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger dotenv ici, si pas déjà fait ailleurs (ex : index.php)
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

class ContactController
{
    public static function showContactForm()
    {
        require __DIR__ . '/../../views/contact.php';
    }

    public static function sendMessage($postData)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($postData['name']));
            $email = htmlspecialchars(trim($postData['email']));
            $message = htmlspecialchars(trim($postData['message']));

            if (strlen($name) > 20) {
                $_SESSION['errorMessage'] = "Le nom est trop long (max 20 caractères).";
                header('Location: index.php?page=contact');
                exit;
            }
            if (strlen($email) > 50) {
                $_SESSION['errorMessage'] = "L'adresse email est trop longue (max 50 caractères).";
                header('Location: index.php?page=contact');
                exit;
            }
            if (strlen($message) > 1000) {
                $_SESSION['errorMessage'] = "Le message est trop long (max 1000 caractères).";
                header('Location: index.php?page=contact');
                exit;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['errorMessage'] = "L'adresse email est invalide.";
                header('Location: index.php?page=contact');
                exit;
            }

            $mail = new PHPMailer(true);

            try {
                // Config SMTP depuis .env
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USER'];
                $mail->Password = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
                $mail->Port = (int) $_ENV['SMTP_PORT'];

                $mail->CharSet = 'UTF-8';
                $mail->setFrom($_ENV['SMTP_USER'], 'EcoRide');
                $mail->addAddress($_ENV['SMTP_RECIPIENT']);
                $mail->addReplyTo($email, $name);

                $mail->isHTML(true);
                $mail->Subject = "Nouveau message de $name";
                $mail->Body = "
                    <h3>Nouveau message depuis le formulaire de contact :</h3>
                    <p><strong>Nom :</strong> $name</p>
                    <p><strong>Email :</strong> $email</p>
                    <p><strong>Message :</strong><br>$message</p>
                ";

                $mail->send();

                $_SESSION['successMessage'] = "Votre message a bien été envoyé.";
                header('Location: index.php?page=contact');

                exit;
            } catch (Exception $e) {
                error_log("Erreur d'envoi du mail : " . $mail->ErrorInfo);
                $_SESSION['errorMessage'] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
                header('Location: index.php?page=contact');
                exit;
            }
        } else {
            header('Location: index.php?page=contact');
            exit;
        }
    }
}
