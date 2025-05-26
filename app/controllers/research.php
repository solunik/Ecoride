<?php
require_once __DIR__ . '/../models/covoiturage.php';

class Covoit {
    public static function research() {
        // Vérification de la soumission du formulaire
        if (!isset($_POST['depart'], $_POST['arrivee'], $_POST['date'])) {
            $_SESSION['errorMessage'] = "Veuillez remplir tous les champs.";
            header("Location: index.php?page=recherche");
            exit();
        }

        // Nettoyage des entrées
        $depart = htmlspecialchars(trim($_POST['depart']));
        $arrivee = htmlspecialchars(trim($_POST['arrivee']));
        $dateInput = trim($_POST['date']);

        // Validation du format JJ/MM/AAAA
        if (!preg_match('~^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(20[2-9][0-9])$~', $dateInput)) {
            $_SESSION['errorMessage'] = "Format de date invalide. Utilisez JJ/MM/AAAA (ex: 25/12/2023)";
            header("Location: index.php?page=recherche");
            exit();
        }

        // Conversion en objet DateTime
        $dateObj = DateTime::createFromFormat('d/m/Y', $dateInput);
        if (!$dateObj) {
            $_SESSION['errorMessage'] = "Date invalide (vérifiez le jour/mois)";
            header("Location: index.php?page=recherche");
            exit();
        }

        // Vérification que la date est future
        $aujourdhui = new DateTime();
        $aujourdhui->setTime(0, 0, 0); // Réinitialiser l'heure à minuit

        if ($dateObj < $aujourdhui) {
            $_SESSION['errorMessage'] = "Veuillez choisir une date à partir d'aujourd'hui";
            header("Location: index.php?page=recherche");
            exit();
        }

        // Conversion pour la base de données (format Y-m-d)
        $dateSQL = $dateObj->format('Y-m-d');

        // Marquer la recherche comme effectuée
        $_SESSION['recherche_effectuee'] = true;

        // Recherche dans le modèle
        $covoiturageModel = new Covoiturage();
        $resultats = $covoiturageModel->search($depart, $arrivee, $dateSQL);

        // Gestion des résultats
        if (isset($resultats["error"])) {
            $_SESSION['errorMessage'] = $resultats["error"];
            $_SESSION['resultats_recherche'] = [];
        } else {
            // Formatage des dates pour chaque résultat avant de les stocker en session
            foreach ($resultats as &$resultat) {
                $dateObj = DateTime::createFromFormat('Y-m-d', $resultat['date_depart']);
                $resultat['date_formatted'] = $dateObj ? $dateObj->format('d/m/Y') : $resultat['date_depart'];
            }
            unset($resultat); // Détruire la référence
            
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

            // Formatage de la date en JJ/MM/AAAA pour l'affichage
            $dateDepart = DateTime::createFromFormat('Y-m-d', $details['date_depart']);
            $dateFormatted = $dateDepart ? $dateDepart->format('d/m/Y') : $details['date_depart'];

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
                        'date' => $dateFormatted,
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