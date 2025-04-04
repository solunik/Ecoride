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

     // Ajouter un employé sans pseudo et avec un crédit de 0
     public function addEmployee($nom, $prenom, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer l'employé avec un crédit de 0 et sans pseudo
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (nom, prenom, email, password, credit) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$nom, $prenom, $email, $hashedPassword]);

        return $this->pdo->lastInsertId();
    }

    public function getAllForAdmin() {
        $query = "SELECT u.utilisateur_id, u.nom, u.prenom, u.email, 
                         u.suspended, GROUP_CONCAT(r.libelle) as roles
                  FROM {$this->table} u
                  LEFT JOIN utilisateur_role ur ON u.utilisateur_id = ur.utilisateur_id
                  LEFT JOIN role r ON ur.role_id = r.role_id
                  GROUP BY u.utilisateur_id
                  HAVING 
                      FIND_IN_SET('employe', roles) > 0 
                      OR FIND_IN_SET('utilisateur', roles) > 0";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function toggleSuspension($userId) {
        // Inverse l'état actuel
        $query = "UPDATE {$this->table} 
                  SET suspended = NOT suspended 
                  WHERE utilisateur_id = :userId";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);
        
        // Retourne le nouvel état
        return $this->isSuspended($userId);
    }

    public function isSuspended($userId) {
        $query = "SELECT suspended FROM {$this->table} 
                  WHERE utilisateur_id = :userId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['userId' => $userId]);
        return (bool)$stmt->fetchColumn();
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
