<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/promotion.php';  
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
        if ($action === 'promotions') {
            echo json_encode(Promotion_model::getAllPromotions());
        } elseif (is_numeric($action)) {
            echo json_encode(Promotion_model::getPromotionById($action));
        } elseif (isset($_GET['nom_promotion'])) {
            echo json_encode(Promotion_model::getPromotionByNomPromotion($_GET['nom_promotion']));
        } elseif (isset($_GET['annee'])) {
            echo json_encode(Promotion_model::getPromotionByAnnee($_GET['annee']));
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1); 
        
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Promotion_model::createPromotion(
            $data['nom_promotion'], $data['annee']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID de promotion invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_promotion = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['nom_promotion'])) {
            $updatedFields['nom_promotion'] = Promotion_model::updateNomPromotion($id_promotion, $data['nom_promotion']);
        }
        if (isset($data['annee'])) {
            $updatedFields['annee'] = Promotion_model::updateAnneePromotion($id_promotion, $data['annee']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        
        if (is_numeric($action)) {
            echo json_encode(["success" => Promotion_model::deletePromotion($action)]);
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