<?php
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class ManagerUser {
    const EMPLOYEE_ROLE = 'employe'; // Rôle d'employé
    const UTILISATEUR_ROLE = 'utilisateur';

    public static function getUsers() {
        
        header('Content-Type: application/json');
    
        try {
            $utilisateur = new Utilisateur();
            $users = $utilisateur->getAllForAdmin();
    
            echo json_encode([
                'success' => true,
                'users' => $users
            ]);
            exit; // Important pour éviter tout output supplémentaire
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public static function handleSuspension() {
        try {
            if (!isset($_POST['utilisateur_id'])) {
                throw new Exception('ID utilisateur manquant');
            }

            $userId = (int)$_POST['utilisateur_id'];
            $utilisateur = new Utilisateur();
            $newState = $utilisateur->toggleSuspension($userId);

            echo json_encode([
                'success' => true,
                'nouvel_etat' => $newState,
                'message' => $newState ? 'Compte suspendu' : 'Compte réactivé'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Erreur: ' . $e->getMessage()
            ]);
        }
        exit;
    }

}
?>
