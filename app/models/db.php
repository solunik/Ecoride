<?php
// Chargement des informations de connexion depuis le fichier config
$config = require __DIR__ . '/Covoiturage/app/config/config.php'; 
// Le fichier config.php se trouve dans le dossier config, un niveau au-dessus de ce fichier

try {
    // Crée la connexion avec PDO
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']}", // Spécifie l'hôte et la base de données à utiliser
        $config['user'], // Nom d'utilisateur pour la connexion
        $config['password'] // Mot de passe pour la connexion
    );
    
    // Configure le mode de gestion des erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // Cela configure PDO pour lancer des exceptions en cas d'erreurs, permettant ainsi une gestion des erreurs plus propre et facile

    echo "Connexion réussie à la base de données '{$config['dbname']}'!"; 
    // Si la connexion réussie, un message est affiché pour indiquer que la connexion à la base de données a été effectuée avec succès
} catch (PDOException $e) { 
    // Si une exception PDO est lancée (erreur de connexion), le code dans ce bloc sera exécuté
    // PDOException est une classe d'exception spécifique pour les erreurs liées à PDO
    
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard."); 
    // Arrête l'exécution du script et affiche un message générique, afin de ne pas exposer d'informations sensibles (comme les erreurs de connexion)
}
?>