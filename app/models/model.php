<?php

require_once __DIR__ . '/../models/db.php';

class Model {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id'; // Clé primaire par défaut

    public function __construct($data = []) {
        $this->pdo = Database::getInstance();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    // Mapper un tableau de données à un objet
    protected function mapToObject($data) {
        $className = static::class;
        $object = new $className();
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }

    // Récupérer tous les enregistrements de la table
    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => $this->mapToObject($item), $data);
    }

    // Récupérer un enregistrement par son ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToObject($data) : null;
    }

    // Récupérer un enregistrement par une colonne spécifique
    public function findBy($column, $value) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToObject($data) : null;
    }

    // Récupérer tous les enregistrements correspondant à une colonne spécifique
    public function findAllBy($column, $value) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => $this->mapToObject($item), $data);
    }

    // Insérer un nouvel enregistrement
    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($col) => ":$col", array_keys($data)));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        
        if ($stmt->execute($data)) {
            $lastId = $this->pdo->lastInsertId();
            return $this->getById($lastId); // Retourne l'objet créé
        }
        return false;
    }

    // Mettre à jour un enregistrement
    public function update($id, $data) {
        $setClause = implode(", ", array_map(fn($col) => "$col = :$col", array_keys($data)));
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :id");
        $data['id'] = $id;
        
        return $stmt->execute($data);
    }

    // Supprimer un enregistrement
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
