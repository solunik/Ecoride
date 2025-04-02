<?php

require_once __DIR__ . '/../models/model.php';

class Utilisateur extends Model {
    protected $table = 'utilisateur';
    protected $primaryKey = 'utilisateur_id';

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
    public $id_configuration;
    public $suspended;

    public function __construct($data = []) {
        parent::__construct($data);
    }

    // Inscription d'un utilisateur
    public function inscription($nom, $prenom, $email, $password, $pseudo) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $db = Database::getInstance();
        
        $stmt = $db->prepare("INSERT INTO {$this->table} (nom, prenom, email, password, pseudo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $hashedPassword, $pseudo]);
        
        return $db->lastInsertId();
    }

    public function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Récupérer un utilisateur par son email
    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    // Récupérer un utilisateur par son pseudo
    public function findByPseudo($pseudo) {
        return $this->findBy('pseudo', $pseudo);
    }

    // Authentifier un utilisateur par son email et mot de passe
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function getRoles() {
        return Role::getRolesByUserId($this->utilisateur_id);
    }
}

?>
