<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require_once __DIR__ . '/../../app/models/db.php';

$errorMessage = ''; // Variable pour l'erreur

// Connexion à la base de données
try {
    $conn = Database::getInstance();

    // Vérifier si la méthode de la requête est POST
    if (isset($_POST['ok'])) {
        $email = htmlspecialchars($_POST['email']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Vérifier si les mots de passe correspondent
        if ($password !== $confirmPassword) {
            $errorMessage = "Les mots de passe ne correspondent pas.";
        } else {
            // Hacher le mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Vérifier si l'email ou le pseudo existent déjà (comparaison insensible à la casse)
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
                // Insérer l'utilisateur en base de données (le pseudo et l'email sont insérés tels quels, sans modification)
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
                header("Location: ../../public/index.php?page=connexion");
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
    header('Location: ../../public/index.php?page=inscription');
    exit;
}
?>
