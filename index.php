<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'controller/ProjetController.php';
require_once __DIR__ . '/vendor/autoload.php';


// Route normale des actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new ProjetController();

    switch ($action) {
        case 'ajouter':
            $controller->ajouter();
            break;
        case 'ajouterF':
            $controller->ajouterF();
            break;
        case 'modifierProjet':
            $controller->modifierProjet();
            break;
        case 'genererDescriptionAjax':
            $controller->genererDescriptionAjax();
            break;
        default:
            echo "Action non reconnue.";
            break;
    }
}
?>
