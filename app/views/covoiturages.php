<!DOCTYPE html>
<html lang="fr"> <!-- Définit la langue du document HTML comme étant le français -->
<head>
    <meta charset="UTF-8"> <!-- Spécifie que le jeu de caractères utilisé est UTF-8 (supporte tous les caractères internationaux) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Définit la mise en page responsive pour les appareils mobiles -->
    <title>Covoiturages</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers le fichier CSS externe pour le style de la page, mis à jour avec le bon chemin -->
</head>
<body> <!-- Début du contenu de la page -->
    
    <header>
        <?php include __DIR__ . '/../partials/header.php'; ?>
    </header>

    <main> <!-- Section principale du contenu -->
        <section class="top-main"> <!-- Section principale avec un style spécifique (défini dans CSS) -->
            <h1>Recherchez un covoiturage</h1> <!-- Titre principal de la page -->
        </section>
        
        <section> <!-- Section contenant le formulaire de recherche -->
            <form action="/Covoiturage/app/views/recherche.php" method="get">
                <label for="depart">Départ</label>
                <input type="text" id="depart" name="depart" required>

                <label for="arrivee">Arrivée</label>
                <input type="text" id="arrivee" name="arrivee" required>

                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>

                <button type="submit">Rechercher</button>
            </form>
        </section>
    </main>

    <footer>
        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html> <!-- Fin du document HTML -->
