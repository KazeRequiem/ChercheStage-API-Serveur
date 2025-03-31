<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/favoris.php';  
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
        requirePermission(0);
        if ($action === 'favoris') {
            echo json_encode(Favoris_model::getAllFavoris());
        } elseif (is_numeric($action)) {
            echo json_encode(Favoris_model::getFavorisByIdUser($action));
        } elseif (isset($_GET['id_offre'], $_GET['id_user'])) {
            echo json_encode(Favoris_model::getFavorisById($_GET['id_offre'], $_GET['id_user']));
        } elseif (isset($_GET['id_offre'])) {
            echo json_encode(Favoris_model::getFavorisByIdOffre($_GET['id_offre']));
        } elseif (isset($_GET['id_user'])) {
            echo json_encode(Favoris_model::getFavorisByIdUser($_GET['id_user']));
        } elseif (isset($_GET['nb_favoris_user'])) {
            echo json_encode(["count" => Favoris_model::getNbFavorisByIdUser($_GET['nb_favoris_user'])]);
        } elseif (isset($_GET['nb_favoris_offre'])) {
            echo json_encode(["count" => Favoris_model::getNbFavorisByIdOffre($_GET['nb_favoris_offre'])]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(0);
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Favoris_model::createFavoris($data['id_offre'], $data['id_user']));
        break;
    
    case 'DELETE':
        requirePermission(0);
        if (isset($_GET['id_offre'], $_GET['id_user'])) {
            Favoris_model::deleteFavoris($_GET['id_offre'], $_GET['id_user']);
            echo json_encode(["success" => true]);
        } elseif (isset($_GET['id_offre'])) {
            Favoris_model::deleteFavorisByIdOffre($_GET['id_offre']);
            echo json_encode(["success" => true]);
        } elseif (isset($_GET['id_user'])) {
            Favoris_model::deleteFavorisByIdUser($_GET['id_user']);
            echo json_encode(["success" => true]);
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
