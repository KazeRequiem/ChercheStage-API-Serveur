<?php

require_once 'config/database.php';


class Promotion_model{

    ### GETTERS ####

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

    public static function getIdPromotion($nom_promotion, $annee){
        $pdo = Database::connect();
        $nom_promotion = Database::validateParams($nom_promotion);
        $annee = Database::validateParams($annee);
        $stmt = $pdo->prepare('SELECT id_promotion FROM promotion WHERE nom_promotion = :nom_promotion AND annee = :annee');
        $stmt->execute([':nom_promotion' => $nom_promotion, ':annee' => $annee]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_promotion'];
    }

    public static function getAllMembersOfAPromotion($id_promotion){
        $pdo = Database::connect();
        $id_promotion = Database::validateParams($id_promotion);
        $stmt = $pdo->prepare('SELECT id_user, prenom, nom, email, tel, date_naissance, permission, id_promotion FROM user WHERE id_promotion = :id_promotion');
        $stmt->execute([':id_promotion' => $id_promotion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ### CREATORS ###

    public static function createPromotion($nom_promotion,$annee){
        $pdo = Database::connect();
        $nom_promotion = Database::validateParams($nom_promotion);
        $annee = Database::validateParams($annee);
        $stmt = $pdo->prepare('INSERT INTO promotion (nom_promotion, annee) VALUES (:nom_promotion, :annee)');
        $stmt->execute([':nom_promotion' => $nom_promotion, ':annee' => $annee]);

        $lastId = $pdo->lastInsertId();
        return self::getPromotionById($lastId);
    }

    ### DELETORS ###

    public static function deletePromotion($id_promotion){ // A VOIR SI CETTE MANIERE DECRIRE EST BONNE
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();
        
            $stmt = $pdo->prepare("UPDATE user SET id_promotion = 1 WHERE id_promotion = :id_promotion");
            $stmt->execute([':id_promotion' => $id_promotion]);
        
            $stmt = $pdo->prepare("DELETE FROM promotion WHERE id_promotion = :id_promotion");
            $stmt->execute([':id_promotion' => $id_promotion]);
        
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }

    ### UPDATORS ###
    public static function updateNomPromotion($id_promotion, $nom_promotion){
        $pdo = Database::connect();
        $id_promotion = Database::validateParams($id_promotion);
        if (!is_numeric($id_promotion)) {
            throw new Exception("ID de promotion invalide : $id_promotion");
        }
        $nom_promotion = Database::validateParams($nom_promotion);
        $stmt = $pdo->prepare('UPDATE promotion SET nom_promotion = :nom_promotion WHERE id_promotion = :id_promotion');
        return $stmt->execute([':nom_promotion' => $nom_promotion, ':id_promotion' => $id_promotion]);
    }

    public static function updateAnneePromotion($id_promotion, $annee){
        $pdo = Database::connect();
        $id_promotion = Database::validateParams($id_promotion);
        if (!is_numeric($id_promotion)) {
            throw new Exception("ID de promotion invalide : $id_promotion");
        }
        $annee = Database::validateParams($annee);
        $stmt = $pdo->prepare('UPDATE promotion SET annee = :annee WHERE id_promotion = :id_promotion');
        return $stmt->execute([':annee' => $annee, ':id_promotion' => $id_promotion]);
    }
}