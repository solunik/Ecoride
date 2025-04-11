<?php


class UpdateUserController {

    public function updateUser() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // Si l'utilisateur n'est pas connecté, retourner une erreur
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté.'
            ]);
            exit;
        }

        // Récupère les données envoyées via POST
        $data = $_POST;

        // Crée une instance de la classe Utilisateur et met à jour les informations
        $utilisateur = new Utilisateur();
        $updateSuccess = $utilisateur->updateUser($_SESSION['user_id'], $data);

        // Vérifie si la mise à jour a réussi
        if ($updateSuccess) {
            echo json_encode([
                'success' => true,
                'message' => 'Informations mises à jour avec succès.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour.'
            ]);
        }
    }
}
