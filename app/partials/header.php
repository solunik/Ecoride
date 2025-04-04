<button id="menu-toggle">☰ Menu</button>

<nav>
    <ul class="menu" id="menu-desktop">
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <?php if (!empty($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
                <li><a href="index.php?page=admin">Admin</a></li>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="index.php?page=accueil">Accueil</a></li>
                <li><a href="index.php?page=recherche">Covoiturages</a></li>
                <li><a href="index.php?page=espace_utilisateur">Espace Utilisateur</a></li>
                <li><a href="index.php?page=contact">Contact</a></li>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li><a href="index.php?page=accueil">Accueil</a></li>
            <li><a href="index.php?page=recherche">Covoiturages</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <li><a href="index.php?page=connexion">Connexion</a></li>
        <?php endif; ?>
    </ul>

    <!-- Menu Mobile -->
    <ul class="menu" id="menu-mobile">
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <?php if (!empty($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
                <li><a href="index.php?page=admin">Admin</a></li>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="index.php?page=accueil">Accueil</a></li>
                <li><a href="index.php?page=recherche">Covoiturages</a></li>
                <li><a href="index.php?page=contact">Contact</a></li>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
                <li><a href="mentions_legales.php">Mentions légales</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li><a href="index.php?page=accueil">Accueil</a></li>
            <li><a href="index.php?page=recherche">Covoiturages</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <li><a href="index.php?page=connexion">Connexion</a></li>
            <li><a href="mentions_legales.php">Mentions légales</a></li>
        <?php endif; ?>
        
    </ul>
</nav>

<?php if (isset($_SESSION['utilisateur_id']) && (!isset($_SESSION['roles']) || !in_array('Administrateur', $_SESSION['roles']))): ?>
    <?php if (!empty($_SESSION['photo'])): ?>
        <img src="<?= 
            // Si c'est un chemin de fichier
            (file_exists($_SESSION['photo'])) 
                ? htmlspecialchars('/' . ltrim($_SESSION['photo'], '/'))
                // Sinon on suppose que c'est du base64
                : 'data:image/jpeg;base64,' . base64_encode($_SESSION['photo'])
        ?>" alt="Photo de profil" class="user-avatar">
    <?php else: ?>
        <!-- Si connecté mais sans photo -->
        <img src="images/photo_defaut.webp" alt="Photo par défaut" class="user-avatar">
    <?php endif; ?>
<?php endif; ?>

