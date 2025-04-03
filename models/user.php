<?php
require_once __DIR__ . '/../config/database.php';   
require_once __DIR__ . '/postule.php';
require_once __DIR__ . '/favoris.php';
require_once __DIR__ . '/note.php';
require_once __DIR__ . '/ticket.php';

class User_model{

    ### GETTERS ####

    public static function getAllUsers(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM user');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllUsersWithoutPassword(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT id_user, prenom, nom, email, tel, date_naissance, permission, id_promotion FROM user');
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

    public static function getUserByIdWithoutPassword($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('SELECT id_user, prenom, nom, email, tel, date_naissance, permission, id_promotion FROM user WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByPrenom($prenom){
            $pdo = Database::connect();
            $prenom = Database::validateParams($prenom);
            $stmt = $pdo->prepare('SELECT * FROM user WHERE prenom = :prenom');
            $stmt->execute([':prenom' =>  $prenom]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

    public static function getUserByNom($nom){
        $pdo = Database::connect();
        $nom = Database::validateParams($nom);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE nom = :nom');
        $stmt->execute([':nom' =>  $nom]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByEmail($email){
        $pdo = Database::connect();
        $email = Database::validateParams($email);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $stmt->execute([':email' =>  $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByTel($tel){
        $pdo = Database::connect();
        $tel = Database::validateParams($tel);
        $stmt = $pdo->prepare('SELECT * FROM user WHERE tel = :tel');
        $stmt->execute([':tel' =>  $tel]);
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

    public static function getIdUser($prenom,$nom,$email){
        $pdo = Database::connect();
        $prenom = Database::validateParams($prenom);
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $stmt = $pdo->prepare('SELECT id_user FROM user WHERE prenom = :prenom AND nom = :nom AND email = :email');
        $stmt->execute([':prenom' =>  $prenom, ':nom' =>  $nom, ':email' =>  $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_user'];
    }

    public static function getNbEtudiant(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT COUNT(*) as nb_etudiant FROM user WHERE permission = 0');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nb_etudiant'];
    }

    ### CREATORS ###

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
    

    public static function createUser2($prenom, $nom, $email, $mdp, $tel, $date_naissance, $permission, $nom_promotion) {
        $pdo = Database::connect();
        $prenom = Database::validateParams($prenom);
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $tel = Database::validateParams($tel);
        $date_naissance = Database::validateParams($date_naissance);
        $permission = Database::validateParams($permission);
        $nom_promotion = Database::validateParams($nom_promotion);

        $stmt = $pdo->prepare('SELECT id_promotion FROM promotion WHERE nom_promotion = :nom_promotion');
        $stmt->execute([':nom_promotion' => $nom_promotion]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $id_promotion = $result['id_promotion'];
        } else {
            throw new Exception("Promotion non trouvée : $nom_promotion");
        }

        $stmt = $pdo->prepare("INSERT INTO user (prenom, nom, email, mdp, tel, date_naissance, permission, id_promotion) VALUES (:prenom, :nom, :email, :mdp, :tel, :date_naissance, :permission, :id_promotion)");
        $stmt->execute([
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email,
            ':mdp' => password_hash($mdp, PASSWORD_DEFAULT),
            ':tel' => $tel,
            ':date_naissance' => $date_naissance,
            ':permission' => $permission,
            ':id_promotion' => $id_promotion
        ]);
        $lastId = $pdo->lastInsertId();
        return self::getUserById($lastId);
    }
    ### DELETORS ###

    public static function deleteUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        Postule_model::deletePostuleByIdUser($id_user);
        Favoris_model::deleteFavorisByIdUser($id_user);
        Note_model::deleteNoteByIdUser($id_user);
        Ticket_model::deleteTicketByIdUser($id_user);
        $stmt = $pdo->prepare('DELETE FROM user WHERE id_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }

    ### UPDATORS ###

    public static function updatePrenomUser($id_user,$prenom){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $prenom = Database::validateParams($prenom);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET prenom = :prenom WHERE id_user = :id_user');
        return $stmt->execute([':prenom' => $prenom, ':id_user' => $id_user]);
    }

    public static function updateNomUser($id_user,$nom){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $nom = Database::validateParams($nom);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET nom = :nom WHERE id_user = :id_user');
        return $stmt->execute([':nom' => $nom, ':id_user' => $id_user]);
    }
    public static function updateEmailUser($id_user,$email){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $email = Database::validateParams($email);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email invalide");
        }
        $stmt = $pdo->prepare('UPDATE user SET email = :email WHERE id_user = :id_user');
        return $stmt->execute([':email' => $email, ':id_user' => $id_user]);
    }
    public static function updatePasswordUser($id_user,$mdp){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $mdp = Database::validateParams($mdp);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET mdp = :mdp WHERE id_user = :id_user');
        return $stmt->execute([':mdp' => password_hash($mdp, PASSWORD_DEFAULT), ':id_user' => $id_user]);
    }

    public static function updateTelUser($id_user,$tel){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $tel = Database::validateParams($tel);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET tel = :tel WHERE id_user = :id_user');
        return $stmt->execute([':tel' => $tel, ':id_user' => $id_user]);
    }

    public static function updateDateNaissanceUser($id_user,$date_naissance){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $date_naissance = Database::validateParams($date_naissance);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET date_naissance = :date_naissance WHERE id_user = :id_user');
        return $stmt->execute([':date_naissance' => $date_naissance, ':id_user' => $id_user]);
    }

    public static function updatePermissionUser($id_user,$permission){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $permission = Database::validateParams($permission);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET permission = :permission WHERE id_user = :id_user');
        return $stmt->execute([':permission' => $permission, ':id_user' => $id_user]);
    }

    public static function updateIdPromotionUser($id_user,$id_promotion){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        $id_promotion = Database::validateParams($id_promotion);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('UPDATE user SET id_promotion = :id_promotion WHERE id_user = :id_user');
        return $stmt->execute([':id_promotion' => $id_promotion, ':id_user' => $id_user]);
    }
}