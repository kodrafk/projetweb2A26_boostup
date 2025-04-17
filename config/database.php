<?php
// Configurations de la base de données
$host = 'localhost';
$dbname = 'projet'; // Nom de ta base de données
$username = 'root'; // Utilisateur de la base de données (peut être 'root' ou autre)
$password = ''; // Mot de passe de l'utilisateur de la base de données

// Fonction pour obtenir une connexion PDO
function getDB() {
    global $host, $dbname, $username, $password;
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        die();
    }
}
?>
