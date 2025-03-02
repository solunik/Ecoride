<?php
session_start();

// Charge les fichiers de configuration et les contrôleurs
require_once __DIR__ . '/../config/config.php';  // Assure-toi que le chemin vers le config.php est correct
require_once __DIR__ . '/../app/controllers/homeController.php';  // Assure-toi que le chemin vers homeController.php est correct

// Vérifie si une route est demandée
$page = $_GET['page'] ?? 'accueil';

// Appelle le contrôleur correspondant
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
    case 'covoiturages':
        covoituragesPage();
        break;
    case 'inscription':
        inscriptionPage();
        break;
    case 'recherche':
        recherchePage();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}
?>
