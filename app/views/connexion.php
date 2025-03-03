<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>
    <main>
        <section class="top-main">
            <h1>Connexion</h1>
        </section>
        <section>
            <form action="../app/controllers/traitement_connexion.php" method="post">
                <label for="email">E-mail </label>
                <input type="email" id="email" name="email" required>
                <label for="password">Mot de passe </label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Se connecter</button>
            </form>
        </section>
        <section class="link-proposal">
            <p>Pas encore de compte ? <a href="index.php?page=inscription">Inscrivez-vous ici</a></p>
        </section>
        <?php if (isset($_SESSION['errorMessage']) && $_SESSION['errorMessage']): ?>
        <div class="error-message"> <?php echo htmlspecialchars($_SESSION['errorMessage']); ?> </div>
        <?php unset($_SESSION['errorMessage']); ?>
        <?php endif; ?>
    </main>
    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>