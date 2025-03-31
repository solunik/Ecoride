<?php
require_once __DIR__ . '/../models/covoiturage.php';

class Covoit {
    public static function research() {
        // Vérification des champs
        if (!isset($_POST['depart'], $_POST['arrivee'], $_POST['date'])) {
            $_SESSION['recherche'] = [
                'resultats' => [],
                'message' => "Veuillez remplir tous les champs.",
                'recherche_effectuee' => false
            ];
            header("Location: index.php?page=recherche");
            exit();
        }

        // Récupération des données
        $depart = htmlspecialchars($_POST['depart']);
        $arrivee = htmlspecialchars($_POST['arrivee']);
        $date = $_POST['date'];

        // Recherche
        $covoiturageModel = new Covoiturage();
        $resultats = $covoiturageModel->search($depart, $arrivee, $date);

        // Structuration des données
        $_SESSION['recherche'] = [
            'resultats' => $resultats,
            'message' => $resultats["error"] ?? '',
            'recherche_effectuee' => true
        ];

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
                'data' => $details
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