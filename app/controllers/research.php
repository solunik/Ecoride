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

    // Marquer qu'une recherche a été effectuée
    $_SESSION['recherche_effectuee'] = true;

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

public static function getRideDetails() {
    header('Content-Type: application/json');
    
    try {
        $rideId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$rideId) throw new Exception("ID de trajet invalide");

        $model = new Covoiturage();
        $details = $model->getDetails($rideId);

        if (!$details) throw new Exception("Trajet non trouvé");

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $details['covoiturage_id'],
                'conducteur' => [
                    'pseudo' => htmlspecialchars($details['pseudo']),
                    'note_moyenne' => $details['note_moyenne'],
                    'commentaire' => $details['commentaire']
                ],
                'trajet' => [
                    'depart' => htmlspecialchars($details['lieu_depart']),
                    'arrivee' => htmlspecialchars($details['lieu_arrivee']),
                    'date' => $details['date_depart'],
                    'heure_depart' => $details['heure_depart'],
                    'prix' => $details['prix_personne']
                ],
                'voiture' => [
                    'modele' => htmlspecialchars($details['modele']),
                    'marque' => htmlspecialchars($details['libelle_marque']),
                    'energie' => htmlspecialchars($details['energie'])
                ],
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}

}
?>