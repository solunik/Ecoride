<?php
require_once __DIR__ . '/../models/model.php';

class Utilisateur extends Model {
    protected $table = 'utilisateur'; // Définition de la table utilisée

    public function __construct() {
        parent::__construct(); // Appelle le constructeur du modèle parent
    }

    // Inscription d'un utilisateur
    public function inscription($nom, $prenom, $email, $password, $pseudo, $credit = 20) {
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'pseudo' => $pseudo,
            'credit' => $credit
        ];
        return parent::create($data);
    }

    // Récupérer un utilisateur par son email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par son pseudo
    public function findByPseudo($pseudo) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Authentifier un utilisateur par son email et mot de passe
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
?>