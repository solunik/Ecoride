<?php

class Voiture extends Model {
    protected $table = 'voiture';

    public $voiture_id;
    public $modele;
    public $immatriculation;
    public $energie;
    public $couleur;
    public $date_premiere_immatriculation;
    public $utilisateur_id;
    public $marque_id;

    public function __construct($data = []) {
        parent::__construct();
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    
}