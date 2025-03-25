<?php

require_once __DIR__ . '/../models/Utilisateur.php';

class Registration {
    public static function register($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
        $_SESSION['error_message'] = ''; // Reset du message d'erreur à chaque nouvelle tentative

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
                $_SESSION['error_message'] = "L'email est déjà utilisé.";
            } elseif ($utilisateur->findByPseudo($pseudo)) {
                $_SESSION['error_message'] = "Le pseudo est déjà pris.";
            } elseif ($password !== $confirmPassword) {
                $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Inscription de l'utilisateur
                $utilisateur->inscription($nom, $prenom, $email, $password, $pseudo);
                
                // Redirection vers la page de connexion après inscription réussie
                header("Location: index.php?page=connexion");
                exit;
            }
        } catch (Exception $e) {
            // Enregistrer l'erreur dans le log et dans la session
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['error_message'] = "Erreur lors de l'inscription, veuillez réessayer plus tard.";
        }

        // Redirection en cas d'erreur
        header("Location: index.php?page=inscription");
        exit;
    }
}
?>
