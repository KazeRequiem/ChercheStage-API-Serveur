<?php

require_once 'config/database.php';

class Ville_model{

    public static function getAllVilles(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM ville');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesById($id){
        $pdo = Database::connect();
        $id = Database::validateParams($id);
        if (!is_numeric($id)) {
            throw new Exception("ID de ville invalide : $id");
        }
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesByName($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE nom_ville = :nom_ville');
        $stmt->execute([':nom_ville' => $ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createVille($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('INSERT INTO ville (nom_ville) VALUES (:nom_ville)');
        $stmt->execute([':nom_ville' => $ville]);

        $lastId = $pdo->lastInsertId();
        return self::getAllVillesById($lastId);
    }
}