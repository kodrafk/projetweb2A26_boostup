<?php
// Exemple de fichier de connexion à la base de données

$host = 'localhost'; // Hôte de la base de données (généralement 'localhost' pour XAMPP)
$dbname = 'categorie'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL (généralement 'root' pour XAMPP)
$password = ''; 
try {
    // Création de la connexion PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gérer l'erreur de connexion
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    die();
}

return $db;
?>
