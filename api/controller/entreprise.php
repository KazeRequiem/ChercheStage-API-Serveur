<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/entreprise.php';  
require_once __DIR__ . '/../session/session.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$action = $request[count($request) - 1];

//if (!isAuthenticated()) {
    //http_response_code(401);
    //echo json_encode(["error" => "Utilisateur non authentifié"]);
    //exit;
//}

switch ($method) {
    case 'GET':
        if ($action === 'entreprises') {
            echo json_encode(Entreprise_model::getAllEntreprisesWithVille());
        } elseif (is_numeric($action)) {
            echo json_encode(Entreprise_model::getEntrepriseById($action));
        } elseif (isset($_GET['nom'])) {
            echo json_encode(Entreprise_model::getEntrepriseByNom($_GET['nom']));
        } elseif (isset($_GET['email'])) {
            echo json_encode(Entreprise_model::getEntrepriseByEmail($_GET['email']));
        } elseif (isset($_GET['tel'])) {
            echo json_encode(Entreprise_model::getEntrepriseByTel($_GET['tel']));
        } elseif ($action === 'logo' && isset($_GET['id'])) {
            echo json_encode(["logo" => Entreprise_model::getLogoEntreprise($_GET['id'])]);
        } elseif (isset($_GET['mot_cle'])) {
            echo json_encode(Entreprise_model::getEntrepriseByMotCle($_GET['mot_cle']));
            break;
        } elseif (isset($_GET['ville'])) {
            echo json_encode(Entreprise_model::getAllEntreprisesByVille($_GET['ville']));
        } elseif (isset($_GET['code_postal'])) {
            echo json_encode(Entreprise_model::getAllEntreprisesByCodePostal($_GET['code_postal']));
        } elseif (isset($_GET['region'])) {
            echo json_encode(Entreprise_model::getAllEntreprisesByRegion($_GET['region']));
        } elseif (isset($_GET['pays'])) {
            echo json_encode(Entreprise_model::getAllEntreprisesByPays($_GET['pays']));
        } elseif (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'nom':
                    echo json_encode(Entreprise_model::getAllEntreprisesSortByNom());
                    break;
                case 'ville':
                    echo json_encode(Entreprise_model::getAllEntreprisesSortByVille());
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(["error" => "Type de tri invalide"]);
                    break;
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Requête invalide"]);
        }
        break;
    
    case 'POST':
        requirePermission(1); // Niveau de permission pour créer une entreprise
        
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Entreprise_model::createEntreprise(
            $data['nom'], $data['email'], $data['description'],
            $data['tel'], $data['logo'], $data['ville'],
            $data['code_postal'], $data['region'], $data['pays']
        ));
        break;
    
    case 'PUT':
        requirePermission(1); // Permission requise pour modifier une entreprise
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID d'entreprise invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_entreprise = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['nom'])) {
            $updatedFields['nom'] = Entreprise_model::updateNomEntreprise($id_entreprise, $data['nom']);
        }
        if (isset($data['email'])) {
            $updatedFields['email'] = Entreprise_model::updateEmailEntreprise($id_entreprise, $data['email']);
        }
        if (isset($data['description'])) {
            $updatedFields['description'] = Entreprise_model::updateDescriptionEntreprise($id_entreprise, $data['description']);
        }
        if (isset($data['tel'])) {
            $updatedFields['tel'] = Entreprise_model::updateTelEntreprise($id_entreprise, $data['tel']);
        }
        if (isset($data['ville'], $data['code_postal'], $data['region'], $data['pays'])) {
            $updatedFields['ville'] = Entreprise_model::updateVilleEntreprise($id_entreprise, $data['ville'], $data['code_postal'], $data['region'], $data['pays']);
        }
        if (isset($data['logo'])) {
            $updatedFields['logo'] = Entreprise_model::updateLogoEntreprise($id_entreprise, $data['logo']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(2); // Niveau de permission pour supprimer une entreprise
        
        if (is_numeric($action)) {
            echo json_encode(["success" => Entreprise_model::deleteEntreprise($action)]);
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