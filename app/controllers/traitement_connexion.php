<?php
// Démarrer la session pour stocker les informations de l'utilisateur
session_start();

// Inclure la classe de connexion
require_once __DIR__ . '/../../app/models/db.php';

$errorMessage = ''; // Variable pour l'erreur

try {
    // Obtenir l'instance de la connexion PDO
    $conn = Database::getInstance();

    // Vérifier si les champs du formulaire sont remplis
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // Récupération de l'email et du mot de passe
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password']; // Mot de passe en clair

        // Requête pour récupérer l'utilisateur correspondant à l'email
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if ($utilisateur) {
            $passwordHash = $utilisateur['password'];

            // Comparer le mot de passe haché avec le mot de passe en clair
            if (password_verify($password, $passwordHash)) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
                $_SESSION['prenom'] = $utilisateur['prenom'];
                $_SESSION['nom'] = $utilisateur['nom'];

                // Rediriger vers la page d'accueil
                header("Location: /Covoiturage/public/index.php");
                exit;
            } else {
                // Mot de passe incorrect
                $errorMessage = "Mot de passe ou email incorrect";
            }
        } else {
            // Email non trouvé
            $errorMessage = "Mot de passe ou email incorrect";
        }
    } else {
        // Formulaire incomplet
        $errorMessage = "Veuillez remplir tous les champs.";
    }
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    $errorMessage = "Erreur de connexion : " . $e->getMessage();
}

// Vérifier si une erreur a été définie, sinon on la passe à la vue
if ($errorMessage) {
    // Inclure la vue avec le message d'erreur
    include('../../app/views/connexion.php');
    exit;
}
?>
