<?php

require_once __DIR__ . '/../config/database.php';

class Ticket_model{

    ### GETTERS ####

    public static function getAllTickets(){
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM ticket');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTicketById($id_ticket){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE id_ticket = :id_ticket');
        $stmt->execute([':id_ticket' => $id_ticket]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTicketByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE id_user = :id_user');
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTicketByTitre($titre){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE titre = :titre');
        $stmt->execute([':titre' => $titre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTicketByDateCreation($date_creation){
        $pdo = Database::connect();
        $date_creation = Database::validateParams($date_creation);
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE date_creation = :date_creation');
        $stmt->execute([':date_creation' => $date_creation]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTicketByEtat($etat){
        $pdo = Database::connect();
        $etat = Database::validateParams($etat);
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE etat = :etat');
        $stmt->execute([':etat' => $etat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTicketByDescription($description){
        $pdo = Database::connect();
        $description = Database::validateParams($description);
        $stmt = $pdo->prepare('SELECT * FROM ticket WHERE description LIKE :description');
        $stmt->execute([':description' => '%'.$description.'%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ### CREATORS ###

    public static function createTicket($titre, $description, $date_creation, $etat, $id_user){
        $pdo = Database::connect();
        $titre = Database::validateParams($titre);
        $description = Database::validateParams($description);
        $date_creation = Database::validateParams($date_creation);
        $etat = Database::validateParams($etat);
        $id_user = Database::validateParams($id_user);

        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }

        $stmt = $pdo->prepare('INSERT INTO ticket (titre, description, date_creation, etat, id_user) VALUES (:titre, :description, :date_creation, :etat, :id_user)');
        return $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':date_creation' => $date_creation,
            ':etat' => $etat,
            ':id_user' => $id_user
        ]);
    }

    ### DELETORS ###

    public static function deleteTicket($id_ticket){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $stmt = $pdo->prepare('DELETE FROM ticket WHERE id_ticket = :id_ticket');
        return $stmt->execute([':id_ticket' => $id_ticket]);
    }

    public static function deleteTicketByIdUser($id_user){
        $pdo = Database::connect();
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('DELETE FROM ticket WHERE id_user = :id_user');
        return $stmt->execute([':id_user' => $id_user]);
    }

    ### UPDATORS ###

    public static function updateTitreTicket($id_ticket, $titre){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $titre = Database::validateParams($titre);
        $stmt = $pdo->prepare('UPDATE ticket SET titre = :titre WHERE id_ticket = :id_ticket');
        return $stmt->execute([':titre' => $titre, ':id_ticket' => $id_ticket]);
    }

    public static function updateDateCreationTicket($id_ticket, $date_creation){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $date_creation = Database::validateParams($date_creation);
        $stmt = $pdo->prepare('UPDATE ticket SET date_creation = :date_creation WHERE id_ticket = :id_ticket');
        return $stmt->execute([':date_creation' => $date_creation, ':id_ticket' => $id_ticket]);
    }

    public static function updateEtatTicket($id_ticket, $etat){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $etat = Database::validateParams($etat);
        $stmt = $pdo->prepare('UPDATE ticket SET etat = :etat WHERE id_ticket = :id_ticket');
        return $stmt->execute([':etat' => $etat, ':id_ticket' => $id_ticket]);
    }

    public static function updateDescriptionTicket($id_ticket, $description){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $description = Database::validateParams($description);
        $stmt = $pdo->prepare('UPDATE ticket SET description = :description WHERE id_ticket = :id_ticket');
        return $stmt->execute([':description' => $description, ':id_ticket' => $id_ticket]);
    }

    public static function updateIdUserTicket($id_ticket, $id_user){
        $pdo = Database::connect();
        $id_ticket = Database::validateParams($id_ticket);
        if (!is_numeric($id_ticket)) {
            throw new Exception('ID de ticket invalide : $id_ticket');
        }
        $id_user = Database::validateParams($id_user);
        if (!is_numeric($id_user)) {
            throw new Exception('ID d\'utilisateur invalide : $id_user');
        }
        $stmt = $pdo->prepare('UPDATE ticket SET id_user = :id_user WHERE id_ticket = :id_ticket');
        return $stmt->execute([':id_user' => $id_user, ':id_ticket' => $id_ticket]);
    }
}