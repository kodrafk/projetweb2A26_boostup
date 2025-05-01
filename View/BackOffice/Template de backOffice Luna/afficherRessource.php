<?php
require_once(__DIR__ . '/../../../Controller/RessourceC.php');
//include_once '../../config.php';

require_once(__DIR__ . '/../../../config.php');



if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $ressourceC = new RessourceC();
    $ressourceC->deleteRessource($id);
    header("Location: ressource.php");
    exit();
}
?>
