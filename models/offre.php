<?php

require_once 'config/database.php';


class Offre_model{

    public static function getAllOffres(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM offre');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOffresById($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $stmt = $pdo->prepare('SELECT * FROM offre WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOffreByTitre($titre){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $stmt = $pdo->prepare('SELECT * FROM offre WHERE titre = :titre');
        $stmt->execute([':titre' => $titre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOffreByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('SELECT * FROM offre WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise'=> $id_entreprise]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createOffre($titre, $description, $date_debut, $date_fin, $id_entreprise){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $description = Database::validateParams($description);
        $date_debut = Database::validateParams($date_debut);
        $date_fin = Database::validateParams($date_fin);
        $id_entreprise = Database::validateParams($id_entreprise);
        
        $stmt = $pdo->prepare('INSERT INTO offre (titre, description, date_debut, date_fin, id_entreprise) VALUES (:titre, :description, :date_debut, :date_fin, :id_entreprise)');
        $stmt->execute([
            ':titre' => $titre, 
            ':description' => $description, 
            ':date_debut' => $date_debut, 
            ':date_fin' => $date_fin, 
            ':id_entreprise' => $id_entreprise]);
        
        $lastId = $pdo->lastInsertId();
        return self::getOffresById($lastId);
    }

}