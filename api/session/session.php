<?php
session_start();
function startSession($user) {
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['permission'] = $user['permission'];
}

function isAuthenticated() {
    return isset($_SESSION['id_user']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        http_response_code(401);
        echo json_encode(["error" => "Non authentifié"]);
        exit();
    }
}

function requirePermission($level) {
    if (!isset($_SESSION['permission']) || $_SESSION['permission'] < $level) {
        http_response_code(403);
        echo json_encode(["error" => "Acces interdit"]);
        exit();
    }
}

function logout() {
    session_unset();
    session_destroy();
}
?>
