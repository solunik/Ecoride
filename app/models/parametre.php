<?php

class Parametre extends Model {
    protected $table = 'parametre';
    protected $primaryKey = 'parametre_id'; // Spécifier la clé primaire

    public $parametre_id;
    public $propriete;
    public $valeur;
    public $id_configuration;

    public function __construct($data = []) {
        parent::__construct($data); // On passe les données au constructeur du parent
    }
}

?>
