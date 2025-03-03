<?php
function accueilPage() {
    require __DIR__ . '/../views/accueil.php';  // Inclut la vue de la page d'accueil
}

function connexionPage() {
    require __DIR__ . '/../views/connexion.php';  // Inclut la vue de la page de connexion
}

function contactPage() {
    require __DIR__ . '/../views/contact.php';  // Inclut la vue de la page de contact
}


function inscriptionPage() {
    require __DIR__ . '/../views/inscription.php';  // Inclut la vue d'inscription
}

function recherchePage() {
    require __DIR__ . '/../views/recherche.php';  // Inclut la vue de recherche
}

function login($postemail,$postpassword) {
    require_once __DIR__ . '/../models/db.php';
$_SESSION['errorMessage'] = '';
try {
    $conn = Database::getInstance();
    if (!empty($postemail) && !empty($postpassword)) {
        $email = htmlspecialchars($postemail);
        $password = $postpassword;
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
            $_SESSION['prenom'] = $utilisateur['prenom'];
            $_SESSION['nom'] = $utilisateur['nom'];
            header("Location: index.php?page=accueil");
            exit;
        } else {
            $_SESSION['errorMessage'] = "Mot de passe ou email incorrect";
        }
    } else {
        $_SESSION['errorMessage'] = "Veuillez remplir tous les champs.";
    }
} catch (PDOException $e) {
    $_SESSION['errorMessage'] = "Erreur de connexion : " . $e->getMessage();
}
header("Location: index.php?page=connexion");
}
?>
