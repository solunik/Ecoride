<?php
session_start();
$resultats = $_SESSION['resultats_recherche'] ?? [];
$errorMessage = $_SESSION['errorMessage'] ?? null;

// Suppression des données stockées après récupération
unset($_SESSION['resultats_recherche']);
unset($_SESSION['errorMessage']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de covoiturages</title>
    <link rel="stylesheet" href="/Covoiturage/public/styles.css">
</head>
<body>
    <header>
        <?php include('../partials/header.php'); ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Recherchez un covoiturage</h1>
        </section>
        <section>
            <form action="/Covoiturage/app/controllers/traitement_recherche.php" method="GET">
                <input type="text" name="depart" placeholder="Départ" required>
                <input type="text" name="arrivee" placeholder="Arrivée" required>
                <input type="date" name="date" required>
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="resultats-covoiturage">
            <?php if ($errorMessage): ?>
                <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
            <?php elseif (!empty($resultats)): ?>
                <?php foreach ($resultats as $covoiturage): ?>
                    <?php
                    $energie = htmlspecialchars($covoiturage['energie']);
                    $ecologique = ($energie == 'électrique') ? 'Oui' : 'Non';
                    ?>
                    <div class='carte-covoiturage'>
                        <h2><?= htmlspecialchars($covoiturage['lieu_depart']) ?> → <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h2>
                        <div class='chauffeur-info'>
                            <p><strong><?= htmlspecialchars($covoiturage['pseudo']) ?></strong></p>
                            <p><strong>Note:</strong> <?= htmlspecialchars($covoiturage['note']) ?> / 5</p>
                        </div>
                        <p><strong>Date de départ</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                        <p><strong>Heure de départ</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?></p>
                        <p><strong>Heure d'arrivée</strong> <?= htmlspecialchars($covoiturage['heure_arrivee']) ?></p>
                        <p><strong>Prix:</strong> <?= htmlspecialchars($covoiturage['prix_personne']) ?> crédits</p>
                        <p><strong>Places restantes</strong> <?= htmlspecialchars($covoiturage['nb_place']) ?></p>
                        <p><strong>Voyage écologique</strong> <?= $ecologique ?></p>
                        <a href='detail.php?id=<?= htmlspecialchars($covoiturage['id_covoiturage']) ?>' class='btn-detail'>Détail</a>
                    </div>
                <?php endforeach; ?>
                <?php elseif (empty($resultats) && $errorMessage === ''): ?>
                    <p>Aucun covoiturage</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?>
    </footer>
</body>
</html>
