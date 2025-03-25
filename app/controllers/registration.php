<?php

require_once __DIR__ . '/../models/utilisateur.php';

function registration($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
    $errorMessage = '';

    try {
        // Assainir les données
        $email = trim(strtolower($postEmail));
        $pseudo = trim(strtolower($postPseudo));
        $prenom = htmlspecialchars($postPrenom);
        $nom = htmlspecialchars($postNom);
        $password = $postPassword;
        $confirmPassword = $postConfirmPassword;

        // Créer une instance du modèle Utilisateur
        $utilisateurModel = new Utilisateur();

        // Vérifier si l'email ou le pseudo existent déjà
        if ($utilisateurModel->findByEmail($email)) {
            $errorMessage = "L'email est déjà utilisé.";
        } elseif ($utilisateurModel->findByPseudo($pseudo)) {
            $errorMessage = "Le pseudo est déjà pris.";
        } elseif ($password !== $confirmPassword) {
            $errorMessage = "Les mots de passe ne correspondent pas.";
        } else {
            // Assigner les valeurs aux propriétés de l'utilisateur
            $utilisateurModel->nom = $nom;
            $utilisateurModel->prenom = $prenom;
            $utilisateurModel->email = $email;
            $utilisateurModel->password = $password;
            $utilisateurModel->pseudo = $pseudo;
            $utilisateurModel->credit = 20; // Crédit par défaut

            // Enregistrer l'utilisateur
            $utilisateurModel->inscription();
                // Vérifier avant redirection
                header("Location: index.php?page=connexion");
                exit;
            
        }
    } catch (Exception $e) {
        $errorMessage = "Erreur : " . $e->getMessage();
    }

    // Gérer les erreurs
    if ($errorMessage) {
        $_SESSION['error_message'] = $errorMessage;
        header('Location: index.php?page=inscription');
        exit;
    }
}
?>
