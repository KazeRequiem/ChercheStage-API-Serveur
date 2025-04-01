<?php

require_once __DIR__ . '/../config/database.php';   

class Note_model{

    ### GETTERS ####

    public static function getAllNotes(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM note');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getNoteByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $stmt = $pdo->prepare('SELECT * FROM note WHERE id_entreprise = :id_entreprise');
        $stmt->execute($id_entreprise) ;
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNoteByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('SELECT * FROM note WHERE id_user = :id_user');
        $stmt->execute($id_user) ;
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNoteById($id_entreprise, $id_user){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('SELECT * FROM note WHERE id_entreprise = :id_entreprise AND id_user = :id_user');
        $stmt->execute([
            ':id_entreprise' => $id_entreprise, 
            ':id_user' => $id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getMoyenneEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $stmt = $pdo->prepare('SELECT AVG(note) as moyenne FROM note WHERE id_entreprise = :id_entreprise');
        $stmt->execute([':id_entreprise' => $id_entreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['moyenne'];
    }

    ### CREATORS ###

    public static function createNote($id_entreprise, $id_user, $note) {
        $pdo = Database::connect();
        
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception("ID d'entreprise invalide : $id_entreprise");
        }
    
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception("ID d'utilisateur invalide : $id_user");
        }
    
        $note = Database::validateParams($note);
        if (!is_numeric($note)) {
            throw new Exception("Note invalide : $note");
        }
        $stmt = $pdo->prepare("INSERT INTO note (id_entreprise, id_user, `note`) VALUES (:id_entreprise, :id_user, :note)");
        
        $stmt->execute([
            ':id_entreprise' => $id_entreprise, 
            ':id_user' => $id_user, 
            ':note' => $note
        ]);
    
        return self::getNoteById($id_entreprise, $id_user);
    }
    

    ### DELETORS ###

    public static function deleteNote($id_entreprise, $id_user){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('DELETE FROM note WHERE id_entreprise = :id_entreprise AND id_user = :id_user');
        return $stmt->execute([
            ':id_entreprise' => $id_entreprise, 
            ':id_user' => $id_user]);
    }

    public static function deleteNoteByIdEntreprise($id_entreprise){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $stmt = $pdo->prepare('DELETE FROM note WHERE id_entreprise = :id_entreprise');
        return $stmt->execute([':id_entreprise' => $id_entreprise]);
    }

    public static function deleteNoteByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('DELETE FROM note WHERE id_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }

    ### UPDATORS ###

    public static function updateNote($id_entreprise, $id_user, $note){
        $pdo = Database::connect();
        $id_entreprise = Database::validateParams($id_entreprise);
        if (!is_numeric($id_entreprise)) {
            throw new Exception('ID d\'entreprise invalide : $id_entreprise');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $note = Database::validateParams($note);
        if (!is_numeric($note)) {
            throw new Exception('Note invalide : $note');
        }
        $stmt = $pdo->prepare('UPDATE note SET note = :note WHERE id_entreprise = :id_entreprise AND id_user = :id_user');
        return $stmt->execute([
            ':note' => $note, 
            ':id_entreprise' => $id_entreprise, 
            ':id_user' => $id_user]);
    }

}