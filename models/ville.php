<?php

require_once 'config/database.php';

class Ville_model{

    ### GETTERS ####

    public static function getAllVilles(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM ville');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesById($id){
        $pdo = Database::connect();
        $id = Database::validateParams($id);
        if (!is_numeric($id)) {
            throw new Exception("ID de ville invalide : $id");
        }
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesByName($ville){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE nom_ville = :nom_ville');
        $stmt->execute([':nom_ville' => $ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesByCodePostal($code_postal){
        $pdo = Database::connect();
        $code_postal = Database::validateParams($code_postal);
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE code_postal = :code_postal');
        $stmt->execute([':code_postal' => $code_postal]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesByRegion($region){
        $pdo = Database::connect();
        $region = Database::validateParams($region);
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE region = :region');
        $stmt->execute([':region' => $region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllVillesByCountry($pays){
        $pdo = Database::connect();
        $pays = Database::validateParams($pays);
        $stmt = $pdo->prepare('SELECT * FROM ville WHERE pays = :pays');
        $stmt->execute([':pays' => $pays]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getIdVille($ville, $code_postal, $region, $pays){
        $pdo = Database::connect();
        $ville = Database::validateParams($ville);
        $stmt = $pdo->prepare('SELECT id_ville FROM ville WHERE ville = :ville AND code_postal = :code_postal AND region = :region AND pays = :pays');
        $stmt->execute([':ville' => $ville, ':code_postal' => $code_postal, ':region' => $region, ':pays' => $pays]);
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_ville'];
    }

    ### CREATOR ###

    public static function createVille($ville, $code_postal, $region, $pays){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('INSERT INTO ville (ville, code_postal, region, pays) VALUES (:ville, :code_postal, :region, :pays)');
        return $stmt->execute([':ville' => $ville, ':code_postal' => $code_postal, ':region' => $region, ':pays' => $pays]);
    }

    ### DELETORS ###

    public static function deleteVille($id_ville){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE se_situe SET id_ville = NULL WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id_ville]);
        $stmt = $pdo->prepare('DELETE FROM ville WHERE id_ville = :id_ville');
        $stmt->execute([':id_ville' => $id_ville]);
    }

    ### UPDATORS ###

    public static function updateVille($id_ville, $ville){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $ville = Database::validateParams($ville);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE ville SET ville = :ville WHERE id_ville = :id_ville');
        return $stmt->execute([':ville' => $ville, ':id_ville' => $id_ville]);
    }

    public static function updateCodePostal($id_ville, $code_postal){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $code_postal = Database::validateParams($code_postal);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE ville SET code_postal = :code_postal WHERE id_ville = :id_ville');
        return $stmt->execute([':code_postal' => $code_postal, ':id_ville' => $id_ville]);
    }

    public static function updateRegion($id_ville, $region){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $region = Database::validateParams($region);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE ville SET region = :region WHERE id_ville = :id_ville');
        return $stmt->execute([':region' => $region, ':id_ville' => $id_ville]);
    }

    public static function updatePays($id_ville, $pays){
        $pdo = Database::connect();
        $id_ville = Database::validateParams($id_ville);
        $pays = Database::validateParams($pays);
        if (!is_numeric($id_ville)) {
            throw new Exception("ID de ville invalide : $id_ville");
        }
        $stmt = $pdo->prepare('UPDATE ville SET pays = :pays WHERE id_ville = :id_ville');
        return $stmt->execute([':pays' => $pays, ':id_ville' => $id_ville]);
    }
}