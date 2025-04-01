<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/se_situe.php';  
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
        if ($action === 'se_situe') {
            echo json_encode(Se_situe_model::getAllSeSitue());
        } elseif (isset($_GET['id_ville'])) {
            echo json_encode(Se_situe_model::getSeSitueByIdVille($_GET['id_ville']));
        } elseif (isset($_GET['id_entreprise'])) {
            echo json_encode(Se_situe_model::getSeSitueByIdEntreprise($_GET['id_entreprise']));
        } elseif (isset($_GET['id_entreprise'], $_GET['id_ville'])) {
            echo json_encode(Se_situe_model::getSeSitueById($_GET['id_entreprise'], $_GET['id_ville']));
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1);
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Se_situe_model::createSeSitue(
            $data['id_ville'], $data['id_entreprise']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['id_entreprise'], $data['id_ville'])) {
            http_response_code(400);
            echo json_encode(["error" => "Données invalides"]);
            exit;
        }
        echo json_encode([
            "success" => Se_situe_model::updateSeSitue($data['id_entreprise'], $data['id_ville'])
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        if (isset($_GET['id_entreprise'], $_GET['id_ville'])) {
            echo json_encode(["success" => Se_situe_model::deleteSeSitue($_GET['id_entreprise'], $_GET['id_ville'])]);
        } elseif (isset($_GET['id_entreprise'])) {
            echo json_encode(["success" => Se_situe_model::deleteSeSitueByIdEntreprise($_GET['id_entreprise'])]);
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
