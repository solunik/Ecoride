<?php

// Inclure la classe Covoiturage
require_once __DIR__ . '/../models/covoiturage.php';
class Covoit{

public static function research() {

    // Vérifier si le formulaire a été soumis
    if (!isset($_POST['depart'], $_POST['arrivee'], $_POST['date'])) {
        $_SESSION['errorMessage'] = "Veuillez remplir tous les champs.";
        header("Location: index.php?page=recherche");
        exit();
    }

    // Récupérer les données du formulaire
    $depart = $_POST['depart'];
    $arrivee = $_POST['arrivee'];
    $date = $_POST['date'];


    // Instancier la classe Covoiturage
    $covoiturageModel = new Covoiturage();
    $resultats = $covoiturageModel->search($depart, $arrivee, $date);

    if (isset($resultats["error"])) {
        $_SESSION['errorMessage'] = $resultats["error"];
        $_SESSION['resultats_recherche'] = [];
    } else {
        $_SESSION['resultats_recherche'] = $resultats;
    }

    header("Location: index.php?page=recherche");
    exit();
}
}
?>