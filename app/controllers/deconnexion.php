<?php
// Démarrer la session
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou une autre page
header("Location: /Covoiturage/public/index.php");  // Remonter d'un niveau pour atteindre le dossier public
exit;
?>
