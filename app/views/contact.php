<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Contactez-nous</h1>
            <p>Vous pouvez nous contacter en utilisant l'adresse e-mail suivante :</p>
            <h4>contact@ecoride.fr</h4>
            <br>
            <p>Ou en remplissant le formulaire ci-dessous :</p>
        </section>

        <section>
            <form action="Covoiturage/app/controllers/send_message.php" method="post"> <!-- Formulaire pointe vers send_message.php dans controllers/ -->
                <label for="name">Votre nom </label>
                <input type="text" id="name" name="name" required>

                <label for="email">Votre e-mail </label>
                <input type="email" id="email" name="email" required>

                <label for="message">Votre message </label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </section>
    </main>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>