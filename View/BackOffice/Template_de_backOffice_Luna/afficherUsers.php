<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header('Location: ../../../View/FrontOffice/login.php');
    exit();
}

require_once(__DIR__ . '/../../../Controller/UserC.php');
require_once(__DIR__ . '/../../../config.php');

// Vérifier si un paramètre 'supprimer' est passé en URL
if (isset($_GET['supprimer'])) {
    // Récupérer l'ID de la ressource à supprimer
    $id = $_GET['supprimer'];

    // Créer une instance de RessourceC pour interagir avec la base de données
    $userC = new UserC();

    // Appeler la méthode de suppression de la ressource
    $userC->supprimerUser($id);

    // Redirection après suppression
    header("Location: index.php");
    exit();
}
?>
