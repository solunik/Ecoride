<?php
session_start(); // Démarre la session PHP
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="/Covoiturage/public/styles.css"> <!-- Lien vers le fichier CSS dans public/ -->
</head>
<body>
    <header>
            <?php include('../partials/header.php'); ?> <!-- Inclusion de header.php depuis partials/ -->
    </header>
    
    <div class="content">
        <?php
        // Connexion à la base de données
        try {
            // Création d'une connexion PDO à la base de données "covoiturage" sur le serveur local
            $conn = new PDO('mysql:host=localhost;dbname=covoiturage', 'root', '');
            
            // Configuration pour afficher les erreurs SQL en cas de problème
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifie si les données sont transmises via le formulaire
            if (isset($_GET['depart'], $_GET['arrivee'], $_GET['date'])) {
                // Récupérer et sécuriser les données en échappant les caractères spéciaux
                $depart = htmlspecialchars($_GET['depart']);
                $arrivee = htmlspecialchars($_GET['arrivee']);
                $date = htmlspecialchars($_GET['date']);

                // Préparation de la requête SQL pour rechercher les covoiturages correspondant aux critères
                $stmt = $conn->prepare("SELECT * FROM Covoiturage WHERE lieu_depart = :depart AND lieu_arrivee = :arrivee AND date_depart = :date ORDER BY heure_depart");

                // Exécution de la requête en remplaçant les valeurs des paramètres par celles de l'utilisateur
                $stmt->execute([
                    ':depart' => $depart,
                    ':arrivee' => $arrivee,
                    ':date' => $date
                ]);

                // Récupération des résultats sous forme de tableau associatif
                $resultats = $stmt->fetchAll();

                // Vérifie si des résultats existent
                if ($resultats) {
                    echo "<h1>Résultats de la recherche</h1>";
                    // Parcourt les résultats et les affiche dynamiquement
                    foreach ($resultats as $covoiturage) {
                        echo "<p>Covoiturage de " . htmlspecialchars($covoiturage['lieu_depart']) . " à " . htmlspecialchars($covoiturage['lieu_arrivee']) . " le " . htmlspecialchars($covoiturage['date_depart']) . " à " . htmlspecialchars($covoiturage['heure_depart']) . " - Prix : " . htmlspecialchars($covoiturage['prix_personne']) . "€</p>";
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
    </div>
    
    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion de footer.php depuis partials/ -->
    </footer>
</body>
</html>
