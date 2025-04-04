<?php

require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class Auth {
    // Méthode pour se connecter
    public static function login($postemail, $postpassword) {
        $_SESSION['errorMessage'] = '';

        try {
            $utilisateur = new Utilisateur();
            $user = $utilisateur->authenticate($postemail, $postpassword);

            if ($user) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['utilisateur_id'] = $user->utilisateur_id;
                $_SESSION['prenom'] = $user->prenom;
                $_SESSION['nom'] = $user->nom;
                $_SESSION['email'] = $user->email;
                $_SESSION['pseudo'] = $user->pseudo;
                $_SESSION['credit'] = $user->credit;
                $_SESSION['photo'] = $user->photo;
                $_SESSION['suspended'] = $user->suspended;

                // Récupérer les rôles de l'utilisateur
                $roles = $user->getRoles();
                $_SESSION['roles'] = array_column($roles, 'libelle'); // Stocker les rôles en session

                // Vérifier si l'utilisateur est un administrateur
                if (in_array('Administrateur', $_SESSION['roles'])) {
                    header("Location: index.php?page=admin"); // Redirection vers la page admin
                } else {
                    header("Location: index.php?page=accueil"); // Redirection pour les autres utilisateurs
                }
                exit;
            } else {
                $_SESSION['errorMessage'] = "Email ou mot de passe incorrect.";
            }
        } catch (Exception $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            $_SESSION['errorMessage'] = "Erreur de connexion, veuillez réessayer plus tard.";
        }

        //session_write_close(); // Fermer la session avant de rediriger
        header("Location: index.php?page=connexion");
        exit;
    }

    // Méthode pour se déconnecter
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();

        header("Location: index.php?page=accueil");
        exit;
    }
}

?>