<?php
// Inclure la connexion à la base de données
include 'config.php';

// Vérifier si l'ID de la tâche est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $sql = "DELETE FROM tache WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Lier l'ID
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Rediriger vers la liste des tâches après la suppression
        header("Location: tache.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la tâche.";
    }
} else {
    echo "ID de tâche manquant.";
}
?>
