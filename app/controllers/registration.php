<?php
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class Registration {
    const DEFAULT_ROLE = 'utilisateur'; // Définition du rôle par libellé

    public static function register($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
        $_SESSION['error_message'] = '';

        try {
            // Nettoyage des données
            $email = trim(strtolower($postEmail));
            $pseudo = trim(strtolower($postPseudo));
            $prenom = htmlspecialchars($postPrenom);
            $nom = htmlspecialchars($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            $utilisateur = new Utilisateur();

            // Vérifications
            $existingUserByEmail = $utilisateur->findByEmail($email);
            $existingUserByPseudo = $utilisateur->findByPseudo($pseudo);

            if ($existingUserByEmail) {
                $_SESSION['error_message'] = "L'email est déjà utilisé.";
            } elseif ($existingUserByPseudo) {
                $_SESSION['error_message'] = "Le pseudo est déjà pris.";
            } elseif ($password !== $confirmPassword) {
                $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Inscription
                $userId = $utilisateur->inscription($nom, $prenom, $email, $password, $pseudo);
                
                try {
                    // Attribution du rôle
                    $role = new Role();
                    $role->assignRoleByLibelle($userId, self::DEFAULT_ROLE);
                    
                    header("Location: index.php?page=connexion");
                    exit;
                } catch (Exception $e) {
                    // Rollback si échec d'attribution de rôle
                    $utilisateur->delete($userId);
                    throw $e;
                }
            }
        } catch (Exception $e) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['error_message'] = "Erreur lors de l'inscription : " . $e->getMessage();
        }

        header("Location: index.php?page=inscription");
        exit;
    }
}
?>