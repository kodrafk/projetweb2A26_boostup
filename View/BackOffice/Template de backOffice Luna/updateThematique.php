<?php
session_start();
include_once("../../../config.php");
require_once("../../../controller/ThematiqueC.php");

$thematiqueC = new ThematiqueC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_thematique'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    $result = $thematiqueC->updateThematique($id, $titre, $description);
    header('Location: thematique.php');
    exit();
}
?>