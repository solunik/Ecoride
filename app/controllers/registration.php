<?php
require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class Registration {
    const DEFAULT_ROLE = 'utilisateur'; // Définition du rôle par libellé
    const PASSWORD_MIN_LENGTH = 8;      // Force minimale du mot de passe

    public static function register($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
        $_SESSION['error_message'] = '';

        try {
            // Nettoyage des données
            $email = trim(strtolower($postEmail));
            $pseudo = trim(strtolower($postPseudo));
            $prenom = trim($postPrenom);
            $nom = trim($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            // Validation des champs vides
            if (empty($email) || empty($pseudo) || empty($prenom) || empty($nom) || empty($password) || empty($confirmPassword)) {
                $_SESSION['error_message'] = "Tous les champs doivent être remplis.";
            }
            // Validation de l'email
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Email invalide.";
            }
            // Validation du pseudo (min 3, max 20 caractères alphanumériques)
            elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $pseudo)) {
                $_SESSION['error_message'] = "Le pseudo doit contenir entre 3 et 20 caractères alphanumériques ou '_'.";
            }
            // Vérification de la longueur des noms et prénoms
            elseif (strlen($prenom) < 2 || strlen($prenom) > 20 || strlen($nom) < 2 || strlen($nom) > 20) {
                $_SESSION['error_message'] = "Les champs prénom et nom doivent contenir entre 2 et 20 caractères.";
            }
            // Vérification de la force du mot de passe
            elseif (strlen($password) < self::PASSWORD_MIN_LENGTH) {
                $_SESSION['error_message'] = "Le mot de passe doit contenir au moins " . self::PASSWORD_MIN_LENGTH . " caractères.";
            }
            // Vérification que les mots de passe correspondent
            elseif ($password !== $confirmPassword) {
                $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
            }
            else {
                // Vérification en base de données
                $utilisateur = new Utilisateur();
                $existingUserByEmail = $utilisateur->findByEmail($email);
                $existingUserByPseudo = $utilisateur->findByPseudo($pseudo);

                if ($existingUserByEmail) {
                    $_SESSION['error_message'] = "L'email est déjà utilisé.";
                } elseif ($existingUserByPseudo) {
                    $_SESSION['error_message'] = "Le pseudo est déjà pris.";
                } else {
                    // Inscription
                    $userId = $utilisateur->inscription($nom, htmlspecialchars($prenom), $email, $password, $pseudo);
                    error_log("Nouvel utilisateur inscrit : id=$userId, email=$email, pseudo=$pseudo");

                    try {
                        // Attribution du rôle
                        $role = new Role();
                        $role->assignRoleByLibelle($userId, self::DEFAULT_ROLE);
                        error_log("Rôle attribué avec succès à l'utilisateur id=$userId");

                        header("Location: index.php?page=connexion");
                        exit;
                    } catch (Exception $e) {
                        // Rollback si échec d'attribution de rôle
                        error_log("Erreur d'attribution de rôle : " . $e->getMessage());
                        $utilisateur->delete($userId);
                        throw $e;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Erreur technique d'inscription : " . $e->getMessage());
            $_SESSION['error_message'] = "Erreur lors de l'inscription : " . $e->getMessage();
        }

        header("Location: index.php?page=inscription");
        exit;
    }
}
?>
