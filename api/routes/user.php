<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/user.php';  
require_once __DIR__ . '/../session/session.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$action = $request[count($request) - 1];

if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(["error" => "Utilisateur non authentifié"]);
    exit;
}

switch ($method) {
    case 'GET':
        requirePermission(1);
        if ($action === 'users') {
            echo json_encode(User_model::getAllUsersWithoutPassword());
        } elseif (is_numeric($action)) {
            echo json_encode(User_model::getUserByIdWithoutPassword($action));
        } elseif (isset($_GET['prenom'])) {
            echo json_encode(User_model::getUserByPrenom($_GET['prenom']));
        } elseif (isset($_GET['nom'])) {
            echo json_encode(User_model::getUserByNom($_GET['nom']));
        } elseif (isset($_GET['email'])) {
            echo json_encode(User_model::getUserByEmail($_GET['email']));
        } elseif (isset($_GET['tel'])) {
            echo json_encode(User_model::getUserByTel($_GET['tel']));
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1);
        
        $data = json_decode(file_get_contents("php://input"), true);
        if ($_SESSION['permission'] < 2 && $data['permission'] > 0) {
            http_response_code(403);
            echo json_encode(["error" => "Permission insuffisante"]);
            exit;
        }
        echo json_encode(User_model::createUser(
            $data['prenom'], $data['nom'], $data['email'],
            $data['mdp'], $data['tel'], $data['date_naissance'],
            $data['permission'], $data['id_promotion']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID utilisateur invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_user = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['prenom'])) {
            $updatedFields['prenom'] = User_model::updatePrenomUser($id_user, $data['prenom']);
        }
        if (isset($data['nom'])) {
            $updatedFields['nom'] = User_model::updateNomUser($id_user, $data['nom']);
        }
        if (isset($data['email'])) {
            $updatedFields['email'] = User_model::updateEmailUser($id_user, $data['email']);
        }
        if (isset($data['mdp'])) {
            $updatedFields['mdp'] = User_model::updatePasswordUser($id_user, $data['mdp']);
        }
        if (isset($data['tel'])) {
            $updatedFields['tel'] = User_model::updateTelUser($id_user, $data['tel']);
        }
        if (isset($data['date_naissance'])) {
            $updatedFields['date_naissance'] = User_model::updateDateNaissanceUser($id_user, $data['date_naissance']);
        }
        if (isset($data['permission'])) {
            $updatedFields['permission'] = User_model::updatePermissionUser($id_user, $data['permission']);
        }
        if (isset($data['id_promotion'])) {
            $updatedFields['id_promotion'] = User_model::updateIdPromotionUser($id_user, $data['id_promotion']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        
        if (is_numeric($action)) {
            echo json_encode(["success" => User_model::deleteUser($action)]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
        break;
}
