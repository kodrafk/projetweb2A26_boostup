<?php
require_once 'C:/xampp/htdocs/gestion_question/controller/QuestionController.php';

if (isset($_GET['id'])) {
    $questionController = new QuestionController();
    if ($questionController->deleteQuestion($_GET['id'])) {
        header("Location: question1.php?success=1");
    } else {
        header("Location: question1.php?error=1");
    }
    exit();
}
header("Location: question1.php");