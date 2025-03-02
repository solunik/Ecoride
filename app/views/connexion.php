<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../public/styles.css">
</head>
<body>
    
    <header>
        <?php include('../partials/header.php'); ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Connexion</h1>
        </section>


        <section>
            <form action="../controllers/traitement_connexion.php" method="post"> <!-- Formulaire pointe vers traitement_connexion.php dans controllers/ -->
                <label for="email">E-mail </label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe </label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
        </section>

        <section class="link-proposal">
            <p>Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a></p> <!-- Lien vers inscription.php dans public/ -->
        </section>
        
        <!-- Afficher l'erreur si elle existe -->
        <?php if (isset($errorMessage) && $errorMessage): ?>
        <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php unset($errorMessage); ?> <!-- Supprimer la variable d'erreur après affichage -->
        <?php endif; ?>
        
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?>
    </footer>
</body>
</html>
