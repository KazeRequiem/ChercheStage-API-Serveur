<?php
require_once __DIR__ . '/../middleware/session.php';

header('Content-Type: application/json');

// Utilisation de la fonction logout()
logout();

echo json_encode(["message" => "Deconnexion reussie"]);
?>