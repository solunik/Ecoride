<?php
session_start();

// Charge les fichiers de configuration et les contrôleurs
require_once __DIR__ . '/../config/config.php';  // Assure-toi que le chemin vers le config.php est correct
require_once __DIR__ . '/../app/controllers/homeController.php';  // Assure-toi que le chemin vers homeController.php est correct

// Vérifie si une route est demandée
$page = 'accueil'; // Page par défaut

if (!empty($_GET['page'])) {
    $page = $_GET['page']; // Récupère la page demandée dans l'URL
}

// Vérifie la page demandée et appelle le contrôleur correspondant
switch ($page) {
    case 'accueil':
        accueilPage();  // Appelle la fonction qui affiche la page d'accueil
        break;
    case 'connexion':
        connexionPage();  // Appelle la fonction qui affiche la page de connexion
        break;
    case 'contact':
        contactPage();  // Appelle la fonction qui affiche la page de contact
        break;
    case 'covoiturages':
        covoituragesPage();  // Appelle la fonction qui affiche la page des covoiturages
        break;
    case 'inscription':
        inscriptionPage();  // Appelle la fonction qui affiche la page d'inscription
        break;
    case 'recherche':
        recherchePage();  // Appelle la fonction qui affiche la page de recherche
        break;
    default:
        // Si la page demandée n'est pas trouvée, affiche une erreur 404
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}
?>
