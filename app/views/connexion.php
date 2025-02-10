<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/Covoiturage/public/styles.css"> <!-- Lien vers le fichier CSS dans public/ -->
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
            <h1>Connexion</h1>
        </section>

        <section>
            <form action="/Covoiturage/app/controllers/traitement_connexion.php" method="post"> <!-- Formulaire pointe vers traitement_connexion.php dans controllers/ -->
                <label for="email">E-mail </label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe </label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
        </section>

        <section class="link-proposal">
            <p>Pas encore de compte ? <a href="/Covoiturage/app/views/inscription.php">Inscrivez-vous ici</a>.</p> <!-- Lien vers inscription.php dans public/ -->
        </section>
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion de footer.php depuis partials/ -->
    </footer>
</body>
</html>
