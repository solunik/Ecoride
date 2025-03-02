<nav>
    <ul class="menu">
        <li><a href="index.php">Accueil</a></li> <!-- Lien vers la page d'accueil dans le dossier public -->
        <li><a href="../app/views/recherche.php">Covoiturages</a></li> <!-- Lien vers la page des covoiturages -->
        <li><a href="../app/views/contact.php">Contact</a></li> <!-- Lien vers la page de contact -->
        <?php 
        if (isset($_SESSION['utilisateur_id'])): ?>
            <li><a href="/Covoiturage/app/controllers/deconnexion.php">Se déconnecter</a></li> <!-- Lien vers la déconnexion dans le dossier controllers -->
        <?php else: ?>
            <li><a href="/Covoiturage/app/views/connexion.php">Connexion</a></li> <!-- Lien vers la page de connexion dans le dossier views -->
        <?php endif; ?>
    </ul>
</nav>
