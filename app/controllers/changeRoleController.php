<?php
class ChangeRoleController {

    

    public static function changeRole() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $nouveau_role = strtolower($input['role'] ?? '');


        if ($nouveau_role === 'utilisateur' || $nouveau_role === 'chauffeur') {
            $_SESSION['role_actif'] = $nouveau_role;
            echo json_encode([
                'success' => true, 
                'message' => "Rôle actif changé en $nouveau_role",
                'role_actif' => $nouveau_role
            ]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'error' => 'Rôle invalide.'
            ]);
            exit;
        }
    }
}
?>
