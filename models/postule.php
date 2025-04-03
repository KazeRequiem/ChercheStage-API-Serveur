<?php

require_once __DIR__ . '/../config/database.php';   

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
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombre_postule'];
    }

    public static function getNombrePostulantParOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $stmt = $pdo->prepare('SELECT COUNT(*) as nombre_postulant FROM postule WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nombre_postulant'];
    }

    public static function getCvOfPostule($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('SELECT cv FROM postule WHERE id_offre = :id_offre AND id_user = :id_user');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['cv'];
    }

    public static function getLettreMotivationOfPostule($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $stmt = $pdo->prepare('SELECT lettre_motivation FROM postule WHERE id_offre = :id_offre AND id_user = :id_user');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['lettre_motivation'];
    }

    public static function getTauxReponseEntreprises(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT COUNT(*) as taux FROM postule WHERE status = 1 OR status = 2');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['taux'];
    }

    ### CREATORS ###

    public static function createPostule($id_offre, $id_user, $date, $cv, $lettre_motivation, $status){ ## en attente : 0, refus : 1, accepté : 2
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $date = Database::validateParams($date);
        $status = Database::validateParams($status);
        $stmt = $pdo->prepare('INSERT INTO postule (id_offre, id_user, date, cv, lettre_motivation, status) VALUES (:id_offre, :id_user, :date, :cv, :lettre_motivation, :status)');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':date' => $date, 
            ':cv' => $cv, 
            ':lettre_motivation' => $lettre_motivation,
            ':status' => $status]);
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

    public static function deletePostuleByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        $stmt = $pdo->prepare('DELETE FROM postule WHERE id_offre IN (SELECT id_offre FROM offres WHERE id_entreprise = :id_entreprise)');
        return $stmt->execute([':id_entreprise'=> $id_entreprise]);
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

    public static function updateCvPostule($id_offre, $id_user, $cv){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $cv = Database::validateParams($cv);
        $stmt = $pdo->prepare('UPDATE postule SET cv = :cv WHERE id_offre = :id_offre AND id_user = :id_user');
        return $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':cv' => $cv]);
    }

    public static function updateLettreMotivationPostule($id_offre, $id_user, $lettre_motivation){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $lettre_motivation = Database::validateParams($lettre_motivation);
        $stmt = $pdo->prepare('UPDATE postule SET lettre_motivation = :lettre_motivation WHERE id_offre = :id_offre AND id_user = :id_user');
        return $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':lettre_motivation' => $lettre_motivation]);
    }

    public static function updateStatusPostule($id_offre, $id_user, $status){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        $id_user = Database::validateParams($id_user);
        $status = Database::validateParams($status);
        $stmt = $pdo->prepare('UPDATE postule SET status = :status WHERE id_offre = :id_offre AND id_user = :id_user');
        return $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user, 
            ':status' => $status]);
    }
}