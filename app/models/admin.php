<?php
require_once __DIR__ . '/../models/model.php';

class Admin extends Model {
    protected $table = 'covoiturage';

    public function getDailyStats($days = 365) {
        $query = "
            SELECT 
                DATE(date_depart) as date,
                COUNT(*) as rides_count,
                SUM(prix_personne) as daily_credits
            FROM 
                {$this->table}
            WHERE 
                date_depart >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                AND statut = 'pending'
            GROUP BY 
                DATE(date_depart)
            ORDER BY 
                date_depart ASC
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCredits() {
        $query = "
            SELECT COALESCE(SUM(prix_personne), 0)
            FROM {$this->table}
            WHERE statut = 'pending'
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}