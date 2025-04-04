<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application de Covoiturage - Accueil</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/evenements.js" defer></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     
</head>
<body>
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?> 
    </header>

    <main>
        <section class="top-main">
            <?php if (isset($_SESSION['utilisateur_id'])): ?> 
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['pseudo']); ?> !</h1> 
                <p>Ravi de vous revoir sur EcoRide</p> 
            <?php else: ?>
                <h1>Bienvenue sur EcoRide</h1>
                <p>Rejoignez notre communauté et recevez 20 crédits pour vos premiers voyages !</p>
            <?php endif; ?>
        </section>

        <section>
            <form action="index.php?page=research" method="post">
                <input type="text" name="depart" placeholder="Départ" required>
                <input type="text" name="arrivee" placeholder="Arrivée" required>
                <input type="text" name="date" id="dateInput" placeholder="jj/mm/aaaa" requiredpattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(20[2-9][0-9])"title="Format JJ/MM/AAAA (date future)">
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="presentation">
            <h2>À propos d'EcoRide</h2>
            <p>EcoRide est une plateforme de covoiturage conçue pour offrir des trajets économiques, écologiques et conviviaux.</p>
            <img src="images/covoiturage1.webp" alt="Covoiturage en route">
            <img src="images/covoiturage2.webp" alt="Communauté EcoRide">
            <img src="images/covoiturage3.webp" alt="Trajets écologiques">
        </section>
    </main>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>

    <script src="js/formresearch.js" defer></script>

</body>
</html>
