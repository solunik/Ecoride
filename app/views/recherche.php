<?php
session_start(); // Démarre la session PHP
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="/Covoiturage/public/styles.css"> <!-- Lien vers le fichier CSS dans public/ -->
</head>
<body>
    <header>
        <?php include('../partials/header.php'); ?> <!-- Inclusion de header.php depuis partials/ -->
    </header>
    
    <main>
    <section class="top-main">
        <h1>Résultats de la recherche</h1>
    </section>

    <section class="resultats-covoiturage">
        <?php
        // Connexion à la base de données
        try {
            $config = require __DIR__ . '/../../config/config.php';
            $conn = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_GET['depart'], $_GET['arrivee'], $_GET['date'])) {
                $depart = htmlspecialchars($_GET['depart']);
                $arrivee = htmlspecialchars($_GET['arrivee']);
                $date = htmlspecialchars($_GET['date']);

                // Préparation de la requête SQL pour rechercher les covoiturages correspondant aux critères
                $stmt = $conn->prepare("SELECT * FROM Covoiturage WHERE lieu_depart = :depart AND lieu_arrivee = :arrivee AND date_depart = :date ORDER BY heure_depart");
                $stmt->execute([':depart' => $depart, ':arrivee' => $arrivee, ':date' => $date]);

                // Récupération des résultats sous forme de tableau associatif
                $resultats = $stmt->fetchAll();

                // Vérifie si des résultats existent
                if ($resultats) {
                    // Affiche chaque résultat sous forme de carte
                    foreach ($resultats as $covoiturage) {
                        $energie = htmlspecialchars($covoiturage['energie']);
                    
                        $ecologique = ($energie == 'électrique' || $energie == 'hybride') ? 'Oui' : 'Non';
                        echo "<div class='carte-covoiturage'>
                                <h2>" . htmlspecialchars($covoiturage['lieu_depart']) . " → " . htmlspecialchars($covoiturage['lieu_arrivee']) . "</h2>
                                <div class='chauffeur-info'>
                                    <img src='" . htmlspecialchars($covoiturage['photo']) . "' alt='Photo du chauffeur' class='photo-chauffeur'>
                                    <p><strong>Pseudo:</strong> " . htmlspecialchars($covoiturage['pseudo']) . "</p>
                                    <p><strong>Note:</strong> " . htmlspecialchars($covoiturage['note']) . " / 5</p>
                                </div>
                                <p><strong>Date de départ:</strong> " . htmlspecialchars($covoiturage['date_depart']) . "</p>
                                <p><strong>Heure de départ:</strong> " . htmlspecialchars($covoiturage['heure_depart']) . "</p>
                                <p><strong>Heure d'arrivée:</strong> " . htmlspecialchars($covoiturage['heure_arrivee']) . "</p>
                                <p><strong>Prix:</strong> " . htmlspecialchars($covoiturage['prix_personne']) . "€</p>
                                <p><strong>Places restantes:</strong> " . htmlspecialchars($covoiturage['nb_place']) . "</p>
                                <p><strong>Voyage écologique:</strong> " . $ecologique . "</p>
                                <a href='detail.php?id=" . htmlspecialchars($covoiturage['id_covoiturage']) . "' class='btn-detail'>Détail</a>
                            </div>";
                    }
                } else {
                    // Message si aucun covoiturage ne correspond aux critères
                    echo "<h1>Aucun résultat</h1>";
                    echo "<p>Pas de covoiturages trouvés pour ces critères.</p>";
                }
            } else {
                // Message d'erreur si l'utilisateur tente d'accéder directement à la page sans passer par le formulaire
                echo "<h1>Erreur</h1>";
                echo "<p>Veuillez utiliser le formulaire de recherche.</p>";
            }
        } catch (PDOException $e) {
            // Affichage d'un message d'erreur en cas d'échec de connexion à la base de données
            echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
        }
        ?>
    </section>
</main>
    
    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion de footer.php depuis partials/ -->
    </footer>
</body>
</html>
