<?php
session_start();
require_once __DIR__ . '/../models/Covoiturage.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo "<p>Veuillez vous connecter pour voir votre historique.</p>";
    exit;
}

$covoit = new Covoiturage();
$historique = $covoit->getHistoriqueByUserId($_SESSION['utilisateur_id']);

if (!$historique) {
    echo "<p>Vous n'avez encore participé à aucun covoiturage.</p>";
    exit;
}

echo "<table border='1'>
        <thead>
            <tr><th>Départ</th><th>Arrivée</th><th>Date</th></tr>
        </thead><tbody>";

foreach ($historique as $trajet) {
    echo "<tr>
            <td>" . htmlspecialchars($trajet['depart']) . "</td>
            <td>" . htmlspecialchars($trajet['arrivee']) . "</td>
            <td>" . htmlspecialchars($trajet['date']) . "</td>
          </tr>";
}

echo "</tbody></table>";
