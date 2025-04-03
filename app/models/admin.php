<?php
require_once __DIR__ . '/../models/model.php';

class Admin extends Model {
    protected $table = 'covoiturage';

    public function getDailyStats($days = 365) {
        $query = "
            SELECT 
                DATE(date_depart) as date,
                COUNT(*) as rides_count,
                COUNT(*) * 2 as daily_credits
            FROM 
                {$this->table}
            WHERE 
                date_depart >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                AND statut = 'completed'
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
        // Récupérer l'ID de l'utilisateur avec le rôle 'Administrateur'
        $queryAdmin = "
            SELECT u.utilisateur_id 
            FROM utilisateur u
            INNER JOIN utilisateur_role ur ON u.utilisateur_id = ur.utilisateur_id
            INNER JOIN role r ON ur.role_id = r.role_id
            WHERE r.libelle = 'Administrateur'
            LIMIT 1
        ";
    
        $stmtAdmin = $this->pdo->prepare($queryAdmin);
        $stmtAdmin->execute();
        $adminId = $stmtAdmin->fetchColumn();
    
        if (!$adminId) {
            return 0; // Aucun administrateur trouvé
        }
    
        // Récupérer les crédits de l'utilisateur administrateur
        $queryCredits = "
            SELECT credit 
            FROM utilisateur 
            WHERE utilisateur_id = :adminId
        ";
    
        $stmtCredits = $this->pdo->prepare($queryCredits);
        $stmtCredits->execute(['adminId' => $adminId]);
    
        // Retourner les crédits de l'administrateur
        return $stmtCredits->fetchColumn();
    }
    
}