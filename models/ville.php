<?php

require_once 'config/database.php';

class Ville_model{

    ### GETTERS ####

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

    public static function getIdVille($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('SELECT id_ville FROM ville WHERE nom_ville = :nom_ville');
        $stmt->execute([':nom_ville' => $ville]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_ville'];
    }

    ### CREATOR ###

    public static function createVille($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('INSERT INTO ville (nom_ville) VALUES (:nom_ville)');
        $stmt->execute([':nom_ville' => $ville]);

        $lastId = $pdo->lastInsertId();
        return self::getAllVillesById($lastId);
    }

    ### DELETORS ###

    public static function deleteVille($id_ville){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE se_situe SET id_ville = NULL WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id_ville]);
        $stmt = $pdo->prepare('DELETE FROM ville WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id_ville]);
    }

    ### UPDATORS ###

    public static function updateVille($id_ville, $nom_ville){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $nom_ville = Database::validateParams($nom_ville);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE ville SET nom_ville = :nom_ville WHERE id_ville = :id_ville');
        return $stmt->execute([':nom_ville' => $nom_ville, ':id_ville' => $id_ville]);
    }
}