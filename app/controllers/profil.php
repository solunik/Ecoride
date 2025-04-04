<?php
require_once __DIR__ . '/../models/Utilisateur.php';

function profilPage() {
    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur_id'])) {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        header('Location: index.php?page=connexion');
        exit;
    }

    // Récupérer l'ID de l'utilisateur connecté
    $utilisateur = new Utilisateur();
    $userId = $_SESSION['utilisateur_id'];
    
    // Utiliser la méthode find() pour récupérer l'utilisateur depuis la BDD
    $user = $utilisateur->findById( $userId);

   
    // Vérifier si l'utilisateur existe
    if (!$user) {
        // Si l'utilisateur n'existe pas, on le redirige vers la page de connexion
        header('Location: index.php?page=connexion');
        exit;
    }

    $_SESSION['user'] = (array) $user;
    
    // Inclure la vue espace_utilisateur.php pour afficher le profil
    include __DIR__ . '/../views/espace_utilisateur.php';
}
?>
