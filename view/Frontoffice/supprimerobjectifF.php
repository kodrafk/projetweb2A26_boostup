<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
try {
    $conn = new PDO('mysql:host=localhost;dbname=nom_de_votre_base', 'nom_utilisateur', 'mot_de_passe');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifiez si l'ID de l'objectif est passé
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Supprimer l'objectif de la base de données
    $query = $conn->prepare("DELETE FROM objectifs WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    // Rediriger après suppression
    header('Location: objectifF.php?message=supprime');
    exit;
} else {
    // Si aucun ID n'est fourni, rediriger avec un message d'erreur
    header('Location: objectifF.php?message=erreur');
    exit;
}
?>