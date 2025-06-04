<?php

require_once __DIR__ . '/../models/voiture.php';
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
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'Méthode de requête non autorisée']);
        return;
    }

    $errors = [];
    $data = [
        'model' => trim($_POST['modele'] ?? ''),
        'plate' => trim($_POST['immatriculation'] ?? ''),
        'energy' => trim($_POST['energy'] ?? ''),
        'color' => trim($_POST['couleur'] ?? ''),
        'first_registration' => trim($_POST['date_premiere_immatriculation'] ?? ''),
        'user_id' => isset($_POST['user_id']) ? (int)$_POST['user_id'] : null,
        'brand_id' => isset($_POST['marque_id']) ? (int)$_POST['marque_id'] : null
    ];

    // --- Validations ---
    if (empty($data['model']) || strlen($data['model']) > 30) {
        $errors['model'] = "Le modèle est requis et doit faire moins de 30 caractères.";
    }

    if (empty($data['plate']) || !preg_match('/^[A-Z0-9-]{3,15}$/i', $data['plate'])) {
        $errors['plate'] = "L'immatriculation est invalide.";
    }

    $valid_energies = ['essence', 'diesel', 'electrique', 'hybride'];
    if (empty($data['energy']) || !in_array(strtolower($data['energy']), $valid_energies)) {
        $errors['energy'] = "Type d'énergie invalide.";
    }

    if (empty($data['color']) || strlen($data['color']) > 15) {
        $errors['color'] = "La couleur est requise et doit faire moins de caractères.";
    }

    if (!empty($data['first_registration']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['first_registration'])) {
        $errors['first_registration'] = "Date de première immatriculation invalide.";
    }

    if (empty($data['user_id']) || $data['user_id'] <= 0) {
        $errors['user_id'] = "Utilisateur invalide.";
    }

    if (empty($data['brand_id']) || $data['brand_id'] <= 0) {
        $errors['brand_id'] = "Marque invalide.";
    }

    if (!empty($errors)) {
        echo json_encode(['error' => 'Données invalides', 'details' => $errors]);
        return;
    }

    // --- Ajout du véhicule ---
    $voitureModel = new Voiture();
    $isAdded = $voitureModel->addVehicule($data);

    if ($isAdded) {
        echo json_encode(['success' => true, 'message' => 'Véhicule ajouté avec succès']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout du véhicule']);
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

    public static function handleForm() {
    header('Content-Type: application/json');

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Méthode non autorisée.');
        }

        $isJson = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false;
        $input = $isJson
            ? json_decode(file_get_contents('php://input'), true)
            : $_POST;

        if (!$input || !is_array($input)) {
            throw new Exception("Données invalides.");
        }

        $requiredFields = ['departureLocation', 'arrivalLocation', 'rideDate', 'rideTime', 'seatCount', 'vehiculeId'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                throw new Exception("Champ manquant : $field");
            }
        }

        $departureLocation = substr(trim(strip_tags($input['departureLocation'])), 0, 39);
        $arrivalLocation = substr(trim(strip_tags($input['arrivalLocation'])), 0, 39);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input['rideDate'])) {
            throw new Exception("Format de date invalide.");
        }

        if (!preg_match('/^\d{2}:\d{2}$/', $input['rideTime'])) {
            throw new Exception("Format d'heure invalide.");
        }

        if (!filter_var($input['seatCount'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 20]])) {
            throw new Exception("Nombre de places invalide (1-20).");
        }

        $pricePerPerson = isset($input['pricePerPerson']) ? $input['pricePerPerson'] : 0;
        if (!is_numeric($pricePerPerson) || $pricePerPerson < 0) {
            throw new Exception("Prix par personne invalide.");
        }

        if (!filter_var($input['vehiculeId'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            throw new Exception("Identifiant véhicule invalide.");
        }

        $data = [
            'lieu_depart' => $departureLocation,
            'lieu_arrivee' => $arrivalLocation,
            'date_depart' => $input['rideDate'],
            'heure_depart' => $input['rideTime'],
            'date_arrive' => $input['rideDate'],
            'heure_arrivee' => $input['rideTime'],
            'nb_place' => (int)$input['seatCount'],
            'prix_personne' => (float)$pricePerPerson,
            'voiture_id' => (int)$input['vehiculeId'],
            'utilisateur_id' => $_SESSION['utilisateur_id'] ?? null
        ];

        if (!$data['utilisateur_id']) {
            throw new Exception('Utilisateur non authentifié.');
        }

        $voitureModel = new Voiture();
        $vehicule = $voitureModel->findById($data['voiture_id']);
        if (!$vehicule) {
            throw new Exception('Véhicule introuvable.');
        }

        $covoiturage = new Covoiturage();
        $success = $covoiturage->create($data);

        if (!$success) {
            throw new Exception('Erreur lors de la création du covoiturage.');
        }

        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        // En production : log seulement les erreurs système
        // Ici, on choisit de loguer seulement les erreurs imprévues
        $message = $e->getMessage();

        // Filtrer les erreurs utilisateur (champ manquant, format invalide, etc.)
        $erreursUtilisateurs = [
            'Méthode non autorisée.',
            'Données invalides.',
            'Utilisateur non authentifié.',
            'Véhicule introuvable.',
        ];

        // Si ce n’est pas une erreur système, pas de log
        if (!in_array($message, $erreursUtilisateurs) &&
            strpos($message, 'Champ manquant') === false &&
            strpos($message, 'Format') === false &&
            strpos($message, 'Nombre de places') === false &&
            strpos($message, 'Prix par personne') === false &&
            strpos($message, 'Identifiant véhicule') === false
        ) {
            error_log("Erreur handleForm: " . $message);
        }

        // Réponse JSON
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }

    exit;
}


    
}
