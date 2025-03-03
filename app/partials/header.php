<nav>
    <ul class="menu">
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <?php 
        if (isset($_SESSION['utilisateur_id'])): ?>
            <li><a href="index.php?page=deconnexion">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="index.php?page=connexion">Connexion</a></li>
        <?php endif; ?>
    </ul>
</nav>
