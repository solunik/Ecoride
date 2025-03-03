<?php
session_start();

// Charge les fichiers de configuration et les contrôleurs
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/homeController.php';

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

    case 'login':
        login($_POST['email'],$_POST['password']);
        break;




    case 'deconnexion':
        require_once __DIR__ . '/../app/controllers/deconnexion.php';
        break;   
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}
?>
