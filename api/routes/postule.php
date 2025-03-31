<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/postule.php';  
require_once __DIR__ . '/../middleware/session.php';

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
        if ($action === 'postules') {
            echo json_encode(Postule_model::getAllPostules());
        } elseif (is_numeric($action)) {
            echo json_encode(Postule_model::getPostuleByIdUser($action));
        } elseif (isset($_GET['id_offre'])) {
            echo json_encode(Postule_model::getPostuleByIdOffre($_GET['id_offre']));
        } elseif (isset($_GET['id_offre'], $_GET['id_user'])) {
            echo json_encode(Postule_model::getPostuleById($_GET['id_offre'], $_GET['id_user']));
        } elseif ($action === 'cv' && isset($_GET['id_offre'], $_GET['id_user'])) {
            echo json_encode(["cv" => Postule_model::getCvOfPostule($_GET['id_offre'], $_GET['id_user'])]);
        } elseif ($action === 'lettre' && isset($_GET['id_offre'], $_GET['id_user'])) {
            echo json_encode(["lettre_motivation" => Postule_model::getLettreMotivationOfPostule($_GET['id_offre'], $_GET['id_user'])]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(0);
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Postule_model::createPostule(
            $data['id_offre'], $data['id_user'], $data['date'],
            $data['cv'], $data['lettre_motivation'], $data['status']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        if (!isset($_GET['id_offre'], $_GET['id_user'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID de candidature invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_offre = intval($_GET['id_offre']);
        $id_user = intval($_GET['id_user']);
        
        $updatedFields = [];
        
        if (isset($data['date'])) {
            $updatedFields['date'] = Postule_model::updateDatePostule($id_offre, $id_user, $data['date']);
        }
        if (isset($data['cv'])) {
            $updatedFields['cv'] = Postule_model::updateCvPostule($id_offre, $id_user, $data['cv']);
        }
        if (isset($data['lettre_motivation'])) {
            $updatedFields['lettre_motivation'] = Postule_model::updateLettreMotivationPostule($id_offre, $id_user, $data['lettre_motivation']);
        }
        if (isset($data['status'])) {
            $updatedFields['status'] = Postule_model::updateStatusPostule($id_offre, $id_user, $data['status']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        if (isset($_GET['id_offre'], $_GET['id_user'])) {
            echo json_encode(["success" => Postule_model::deletePostule($_GET['id_offre'], $_GET['id_user'])]);
        } elseif (isset($_GET['id_offre'])) {
            echo json_encode(["success" => Postule_model::deletePostuleByIdOffre($_GET['id_offre'])]);
        } elseif (isset($_GET['id_user'])) {
            echo json_encode(["success" => Postule_model::deletePostuleByIdUser($_GET['id_user'])]);
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
