<!--?php
function getDB() {
    $host = 'localhost';
    $dbname = 'projetweb';
    $username = 'root';
$password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?-->

 
<?php

class config
{
   private static $pdo = null;

   public static function getConnexion()
   {
         if (!isset(self::$pdo)) {
             try{
                    self::$pdo = new PDO(
                          'mysql:host=localhost;dbname=projetweb',
                          'root',
                           '' ,
                           [
                               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                               PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                           ]
                     );

              //echo "connected successfully";
            } catch (Exception $r) {
                 die('Erreur: ' . $r->getMessage());
            }
        }
         return self::$pdo;

    }
}
 config::getConnexion();
?>