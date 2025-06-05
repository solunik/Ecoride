<?php

class UpdateUserController {

    public function updateUser() {
        if (!isset($_SESSION['utilisateur_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non connecté.'
            ]);
            exit;
        }

        $data = $_POST;
        $userId = $_SESSION['utilisateur_id'];
        $utilisateur = new Utilisateur();

        // Validation email si présent
        if (!empty($data['email'])) {
            $email = trim(strtolower($data['email']));

            // Vérifier la longueur max
            if (strlen($email) > 50) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email trop long (max 50 caractères).'
                ]);
                exit;
            }

            // Validation de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email invalide.'
                ]);
                exit;
            }

            // Vérifier si l'email est déjà utilisé par un autre utilisateur
            $existingUser = $utilisateur->findByEmail($email);
            if ($existingUser && $existingUser->utilisateur_id != $userId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Cet email est déjà utilisé par un autre utilisateur.'
                ]);
                exit;
            }

            $data['email'] = $email;
        }

        // Validation téléphone si présent
        if (!empty($data['telephone'])) {
            $telephone = trim($data['telephone']);

            // Limite la longueur max
            if (strlen($telephone) > 15) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Numéro de téléphone trop long (max 15 caractères).'
                ]);
                exit;
            }

            // Vérifie que le téléphone contient uniquement chiffres, espaces, +, (), -
            if (!preg_match('/^[0-9+\-\s\(\)]+$/', $telephone)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Numéro de téléphone invalide (caractères autorisés : chiffres, +, -, espaces, parenthèses).'
                ]);
                exit;
            }

            $data['telephone'] = $telephone;
        }

        // Validation adresse si présente
        if (!empty($data['adresse'])) {
            $adresse = trim($data['adresse']);
            // Limite la taille max de l'adresse à 255 caractères
            if (strlen($adresse) > 255) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Adresse trop longue (max 255 caractères).'
                ]);
                exit;
            }
            $data['adresse'] = $adresse;
        }

        // Gestion de la photo de profil
        if (!empty($_FILES['photo']['tmp_name'])) {
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoSize = $_FILES['photo']['size'];
            $photoMime = mime_content_type($photoTmpPath);

            if ($photoSize > 65536) {
                echo json_encode([
                    'success' => false,
                    'message' => 'L\'image est trop volumineuse (max 64 Kio).'
                ]);
                exit;
            }

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($photoMime, $allowedMimeTypes)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Format d\'image non autorisé (JPEG, PNG ou WEBP uniquement).'
                ]);
                exit;
            }

            $photoBlob = file_get_contents($photoTmpPath);
            $data['photo'] = $photoBlob;
        }

        $updateSuccess = $utilisateur->updateUser($userId, $data);

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
