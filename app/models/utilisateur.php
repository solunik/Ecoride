<?php

require_once __DIR__ . '/../models/db.php';

class Utilisateur {
    private $pdo;

    // Propriétés correspondant aux colonnes de la table utilisateur
    public $utilisateur_id;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $telephone;
    public $adresse;
    public $date_naissance;
    public $photo;
    public $pseudo;
    public $credit;

    public function __construct() {
        // On récupère l'instance de la connexion via la classe Database
        $this->pdo = Database::getInstance();
    }

    // Inscription d'un utilisateur
    public function inscription() {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, credit) 
                                    VALUES (:nom, :prenom, :email, :password, :pseudo, :credit)");
        if ($stmt->execute([
            ':nom' => $this->nom,
            ':prenom' => $this->prenom,
            ':email' => $this->email,
            ':password' => password_hash($this->password, PASSWORD_BCRYPT),
            ':pseudo' => $this->pseudo,
            ':credit' => $this->credit
        ])) {
            return true;
        } else {
            throw new Exception("Une erreur est survenue lors de l'inscription.");
        }
    }

    // Récupérer un utilisateur par son ID
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->utilisateur_id = $result['utilisateur_id'];
            $this->nom = $result['nom'];
            $this->prenom = $result['prenom'];
            $this->email = $result['email'];
            $this->password = $result['password'];
            $this->telephone = $result['telephone'];
            $this->adresse = $result['adresse'];
            $this->date_naissance = $result['date_naissance'];
            $this->photo = $result['photo'];
            $this->pseudo = $result['pseudo'];
            $this->credit = $result['credit'];
        }

        return $result ? true : false;
    }

    // Récupérer un utilisateur par son email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par son pseudo
    public function findByPseudo($pseudo) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // Mettre à jour un utilisateur
    public function update() {
        $stmt = $this->pdo->prepare("UPDATE utilisateur 
                                     SET nom = :nom, prenom = :prenom, email = :email, password = :password, telephone = :telephone,
                                         adresse = :adresse, date_naissance = :date_naissance, photo = :photo, pseudo = :pseudo, credit = :credit
                                     WHERE utilisateur_id = :id");
        $stmt->execute([
            ':id' => $this->utilisateur_id,
            ':nom' => $this->nom,
            ':prenom' => $this->prenom,
            ':email' => $this->email,
            ':password' => password_hash($this->password, PASSWORD_BCRYPT),
            ':telephone' => $this->telephone,
            ':adresse' => $this->adresse,
            ':date_naissance' => $this->date_naissance,
            ':photo' => $this->photo,
            ':pseudo' => $this->pseudo,
            ':credit' => $this->credit
        ]);
    }

    // Supprimer un utilisateur
    public function delete() {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->execute([':id' => $this->utilisateur_id]);
    }

    // Authentifier un utilisateur par son email et mot de passe
    public function authenticate($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->utilisateur_id = $user['utilisateur_id'];
            $this->nom = $user['nom'];
            $this->prenom = $user['prenom'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->telephone = $user['telephone'];
            $this->adresse = $user['adresse'];
            $this->date_naissance = $user['date_naissance'];
            $this->photo = $user['photo'];
            $this->pseudo = $user['pseudo'];
            $this->credit = $user['credit'];
            return true;
        }
        
        return false;
    }
}
?>
