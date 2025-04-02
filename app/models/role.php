<?php
require_once __DIR__ . '/../models/model.php';

class Role extends Model {
    protected $table = 'role';
    protected $primaryKey = 'role_id';

    public $role_id;
    public $libelle;

    public function __construct($data = []) {
        parent::__construct($data);
    }

    public function assignRoleByLibelle($utilisateur_id, $libelle) {
        $db = Database::getInstance();
        
        $role = $this->findBy('libelle', $libelle);
        if (!$role) {
            throw new Exception("Le rôle '$libelle' n'existe pas");
        }

        $existing = $db->prepare("SELECT 1 FROM utilisateur_role WHERE utilisateur_id = ? AND role_id = ?");
        $existing->execute([$utilisateur_id, $role->role_id]);
        
        if ($existing->fetch()) {
            return true;
        }

        $stmt = $db->prepare("INSERT INTO utilisateur_role (utilisateur_id, role_id) VALUES (?, ?)");
        return $stmt->execute([$utilisateur_id, $role->role_id]);
    }

    public static function getRolesByUserId($utilisateur_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT r.* FROM role r JOIN utilisateur_role ur ON r.role_id = ur.role_id WHERE ur.utilisateur_id = ?");
        $stmt->execute([$utilisateur_id]);
        
        return array_map(function($item) {
            return new Role($item);
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function getAllRoles() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM role");
        
        return array_map(function($item) {
            return new Role($item);
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
?>