<?php
// Inclure la connexion à la base de données
include 'config.php';

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $status = $_POST['status'];
    $date_limite = $_POST['date_limite'];
    $description = $_POST['description'];

    // Validation simple des données
    if (!empty($nom) && !empty($status) && !empty($date_limite) && !empty($description)) {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO tache (nom, status, date_echeance, description) VALUES (:nom, :status, :date_echeance, :description)";
        $stmt = $pdo->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':date_echeance', $date_limite);
        $stmt->bindParam(':description', $description);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Tâche ajoutée avec succès !";
            header("Location: tache.php"); // Redirige vers la liste des tâches après l'ajout
            exit();
        } else {
            echo "Erreur lors de l'ajout de la tâche.";
        }
    } else {
        echo "Tous les champs doivent être remplis.";
    }
}
?>
