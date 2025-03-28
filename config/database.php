<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO('mysql:host=localhost;dbname=projetweb', 'admin', 'pCwNrFqjy1C2y20MR527');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die(json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]));
            }
        }
        return self::$pdo;
    }

    public static function validateParams($params) {
        foreach ($params as $key => $param) {
            if (!is_scalar($param)) {
                throw new Exception("Paramètre invalide : $key => " . json_encode($param));
            }

            if (is_string($param)) {
                $param = trim($param);
                if (preg_match('/[^\w@. -]/', $param)) { // Autorise lettres, chiffres, _, @, ., espace, -
                    throw new Exception("Paramètre potentiellement dangereux détecté : $param");
                }
            }
        }
        return $params;
    }
}
?>
