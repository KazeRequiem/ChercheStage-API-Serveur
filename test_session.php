<?php

include_once 'api/session/session.php';
header("Content-Type: application/json");

if (!isAuthenticated()) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

echo json_encode([
    "id_user" => $_SESSION['id_user'],
    "prenom" => $_SESSION['prenom'],
    "nom" => $_SESSION['nom'],
    "email" => $_SESSION['email'],
    "permission" => $_SESSION['permission']
]);

?>
