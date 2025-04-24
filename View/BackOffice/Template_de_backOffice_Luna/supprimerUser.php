<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header('Location: ../../../View/FrontOffice/login.php');
    exit();
}


require_once(__DIR__ . '/../../../config.php');

include '../../../Controller/UserC.php';

// Vérifie si l'ID de l'utilisateur est bien envoyé par la méthode POST
if (isset($_POST['iduser'])) {
    $id = $_POST['iduser'];
    
    $UserC = new UserC();
    $UserC->supprimerUser($id);

    // Redirection vers la page principale
    header("Location: index.php"); // Remplace par ta vraie page d'affichage des utilisateurs
    exit();
} else {
    echo "Erreur : Aucun ID reçu pour suppression.";
}
?>
