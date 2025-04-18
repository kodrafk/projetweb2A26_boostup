<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'C:/xampp/htdocs/gestion_question/controller/QuestionController.php';
require_once 'C:/xampp/htdocs/gestion_question/model/Question.php';
$questionController = new QuestionController();

// Traitement de la suppression
if (isset($_GET['delete_id'])) {
    if ($questionController->deleteQuestion($_GET['delete_id'])) {
        header("Location: afficherquestion.php?success=La question a été supprimée");
        exit();
    } else {
        header("Location: afficherquestion.php?error=Erreur lors de la suppression");
        exit();
    }
}

// Récupérer toutes les questions
$questions = $questionController->getQuestions();

// Récupérer la question à modifier si ID présent
$questionToEdit = null;
if (isset($_GET['edit_id'])) {
    $questionToEdit = $questionController->getQuestionById($_GET['edit_id']);
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $question = new Question(
        $_POST['id_question'],
        $_POST['titre'],
        $_POST['date_creation'],
        $_POST['contenu']
    );
    
    if($questionController->updateQuestion($question)) {
        header("Location: afficherquestion.php?success=Question modifiée avec succès");
        exit();
    } else {
        $error = "Erreur lors de la modification de la question";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/afficher.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Communauté - BoostUp</title>
  <style>
    /* Styles de base */
    .questions-list {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .questions-list h3 {
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      color: #1e293b;
      font-weight: 600;
    }
    
    .question-item {
      padding: 1.5rem 0;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .question-item:last-child {
      border-bottom: none;
    }
    
    .question-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }
    
    .question-title {
      font-weight: 600;
      color: #1e293b;
    }
    
    .question-date {
      color: #64748b;
      font-size: 0.875rem;
    }
    
    .question-content {
      color: #475569;
      line-height: 1.6;
      margin-bottom: 1rem;
    }
    
    /* Styles des boutons d'action */
    .question-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1rem;
    }
    
    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s;
      border: 1px solid transparent;
    }
    
    .delete-btn {
      background-color: #fee2e2;
      color: #dc2626;
      border-color: #fecaca;
    }
    
    .delete-btn:hover {
      background-color: #fecaca;
    }
    
    .edit-btn {
      background-color: #dbeafe;
      color: #2563eb;
      border-color: #bfdbfe;
    }
    
    .edit-btn:hover {
      background-color: #bfdbfe;
    }
    
    /* Formulaire de modification */
    .edit-form-container {
      display: <?= isset($_GET['edit_id']) ? 'block' : 'none' ?>;
      background: #f8fafc;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      border: 1px solid #e2e8f0;
    }
    
    .edit-form-container h4 {
      margin-top: 0;
      color: #1e293b;
      font-size: 1.25rem;
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #334155;
    }
    
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      font-size: 1rem;
    }
    
    .form-group textarea {
      min-height: 150px;
    }
    
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
      margin-top: 1rem;
    }
    
    .btn {
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      border: 1px solid transparent;
    }
    
    .btn-cancel {
      background-color: #e2e8f0;
      color: #334155;
    }
    
    .btn-cancel:hover {
      background-color: #cbd5e1;
    }
    
    .btn-submit {
      background-color: #2563eb;
      color: white;
    }
    
    .btn-submit:hover {
      background-color: #1d4ed8;
    }
    
    /* Messages d'alerte */
    .alert {
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1.5rem;
    }
    
    .alert-success {
      background-color: #dcfce7;
      color: #166534;
      border: 1px solid #bbf7d0;
    }
    
    .alert-error {
      background-color: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }
  </style>
</head>
<body>
  <div class="questions-list">
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    
    <h3>Questions récentes</h3>
    
    <!-- Formulaire de modification -->
    <div class="edit-form-container">
      <?php if ($questionToEdit): ?>
      <form method="POST" action="afficherquestion.php">
        <h4>Modifier la question</h4>
        <input type="hidden" name="id_question" value="<?= $questionToEdit->getId_question() ?>">
        <input type="hidden" name="date_creation" value="<?= $questionToEdit->getDate_creation() ?>">
        
        <div class="form-group">
          <label>Titre</label>
          <input type="text" name="titre" value="<?= htmlspecialchars($questionToEdit->getTitre()) ?>" required>
        </div>
        
        <div class="form-group">
          <label>Contenu</label>
          <textarea name="contenu" required><?= htmlspecialchars($questionToEdit->getContenu()) ?></textarea>
        </div>
        
        <div class="form-actions">
          <a href="afficherquestion.php" class="btn btn-cancel">Annuler</a>
          <button type="submit" name="update" class="btn btn-submit">Enregistrer</button>
        </div>
      </form>
      <?php endif; ?>
    </div>
    
    <!-- Liste des questions -->
    <?php if (!empty($questions)): ?>
      <?php foreach ($questions as $question): ?>
      <div class="question-item">
        <div class="question-header">
          <span class="question-title"><?= htmlspecialchars($question['titre']) ?></span>
          <span class="question-date"><?= date('d/m/Y H:i', strtotime($question['date_creation'])) ?></span>
        </div>
        <div class="question-content">
          <?= nl2br(htmlspecialchars($question['contenu'])) ?>
        </div>
        <div class="question-actions">
          <a href="afficherquestion.php?delete_id=<?= $question['id_question'] ?>" 
             class="action-btn delete-btn"
             onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question?')">
            <i class="fas fa-trash-alt"></i> Supprimer
          </a>
          <a href="afficherquestion.php?edit_id=<?= $question['id_question'] ?>" 
             class="action-btn edit-btn">
            <i class="fas fa-edit"></i> Modifier
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center; color: #64748b;">Aucune question pour le moment</p>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Masquer le formulaire si on clique sur Annuler
      document.querySelector('.btn-cancel')?.addEventListener('click', function() {
        document.querySelector('.edit-form-container').style.display = 'none';
      });
    });
  </script>
</body>
</html>