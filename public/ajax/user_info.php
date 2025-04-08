<?php
session_start();
require_once __DIR__ . '/../models/Utilisateur.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo "<p>Utilisateur non connecté.</p>";
    exit;
}

$utilisateur = new Utilisateur();
$user = $utilisateur->findById($_SESSION['utilisateur_id']);

if (!$user) {
    echo "<p>Impossible de charger les informations utilisateur.</p>";
    exit;
}

// Générer un mini formulaire (ou juste l’affichage)
?>
<div>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user->nom) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($user->prenom) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->email) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($user->telephone ?? 'Non renseigné') ?></p>
</div>
