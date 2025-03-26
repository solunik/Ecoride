<?php

require_once __DIR__ . '/../models/db.php';

class Model {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id'; // Par défaut, on suppose que la clé primaire s'appelle 'id'

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => $this->mapToObject($item), $data); // Map chaque ligne vers un objet
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->mapToObject($data); // Retourner un objet
    }

    // Mapper un tableau de données à un objet
    protected function mapToObject($data) {
        $className = ucfirst($this->table);
        $object = new $className(); // Crée un objet de la classe correspondant à la table
        foreach ($data as $key => $value) {
            $object->{$key} = $value; // Assigne les valeurs à l'objet
        }
        return $object;
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

    // Relation "Un à plusieurs" (hasMany)
    public function hasMany($relatedModel, $foreignKey) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$relatedModel} WHERE {$foreignKey} = :id");
        $stmt->execute(['id' => $this->{$this->primaryKey}]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => new $relatedModel($item), $data);
    }

    // Relation "Appartient à" (belongsTo)
    public function belongsTo($relatedModel, $foreignKey) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$relatedModel} WHERE id = :id");
        $stmt->execute(['id' => $this->{$foreignKey}]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return new $relatedModel($data);
    }

    // Relation "Plusieurs à plusieurs" (belongsToMany)
    public function belongsToMany($relatedModel, $pivotTable, $foreignKey, $relatedKey) {
        $stmt = $this->pdo->prepare("SELECT {$relatedModel}.* FROM {$relatedModel}
                                      JOIN {$pivotTable} ON {$pivotTable}.{$relatedKey} = {$relatedModel}.id
                                      WHERE {$pivotTable}.{$foreignKey} = :id");
        $stmt->execute(['id' => $this->{$this->primaryKey}]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($item) => new $relatedModel($item), $data);
    }
}

?>
