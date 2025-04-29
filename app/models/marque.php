<?php

require_once __DIR__ . '/model.php'; // Assure-toi que le chemin est correct

class Marque extends Model {
    protected $table = 'marque';
    protected $primaryKey = 'marque_id';

    public $marque_id;
    public $libelle;

    public function __construct($data = []) {
        parent::__construct($data);
    }

    // Tu n’as pas besoin de redéfinir les méthodes create, update, delete sauf si tu veux ajouter une logique spécifique
    // Mais si tu veux une méthode spécifique pour récupérer les voitures d'une marque par exemple, tu peux faire :

    public function getVoitures() {
        $stmt = $this->pdo->prepare("SELECT * FROM voiture WHERE marque_id = :id");
        $stmt->execute(['id' => $this->marque_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/voiture.php';
        return array_map(fn($row) => new Voiture($row), $data);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY libelle ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
