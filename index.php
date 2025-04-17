<?php
require_once 'Controller/ProjetController.php';

$controller = new ProjetController();

$action = $_GET['action'] ?? 'afficher';

switch ($action) {
    case 'ajouter':
        $controller->ajouter();
        //header('Location: /view/Backoffice/template/Template_Luna/Projet.php ');
        break;
    case 'ajouterF':
        $controller->ajouterF();
        break;
    case 'modifier':
        $controller->modifier();
        break;
    case 'supprimer':
        $controller->supprimer();
        break;
    case 'afficher':
    default:
        $controller->afficher();
        break;
}
?>
