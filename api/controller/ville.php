<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/ville.php';  
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
        if ($action === 'villes') {
            echo json_encode(Ville_model::getAllVilles());
        } elseif (is_numeric($action)) {
            echo json_encode(Ville_model::getAllVillesById($action));
        } elseif (isset($_GET['nom'])) {
            echo json_encode(Ville_model::getAllVillesByName($_GET['nom']));
        } elseif (isset($_GET['code_postal'])) {
            echo json_encode(Ville_model::getAllVillesByCodePostal($_GET['code_postal']));
        } elseif (isset($_GET['region'])) {
            echo json_encode(Ville_model::getAllVillesByRegion($_GET['region']));
        } elseif (isset($_GET['pays'])) {
            echo json_encode(Ville_model::getAllVillesByCountry($_GET['pays']));
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1);
        
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Ville_model::createVille(
            $data['ville'], $data['code_postal'],
            $data['region'], $data['pays']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID de ville invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_ville = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['ville'])) {
            $updatedFields['ville'] = Ville_model::updateVille($id_ville, $data['ville']);
        }
        if (isset($data['code_postal'])) {
            $updatedFields['code_postal'] = Ville_model::updateCodePostal($id_ville, $data['code_postal']);
        }
        if (isset($data['region'])) {
            $updatedFields['region'] = Ville_model::updateRegion($id_ville, $data['region']);
        }
        if (isset($data['pays'])) {
            $updatedFields['pays'] = Ville_model::updatePays($id_ville, $data['pays']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        
        if (is_numeric($action)) {
            echo json_encode(["success" => Ville_model::deleteVille($action)]);
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
