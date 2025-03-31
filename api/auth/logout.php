<?php
require_once __DIR__ . '/../session/session.php';

header('Content-Type: application/json');

logout();

echo json_encode(["message" => "Deconnexion reussie"]);
?>