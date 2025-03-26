<?php

class Parametre extends Model {
    protected $table = 'parametre';

    public $parametre_id;
    public $propriete;
    public $valeur;
    public $id_configuration;

    public function __construct($data = []) {
        parent::__construct();
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    
}