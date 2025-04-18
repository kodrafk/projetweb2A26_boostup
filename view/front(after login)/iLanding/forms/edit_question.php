<?php
require_once 'C:/xampp/htdocs/gestion_question/controller/QuestionController.php';
require_once 'C:/xampp/htdocs/gestion_question/model/Question.php';

$questionController = new QuestionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = new Question(
        $_POST['id_question'],
        $_POST['titre'],
        $_POST['date_creation'],
        $_POST['contenu']
    );
    
    if ($questionController->updateQuestion($question)) {
        header("Location: question1.php?success=1");
        exit();
    }
}

$question = null;
if (isset($_GET['id'])) {
    $question = $questionController->getQuestionById($_GET['id']);
}

if (!$question) {
    header("Location: question1.php");
    exit();
}
?>

<!-- Créez ici un formulaire de modification similaire à votre formulaire d'ajout -->