<?php
require_once __DIR__ . '/api/session/session.php';

header('Content-Type: application/json');

requireAuth(); // Doit bloquer l'accès si l'utilisateur n'est pas connecté

echo json_encode(["message" => "Vous êtes bien authentifié"]);
?>
