<?php
$host = 'localhost';
$dbname = 'tache_objectif_db'; // Le nom de la base que tu as créée
$user = 'root';
$pass = ''; // Mot de passe vide par défaut dans XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Pour voir les erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
