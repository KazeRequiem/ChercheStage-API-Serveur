<?php
require_once 'config/database.php';

class User_model{
    public static function getAllUsers(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserById($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('SELECT * FROM user WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByEMail($email){
        $pdo = Database::connect();
        $email = Database::validateParams($email);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $stmt->execute([':email' =>  $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByPrenom($prenom){
        $pdo = Database::connect();
        $prenom = Database::validateParams($prenom);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE prenom = :prenom');
        $stmt->execute([':prenom' =>  $prenom]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByPermission($permission){
        $pdo = Database::connect();
        $permission = Database::validateParams($permission);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE permission = :permission');
        $stmt->execute([':permission' =>  $permission]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserByIdPromotion($id_promotion){
        $pdo = Database::connect();
        $id_promotion = Database::validateParams($id_promotion);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE id_promotion = :id_promotion');
        $stmt->execute([':id_promotion' =>  $id_promotion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createUser($prenom, $nom, $email, $mdp, $tel, $date_naissance, $permission, $id_promotion) {
        $pdo = Database::connect();
    
        $prenom = Database::validateParams($prenom);
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $tel = Database::validateParams($tel);
        $date_naissance = Database::validateParams($date_naissance);
        $permission = Database::validateParams($permission);
        $id_promotion = (int) Database::validateParams($id_promotion);
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
    
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    
        $stmt = $pdo->prepare('INSERT INTO user (prenom, nom, email, mdp, tel, date_naissance, permission, id_promotion) VALUES (:prenom, :nom, :email, :mdp, :tel, :date_naissance, :permission, :id_promotion)');
        $stmt->execute([
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email,
            ':mdp' => $mdp,
            ':tel' => $tel,
            ':date_naissance' => $date_naissance,
            ':permission' => $permission,
            ':id_promotion' => $id_promotion
        ]);
    
        $lastId = $pdo->lastInsertId();
        return self::getUserById($lastId);
    }
    

}