<?php

require_once __DIR__ . '/../models/model.php';

class Covoiturage extends Model {
    protected $table = 'covoiturage';
    protected $primaryKey = 'covoiturage_id';

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
        parent::__construct($data);
    }

    //rechercher un covoiturage
    public function search($depart, $arrivee, $date) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    c.covoiturage_id AS id_covoiturage,
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

    public function getDetails($id) {
        try {
            // Requête principale pour les infos du trajet
            $stmt = $this->pdo->prepare("
                SELECT 
                    c.*,
                    u.pseudo,
                    u.photo,
                    v.modele,
                    v.energie,
                    m.libelle AS libelle_marque,
                    a.commentaire,
                    ROUND(AVG(a.note), 1) AS note_moyenne
                FROM covoiturage c
                JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
                JOIN voiture v ON c.voiture_id = v.voiture_id
                JOIN marque m ON v.marque_id = m.marque_id
                LEFT JOIN avis a ON a.utilisateur_id = u.utilisateur_id
                WHERE c.covoiturage_id = ?
                GROUP BY c.covoiturage_id
            ");
            $stmt->execute([$id]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$details) return null;
    
            // Requête supplémentaire pour les avis
            $stmtAvis = $this->pdo->prepare("
                SELECT a.commentaire, a.note, a.statut
                FROM avis a
                WHERE a.utilisateur_id = ?
                AND a.statut = 'actif'
            ");
            $stmtAvis->execute([$details['utilisateur_id']]);
            $details['avis'] = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);
    
            return $details;
    
        } catch (PDOException $e) {
            error_log("Erreur SQL getDetails: " . $e->getMessage());
            return null;
        }
    }

}