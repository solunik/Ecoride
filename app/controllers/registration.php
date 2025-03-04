<?php

function registration($postEmail, $postPseudo, $postPrenom, $postNom, $postPassword, $postConfirmPassword) {
    require_once __DIR__ . '/../models/db.php';
    $errorMessage = ''; // Variable pour l'erreur

    try {
        $conn = Database::getInstance();

        // Vérifier si la méthode de la requête est POST
        if (isset($_POST['ok'])) {
            // Assainissement des entrées
            $email = htmlspecialchars($postEmail);
            $pseudo = htmlspecialchars($postPseudo);
            $prenom = htmlspecialchars($postPrenom);
            $nom = htmlspecialchars($postNom);
            $password = $postPassword;
            $confirmPassword = $postConfirmPassword;

            // Vérifier si les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            } else {
                // Hacher le mot de passe
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Vérifier si l'email ou le pseudo existent déjà
                $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE LOWER(email) = LOWER(:email) OR LOWER(pseudo) = LOWER(:pseudo)");
                $stmt->execute([':email' => $email, ':pseudo' => $pseudo]);
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($utilisateur) {
                    if (strtolower($utilisateur['email']) === strtolower($email)) {
                        $errorMessage = "Cet email est déjà utilisé.";
                    } elseif (strtolower($utilisateur['pseudo']) === strtolower($pseudo)) {
                        $errorMessage = "Ce pseudo est déjà pris.";
                    }
                } else {
                    // Insérer l'utilisateur en base de données
                    $stmt = $conn->prepare("INSERT INTO utilisateur (email, pseudo, prenom, nom, password, credit) VALUES (:email, :pseudo, :prenom, :nom, :password, :credit)");
                    $stmt->execute([
                        ':email' => $email,
                        ':pseudo' => $pseudo,
                        ':prenom' => $prenom,
                        ':nom' => $nom,
                        ':password' => $passwordHash,
                        ':credit' => 20
                    ]);

                    // Rediriger vers la page de connexion
                    header("Location: index.php?page=connexion");
                    exit;
                }
            }
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de base de données
        $errorMessage = "Erreur de connexion : " . $e->getMessage();
    }

    // Passer le message d'erreur à la vue via une session
    if ($errorMessage) {
        $_SESSION['error_message'] = $errorMessage;
        header('Location: index.php?page=inscription');
        exit;
    }
}
?>