<?php
require_once __DIR__ . '/api/session/session.php';

header('Content-Type: application/json');

if (isAuthenticated()) {
    echo json_encode(["message" => "Utilisateur connecté", "session" => $_SESSION]);
} else {
    echo json_encode(["error" => "Utilisateur non connecté"]);
}
?>
