<?php
session_start(); // Démarre la session PHP, permettant d'utiliser les variables de session (comme l'authentification de l'utilisateur)
?>
<!DOCTYPE html>
<html lang="fr"> <!-- Définit la langue du document HTML comme étant le français -->
<head>
    <meta charset="UTF-8"> <!-- Spécifie que le jeu de caractères utilisé est UTF-8 (supporte tous les caractères internationaux) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Définit la mise en page responsive pour les appareils mobiles -->
    <title>Covoiturages</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="/Covoiturage/public/styles.css"> <!-- Lien vers le fichier CSS externe pour le style de la page, mis à jour avec le bon chemin -->
</head>
<body> <!-- Début du contenu de la page -->
    
    <header> <!-- Section de l'en-tête contenant la navigation -->
        <nav> <!-- Section de la barre de navigation -->
            <ul>
                <?php include('../partials/header.php'); ?> <!-- Inclusion de header.php depuis partials/ -->
            </ul>
        </nav>
    </header>

    <main> <!-- Section principale du contenu -->
        <section class="top-main"> <!-- Section principale avec un style spécifique (défini dans CSS) -->
            <h1>Recherchez un covoiturage</h1> <!-- Titre principal de la page -->
        </section>
        
        <section> <!-- Section contenant le formulaire de recherche -->
            <form action="/Covoiturage/app/views/recherche.php" method="get"> <!-- Formulaire qui enverra les données via la méthode GET à 'recherche.php' -->
                <label for="depart">Départ</label> <!-- Etiquette du champ départ -->
                <input type="text" id="depart" name="depart" required> <!-- Champ de saisie pour le lieu de départ, avec champ requis -->

                <label for="arrivee">Arrivée</label> <!-- Etiquette du champ arrivée -->
                <input type="text" id="arrivee" name="arrivee" required> <!-- Champ de saisie pour le lieu d'arrivée, avec champ requis -->

                <label for="date">Date</label> <!-- Etiquette du champ date -->
                <input type="date" id="date" name="date" required> <!-- Champ de saisie pour la date, avec champ requis -->

                <button type="submit">Rechercher</button> <!-- Bouton pour soumettre le formulaire -->
            </form>
        </section>
    </main>

    <footer>
        <?php include('../partials/footer.php'); ?> <!-- Inclusion de footer.php depuis partials/ -->
    </footer>
</body>
</html> <!-- Fin du document HTML -->
