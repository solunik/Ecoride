<?php
class Auth {
    
    public static function login($postemail, $postpassword) {
        
        require_once __DIR__ . '/../models/db.php';
        $_SESSION['errorMessage'] = '';

        try {
            $conn = Database::getInstance();

            if (!empty($postemail) && !empty($postpassword)) {
                $email = trim($postemail);
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
            error_log("Erreur de connexion : " . $e->getMessage());
            $_SESSION['errorMessage'] = "Erreur de connexion, veuillez réessayer plus tard.";
        }

        header("Location: index.php?page=connexion");
        exit;
    }

    public static function logout() {
        session_unset();
        session_destroy();

        header("Location: index.php?page=accueil");
        exit;
    }
}
?>