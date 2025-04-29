<?php

require_once __DIR__ . '/../models/Voiture.php';
require_once __DIR__ . '/../models/marque.php';
require_once __DIR__ . '/../models/covoiturage.php';
class EspaceUtilisateurController {

    public static function showForm() {
        if (!isset($_SESSION['utilisateur_id'])) {
            header('Location: /?page=connexion');
            exit;
        }
    
        
   
        // 🆕 Charger toutes les marques
        $marqueModel = new Marque();
        $marques = $marqueModel->getAll(); // Une méthode qu'on va ajouter juste après
        //var_dump($marques);

        $voitureModel = new Voiture();
        $vehicules = $voitureModel->getByUserId($_SESSION['utilisateur_id']);
        //var_dump($vehicules);

        $covoiturageModel = new Covoiturage();
        $historiqueCovoiturages = $covoiturageModel->getHistoriqueByUserId($_SESSION['utilisateur_id']);
        //var_dump($historiqueCovoiturages);


        include __DIR__ . '/../views/espace_utilisateur.php';
    }
    
    


    // Action pour récupérer tous les véhicules d'un utilisateur
    public function getAll() {
        if (isset($_GET['user_id'])) {
            $userId = (int)$_GET['user_id'];

            // Récupérer les véhicules depuis le modèle
            $voitureModel = new Voiture();
            $vehicules = $voitureModel->getByUserId($userId);
            
            header('Content-Type: application/json');
            // Retourner les véhicules sous forme de JSON
            echo json_encode($vehicules);
        } else {
            echo json_encode(['error' => 'User ID not provided']);
        }
        
    }

    // Action pour ajouter un véhicule
    public function add() {
        // Vérifier si les données du formulaire sont présentes
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'model' => $_POST['modele'] ?? null,
                'plate' => $_POST['immatriculation'] ?? null,
                'energy' => $_POST['energy'] ?? null,
                'color' => $_POST['couleur'] ?? null,
                'first_registration' => $_POST['date_premiere_immatriculation'] ?? null,
                'user_id' => $_POST['user_id'] ?? null,
                'brand_id' => $_POST['marque_id'] ?? null
            ];

            // Valider les données
            if (!empty($data['model']) && !empty($data['plate']) && !empty($data['energy']) && !empty($data['color'])) {
                $voitureModel = new Voiture();

                // Ajouter la voiture
                $isAdded = $voitureModel->addVehicule($data);

                if ($isAdded) {
                    echo json_encode(['message' => 'Véhicule ajouté avec succès']);
                } else {
                    echo json_encode(['error' => 'Erreur lors de l\'ajout du véhicule']);
                }
            } else {
                echo json_encode(['error' => 'Données invalides']);
            }
        } else {
            echo json_encode(['error' => 'Méthode de requête non autorisée']);
        }
    }

    public function delete() {
        if (isset($_POST['voiture_id'])) {
            $voitureId = (int)$_POST['voiture_id'];
    
            $voitureModel = new Voiture();
            $success = $voitureModel->deleteVehicule($voitureId);
    
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Véhicule supprimé avec succès']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression du véhicule']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'ID du véhicule non fourni']);
        }
    }


    //Covoiturage
    // Traite la soumission du formulaire (POST ou AJAX)
    public static function handleForm() {
        // Force le header JSON
        header('Content-Type: application/json');
    
        try {
            // Vérifie que la requête est bien en POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée.');
            }
    
            // Détecte si c'est une requête JSON
            $isJson = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;
    
            // Récupération des données
            $input = $isJson
                ? json_decode(file_get_contents('php://input'), true)
                : $_POST;
    
            if (!$input || !is_array($input)) {
                throw new Exception("Données invalides.");
            }
    
            // Validation des champs requis
            $requiredFields = ['departureLocation', 'arrivalLocation', 'rideDate', 'rideTime', 'seatCount', 'vehiculeId'];
            foreach ($requiredFields as $field) {
                if (empty($input[$field])) {
                    throw new Exception("Champ manquant : $field");
                }
            }
    
            // Préparation des données
            $data = [
                'lieu_depart' => $input['departureLocation'],
                'lieu_arrivee' => $input['arrivalLocation'],
                'date_depart' => $input['rideDate'],
                'heure_depart' => $input['rideTime'],
                'date_arrive' => $input['rideDate'], // Peut être adapté
                'heure_arrivee' => $input['rideTime'],
                'nb_place' => (int)$input['seatCount'],
                'prix_personne' => isset($input['pricePerPerson']) ? (float)$input['pricePerPerson'] : 0,
                'voiture_id' => (int)$input['vehiculeId'],
                'utilisateur_id' => $_SESSION['utilisateur_id'] ?? null // Corrigé ici
                
            ];
    
            if (!$data['utilisateur_id']) {
                throw new Exception('Utilisateur non authentifié.');
            }
    
            // Vérification du véhicule
            $voitureModel = new Voiture();
            $vehicule = $voitureModel->findById($data['voiture_id']);
            if (!$vehicule) {
                throw new Exception('Véhicule introuvable.');
            }
    
            // Insertion du covoiturage
            $covoiturage = new Covoiturage();
            $success = $covoiturage->create($data);
    
            if (!$success) {
                throw new Exception('Erreur lors de la création du covoiturage.');
            }
    
            // Réponse OK
            echo json_encode(['success' => true]);
    
        } catch (Exception $e) {
            // En cas d'exception, réponse d'erreur propre
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    
        exit;
    }
    

    // Méthode pour envoyer la réponse selon le type de requête (JSON ou Redirection)
    private static function respond($isJson, $response) {
        if ($isJson) {
            // Réponse JSON pour une requête AJAX
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            // Redirection en cas de soumission classique du formulaire
            $url = '/?page=dashboard' . ($response['success'] ? '&success=1' : '&error=1');
            header("Location: $url");
        }
        exit;
    }
    
}
