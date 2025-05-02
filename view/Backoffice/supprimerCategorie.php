<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'].'/BoostUp/config/database.php');

if(isset($_GET['id'])) {
    try {
        $db = getDB();
        $id = $_GET['id'];

        $sql = "DELETE FROM categorie WHERE id_categorie = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if($stmt->execute()) {
            header("Location: /BoostUp/view/Backoffice/template/Template_Luna/Categorie.php?message=supprime");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: /BoostUp/view/Backoffice/template/Template_Luna/Categorie.php?message=erreur");
        exit();
    }
}
