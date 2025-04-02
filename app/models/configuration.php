<?php

class Configuration extends Model {
    protected $table = 'configuration';
    protected $primaryKey = 'id_configuration';

    public $id_configuration;

    public function __construct($data = []) {
        parent::__construct($data);
    }
}