<?php
class Auth {

    // Méthode pour se connecter
    public static function login($postemail, $postpassword) {
        $_SESSION['errorMessage'] = '';

        try {
            // Créer une instance de la classe Utilisateur
            $utilisateur = new Utilisateur();
            // Récupérer l'utilisateur par email
            if ($utilisateur->authenticate($postemail,$postpassword)) {
                $_SESSION['utilisateur_id'] = $utilisateur->utilisateur_id;
                $_SESSION['prenom'] = $utilisateur->prenom;
                $_SESSION['nom'] = $utilisateur->nom;
                header("Location: index.php?page=accueil");
                exit;
            } else {
                $_SESSION['errorMessage'] = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            $_SESSION['errorMessage'] = "Erreur de connexion, veuillez réessayer plus tard.";
        }

        // Si la connexion échoue, rediriger vers la page de connexion
        header("Location: index.php?page=connexion");
        exit;
    }

    // Méthode pour se déconnecter
    public static function logout() {
        // Supprimer les variables de session
        session_unset();
        session_destroy();

        // Rediriger vers la page d'accueil
        header("Location: index.php?page=accueil");
        exit;
    }
}
?>
