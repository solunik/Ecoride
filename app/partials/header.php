  <button id="menu-toggle">☰ Menu</button>
    <nav>
        <ul class="menu" id="menu-desktop">
            <li><a href="index.php?page=accueil">Accueil</a></li>
            <li><a href="index.php?page=recherche">Covoiturages</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <li><a href="index.php?page=logout">Se déconnecter</a></li>
            <?php else: ?>
                <li><a href="index.php?page=connexion">Connexion</a></li>
            <?php endif; ?>
        </ul>

        <!-- Menu burger (Mobile) -->
        <ul id="menu-mobile">
            <li><a href="index.php?page=accueil">Accueil</a></li>
            <li><a href="index.php?page=recherche">Covoiturages</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <?php if (isset($_SESSION['utilisateur_id'])): ?>
                <li><a href="index.php?page=logout">Se déconnecter</a></li>
            <?php else: ?>
                <li><a href="index.php?page=connexion">Connexion</a></li>
            <?php endif; ?>
            <!-- Mentions légales ajouté uniquement dans le menu mobile -->
            <li><a href="mentions_legales.php">Mentions légales</a></li>
        </ul>
    </nav>
