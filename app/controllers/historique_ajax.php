<?php
require_once __DIR__ . '/../models/Covoiturage.php';
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    echo "Utilisateur non connecté.";
    exit;
}

$covoiturage = new Covoiturage();
$historique = $covoiturage->getHistoriqueByUserId($_SESSION['utilisateur_id']);

if (empty($historique)) {
    echo "<p>Aucun trajet trouvé.</p>";
} else {
    echo "<ul>";
    foreach ($historique as $trajet) {
        echo "<li>{$trajet['depart']} → {$trajet['arrivee']} le {$trajet['date']}</li>";
    }
    echo "</ul>";
}
