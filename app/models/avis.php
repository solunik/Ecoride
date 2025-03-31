<?php

class Avis extends Model {
    protected $table = 'avis';

    public $avis_id;
    public $commentaire;
    public $note;
    public $statut;
    public $utilisateur_id;

    public function __construct($data = []) {
        parent::__construct();
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    
}