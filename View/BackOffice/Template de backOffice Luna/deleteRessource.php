<?php

require_once(__DIR__ . '/../../../config.php');

include '../../../Controller/RessourceC.php';

// Vérifie si l'ID est bien envoyé par la méthode POST
if (isset($_POST['id_ressource'])) {
    $id = $_POST['id_ressource'];
    
    $RessourceC = new RessourceC();
    $RessourceC->deleteRessource($id);

    // Redirection vers la page principale
    header("Location: ressource.php"); // Remplace par ta vraie page d'affichage
    exit();
} else {
    echo "Erreur : Aucun ID reçu pour suppression.";
}
?>
