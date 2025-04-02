<button id="menu-toggle">☰ Menu</button>

<nav>
    <!-- Menu Desktop -->
    <ul class="menu" id="menu-desktop">
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <?php if (!empty($_SESSION['roles']) && is_array($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
                <li><a href="index.php?page=admin">Admin</a></li>
            <?php endif; ?>
            <li><a href="index.php?page=logout">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="index.php?page=connexion">Connexion</a></li>
        <?php endif; ?>
    </ul>

    <!-- Menu Mobile -->
    <ul class="menu" id="menu-mobile">
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <?php if (isset($_SESSION['utilisateur_id'])): ?>
            <?php if (!empty($_SESSION['roles']) && is_array($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
                <li><a href="index.php?page=admin">Admin</a></li>
            <?php endif; ?>
            <li><a href="index.php?page=logout">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="index.php?page=connexion">Connexion</a></li>
        <?php endif; ?>
        <li><a href="mentions_legales.php">Mentions légales</a></li>
    </ul>
</nav>

<?php if (isset($_SESSION['utilisateur_id'])): ?>
    <?php if (!empty($_SESSION['photo'])): ?>
        <img src="<?= 
            (file_exists($_SESSION['photo'])) 
                ? htmlspecialchars('/' . ltrim($_SESSION['photo'], '/'))
                : 'data:image/jpeg;base64,' . base64_encode($_SESSION['photo'])
        ?>" alt="Photo de profil" class="user-avatar">
    <?php else: ?>
        <img src="images/photo_defaut.webp" alt="Photo par défaut" class="user-avatar">
    <?php endif; ?>
<?php endif; ?>
