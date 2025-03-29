<?php

require_once 'config/database.php';

class Se_situe_model{

    ### GETTERS ####

    public static function getAllSeSitue(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM se_situe');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSeSitueByIdVille($id_ville){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $stmt = $pdo->prepare('SELECT * FROM se_situe WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id_ville]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getSeSitueByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $stmt = $pdo->prepare('SELECT * FROM se_situe WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise' => $id_entreprise]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getSeSitueById($id_entreprise, $id_ville){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $id_ville = Database::validateParams($id_ville);
        $stmt = $pdo->prepare('SELECT * FROM se_situe WHERE id_entreprise = :id_entreprise AND id_ville = :id_ville');
        $stmt->execute([':id_entreprise' => $id_entreprise, ':id_ville' => $id_ville]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ### CREATORS ###

    public static function createSeSitue($id_ville, $id_entreprise){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $id_entreprise = Database::validateParams($id_entreprise);
        $stmt = $pdo->prepare('INSERT INTO se_situe (id_ville, id_entreprise) VALUES (:id_ville, :id_entreprise)');
        $stmt->execute([
            ':id_ville' => $id_ville, 
            ':id_entreprise' => $id_entreprise]);
        return self::getSeSitueById($id_entreprise, $id_ville);
    }
    
    ### DELETORS ###

    public static function deleteSeSitue($id_entreprise, $id_ville){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $id_ville = Database::validateParams($id_ville);
        $stmt = $pdo->prepare('DELETE FROM se_situe WHERE id_entreprise = :id_entreprise AND id_ville = :id_ville');
        return $stmt->execute([':id_entreprise' => $id_entreprise, ':id_ville' => $id_ville]);
    }

    public static function deleteSeSitueByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $stmt = $pdo->prepare('DELETE FROM se_situe WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':id_entreprise' => $id_entreprise]);
    }

    ### UPDATORS ###

    public static function updateSeSitue($id_entreprise, $id_ville){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $id_ville = Database::validateParams($id_ville);
        $stmt = $pdo->prepare('UPDATE se_situe SET id_ville = :id_ville WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':id_entreprise' => $id_entreprise, ':id_ville' => $id_ville]);
    }
}