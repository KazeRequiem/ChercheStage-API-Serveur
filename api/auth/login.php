<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . "/../../models/user.php";
require_once __DIR__ . "/../session/session.php";

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}

if (isAuthenticated()) {
    http_response_code(403); // 403 = Accès refusé
    echo json_encode(["error" => "Déjà connecté"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['email'], $data['mdp'])) {
        http_response_code(400); // 400 = Mauvaise requête
        echo json_encode(["error" => "Email et mot de passe requis"]);
        exit();
    }

    $user = User_model::getUserByEmail($data['email']);

    if (!$user) {
        http_response_code(401); // 401 = Non autorisé
        echo json_encode(["error" => "Utilisateur non trouvé"]);
        exit();
    }

    if (!password_verify($data['mdp'], $user['mdp'])) {
        http_response_code(401); // 401 = Non autorisé
        echo json_encode(["error" => "Mot de passe incorrect"]);
        exit();
    }

    startSession($user);

    echo json_encode([
        "message" => "Connexion réussie",
        "user" => [
            "id" => $_SESSION['id_user'],
            "prenom" => $_SESSION['prenom'],
            "nom" => $_SESSION['nom'],
            "email" => $_SESSION['email'],
            "permission" => $_SESSION['permission']
        ]
    ]);
}
?>
