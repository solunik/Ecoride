<?php
// Démarrer la session pour stocker les informations de l'utilisateur
session_start();

// Connexion à la base de données
try {
    // Charger la configuration de la base de données depuis config.php
    $config = require __DIR__ . '/../../config/config.php';
    
    // Utilisation des valeurs du fichier config.php pour se connecter à la base de données
    $conn = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                // Mot de passe incorrect, stocker l'erreur dans la session et rediriger
                $_SESSION['error'] = "Mot de passe ou email incorrect";
                header("Location: /Covoiturage/app/views/connexion.php");
                exit;
            }
        } else {
            // Email non trouvé, stocker l'erreur dans la session et rediriger
            $_SESSION['error'] = "Utilisateur introuvable.";
            header("Location: /Covoiturage/app/views/connexion.php");
            exit;
        }
    } else {
        // Formulaire incomplet, stocker l'erreur dans la session et rediriger
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: /Covoiturage/app/views/connexion.php");
        exit;
    }
} catch (PDOException $e) {
    // Erreur de connexion à la base de données, stocker l'erreur dans la session et rediriger
    $_SESSION['error'] = "Erreur de connexion : " . $e->getMessage();
    header("Location: /Covoiturage/app/views/connexion.php");
    exit;
}
?>
