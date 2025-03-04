<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Vérifier si on est sur Heroku (JAWSDB_URL existe)
        $url = getenv('JAWSDB_URL');

        if ($url) {
            // On est sur Heroku, on parse l'URL de connexion
            $dbparts = parse_url($url);

            $host = $dbparts['host'];
            $dbname = ltrim($dbparts['path'], '/');
            $user = $dbparts['user'];
            $password = $dbparts['pass'];
        } else {
            // On est en local, on utilise le fichier config.php
            $config = require __DIR__ . '/../../config/config.php';

            $host = $config['host'];
            $dbname = $config['dbname'];
            $user = $config['user'];
            $password = $config['password'];
        }

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
