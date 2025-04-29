<button id="menu-toggle">☰ Menu</button>
<script>window.roleActif = "<?= $_SESSION['role_actif'] ?? 'utilisateur' ?>"; </script>
<script src="js/evenements.js" defer></script>

<nav>
<ul class="menu" id="menu-desktop">
    <?php if (isset($_SESSION['utilisateur_id'])): ?>
        <?php if (!empty($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
            <li><a href="index.php?page=admin">Admin</a></li>
        <?php endif; ?>

        <!-- ✅ Zone modifiable par JS -->
        <li id="menu-desktop-role-wrapper">
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <?php if (isset($_SESSION['role_actif']) && $_SESSION['role_actif'] === 'utilisateur'): ?>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <?php endif; ?>
        <li><a href="index.php?page=contact">Contact</a></li>

        <li><a href="index.php?page=logout">Déconnexion</a></li>
    <?php else: ?>
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <li><a href="index.php?page=connexion">Connexion</a></li>
    <?php endif; ?>
</ul>


<ul class="menu" id="menu-mobile">
    <?php if (isset($_SESSION['utilisateur_id'])): ?>
        <?php if (!empty($_SESSION['roles']) && in_array('Administrateur', $_SESSION['roles'])): ?>
            <li><a href="index.php?page=admin">Admin</a></li>
        <?php endif; ?>
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <?php if (isset($_SESSION['role_actif']) && $_SESSION['role_actif'] === 'utilisateur'): ?>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <?php endif; ?>
        <li><a href="index.php?page=contact">Contact</a></li>
        <li><a href="index.php?page=logout">Déconnexion</a></li>
        <li><a href="mentions_legales.php">Mentions légales</a></li>
    <?php else: ?>
        <li><a href="index.php?page=accueil">Accueil</a></li>
        <li><a href="index.php?page=recherche">Covoiturages</a></li>
        <li><a href="index.php?page=contact">Contact</a></li>
        <li><a href="index.php?page=connexion">Connexion</a></li>
        <li><a href="mentions_legales.php">Mentions légales</a></li>
    <?php endif; ?>
</ul>


</nav>

<?php if (isset($_SESSION['utilisateur_id'])): ?>
    <!-- Affichage du rôle actif à côté de la photo de profil -->
    <div class="user-info">
        <a href="index.php?page=espace_utilisateur" title="Changer de rôle">
            <?php if (!empty($_SESSION['photo'])): ?>
                
                <img src="<?= 
                    (file_exists($_SESSION['photo'])) 
                        ? htmlspecialchars('/' . ltrim($_SESSION['photo'], '/')) 
                        : 'data:image/jpeg;base64,' . base64_encode($_SESSION['photo'])
                ?>" alt="Photo de profil" class="user-avatar">
            <?php else: ?>
                <img src="images/photo_defaut.webp" alt="Photo par défaut" class="user-avatar">
            <?php endif; ?>
        </a>

  
    </div>
<?php endif; ?>


