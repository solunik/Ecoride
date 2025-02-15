<?php
// Démarrer la session pour stocker les informations de l'utilisateur
session_start();

// Inclure la classe de connexion
require_once __DIR__ . '/../../app/models/db.php';

// Initialisation de la variable d'erreur
$errorMessage = ''; 

try {
    // Connexion à la base de données en utilisant la classe Database
    $conn = Database::getInstance();

    // Initialisation du tableau des résultats
    $resultats = [];

    // Vérification de la présence des paramètres GET
    if (isset($_GET['depart'], $_GET['arrivee'], $_GET['date'])) {
        // Assainissement des entrées utilisateur pour éviter les attaques XSS
        $depart = htmlspecialchars($_GET['depart']);
        $arrivee = htmlspecialchars($_GET['arrivee']);
        $date = htmlspecialchars($_GET['date']);

        // Requête SQL pour récupérer les données nécessaires
    $stmt = $conn->prepare("
    SELECT
    c.date_depart,
    c.lieu_depart, 
    c.lieu_arrivee, 
    c.heure_depart,
    c.heure_arrivee,
    c.nb_place,
    c.prix_personne,
    u.photo,
    u.pseudo, 
    v.energie,
    ROUND(AVG(a.note), 1) AS note
FROM 
    Covoiturage c
JOIN 
    utilisateur u ON c.conducteur_id = u.utilisateur_id
JOIN 
    voiture v ON c.voiture_id = v.voiture_id
LEFT JOIN
    avis a ON a.utilisateur_id = u.utilisateur_id
WHERE 
    c.lieu_depart = :depart 
    AND 
        c.lieu_arrivee = :arrivee 
    AND 
        c.date_depart = :date
GROUP BY 
    c.covoiturage_id, c.date_depart, c.lieu_depart, c.lieu_arrivee, 
    c.heure_depart, c.heure_arrivee, c.nb_place, c.prix_personne, u.photo,
    u.pseudo, v.energie
ORDER BY 
    c.heure_depart;

");

// Exécution et récupération des résultats
$stmt->execute([':depart' => $depart, ':arrivee' => $arrivee, ':date' => $date]);
$resultats = $stmt->fetchAll();;
    }
} catch (PDOException $e) {
    // En cas d'erreur de connexion ou d'exécution, on stocke le message d'erreur
    $errorMessage = "Erreur de connexion : " . htmlspecialchars($e->getMessage());
}

// Stockage des résultats et du message d'erreur dans la session
$_SESSION['resultats_recherche'] = $resultats;
$_SESSION['errorMessage'] = $errorMessage ?? null;

// Redirection vers la page de recherche
header("Location: /Covoiturage/app/views/recherche.php");
exit();
