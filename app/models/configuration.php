<?php

class Configuration extends Model {
    protected $table = 'configuration';

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