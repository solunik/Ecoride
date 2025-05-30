<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
    <script src="js/evenements.js" defer></script>
</head>
<body>
    
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Inscription</h1>
        </section>


        <section>
            <form action="index.php?page=registration" method="post">
                <label for="email">E-mail </label>
                <input type="email" id="email" name="email" required>

                <label for="pseudo">Pseudo </label>
                <input type="text" id="pseudo" name="pseudo" required>

                <label for="prenom">Prénom </label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="nom">Nom </label>
                <input type="text" id="nom" name="nom" required>

                <label for="password">Mot de passe </label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirmer le mot de passe </label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit" name="ok">S'inscrire</button>
            </form>
        </section>

        <section class="link-proposal">
            <p>Déjà un compte ? <a href="index.php?page=connexion">Connectez-vous ici</a></p>
        </section>

        <!-- Affichage du message d'erreur -->
        <?php if (isset($_SESSION['error_message']) && $_SESSION['error_message']): ?>
        <div class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
        <?php unset($_SESSION['error_message']); ?> <!-- Supprimer le message après affichage -->
        <?php endif; ?>
    
    </main>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>
