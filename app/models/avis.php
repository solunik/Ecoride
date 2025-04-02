<?php

class Avis extends Model {
    protected $table = 'avis';
    protected $primaryKey = 'avis_id';

    public $avis_id;
    public $commentaire;
    public $note;
    public $statut;
    public $utilisateur_id;

    public function __construct($data = []) {
        parent::__construct($data);
    }

    
}