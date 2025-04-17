<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin absolu vers la configuration
include_once($_SERVER['DOCUMENT_ROOT'].'/BoostUp/config/database.php');

if(isset($_GET['id'])) {
    try {
        $db = getDB();
        $id = $_GET['id'];
        
        $sql = "DELETE FROM Projet WHERE id_projet = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            header("Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php?message=supprime");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php?message=erreur");
        exit();
    }
}
?>