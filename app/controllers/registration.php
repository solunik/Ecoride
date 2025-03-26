<?php

require_once __DIR__ . '/../models/utilisateur.php';

class Registration {
    public static function register($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
        $_SESSION['error_message'] = ''; // Réinitialisation du message d'erreur à chaque nouvelle tentative

        try {
            // Assainir les entrées utilisateur
            $email = trim(strtolower($postEmail)); // Convertir en minuscule
            $pseudo = trim(strtolower($postPseudo));
            $prenom = htmlspecialchars($postPrenom); // Protection contre les injections XSS
            $nom = htmlspecialchars($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            // Création de l'objet Utilisateur
            $utilisateur = new Utilisateur();

            // Vérifier si l'email ou le pseudo existent déjà
            $existingUserByEmail = $utilisateur->findByEmail($email);
            $existingUserByPseudo = $utilisateur->findByPseudo($pseudo);

            if ($existingUserByEmail) {
                $_SESSION['error_message'] = "L'email est déjà utilisé.";
            } elseif ($existingUserByPseudo) {
                $_SESSION['error_message'] = "Le pseudo est déjà pris.";
            } elseif ($password !== $confirmPassword) {
                $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
            } else {
                // Inscription de l'utilisateur si toutes les conditions sont remplies
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
