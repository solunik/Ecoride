<?php

require_once __DIR__ . '/../models/admin.php';

class Stats {
    private $model;

    public function __construct() {
        $this->model = new Admin();
    }

    public function showDashboard() {
        include __DIR__ . '/../views/admin.php';
    }

    public function getStatsData() {

        header('Content-Type: application/json');

        try {
            $stats = $this->model->getDailyStats();
            $totalCredits = $this->model->getTotalCredits();

            echo json_encode([
                'success' => true,
                'dates' => array_column($stats, 'date'),
                'rides' => array_column($stats, 'rides_count'),
                'credits' => array_column($stats, 'daily_credits'),
                'totalCredits' => $totalCredits
            ]);
            
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur de base de données'
            ]);
        }
        exit;
    }
}
