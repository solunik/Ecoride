<?php

require_once __DIR__ . '/../models/db.php';

class Model {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id'; // Par défaut, on suppose que la clé primaire s'appelle 'id'

    public function __construct($data = []) {
        $this->pdo = Database::getInstance();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value; // Assigne chaque valeur à la propriété correspondante
            }
        }
    }

     // Mapper un tableau de données à un objet
     protected function mapToObject($data) {
        $className = static::class; // Instancie la classe appelante
        $object = new $className();
        foreach ($data as $key => $value) {
            $object->{$key} = $value; // Assigne les valeurs à l'objet
        }
        return $object;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => $this->mapToObject($item), $data); // Map chaque ligne vers un objet
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToObject($data) : null;
    }

    public function findBy($column, $value) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToObject($data) : null;
    }    

    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(fn($col) => ":$col", array_keys($data)));
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $setClause = implode(", ", array_map(fn($col) => "$col = :$col", array_keys($data)));
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $setClause WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

?>
