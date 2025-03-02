<nav>
    <ul class="menu">
        <li><a href="accueil.php">Accueil</a></li> <!-- Lien vers la page d'accueil dans le dossier public -->
        <li><a href="recherche.php">Covoiturages</a></li> <!-- Lien vers la page des covoiturages -->
        <li><a href="contact.php">Contact</a></li> <!-- Lien vers la page de contact -->
        <?php 
        if (isset($_SESSION['utilisateur_id'])): ?>
            <li><a href="../app/controllers/deconnexion.php">Se déconnecter</a></li> <!-- Lien vers la déconnexion dans le dossier controllers -->
        <?php else: ?>
            <li><a href="connexion.php">Connexion</a></li> <!-- Lien vers la page de connexion dans le dossier views -->
        <?php endif; ?>
    </ul>
</nav>
