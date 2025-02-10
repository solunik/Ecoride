<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application de Covoiturage - Accueil</title>
    <link rel="stylesheet" href="styles.css"> <!-- Nouveau chemin vers styles.css -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php include __DIR__ . '/../app/partials/header.php'; ?> 
                <!-- Mise à jour du chemin vers le header (répertoire app/partials) -->
            </ul>
        </nav>
    </header>

    <main>
        <section class="top-main">
            <?php if (isset($_SESSION['utilisateur_id'])): ?> 
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?> !</h1> 
                <p>Ravi de vous revoir sur EcoRide</p> 
            <?php else: ?>
                <h1>Bienvenue sur EcoRide</h1>
                <p>Rejoignez notre communauté pour des trajets économiques et respectueux de l'environnement.</p>
            <?php endif; ?>
        </section>

        <section>
            <form action="/Covoiturage/app/views/recherche.php" method="get" class="search-form">
                <!-- Nouveau chemin pour envoyer les données à recherche.php -->
                <label for="depart">Départ</label>
                <input type="text" id="depart" name="depart" placeholder="Ex : Paris" required>

                <label for="arrivee">Arrivée</label>
                <input type="text" id="arrivee" name="arrivee" placeholder="Ex : Lyon" required>

                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>

                <button type="submit">Rechercher</button>
            </form>
        </section>
    </main>

    <footer>
        <?php include __DIR__ . '/../app/partials/footer.php'; ?> 
        <!-- Mise à jour du chemin vers le footer (répertoire app/partials) -->
    </footer>
</body>
</html>
