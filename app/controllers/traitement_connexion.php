<?php
// Démarrer la session pour stocker les informations de l'utilisateur
session_start();

// Connexion à la base de données
try {
    $conn = new PDO('mysql:host=localhost;dbname=covoiturage', 'root', '');
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
                // Mot de passe incorrect
                $error = "Mot de passe ou email incorrect.";
            }
        } else {
            // Email non trouvé
            $error = "Utilisateur introuvable.";
        }
    } else {
        // Formulaire incomplet
        $error = "Veuillez remplir tous les champs.";
    }
} catch (PDOException $e) {
    // Erreur de connexion à la base de données
    $error = "Erreur de connexion : " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../public/styles.css"> <!-- Chemin vers ton CSS -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php include('../partials/header.php'); ?> <!-- Inclusion de header.php depuis partials/ -->
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h1>Connexion</h1>

            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <p><a href="/Covoiturage/app/views/connexion.php">Retour à la page de connexion</a></p> <!-- Lien vers la page de connexion -->
        </section>
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion du footer -->
    </footer>
</body>
</html>
