<?php

require_once __DIR__ . '/../../config/database.php';  
require_once __DIR__ . '/../../models/offres.php';  
require_once __DIR__ . '/../session/session.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Permet les requêtes CORS
header("Access-Control-Allow-Origin: *");  // Remplacez "*" par l'URL de votre frontend pour plus de sécurité
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Si c'est une requête OPTIONS, on répond immédiatement sans continuer l'exécution
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
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
    if ($action === 'offres') {
        echo json_encode(Offre_model::getAllOffres());
    } elseif (is_numeric($action)) {
        echo json_encode(Offre_model::getOffresById($action));
    } elseif (isset($_GET['titre'])) {
        echo json_encode(Offre_model::getOffreByTitre($_GET['titre']));
    } elseif (isset($_GET['id_entreprise'])) {
        echo json_encode(Offre_model::getOffreByIdEntreprise($_GET['id_entreprise']));
    } elseif (isset($_GET['type_contrat'])) {
        echo json_encode(Offre_model::getOffreByTypeContrat($_GET['type_contrat']));
    } elseif (isset($_GET['mot_cle'])) {
        echo json_encode(Offre_model::getOffreByMotCle($_GET['mot_cle']));
    } elseif (isset($_GET['ville'])) {
        echo json_encode(Offre_model::getAllOffresByVille($_GET['ville']));
    }elseif (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'nom':
                echo json_encode(Offre_model::getAllOffreSortByNom());
                break;
            case 'nb_candidatures':
                echo json_encode(Offre_model::getAllOffresSortByNbCandid());
                break;
            case 'avg_note':
                echo json_encode(Offre_model::getAllOffresSortByAvgNote());
                break;
            case 'salaire_asc' :
                echo json_encode(Offre_model::getAllOffresSortBySalaireCroissant());
                break;
            case 'salaire_desc' :
                echo json_encode(Offre_model::getAllOffresSortBySalaireDecroissant());
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
        requirePermission(1);
        
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(Offre_model::createOffreWithNomEntreprise(
            $data['titre'], $data['description'], $data['date_debut'],
            $data['date_fin'], $data['nom_entreprise'], $data['type_contrat'], $data['salaire']
        ));
        break;
    
    case 'PUT':
        requirePermission(1);
        
        if (!is_numeric($action)) {
            http_response_code(400);
            echo json_encode(["error" => "ID d'offre invalide"]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $id_offre = intval($action);
        
        $updatedFields = [];
        
        if (isset($data['titre'])) {
            $updatedFields['titre'] = Offre_model::updateTitreOffre($id_offre, $data['titre']);
        }
        if (isset($data['description'])) {
            $updatedFields['description'] = Offre_model::updateDescriptionOffre($id_offre, $data['description']);
        }
        if (isset($data['date_debut'])) {
            $updatedFields['date_debut'] = Offre_model::updateDateDebutOffre($id_offre, $data['date_debut']);
        }
        if (isset($data['date_fin'])) {
            Offre_model::updateDateFinOffre($id_offre, $data['date_fin']);
            $updatedFields['date_fin'] = $data['date_fin'];
        }
        if (isset($data['id_entreprise'])) {
            $updatedFields['id_entreprise'] = Offre_model::updateIdEntrepriseOffre($id_offre, $data['id_entreprise']);
        }
        if (isset($data['type_contrat'])) {
            $updatedFields['type_contrat'] = Offre_model::updateTypeContratOffre($id_offre, $data['type_contrat']);
        }
        if (isset($data['salaire'])) {
            $updatedFields['salaire'] = Offre_model::updateSalaireOffre($id_offre, $data['salaire']);
        }
        
        echo json_encode([
            "success" => true,
            "updated_fields" => $updatedFields
        ]);
        break;
    
    case 'DELETE':
        requirePermission(1);
        
        if (is_numeric($action)) {
            echo json_encode(["success" => Offre_model::deleteOffre($action)]);
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