<?php
namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger dotenv
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

    public static function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function sendMessage($postData)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=contact');
            exit;
        }

        // Vérifier le token CSRF
        if (!self::verifyCsrfToken($postData['csrf_token'] ?? '')) {
            $_SESSION['errorMessage'] = "Veuillez réessayer plus tard.";
            header('Location: index.php?page=contact');
            exit;
        }

        // Limite anti-spam : 1 message toutes les 2 minutes max par session
        if (isset($_SESSION['last_contact_time']) && (time() - $_SESSION['last_contact_time'] < 120)) {
            $_SESSION['errorMessage'] = "Veuillez patienter avant de renvoyer un message.";
            header('Location: index.php?page=contact');
            exit;
        }

        // Récupérer et valider les données
        $name = trim($postData['name'] ?? '');
        $email = trim($postData['email'] ?? '');
        $message = trim($postData['message'] ?? '');

        // Nettoyer nom et email pour éviter injection header mail
        $name = str_replace(["\r", "\n"], '', $name);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Validation
        if (empty($name) || strlen($name) > 20) {
            $_SESSION['errorMessage'] = "Le nom est obligatoire et doit faire moins de 20 caractères.";
            header('Location: index.php?page=contact');
            exit;
        }
        if (empty($email) || strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errorMessage'] = "L'adresse email est invalide ou trop longue (max 50 caractères).";
            header('Location: index.php?page=contact');
            exit;
        }
        if (empty($message) || strlen($message) > 1000) {
            $_SESSION['errorMessage'] = "Le message est obligatoire et doit faire moins de 1000 caractères.";
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
            $mail->Subject = "Nouveau message de " . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5);
            $mail->Body = "
                <h3>Nouveau message depuis le formulaire de contact :</h3>
                <p><strong>Nom :</strong> " . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "</p>
                <p><strong>Email :</strong> " . htmlspecialchars($email, ENT_QUOTES | ENT_HTML5) . "</p>
                <p><strong>Message :</strong><br>" . nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_HTML5)) . "</p>
            ";

            $mail->send();

            // Enregistrement du timestamp dernier envoi
            $_SESSION['last_contact_time'] = time();

            $_SESSION['successMessage'] = "Votre message a bien été envoyé.";
            header('Location: index.php?page=contact');
            exit;
        } catch (Exception $e) {
            error_log("Erreur d'envoi du mail : " . $mail->ErrorInfo);
            $_SESSION['errorMessage'] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
            header('Location: index.php?page=contact');
            exit;
        }
    }
}
