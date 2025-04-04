<?php

require_once __DIR__ . '/../config/database.php';   
require_once __DIR__ . '/se_situe.php';
require_once __DIR__ . '/ville.php';
require_once __DIR__ . '/offres.php';
require_once __DIR__ . '/note.php';


class Entreprise_model{

    ### GETTERS ####

    public static function getAllEntreprises(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM entreprise');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllEntreprisesWithVille(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM entreprise JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise JOIN ville ON se_situe.id_ville = ville.id_ville');
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

    public static function getEntrepriseByMotCle($motCle){
        $pdo = Database::connect();
        $motCle = Database::validateParams($motCle);
        $stmt = $pdo->prepare('SELECT * FROM entreprise WHERE nom LIKE :motCle OR description LIKE :motCle');
        $stmt->execute([':motCle' => '%' . $motCle . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllEntreprisesByVille($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('SELECT * FROM entreprise JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise JOIN ville ON se_situe.id_ville = ville.id_ville WHERE ville.ville = :ville');
        $stmt->execute([':ville' => $ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllEntreprisesByPays($pays){
        $pdo = Database::connect();
        $pays = Database::validateParams($pays);
        $stmt = $pdo->prepare('SELECT * FROM entreprise JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise JOIN ville ON se_situe.id_ville = ville.id_ville WHERE ville.pays = :pays');
        $stmt->execute([':pays' => $pays]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getAllEntreprisesByCodePostal($code_postal){
        $pdo = Database::connect();
        $code_postal = Database::validateParams($code_postal);
        $stmt = $pdo->prepare('SELECT * FROM entreprise JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise JOIN ville ON se_situe.id_ville = ville.id_ville WHERE ville.code_postal = :code_postal');
        $stmt->execute([':code_postal' => $code_postal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getAllEntreprisesSortByNom(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM entreprise ORDER BY nom');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllEntreprisesSortByVille(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM entreprise JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise JOIN ville ON se_situe.id_ville = ville.id_ville ORDER BY ville.ville');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getNomEntrepriseById($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('SELECT nom FROM entreprise WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise' => $id_entreprise]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }

    ###CREATORS###

    public static function createEntreprise($nom, $email, $description, $tel, $logo, $ville, $code_postal, $pays){
        $pdo = Database::connect();
        $nom = Database::validateParams($nom);
        $email = Database::validateParams($email);
        $tel = Database::validateParams($tel);
        $description = trim($description);
        if (preg_match('/[^\p{L}\p{N}.,\'"()\- ]/u', $description)) {
            throw new Exception("Paramètre potentiellement dangereux détecté dans la description.");
        }
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM entreprise WHERE nom = :nom');
        $stmt->execute([':nom' => $nom]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['COUNT(*)'] > 0) {
            throw new Exception("Une entreprise avec ce nom existe déjà.");
        }
        $stmt = $pdo->prepare('INSERT INTO entreprise (nom, email, description, tel, logo) VALUES (:nom, :email, :description, :tel, :logo)');
        $stmt->execute([
            ':nom' => $nom, 
            ':email' => $email, 
            ':description' => $description, 
            ':tel' => $tel,
            ':logo' => $logo]);
        $lastId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ville WHERE ville = :ville AND code_postal = :code_postal AND pays = :pays');
        $stmt->execute([
            ':ville' => $ville, 
            ':code_postal' => $code_postal,  
            ':pays' => $pays]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['COUNT(*)'] == 0) {
            Ville_model::createVille($ville, $code_postal, $pays);
        }
        $id_ville = Ville_model::getIdVille($ville, $code_postal, $pays);
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

    public static function updateVilleEntreprise($id_entreprise, $ville, $code_postal, $pays){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $id_ville = Ville_model::getIdVille($ville, $code_postal, $pays);
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