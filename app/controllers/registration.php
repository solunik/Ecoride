<?php

require_once __DIR__ . '/../models/Utilisateur.php';

class Registration {
    public static function register($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
        $_SESSION['errorMessage'] = '';

        try {
            // Assainir les entrées utilisateur
            $email = trim(strtolower($postEmail));
            $pseudo = trim(strtolower($postPseudo));
            $prenom = htmlspecialchars($postPrenom);
            $nom = htmlspecialchars($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            $utilisateur = new Utilisateur();

            // Vérifier si l'email ou le pseudo existent déjà
            if ($utilisateur->findByEmail($email)) {
                $_SESSION['errorMessage'] = "L'email est déjà utilisé.";
            } elseif ($utilisateur->findByPseudo($pseudo)) {
                $_SESSION['errorMessage'] = "Le pseudo est déjà pris.";
            } elseif ($password !== $confirmPassword) {
                $_SESSION['errorMessage'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Inscription de l'utilisateur
                $utilisateur->inscription($nom, $prenom, $email, $password, $pseudo);
                
                // Redirection vers la page de connexion après inscription réussie
                header("Location: index.php?page=connexion");
                exit;
            }
        } catch (Exception $e) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['errorMessage'] = "Erreur lors de l'inscription, veuillez réessayer plus tard.";
        }

        // Redirection en cas d'erreur
        header("Location: index.php?page=inscription");
        exit;
    }
}
?>
