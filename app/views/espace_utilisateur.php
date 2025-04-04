<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Utilisateur</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/evenements.js" defer></script>
</head>
<body>

    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Mon profil</h1>
        </section>

        <section class="profil-section">
            <h2>Mes informations</h2>
            <form action="index.php?page=update_profile" method="post" enctype="multipart/form-data">
                
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($user->pseudo) ?>" required>

                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user->prenom) ?>" required>

                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->nom) ?>" required>
                
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                
                <label for="nom">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($user->adresse) ?>" required>

                <h3>Changer mon mot de passe</h3>
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password">

                <label for="confirm_new_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password">

                <h3>Photo de profil</h3>
                <?php if (!empty($user->photo)): ?>
                    <img src="uploads/<?= htmlspecialchars($user->photo) ?>" alt="Photo de profil" class="profil-photo">
                <?php else: ?>
                    <p>Pas de photo pour l'instant.</p>
                <?php endif; ?>

                <label for="photo">Modifier la photo</label>
                <input type="file" id="photo" name="photo" accept="image/*">

                <button type="submit" name="update_profile">Mettre à jour</button>
            </form>
        </section>

        <!-- Message d'erreur ou de succès -->
        <?php if (isset($_SESSION['profile_message'])): ?>
            <div class="message"><?= htmlspecialchars($_SESSION['profile_message']) ?></div>
            <?php unset($_SESSION['profile_message']); ?>
        <?php endif; ?>

    </main>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>
