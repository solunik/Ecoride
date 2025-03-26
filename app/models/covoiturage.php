<?php

require_once __DIR__ . '/../models/model.php';

class Covoiturage extends Model {
    protected $table = 'covoiturage';

    public $covoiturage_id;
    public $date_depart;
    public $heure_depart;
    public $lieu_depart;
    public $date_arrive;
    public $heure_arrivee;
    public $lieu_arrivee;
    public $statut;
    public $nb_place;
    public $prix_personne;
    public $voiture_id;
    public $utilisateur_id;

    public function __construct($data = []) {
        parent::__construct();
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    //rechercher un covoiturage
    public function search($depart, $arrivee, $date) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    c.date_depart,
                    c.lieu_depart, 
                    c.lieu_arrivee, 
                    c.heure_depart,
                    c.heure_arrivee,
                    c.nb_place,
                    c.prix_personne,
                    u.photo,
                    u.pseudo, 
                    v.energie,
                    ROUND(AVG(a.note), 1) AS note
                FROM 
                    covoiturage c
                JOIN 
                    utilisateur u ON c.utilisateur_id = u.utilisateur_id
                JOIN 
                    voiture v ON c.voiture_id = v.voiture_id
                LEFT JOIN
                    avis a ON a.utilisateur_id = u.utilisateur_id
                WHERE 
                    c.lieu_depart = :depart 
                    AND 
                    c.lieu_arrivee = :arrivee 
                    AND 
                    c.date_depart = :date
                GROUP BY 
                    c.covoiturage_id, c.date_depart, c.lieu_depart, c.lieu_arrivee, 
                    c.heure_depart, c.heure_arrivee, c.nb_place, c.prix_personne, u.photo,
                    u.pseudo, v.energie
                ORDER BY 
                    c.heure_depart;
            ");

            $stmt->execute([
                ':depart' => htmlspecialchars($depart),
                ':arrivee' => htmlspecialchars($arrivee),
                ':date' => htmlspecialchars($date)
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => "Erreur de connexion : " . htmlspecialchars($e->getMessage())];
        }
    }
}