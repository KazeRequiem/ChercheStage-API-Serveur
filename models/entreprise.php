<?php

require_once __DIR__ . '/../config/database.php';   
require_once __DIR__ . '/se_situe.php';
require_once __DIR__ . '/ville.php';
require_once __DIR__ . '/offre.php';
require_once __DIR__ . '/note.php';


class Entreprise_model{

    ### GETTERS ####

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

    public static function getLogoEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('SELECT logo FROM entreprise WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise' => $id_entreprise]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['logo'];
    }

    ###CREATORS###

    public static function createEntreprise($nom, $email, $description, $tel, $logo, $ville, $code_postal, $region, $pays){
        $pdo = Database::connect();
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $tel = Database::validateParams($tel);
        $description = trim($description);
        if (preg_match('/[^\p{L}\p{N}.,\'"()\- ]/u', $description)) {
            throw new Exception("Paramètre potentiellement dangereux détecté dans la description.");
        }
        $stmt = $pdo->prepare('INSERT INTO entreprise (nom, email, description, tel, logo) VALUES (:nom, :email, :description, :tel, :logo)');
        $stmt->execute([
            ':nom' => $nom, 
            ':email' => $email, 
            ':description' => $description, 
            ':tel' => $tel,
            ':logo' => $logo]);
        $lastId = $pdo->lastInsertId();
        $id_ville = Ville_model::getIdVille($ville, $code_postal, $region, $pays);
        Se_situe_model::createSeSitue($id_ville, $lastId);
        return self::getEntrepriseById($lastId);
    }

    ### DELETORS ###

    public static function deleteEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        Offre_model::deleteOffreByIdEntreprise($id_entreprise);
        Note_model::deleteNoteByIdEntreprise($id_entreprise);
        Se_situe_model::deleteSeSitueByIdEntreprise($id_entreprise);
        $stmt = $pdo->prepare('DELETE FROM entreprise WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':id_entreprise' => $id_entreprise]);
    }

    ### UPDATORS ###

    public static function updateNomEntreprise($id_entreprise, $nom){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $nom = Database::validateParams($nom);
        $stmt = $pdo->prepare('UPDATE entreprise SET nom = :nom WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':nom' => $nom, ':id_entreprise' => $id_entreprise]);
    }

    public static function updateEmailEntreprise($id_entreprise, $email){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $email = Database::validateParams($email);
        $stmt = $pdo->prepare('UPDATE entreprise SET email = :email WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':email' => $email, ':id_entreprise' => $id_entreprise]);
    }

    public static function updateDescriptionEntreprise($id_entreprise, $description){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $description = Database::validateParams($description);
        $stmt = $pdo->prepare('UPDATE entreprise SET description = :description WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':description' => $description, ':id_entreprise' => $id_entreprise]);
    }

    public static function updateTelEntreprise($id_entreprise, $tel){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $tel = Database::validateParams($tel);
        $stmt = $pdo->prepare('UPDATE entreprise SET tel = :tel WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':tel' => $tel, ':id_entreprise' => $id_entreprise]);
    }

    public static function updateVilleEntreprise($id_entreprise, $ville, $code_postal, $region, $pays){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $id_ville = Ville_model::getIdVille($ville, $code_postal, $region, $pays);
        Se_situe_model::updateSeSitue($id_entreprise, $id_ville);
        return self::getEntrepriseById($id_entreprise);
    }

    public static function updateLogoEntreprise($id_entreprise, $logo){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('UPDATE entreprise SET logo = :logo WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':logo' => $logo, ':id_entreprise' => $id_entreprise]);
    }
}