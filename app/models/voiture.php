<?php

class Voiture extends Model {
    protected $table = 'voiture';
    protected $primaryKey = 'voiture_id';

    public $voiture_id;
    public $modele;
    public $immatriculation;
    public $energie;
    public $couleur;
    public $date_premiere_immatriculation;
    public $utilisateur_id;
    public $marque_id;
    
    public $marque_nom;
    
    public function __construct($data = []) {
        parent::__construct($data);
    } 

    // Méthode pour ajouter une nouvelle voiture dans la base de données
    public function addVehicule($data) {
        // Préparer les données pour l'insertion
        $sql = "INSERT INTO {$this->table} (modele, immatriculation, energie, couleur, date_premiere_immatriculation, utilisateur_id, marque_id)
                VALUES (:modele, :immatriculation, :energie, :couleur, :date_premiere_immatriculation, :utilisateur_id, :marque_id)";
        
        $stmt = $this->pdo->prepare($sql); // Remplacer $this->db par $this->pdo
        return $stmt->execute([
            ':modele' => $data['model'],
            ':immatriculation' => $data['plate'],
            ':energie' => $data['energy'],
            ':couleur' => $data['color'],
            ':date_premiere_immatriculation' => $data['first_registration'],
            ':utilisateur_id' => $data['user_id'],
            ':marque_id' => $data['brand_id'] ?? null // Optionnel, si vous avez une marque à lier
        ]);
    }

    // Méthode pour récupérer une voiture par son ID
    public function findById($voiture_id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :voiture_id";
        $stmt = $this->pdo->prepare($sql); // Remplacer $this->db par $this->pdo
        $stmt->execute([':voiture_id' => $voiture_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data) {
            return new self($data);
        }

        return null; // Si aucune voiture n'est trouvée
    }

    // Méthode pour récupérer toutes les voitures d'un utilisateur
    // Méthode pour récupérer toutes les voitures d'un utilisateur avec leur nom de marque
    public function getByUserId($utilisateur_id) {
        // Effectuer la jointure avec la table 'marque'
        $sql = "SELECT v.*, m.libelle AS marque_nom 
                FROM {$this->table} v
                LEFT JOIN marque m ON v.marque_id = m.marque_id
                WHERE v.utilisateur_id = :utilisateur_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($voitureData) {
            return new self($voitureData);
        }, $data);
    }
    


    // Méthode pour mettre à jour les informations d'une voiture
    public function updateVehicule($voiture_id, $data) {
        $sql = "UPDATE {$this->table} 
                SET modele = :modele, immatriculation = :immatriculation, energie = :energie, 
                    couleur = :couleur, date_premiere_immatriculation = :date_premiere_immatriculation, 
                    marque_id = :marque_id
                WHERE {$this->primaryKey} = :voiture_id";
        
        $stmt = $this->pdo->prepare($sql); // Remplacer $this->db par $this->pdo
        return $stmt->execute([
            ':modele' => $data['modele'],
            ':immatriculation' => $data['immatriculation'],
            ':energie' => $data['energie'],
            ':couleur' => $data['couleur'],
            ':date_premiere_immatriculation' => $data['date_premiere_immatriculation'],
            ':marque_id' => $data['marque_id'] ?? null, // Si la marque est optionnelle
            ':voiture_id' => $voiture_id
        ]);
    }

    // Méthode pour supprimer une voiture de la base de données
    public function deleteVehicule($voiture_id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :voiture_id";
        $stmt = $this->pdo->prepare($sql); // Remplacer $this->db par $this->pdo
        return $stmt->execute([':voiture_id' => $voiture_id]);
    }

}
?>
