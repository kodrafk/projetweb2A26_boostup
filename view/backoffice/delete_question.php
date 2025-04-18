<?php
require_once __DIR__ . '/../../controller/QuestionController.php';

if (isset($_GET['id'])) {
    $questionController = new QuestionController();
    if ($questionController->deleteQuestion($_GET['id'])) {
        header("Location: questionne.php?success=1");
    } else {
        header("Location: questionne.php?error=1");
    }
} else {
    header("Location: questionne.php");
}
exit();