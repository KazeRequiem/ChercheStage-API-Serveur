<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/note.php';  
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
        if ($action === 'notes') {
            echo json_encode(Note_model::getAllNotes());
        } elseif (isset($_GET['id_entreprise']) && isset($_GET['id_user'])) {
            echo json_encode(Note_model::getNoteById($_GET['id_entreprise'], $_GET['id_user']));
        } elseif (isset($_GET['id_entreprise'])) {
            echo json_encode(Note_model::getNoteByIdEntreprise($_GET['id_entreprise']));
        } elseif (isset($_GET['id_user'])) {
            echo json_encode(Note_model::getNoteByIdUser($_GET['id_user']));
        } elseif ($action === 'moyenne' && isset($_GET['id_entreprise'])) {
            echo json_encode(["moyenne" => Note_model::getMoyenneEntreprise($_GET['id_entreprise'])]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(level: 0);
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Note_model::createNote(
            $data['id_entreprise'], $data['id_user'], $data['note']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
    
        if (!isset($_GET['id_entreprise'], $_GET['id_user'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID entreprise et ID utilisateur requis"]);
            exit;
        }
    
        $id_entreprise = intval($_GET['id_entreprise']);
        $id_user = intval($_GET['id_user']);
    
        $data = json_decode(file_get_contents("php://input"), true);
    
        $updatedFields = [];
    
        if (isset($data['note'])) {
            $updatedFields['note'] = Note_model::updateNote($id_entreprise, $id_user, $data['note']);
        }
    
        if (empty($updatedFields)) {
            http_response_code(400);
            echo json_encode(["error" => "Aucun champ valide à mettre à jour"]);
            exit;
        }
    
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;        
    
    case 'DELETE':
        if (isset($_GET['id_entreprise']) && isset($_GET['id_user'])) {
            requirePermission(0);
            echo json_encode(["success" => Note_model::deleteNote($_GET['id_entreprise'], $_GET['id_user'])]);
        } elseif (isset($_GET['id_entreprise'])) {
            requirePermission(2);
            echo json_encode(["success" => Note_model::deleteNoteByIdEntreprise($_GET['id_entreprise'])]);
        } elseif (isset($_GET['id_user'])) {
            requirePermission(2);
            echo json_encode(["success" => Note_model::deleteNoteByIdUser($_GET['id_user'])]);
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
