<?php
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class EmployeeRegistration {
    const EMPLOYEE_ROLE = 'employe'; // Rôle d'employé

    public static function registerEmployee($postNom, $postPrenom, $postEmail, $postPassword, $postConfirmPassword) {
        $response = ['success' => false, 'message' => '']; // Réponse initiale

        try {
            // Nettoyage des données
            $email = trim(strtolower($postEmail));
            $prenom = htmlspecialchars($postPrenom);
            $nom = htmlspecialchars($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            $utilisateur = new Utilisateur();

            // Vérifications
            $existingUserByEmail = $utilisateur->findByEmail($email);
            if ($existingUserByEmail) {
                $response['message'] = "L'email est déjà utilisé.";
            } elseif ($password !== $confirmPassword) {
                $response['message'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Inscription de l'employé
                $userId = $utilisateur->addEmployee($nom, $prenom, $email, $password);

                try {
                    // Attribution du rôle d'employé
                    $role = new Role();
                    $role->assignRoleByLibelle($userId, self::EMPLOYEE_ROLE);

                    // Réponse succès
                    $response['success'] = true;
                } catch (Exception $e) {
                    // Rollback si échec d'attribution du rôle
                    $utilisateur->delete($userId);
                    throw $e;
                }
            }
        } catch (Exception $e) {
            $response['message'] = "Erreur lors de l'inscription de l'employé : " . $e->getMessage();
        }

        // Retourner la réponse sous forme JSON
        echo json_encode($response);
        exit;
    }
}
?>
