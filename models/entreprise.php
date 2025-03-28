<?php

require_once 'config/database.php';
require_once 'models/se_situe.php';

class Entreprise_model{

    public static function getAllEntreprises(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM entreprise');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEntrepriseById($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise' => $id_entreprise]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getEntrepriseByNom($nom){
        $pdo = Database::connect();
        $nom = Database::validateParams($nom);
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE nom = :nom');
        $stmt->execute([':nom' => $nom]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEntrepriseByEmail($email){
        $pdo = Database::connect();
        $email = Database::validateParams($email);
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEntrepriseByTel($tel){
        $pdo = Database::connect();
        $tel = Database::validateParams($tel);
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE tel = :tel');
        $stmt->execute([':tel' => $tel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createEntreprise($nom, $email, $description, $tel,$ville){
        $pdo = Database::connect();
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $description = Database::validateParams($description);
        $tel = Database::validateParams($tel);
        $stmt = $pdo->prepare('INSERT INTO entreprise (nom, email, description, tel) VALUES (:nom, :email, :description, :tel)');
        $stmt->execute([
            ':nom' => $nom, 
            ':email' => $email, 
            ':description' => $description, 
            ':tel' => $tel]);
        $lastId = $pdo->lastInsertId();
        Se_situe::createSeSitue($ville, $lastId);
        return self::getEntrepriseById($lastId);
    }

}