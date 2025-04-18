<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/gestion_question/controller/QuestionController.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/gestion_question/model/Question.php';

$questionController = new QuestionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = new Question(
        $_POST['id_question'],
        $_POST['titre'],
        $_POST['date_creation'],
        $_POST['contenu']
    );
    
    if ($questionController->updateQuestion($question)) {
        header("Location: questionne.php?success=1");
    } else {
        header("Location: questionne.php?error=1");
    }
    exit();
}