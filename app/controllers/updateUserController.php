<?php


class UpdateUserController {

    public function updateUser() {
        // Vérifie si l'utilisateur est connecté
        //var_dump($_POST);
        if (!isset($_SESSION['utilisateur_id'])) {
            // Si l'utilisateur n'est pas connecté, retourner une erreur
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté.'
            ]);
            exit;
        }

        // Récupère les données envoyées via POST
        $data = $_POST;

        // Gestion de la photo de profil
        if (!empty($_FILES['photo']['name'])) {
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoName = basename($_FILES['photo']['name']);
            $targetDir = __DIR__ . '/../../public/uploads/';
            $targetPath = $targetDir . $photoName;

            // Créer le dossier si besoin
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Déplacer le fichier
            if (move_uploaded_file($photoTmpPath, $targetPath)) {
                // On stocke le nom dans la BDD
                $data['photo'] = 'uploads/' . $photoName;
            }
        }

        $userId = $_SESSION['utilisateur_id'];

        // Crée une instance de la classe Utilisateur et met à jour les informations
        $utilisateur = new Utilisateur();
        $updateSuccess = $utilisateur->updateUser($userId, $data);

        // Vérifie si la mise à jour a réussi
        if ($updateSuccess) {
            echo json_encode([
                'success' => true,
                'message' => 'Informations mises à jour avec succès.'
            ]);
            $updatedUser = $utilisateur->findById($userId);
            $_SESSION['email'] = $updatedUser->email;
            $_SESSION['adresse'] = $updatedUser->adresse;
            $_SESSION['telephone'] = $updatedUser->telephone;
            $_SESSION['photo'] = $updatedUser->photo;
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour.'
            ]);
        }
    }
}

?>
