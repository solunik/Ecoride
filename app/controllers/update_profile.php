<?php

require_once __DIR__ . '/../models/Utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur_id'])) {
        $_SESSION['profile_message'] = "Vous devez être connecté pour mettre à jour votre profil.";
        header('Location: index.php?page=connexion');
        exit;
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['utilisateur_id'];

    // Récupérer les données soumises par le formulaire
    $email = $_POST['email'];
    $pseudo = $_POST['pseudo'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_new_password'];
    
    // Récupérer la photo de profil téléchargée
    $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;

    // Validation des données
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['profile_message'] = "L'email n'est pas valide.";
        header('Location: index.php?page=espace_utilisateur');
        exit;
    }

    // Vérification que les mots de passe correspondent
    if ($newPassword && $newPassword !== $confirmPassword) {
        $_SESSION['profile_message'] = "Les mots de passe ne correspondent pas.";
        header('Location: index.php?page=espace_utilisateur');
        exit;
    }

    // Récupérer l'utilisateur actuel depuis la base de données
    $utilisateur = new Utilisateur();
    $user = $utilisateur->findById($userId);

    // Traitement de la photo
    $photoPath = $user->photo; // Utiliser la photo actuelle par défaut
    if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
        // Vérifier si le fichier est une image valide
        $validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($photo['type'], $validImageTypes)) {
            $_SESSION['profile_message'] = "Le fichier téléchargé n'est pas une image valide.";
            header('Location: index.php?page=espace_utilisateur');
            exit;
        }

        // Créer un nom unique pour la photo afin d'éviter les conflits
        $photoPath = 'uploads/' . uniqid('photo_') . '-' . basename($photo['name']);
        
        // Déplacer l'image téléchargée dans le dossier 'uploads'
        if (!move_uploaded_file($photo['tmp_name'], __DIR__ . '/../' . $photoPath)) {
            $_SESSION['profile_message'] = "Échec du téléchargement de la photo.";
            header('Location: index.php?page=espace_utilisateur');
            exit;
        }
    }

    // Préparer les données à mettre à jour
    $updateData = [
        'email' => $email,
        'pseudo' => $pseudo,
        'prenom' => $prenom,
        'nom' => $nom,
        'photo' => $photoPath,
        'adresse' =>$adresse,
        'telephone' =>$telephone
    ];

    // Si un nouveau mot de passe est fourni, ajouter le mot de passe mis à jour
    if ($newPassword) {
        $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $updateSuccess = $utilisateur->updateUser($userId, $updateData);

    if ($updateSuccess) {
        // Mettre à jour les informations dans la session
        $_SESSION['email'] = $email;
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['nom'] = $nom;
        $_SESSION['photo'] = $photoPath;
        $_SESSION['adresse'] = $adresse;
        $_SESSION['telephone'] = $telephone;

        $_SESSION['profile_message'] = "Profil mis à jour avec succès!";
    } else {
        $_SESSION['profile_message'] = "Échec de la mise à jour du profil.";
    }

    // Rediriger l'utilisateur vers la page de son profil
    header('Location: index.php?page=espace_utilisateur');
    exit;
} 
?>
