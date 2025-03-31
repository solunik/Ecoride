<?php
// Initialisation par défaut
if (!isset($_SESSION['recherche'])) {
    $_SESSION['recherche'] = [
        'resultats' => [],
        'message' => '',
        'recherche_effectuee' => false
    ];
}

// Récupération des données
$recherche = $_SESSION['recherche'];
unset($_SESSION['recherche']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de covoiturages</title>
    <link rel="stylesheet" href="styles.css">
    <script src="js/evenements.js" defer></script>
</head>
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

        <section class="filtres">
            <button id="btn-filtres-avances" class="btn-filtres">Filtres</button>
        </section>

        <section class="resultats-covoiturage">
            <?php if (!empty($recherche['message'])): ?>
                <p class="error-message"><?= htmlspecialchars($recherche['message']) ?></p>
            <?php elseif ($recherche['recherche_effectuee'] && empty($recherche['resultats'])): ?>
                <p>Aucun covoiturage trouvé.</p>
            <?php elseif (!empty($recherche['resultats'])): ?>
                <?php foreach ($recherche['resultats'] as $covoiturage): ?>
                    <?php
                    $energie = htmlspecialchars($covoiturage['energie']);
                    $ecologique = ($energie == 'électrique') ? 'ecologique' : '';
                    ?>

                    <div class="carte-covoiturage" 
                         data-ecologique="<?= $ecologique ?>"
                         data-prix="<?= htmlspecialchars($covoiturage['prix_personne']) ?>"
                         data-note="<?= htmlspecialchars(explode('/', $covoiturage['note'])[0]) ?>">

                        <h2><?= htmlspecialchars($covoiturage['lieu_depart']) ?> → <?= htmlspecialchars($covoiturage['lieu_arrivee']) ?></h2>
                        
                        <div class="chauffeur-info">
                            <?php if (!empty($covoiturage['photo'])): ?>
                                <img src="<?= 'data:image/jpeg;base64,' . base64_encode($covoiturage['photo']) ?>" 
                                     alt="Photo du chauffeur" 
                                     class="photo-chauffeur">
                            <?php else: ?>
                                <img src="images/photo_defaut.webp" 
                                     alt="Photo par défaut du chauffeur" 
                                     class="photo-chauffeur">
                            <?php endif; ?>
                            <p><strong><?= htmlspecialchars($covoiturage['pseudo']) ?></strong></p>
                            <p><?= htmlspecialchars($covoiturage['note']) ?> / 5</p>
                        </div>
                        
                        <p><strong>Date de départ</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?></p>
                        <p class="heure-depart"><strong>Heure de départ</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?></p>
                        <p class="heure-arrivee"><strong>Heure d'arrivée</strong> <?= htmlspecialchars($covoiturage['heure_arrivee']) ?></p>
                        <p class="prix-covoiturage"><strong>Prix</strong> <?= htmlspecialchars($covoiturage['prix_personne']) ?> crédits</p>
                        <p><strong>Places restantes</strong> <?= htmlspecialchars($covoiturage['nb_place']) ?></p>
                        
                        <div class="btn-container">
                            <button class='btn-detail' data-id='<?= htmlspecialchars($covoiturage['id_covoiturage']) ?>'>Détail</button>
                            <a href='participer.php?id=<?= htmlspecialchars($covoiturage['id_covoiturage']) ?>' class='btn-participer'>Participer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modale des filtres -->
    <div id="modalFiltres" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            
            <form id="form-filtres">
                <div class="filtre-groupe">
                    <label for="filtre-ecologique">écologique</label>
                    <select id="filtre-ecologique" name="ecologique">
                        <option value=""></option>
                        <option value="ecologique">oui</option>
                    </select>
                </div>
                
                <div class="filtre-groupe">
                    <label for="filtre-prix">Prix maximum (crédits)</label>
                    <input type="range" id="filtre-prix" name="prix" min="0" max="50" step="1" value="50">
                    <span id="prix-value">50</span>
                </div>
                
                <div class="filtre-groupe">
                    <label for="filtre-duree">Durée maximale (heures)</label>
                    <input type="range" id="filtre-duree" name="duree" min="1" max="24" step="1" value="24">
                    <span id="duree-value">24</span>
                </div>
                
                <div class="filtre-groupe">
                    <label for="filtre-note">Note minimale du conducteur</label>
                    <input type="range" id="filtre-note" name="note" min="0" max="5" step="0.5" value="0">
                    <span id="note-value">0</span>
                </div>
                
                <div class="boutons-filtres">
                    <button type="button" id="btn-appliquer-filtres">Appliquer</button>
                    <button type="button" id="btn-reinitialiser-filtres">Réinitialiser</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale des détails -->
    <div id="modalDetails" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>