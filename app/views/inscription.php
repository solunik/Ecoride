<?php
// Démarrer la session
session_start(); 
// Cette ligne démarre une session pour gérer l'état de l'utilisateur pendant la navigation (par exemple, pour stocker des informations de connexion ou des messages d'erreur).

// Connexion à la base de données
try {
    $conn = new PDO('mysql:host=localhost;dbname=covoiturage', 'root', ''); 
    // Crée une connexion à la base de données MySQL. Ici, la base de données s'appelle 'covoiturage' et l'utilisateur est 'root' sans mot de passe.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // Définit l'attribut de gestion des erreurs pour que les exceptions soient lancées en cas d'erreur.

    // Vérifie si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        // Si la méthode de la requête est POST, cela signifie que le formulaire a été soumis.

        $email = htmlspecialchars($_POST['email']);
        $pseudo = htmlspecialchars($_POST['pseudo']); 
        $prenom = htmlspecialchars($_POST['prenom']); 
        $nom = htmlspecialchars($_POST['nom']); 
        $password = $_POST['password']; 
        $confirmPassword = $_POST['confirm_password']; 
        // Récupère les données envoyées par le formulaire et les nettoie avec htmlspecialchars pour éviter les attaques XSS.

        // Vérifier si les mots de passe correspondent
        if ($password === $confirmPassword) { 
            // Si les mots de passe correspondent, on continue.

            // Hacher le mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Vérifier si l'email existe déjà
            $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = :email OR pseudo = :pseudo");
            // Prépare une requête SQL pour rechercher un utilisateur avec l'email fourni.
            $stmt->execute([':email' => $email, ':pseudo' => $pseudo]); 
            // Exécute la requête avec l'email comme paramètre.
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 
            // Récupère les résultats sous forme de tableau associatif.

            if ($user) { 
                // Si un utilisateur avec cet email ou pseudo existe déjà, on affiche une erreur.
                if ($user['email'] === $email) {
                    $error = "Cet email est déjà utilisé.";
                } elseif ($user['pseudo'] === $pseudo) {
                    $error = "Ce pseudo est déjà pris.";
                }
            } else {
                // Si l'email est unique et le pseudo, on insère l'utilisateur dans la base de données.
                $stmt = $conn->prepare("INSERT INTO utilisateur (email, pseudo, prenom, nom, password, credit) VALUES (:email, :pseudo, :prenom, :nom, :password, :credit)");
                // Prépare une requête SQL pour insérer un nouvel utilisateur dans la table 'utilisateur'.
                $stmt->execute([':email' => $email, ':pseudo' => $pseudo, ':prenom' => $prenom, ':nom' => $nom, ':password' => $passwordHash, ':credit' => 20]); 
                // Exécute la requête en insérant les données de l'utilisateur.

                // Rediriger l'utilisateur vers la page de connexion après l'inscription
                header("Location: /Covoiturage/app/views/connexion.php");
                exit; 
                // Redirige l'utilisateur vers la page de connexion après une inscription réussie.
            }
        } else {
            // Si les mots de passe ne correspondent pas, on affiche un message d'erreur.
            $error = "Les mots de passe ne correspondent pas.";
        }
    }
} catch (PDOException $e) {
    // Si une erreur se produit lors de la connexion à la base de données, on capture l'exception et on affiche un message d'erreur générique.
    $error = "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="/Covoiturage/public/styles.css">
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
        <section class="top-main">
            <h1>Inscription</h1>
            <!-- Affiche le titre de la page -->
        </section>
        
        <section>
            <?php if (isset($error)): ?> 
                <!-- Si une erreur est présente, elle sera affichée ici -->
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="/Covoiturage/app/views/inscription.php">
                <!-- Le formulaire envoie les données à la même page 'inscription.php' avec la méthode POST -->

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <!-- Champ pour l'email avec validation HTML5 pour un format valide -->

                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" required>

                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" required>
                <!-- Champ pour le prénom -->

                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" required>
                <!-- Champ pour le nom -->

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
                <!-- Champ pour le mot de passe -->

                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <!-- Champ pour confirmer le mot de passe -->

                <button type="submit">S'inscrire</button>
                <!-- Bouton pour soumettre le formulaire -->
            </form>
        </section>
        
        <section class="link-proposal">
            <p>Vous avez déjà un compte ? <a href="/Covoiturage/app/views/connexion.php">Connectez-vous ici</a></p>
            <!-- Lien vers la page de connexion pour les utilisateurs déjà inscrits -->
        </section>
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion de footer.php depuis partials/ -->
    </footer>
</body>
</html>
