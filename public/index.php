<?php

use App\Controllers\ContactController;

// Démarre la session
session_start();

// Génère un token CSRF si nécessaire
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Headers CORS globaux
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

// Charge les fichiers de configuration et les contrôleurs
require_once __DIR__ . '/../app/controllers/home.php';
require_once __DIR__ . '/../app/controllers/auth.php';
require_once __DIR__ . '/../app/controllers/research.php';
require_once __DIR__ . '/../app/controllers/registration.php';
require_once __DIR__ . '/../app/controllers/stats.php';
require_once __DIR__ . '/../app/controllers/manadmin.php';
require_once __DIR__ . '/../app/controllers/suspend.php';
require_once __DIR__ . '/../app/controllers/updateUserController.php';
require_once __DIR__ . '/../app/controllers/changeRoleController.php';
require_once __DIR__ . '/../app/controllers/EspaceUtilisateurController.php';
require_once __DIR__ . '/../app/controllers/contact.php';

// Liste des routes nécessitant un rôle spécifique
$protectedRoutes = [
    'admin' => 'Administrateur',

];

// Récupère la page demandée et la sécurise
$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['page']) : 'accueil';

// Vérifie si la page est protégée par un rôle
if (isset($protectedRoutes[$page])) {
    $requiredRole = $protectedRoutes[$page];

    $userId = $_SESSION['utilisateur_id'] ?? 'inconnu';
    $userIp = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';

    if ($requiredRole === 'Administrateur') {
        if (!isset($_SESSION['roles']) || !in_array('Administrateur', $_SESSION['roles'])) {
            // Log interne
            error_log("Tentative d'accès interdit - Page: $page - Utilisateur: $userId - IP: $userIp - Rôle requis: $requiredRole");

            header("HTTP/1.0 403 Forbidden");
            echo "Page non trouvée.";
            exit;
        }
    }
}


// Gestion des routes
switch ($page) {
    case 'accueil':
        accueilPage();
        break;

    case 'connexion':
        connexionPage();
        break;

    case 'contact':
        contactPage();
        break;

    case 'inscription':
        inscriptionPage();
        break;

    case 'mentions':
        mentionsPage();
        break;

    case 'recherche':
        recherchePage();
        break;

    case 'admin':
        $statsController = new Stats();
        $statsController->showDashboard();
        break;

    case 'espace_utilisateur': 
        EspaceUtilisateurController::showForm();
        break;

    case 'login':
        Auth::login($_POST['email'], $_POST['password']);
        break;

    case 'logout':
        Auth::logout();
        break;

    case 'research':
        Covoit::research($_POST['depart'], $_POST['arrivee'], $_POST['date']);
        break;

    case 'ridedetails':
        if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(['success' => false, 'error' => 'ID de trajet invalide']);
            exit;
        }
        Covoit::getRideDetails();
        break;

    case 'registration':
        Registration::register($_POST['email'], $_POST['pseudo'], $_POST['prenom'], $_POST['nom'], $_POST['password'], $_POST['confirm_password']);
        break;

    case 'contact_send':
        ContactController::sendMessage($_POST);
        break;

    case 'manadmin':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            EmployeeRegistration::registerEmployee(
                $_POST['lastName'],
                $_POST['firstName'],
                $_POST['email'],
                $_POST['password'],
                $_POST['confirmPassword']
            );
        }
        break;

    case 'suspend':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['utilisateur_id'])) {
                ManagerUser::handleSuspension();
            }
        } elseif (isset($_GET['getUsers'])) {
            ManagerUser::getUsers();
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['success' => false, 'error' => 'Requête non reconnue.']);
        }
        break;

    case 'api_update_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UpdateUserController();
            $controller->updateUser();
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        }
        break;

    case 'api_change_role':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new ChangeRoleController();
            $controller->changeRole();
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        }
        break;

    case 'submit_covoiturage':
    case 'api_proposer_covoiturage':
        EspaceUtilisateurController::handleForm();
        break;

    case 'api_get_all_vehicules':
        $controller = new EspaceUtilisateurController();
        $controller->getAll();
        break;

    case 'api_add_vehicule':
        $controller = new EspaceUtilisateurController();
        $controller->add();
        break;

    case 'api_delete_vehicule':
        $controller = new EspaceUtilisateurController();
        $controller->delete();
        break;

    case 'api_delete_covoiturage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $covoiturage = new Covoiturage();
            $success = $covoiturage->deleteById((int)$_POST['covoiturage_id']);

            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        }
        break;

    case 'api_get_user_covoiturages':
        $covoiturage = new Covoiturage();
        $userId = $_SESSION['utilisateur_id'] ?? null;
        if ($userId) {
            $historique = $covoiturage->getHistoriqueByUserId($userId);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'historique' => $historique]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        }
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}