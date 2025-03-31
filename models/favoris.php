<?php

require_once __DIR__ . '/../config/database.php';   


class Favoris_model{

    ### GETTERS ####

    public static function getAllFavoris(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM favoris');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFavorisByIdOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception("ID d'offre invalide : $id_offre");
        }
        $stmt = $pdo->prepare('SELECT * FROM favoris WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFavorisByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
        $stmt = $pdo->prepare('SELECT * FROM favoris WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFavorisById($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception('ID d\'offre invalide : $id_offre');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('SELECT * FROM favoris WHERE id_offre = :id_offre AND id_user = :id_user');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNbFavorisByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('SELECT COUNT(*) AS nbFavorisUser FROM favoris WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nbFavorisUser'];
    }

    public static function getNbFavorisByIdOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception('ID d\'offre invalide : $id_offre');
        }
        $stmt = $pdo->prepare('SELECT COUNT(*) as nbFavorisOffre FROM favoris WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nbFavorisOffre'];
    }

    ### CREATORS ###

    public static function createFavoris($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception('ID d\'offre invalide : $id_offre');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('INSERT INTO favoris (id_offre, id_user) VALUES (:id_offre, :id_user)');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
        return self::getFavorisById($id_offre, $id_user);
    }

    ### DELETORS ###

    public static function deleteFavoris($id_offre, $id_user){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception('ID d\'offre invalide : $id_offre');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('DELETE FROM favoris WHERE id_offre = :id_offre AND id_user = :id_user');
        $stmt->execute([
            ':id_offre' => $id_offre, 
            ':id_user' => $id_user]);
    }

    public static function deleteFavorisByIdOffre($id_offre){
        $pdo = Database::connect();
        $id_offre = Database::validateParams($id_offre);
        if (!is_numeric($id_offre)) {
            throw new Exception('ID d\'offre invalide : $id_favoris');
        }
        $stmt = $pdo->prepare('DELETE FROM favoris WHERE id_offre = :id_offre');
        $stmt->execute([':id_offre' => $id_offre]);
    }

    public static function deleteFavorisByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('DELETE FROM favoris WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
    }

    ### UPDATORS ###
    // Pas besoin d'updater un favoris, on le supprime et on le recrée, on ne modifie pas une entreprise ou un utilisateur car favoris personnel
}