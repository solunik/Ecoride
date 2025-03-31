<?php

require_once __DIR__ . '/../models/model.php';

class Utilisateur extends Model {
    protected $table = 'utilisateur';

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

    public function __construct($data = []) {
        parent::__construct();
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
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
        return parent::create($data);  // Utilisation de la méthode create() du modèle parent
    }

    // Récupérer un utilisateur par son email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return new Utilisateur($user);  // Retourner un objet Utilisateur si l'email existe
        }
        return null;  // Retourner null si l'email n'est pas trouvé
    }

    // Récupérer un utilisateur par son pseudo
    public function findByPseudo($pseudo) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return new Utilisateur($user);  // Retourner un objet Utilisateur si le pseudo existe
        }
        return null;  // Retourner null si le pseudo n'est pas trouvé
    }

    // Authentifier un utilisateur par son email et mot de passe
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // Récupérer les posts d'un utilisateur (relation "Un à plusieurs")
    public function getPosts() {
        return $this->hasMany('Post', 'utilisateur_id');
    }

    // Récupérer les rôles d'un utilisateur (relation "Plusieurs à plusieurs")
    public function getRoles() {
        return $this->belongsToMany('Role', 'utilisateur_role', 'utilisateur_id', 'role_id');
    }
}


?>
