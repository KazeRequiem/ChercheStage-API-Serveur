<?php

require_once 'config/database.php';

class Postule_model{

    ### GETTERS ####

    public static function getAllPostules(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM postule');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPostuleByIdOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $stmt = $pdo->prepare('SELECT * FROM postule WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPostuleByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('SELECT * FROM postule WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPostuleById($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('SELECT * FROM postule WHERE id_offre = :id_offre AND id_user = :id_user');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNombrePostuleParUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('SELECT COUNT(*) as nombre_postule FROM postule WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNombrePostulantParOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $stmt = $pdo->prepare('SELECT COUNT(*) as nombre_postulant FROM postule WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ### CREATORS ###

    public static function createPostule($id_offre, $id_user, $date){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $date = Database::validateParams($date);
        $stmt = $pdo->prepare('INSERT INTO postule (id_offre, id_user, date) VALUES (:id_offre, :id_user, :date)');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':date' => $date]);
        return self::getPostuleById($id_offre, $id_user);
    }

    ### DELETORS ###
    
    public static function deletePostule($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('DELETE FROM postule WHERE id_offre = :id_offre AND id_user = :id_user');
        return $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
    }

    public static function deletePostuleByIdOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $stmt = $pdo->prepare('DELETE FROM postule WHERE id_offre = :id_offre');
        return $stmt->execute([':id_offre' => $id_offre]);
    }

    public static function deletePostuleByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('DELETE FROM postule WHERE id_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }

    ### UPDATORS ###

    public static function updateDatePostule($id_offre, $id_user, $date){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $date = Database::validateParams($date);
        $stmt = $pdo->prepare('UPDATE postule SET date = :date WHERE id_offre = :id_offre AND id_user = :id_user');
        return $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':date' => $date]);
    }
}