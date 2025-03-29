<?php
class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO('mysql:host=localhost;dbname=projetweb;charset=utf8', 'admin', 'pCwNrFqjy1C2y20MR527');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die(json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]));
            }
        }
        return self::$pdo;
    }

    public static function validateParams($param) {
        // Vérification que le paramètre est scalaire (int, string, float)
        if (!is_scalar($param)) {
            throw new Exception("Paramètre invalide : " . json_encode($param));
        }
    
        // Si le paramètre est une chaîne, on vérifie les caractères
        if (is_string($param)) {
            $param = trim($param);
            if (preg_match('/[^\p{L}\p{N}@. \-]/u', $param)) {  
                // \p{L} : Lettres (y compris accentuées)
                // \p{N} : Chiffres
                // @ . - : Autorisés explicitement
                throw new Exception("Paramètre potentiellement dangereux détecté : $param");
            }
        }
    
        return $param;
    }
    
    
}
?>
