<?php
session_start();
require_once __DIR__ . '/../models/db.php';
$_SESSION['errorMessage'] = '';
try {
    $conn = Database::getInstance();
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
            $_SESSION['prenom'] = $utilisateur['prenom'];
            $_SESSION['nom'] = $utilisateur['nom'];
            header("Location: ../../public/index.php?page=accueil");
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
header("Location: ../../public/index.php?page=connexion");
exit;