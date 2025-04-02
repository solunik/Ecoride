<?php
session_start();

// Headers CORS globaux
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");


// Charge les fichiers de configuration et les contrôleurs
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/home.php';
require_once __DIR__ . '/../app/controllers/auth.php';
require_once __DIR__ . '/../app/controllers/research.php';
require_once __DIR__ . '/../app/controllers/registration.php';

// Vérifie si une route est demandée
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['page']) : 'accueil'; // Sécurisation du paramètre page

// Vérifie la page demandée et appelle le contrôleur correspondant
switch ($page) {
    case 'accueil':
        accueilPage();
        break;
    case 'connexion':
        connexionPage();
        break;
    case 'contact':
        contactPage();
        break;
    case 'inscription':
        inscriptionPage();
        break;
    case 'recherche':
        recherchePage();
        break;
    case 'admin':
        adminPage();
        break;

    case 'login':
            Auth::login($_POST['email'], $_POST['password']);
        break;
    case 'logout':
            Auth::logout();
        break;

    case 'research':
        Covoit::research($_POST['depart'], $_POST['arrivee'], $_POST['date']);
    break;

    case 'ridedetails':
        if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(['success' => false, 'error' => 'ID de trajet invalide']);
            exit;
        }
        Covoit::getRideDetails();
        break;

    case 'registration':
        Registration::register($_POST['email'], $_POST['pseudo'], $_POST['prenom'], $_POST['nom'], $_POST['password'], $_POST['confirm_password']);
    break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}
?>