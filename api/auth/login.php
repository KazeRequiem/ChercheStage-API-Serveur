<?php
require_once __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../session/session.php';


header('Content-Type: application/json');

if (isAuthenticated()){
    echo json_encode(["error" => "Déjà connecté"]);
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['email'], $data['mdp'])) {
        echo json_encode(["error" => "Email et mot de passe requis"]);
        exit();
    }

    $user = User_model::getUserByEmail($data['email']);

    if (!$user || !password_verify($data['mdp'], $user['mdp'])) {
        echo json_encode(["error" => "Identifiants incorrects"]);
        exit();
    }

    startSession($user);

    echo json_encode(["message" => "Connexion réussie", "user" => [
        User_model::getUserByIdWithoutPassword($_SESSION['id_user']),
    ]]);

}
?>
