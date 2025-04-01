<?php

require_once __DIR__ . '/../config/database.php';   
require_once __DIR__ . '/postule.php';
require_once __DIR__ . '/favoris.php';

class Offre_model{

    ### GETTERS ####

    public static function getAllOffres(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM offres');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOffresById($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $stmt = $pdo->prepare('SELECT * FROM offres WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOffreByTitre($titre){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $stmt = $pdo->prepare('SELECT * FROM offres WHERE titre = :titre');
        $stmt->execute([':titre' => $titre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOffreByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('SELECT * FROM offres WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise'=> $id_entreprise]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOffreByTypeContrat($type_contrat){
        $pdo = Database::connect();
        $type_contrat = Database::validateParams($type_contrat);
        $stmt = $pdo->prepare('SELECT * FROM offres WHERE type_contrat = :type_contrat');
        $stmt->execute([':type_contrat' => $type_contrat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOffreByMotCle($mot_cle){
        $pdo = Database::connect();
        $mot_cle = Database::validateParams($mot_cle);
        $stmt = $pdo->prepare('SELECT * FROM offres WHERE titre LIKE :mot_cle OR description LIKE :mot_cle');
        $stmt->execute([':mot_cle' => '%'.$mot_cle.'%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getIdOffre($titre, $description, $date_debut, $date_fin, $id_entreprise){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $description = Database::validateParams($description);
        $date_debut = Database::validateParams($date_debut);
        $date_fin = Database::validateParams($date_fin);
        $id_entreprise = Database::validateParams($id_entreprise);
        
        $stmt = $pdo->prepare('SELECT id_offre FROM offres WHERE titre = :titre AND description = :description AND date_debut = :date_debut AND date_fin = :date_fin AND id_entreprise = :id_entreprise');
        $stmt->execute([
            ':titre' => $titre, 
            ':description' => $description, 
            ':date_debut' => $date_debut, 
            ':date_fin' => $date_fin, 
            ':id_entreprise' => $id_entreprise]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_offre'];
    }

    public static function getAllOffreSortByNom(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM offres ORDER BY titre');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllOffresSortByNbCandid() {
        $pdo = Database::connect(); // Connexion à la base de données
        $stmt = $pdo->prepare('SELECT offres.*, entreprise.nom AS nom_entreprise, ville.nom AS nom_ville, 
            COUNT(postule.id_offre) AS nb_candidatures 
            FROM offres
            JOIN entreprise ON offres.id_entreprise = entreprise.id_entreprise
            JOIN se_situe ON entreprise.id_entreprise = se_situe.id_entreprise
            JOIN ville ON se_situe.id_ville = ville.id_ville
            LEFT JOIN postule ON offres.id_offre = postule.id_offre
            GROUP BY offres.id_offre
            ORDER BY nb_candidatures DESC;'); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function getAllOffresSortByLocalisation(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM offres ORDER BY ville');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllOffresSortByAvgNote(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM offres LEFT JOIN note ON offres.id_entreprise = note.id_entreprise GROUP BY offres.id_offre ORDER BY AVG(note.note) DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ### CREATORS ###

    public static function createOffre($titre, $description, $date_debut, $date_fin, $id_entreprise, $type_contrat, $salaire){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $description = Database::validateParams($description);
        $date_debut = Database::validateParams($date_debut);
        $date_fin = Database::validateParams($date_fin);
        $id_entreprise = Database::validateParams($id_entreprise);
        $type_contrat = Database::validateParams($type_contrat);
        
        $stmt = $pdo->prepare('INSERT INTO offres (titre, description, date_debut, date_fin, id_entreprise, type_contrat, salaire) VALUES (:titre, :description, :date_debut, :date_fin, :id_entreprise, :type_contrat, :salaire)');
        $stmt->execute([
            ':titre' => $titre, 
            ':description' => $description, 
            ':date_debut' => $date_debut, 
            ':date_fin' => $date_fin, 
            ':id_entreprise' => $id_entreprise,
            ':type_contrat' => $type_contrat,
            ':salaire' => $salaire]);
        $lastId = $pdo->lastInsertId();
        return self::getOffresById($lastId);
    }

    ### DELETORS ###

    public static function deleteOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        Postule_model::deletePostuleByIdOffre($id_offre);
        Favoris_model::deleteFavorisByIdOffre($id_offre);
        $stmt = $pdo->prepare('DELETE FROM offres WHERE id_offre = :id_offre');
        return $stmt->execute([':id_offre' => $id_offre]);
    }

    public static function deleteOffreByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('DELETE FROM offres WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':id_entreprise' => $id_entreprise]);
    }

    ### UPDATORS ###

    public static function updateTitreOffre($id_offre, $titre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $titre = Database::validateParams($titre);
        $stmt = $pdo->prepare('UPDATE offres SET titre = :titre WHERE id_offre = :id_offre');
        return $stmt->execute([':titre' => $titre, ':id_offre' => $id_offre]);
    }
    
    public static function updateDescriptionOffre($id_offre, $description){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $description = Database::validateParams($description);
        $stmt = $pdo->prepare('UPDATE offres SET description = :description WHERE id_offre = :id_offre');
        return $stmt->execute([':description' => $description, ':id_offre' => $id_offre]);
    }

    public static function updateDateDebutOffre($id_offre, $date_debut){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $date_debut = Database::validateParams($date_debut);
        $stmt = $pdo->prepare('UPDATE offres SET date_debut = :date_debut WHERE id_offre = :id_offre');
        return $stmt->execute([':date_debut' => $date_debut, ':id_offre' => $id_offre]);
    }

    public static function updateDateFinOffre($id_offre, $date_fin){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $date_fin = Database::validateParams($date_fin);
        $stmt = $pdo->prepare('UPDATE offres SET date_fin = :date_fin WHERE id_offre = :id_offre');
        $stmt->execute([':date_fin' => $date_fin, ':id_offre' => $id_offre]);
    }

    public static function updateIdEntrepriseOffre($id_offre, $id_entreprise){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
        $stmt = $pdo->prepare('UPDATE offres SET id_entreprise = :id_entreprise WHERE id_offre = :id_offre');
        return $stmt->execute([':id_entreprise' => $id_entreprise, ':id_offre' => $id_offre]);
    }

    public static function updateTypeContratOffre($id_offre, $type_contrat){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $type_contrat = Database::validateParams($type_contrat);
        $stmt = $pdo->prepare('UPDATE offres SET type_contrat = :type_contrat WHERE id_offre = :id_offre');
        return $stmt->execute([':type_contrat' => $type_contrat, ':id_offre' => $id_offre]);
    }

    public static function updateSalaireOffre($id_offre, $salaire){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $salaire = Database::validateParams($salaire);
        $stmt = $pdo->prepare('UPDATE offres SET salaire = :salaire WHERE id_offre = :id_offre');
        return $stmt->execute([':salaire' => $salaire, ':id_offre' => $id_offre]);
    }
}