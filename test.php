<?php

require_once 'config/database.php';

require_once 'models/entreprise.php';
require_once 'models/favoris.php';
require_once 'models/note.php';
require_once 'models/offre.php';
require_once 'models/postule.php';
require_once 'models/promotion.php';
require_once 'models/se_situe.php';
require_once 'models/user.php';
require_once 'models/ville.php';

if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL extension not loaded');
}

try {

    //User_model::createUser('Yanis','Barral','yanis.barral@domain.fr','test123','0745621458','2006-09-07',0,1);
    //User_model::createUser('Baptiste','Noisette','baptiste.noisette@domain.fr','test123','0612345678','2005-12-12',2,2);
    //User_model::createUser('Sab','Carp','sab.carp@domain.fr','test123','0687654321','2003-03-25',1,2);
    //print_r(User_model::getAllUsers());
    //print_r(User_model::getUserById(3));

    //print_r(Promotion_model::getPromotionById(1));
    //print_r("Tous les membres de la promotion 1 : ");
    //print_r(Promotion_model::getAllMembersOfAPromotion(1));
    //print_r(Promotion_model::getAllPromotions());
    //print_r(Favoris_model::getNbFavorisByIdUser(1));

    Entreprise_model::createEntreprise('Thales','thales.rh@domain.com',"Thales est une entreprise internationale spécialisée dans les technologies de pointe, opérant dans les secteurs de la défense, de l'aérospatiale, de la sécurité et du transport. Elle conçoit des solutions innovantes pour les gouvernements, les industries et les entreprises, offrant des systèmes de communication, de cybersécurité, d'intelligence artificielle et de gestion de l'information à travers le monde.","0123456789",'chemin vers logo','Paris','75000','Ile-de-France','France');

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
