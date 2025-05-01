<?php
require_once(__DIR__ . '/../../../controller/ThematiqueC.php');
require_once(__DIR__ . '/../../../config.php');

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $thematiqueC = new ThematiqueC();
    $thematiqueC->deleteThematique($id);
    header("Location: thematique.php");
    exit();
}
?>
