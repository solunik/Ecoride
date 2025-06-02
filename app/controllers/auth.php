<?php

require_once __DIR__ . '/../models/utilisateur.php';
require_once __DIR__ . '/../models/role.php';

class Auth {
    // Méthode pour se connecter
    public static function login($postemail, $postpassword) {
    $_SESSION['errorMessage'] = '';

    // Nettoyage / validation simple
    $email = filter_var(trim(strtolower($postemail)), FILTER_VALIDATE_EMAIL);
    $password = trim($postpassword);

    if (!$email || empty($password)) {
        $_SESSION['errorMessage'] = "Email ou mot de passe invalide.";
        header("Location: index.php?page=connexion");
        exit;
    }

    // Initialiser les variables de tentative et temps si inexistants
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['first_attempt_time'] = time(); // timestamp du premier échec
    }

    // Durée du blocage en secondes (ici 10 minutes)
    $blockDelay = 2 * 60; 

    // Vérifier si on est en période de blocage (trop de tentatives + délai non écoulé)
    if ($_SESSION['login_attempts'] >= 5) {
        $elapsed = time() - $_SESSION['first_attempt_time'];

        if ($elapsed < $blockDelay) {
            $remaining = ceil(($blockDelay - $elapsed) / 60); // minutes restantes
            $_SESSION['errorMessage'] = "Trop de tentatives. Veuillez patienter $remaining minute(s) avant de réessayer.";
            header("Location: index.php?page=connexion");
            exit;
        } else {
            // Délai écoulé, on réinitialise
            $_SESSION['login_attempts'] = 0;
            $_SESSION['first_attempt_time'] = time();
        }
    }

    try {
        $utilisateur = new Utilisateur();
        $user = $utilisateur->authenticate($email, $password);

        if ($user) {
            // Succès : régénérer l'ID de session pour sécurité
            session_regenerate_id(true);

            // Stocker les infos utilisateur en session
            $_SESSION['utilisateur_id'] = $user->utilisateur_id;
            $_SESSION['prenom'] = $user->prenom;
            $_SESSION['nom'] = $user->nom;
            $_SESSION['email'] = $user->email;
            $_SESSION['adresse'] = $user->adresse;
            $_SESSION['telephone'] = $user->telephone;
            $_SESSION['pseudo'] = $user->pseudo;
            $_SESSION['credit'] = $user->credit;
            $_SESSION['photo'] = $user->photo;
            $_SESSION['suspended'] = $user->suspended;

            $roles = $user->getRoles();
            $_SESSION['roles'] = array_column($roles, 'libelle');
            $_SESSION['role_actif'] = 'utilisateur';

            // Réinitialiser les tentatives après succès
            $_SESSION['login_attempts'] = 0;
            $_SESSION['first_attempt_time'] = time();

            // Redirections selon rôle
            if (in_array('Administrateur', $_SESSION['roles'])) {
                header("Location: index.php?page=admin");
            } else {
                header("Location: index.php?page=accueil");
            }
            exit;
        } else {
            // Échec : augmenter compteur et afficher message erreur
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] == 1) {
                $_SESSION['first_attempt_time'] = time(); // date du premier échec
            }
            $_SESSION['errorMessage'] = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        error_log("Erreur de connexion : " . $e->getMessage());
        $_SESSION['errorMessage'] = "Erreur de connexion, veuillez réessayer plus tard.";
    }

    header("Location: index.php?page=connexion");
    exit;
}

    // Méthode pour se déconnecter
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();

        header("Location: index.php?page=accueil");
        exit;
    }
}

?>