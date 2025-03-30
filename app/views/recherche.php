<?php
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
    <link rel="stylesheet" href="styles.css">
    <script src="js/evenements.js" defer></script>
<body>
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main>
        <section class="top-main">
            <h1>Recherchez un covoiturage</h1>
        </section>
        <section>
            <form action="index.php?page=research" method="post">
                <input type="text" name="depart" placeholder="Départ" required>
                <input type="text" name="arrivee" placeholder="Arrivée" required>
                <input type="date" name="date" required>
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="filtre-eco">
            <select id="filtre-covoiturages">
                <option value="">Filtrer par...</option>
                <option value="ecologique">Écologique</option>
            </select>
        </section>

        <section class="resultats-covoiturage">
            <?php if ($errorMessage): ?>
                <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
            <?php elseif (!empty($resultats)): ?>
                <?php foreach ($resultats as $covoiturage): ?>
                    <?php
                    $energie = htmlspecialchars($covoiturage['energie']);
                    $ecologique = ($energie == 'électrique') ? 'ecologique' : ''; // Classe écologique si voiture électrique
                    ?>

                    <div class="carte-covoiturage" 
                    
                    data-ecologique="<?= $ecologique ?>">

                        <h2><?= htmlspecialchars($covoiturage['lieu_depart']) ?> → <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h2>
                        <div class="chauffeur-info">
                            <?php if (!empty($covoiturage['photo'])): ?>
                                <img src="<?= 'data:image/jpeg;base64,' . base64_encode($covoiturage['photo']) ?>" alt="Photo du chauffeur" class="photo-chauffeur">
                            <?php else: ?>
                                <img src="images/photo_defaut.webp" alt="Photo par défaut du chauffeur" class="photo-chauffeur">
                            <?php endif; ?>
                            <p><strong><?= htmlspecialchars($covoiturage['pseudo']) ?></strong></p>
                            <p><?= htmlspecialchars($covoiturage['note']) ?> / 5</p>
                        </div>
                        
                        <p><strong>Date de départ</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                        <p><strong>Heure de départ</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?></p>
                        <p><strong>Heure d'arrivée</strong> <?= htmlspecialchars($covoiturage['heure_arrivee']) ?></p>
                        <p><strong>Prix</strong> <?= htmlspecialchars($covoiturage['prix_personne']) ?> crédits</p>
                        <p><strong>Places restantes</strong> <?= htmlspecialchars($covoiturage['nb_place']) ?></p>
                        
                        <div class="btn-container">
                            <button class='btn-detail' data-id='<?= htmlspecialchars($covoiturage['id_covoiturage']) ?>'>Détail</button>
                            <a href='participer.php?id=<?= htmlspecialchars($covoiturage['id_covoiturage']) ?>' class='btn-participer'>Participer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (empty($resultats) && $errorMessage === ''): ?>
                <p>Aucun covoiturage trouvé.</p>
            <?php endif; ?>
        </section>
    </main>


    <div id="modalDetails" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="modalContent">
            <!-- Contenu chargé dynamiquement -->
        </div>
    </div>
    </div>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>