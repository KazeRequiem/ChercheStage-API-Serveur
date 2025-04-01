<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/ticket.php';  
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
        if ($action === 'tickets') {
            echo json_encode(Ticket_model::getAllTickets());
        } elseif (is_numeric($action)) {
            echo json_encode(Ticket_model::getTicketById($action));
        } elseif (isset($_GET['id_user'])) {
            echo json_encode(Ticket_model::getTicketByIdUser($_GET['id_user']));
        } elseif (isset($_GET['titre'])) {
            echo json_encode(Ticket_model::getTicketByTitre($_GET['titre']));
        } elseif (isset($_GET['date_creation'])) {
            echo json_encode(Ticket_model::getTicketByDateCreation($_GET['date_creation']));
        } elseif (isset($_GET['etat'])) {
            echo json_encode(Ticket_model::getTicketByEtat($_GET['etat']));
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1);
        
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Ticket_model::createTicket(
            $data['titre'], $data['description'], $data['date_creation'],
            $data['etat'], $data['id_user']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID de ticket invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_ticket = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['titre'])) {
            $updatedFields['titre'] = Ticket_model::updateTitreTicket($id_ticket, $data['titre']);
        }
        if (isset($data['description'])) {
            $updatedFields['description'] = Ticket_model::updateDescriptionTicket($id_ticket, $data['description']);
        }
        if (isset($data['date_creation'])) {
            $updatedFields['date_creation'] = Ticket_model::updateDateCreationTicket($id_ticket, $data['date_creation']);
        }
        if (isset($data['etat'])) {
            $updatedFields['etat'] = Ticket_model::updateEtatTicket($id_ticket, $data['etat']);
        }
        if (isset($data['id_user'])) {
            $updatedFields['id_user'] = Ticket_model::updateIdUserTicket($id_ticket, $data['id_user']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2);
        
        if (is_numeric($action)) {
            echo json_encode(["success" => Ticket_model::deleteTicket($action)]);
        } elseif (isset($_GET['id_user'])) {
            echo json_encode(["success" => Ticket_model::deleteTicketByIdUser($_GET['id_user'])]);
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