<?php

// Vérifie si on est en local (le fichier .env existe)
if (file_exists(__DIR__ . '/../../.env')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Vérifier si on est sur Heroku (JAWSDB_URL existe)
        $url = getenv('JAWSDB_URL');

        if ($url) {
            // On est sur Heroku
            $dbparts = parse_url($url);
            $host = $dbparts['host'];
            $dbname = ltrim($dbparts['path'], '/');
            $user = $dbparts['user'];
            $password = $dbparts['pass'];
        } else {
            // On est en local (via .env)
            $host = $_ENV["DB_HOST"];
            $dbname = $_ENV["DB_NAME"];
            $user = $_ENV["DB_USER"];
            $password = $_ENV["DB_PASSWORD"];
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
