<?php

require_once 'models/user.php';
require_once 'models/promotion.php';

if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL extension not loaded');
}

try {

    //User_model::createUser('Yanis','Barral','yanis.barral@domain.fr','test123','0745621458','2006-09-07',0,1);
    //User_model::createUser('Baptiste','Noisette','baptiste.noisette@domain.fr','test123','0612345678','2005-12-12',2,2);
    //User_model::createUser('Sab','Carp','sab.carp@domain.fr','test123','0687654321','2003-03-25',1,2);
    print_r(User_model::getAllUsers());
    //print_r(User_model::getUserById(3));

    print_r(Promotion_model::getPromotionById(1));
    print_r("Tous les membres de la promotion 1 : ");
    print_r(Promotion_model::getAllMembersOfAPromotion(1));
    print_r(Promotion_model::getAllPromotions());
    print_r(Favoris_model::getNbFavorisByIdUser(1));
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
