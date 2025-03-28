<?php

require_once 'config/database.php';


class Promotion_model{
    public static function getAllPromotions(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM promotion');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPromotionById($id_promotion){
        $pdo = Database::connect();
        $id_promotion = Database::validateParams($id_promotion);
        if (!is_numeric($id_promotion)) {
            throw new Exception("ID de promotion invalide : $id_promotion");
        }
        $stmt = $pdo->prepare('SELECT * FROM promotion WHERE id_promotion = :id_promotion');
        $stmt->execute([':id_promotion' => $id_promotion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPromotionByNomPromotion($nom_promotion){
        $pdo = Database::connect();
        $nom_promotion = Database::validateParams($nom_promotion);
        $stmt = $pdo->prepare('SELECT * FROM promotion WHERE nom_promotion = :nom_promotion');
        $stmt->execute([':nom_promotion' => $nom_promotion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPromotionByAnnee($annee){
        $pdo = Database::connect();
        $annee = Database::validateParams($annee);
        $stmt = $pdo->prepare('SELECT * FROM promotion WHERE annee = :annee');
        $stmt->execute([':annee' => $annee]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createPromotion($id_promotion,$nom_promotion,$annee){
        $pdo = Database::connect();

        $id_promotion = Database::validateParams($id_promotion);
        $nom_promotion = Database::validateParams($nom_promotion);
        $annee = Database::validateParams($annee);

        $stmt = $pdo->prepare('INSERT INTO promotion (id_promotion, nom_promotion, annee) VALUES (:id_promotion, :nom_promotion, :annee)');
        $stmt->execute([
            ':id_promotion' => $id_promotion, 
            ':nom_promotion' => $nom_promotion, 
            ':annee' => $annee]);
        
        $lastId = $pdo->lastInsertId();
        return self::getPromotionById($lastId);
    }
}