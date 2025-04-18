<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'C:/xampp/htdocs/gestion_question/controller/QuestionController.php';
require_once 'C:/xampp/htdocs/gestion_question/model/Question.php';
$questionController = new QuestionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    
    if(empty($titre) || empty($contenu)) {
        $error = "Tous les champs sont obligatoires";
    } else {
        $question = new Question(
            null,
            $titre,
            date('Y-m-d H:i:s'),
            $contenu
        );
        
        if($questionController->addQuestion($question)) {
            header("Location: question1.php?success=1");
            exit();
        } else {
            $error = "Erreur lors de l'ajout de la question";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter une question - BoostUp</title>
  <style>
    /* Reset de base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Roboto', sans-serif;
    }
    
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background-color: #f5f7fa;
    }
    
    /* Header */
    header {
      background-color: white;
      padding: 15px 0;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 100;
    }
    
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .logo img {
      height: 35px;
    }
    
    /* Layout principal */
    .main-container {
      display: flex;
      flex: 1;
      margin-top: 70px;
    }
    
    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: white;
      padding: 30px 20px;
      border-right: 1px solid #e1e5eb;
      position: fixed;
      height: calc(100vh - 70px);
    }
    
    .sidebar-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 30px;
      color: #2d465e;
    }
    
    .sidebar-menu {
      list-style: none;
    }
    
    .sidebar-menu li {
      margin-bottom: 15px;
    }
    
    .sidebar-menu a {
      display: flex;
      align-items: center;
      padding: 8px 12px;
      color: #495057;
      text-decoration: none;
      border-radius: 4px;
    }
    
    .sidebar-menu a::before {
      content: "[ ]";
      margin-right: 10px;
      color: #adb5bd;
    }
    
    .sidebar-menu a.active {
      background-color: rgba(13, 131, 253, 0.1);
      color: #0d83fd;
    }
    
    .sidebar-menu a.active::before {
      content: "[x]";
      color: #0d83fd;
    }
    
    /* Contenu principal */
    .main-content {
      flex: 1;
      margin-left: 250px;
      padding: 40px;
      background-color: #f5f7fa;
    }
    
    /* Formulaire */
    .question-form {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
      max-width: 800px;
      margin: 0 auto;
    }
    
    .form-title {
      font-size: 1.75rem;
      margin-bottom: 20px;
      color: #2d465e;
      padding-bottom: 10px;
      border-bottom: 2px solid rgba(13, 131, 253, 0.2);
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2d465e;
    }
    
    input, textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ced4da;
      border-radius: 6px;
      font-size: 16px;
    }
    
    textarea {
      min-height: 200px;
      resize: vertical;
    }
    
    .form-hint {
      font-size: 0.875rem;
      color: #6c757d;
      margin-top: 5px;
    }
    
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 30px;
    }
    
    .btn {
      padding: 10px 25px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      border: none;
      font-size: 16px;
    }
    
    .btn-cancel {
      background-color: #e9ecef;
      color: #495057;
    }
    
    .btn-submit {
      background-color: #0d83fd;
      color: white;
    }
    
    /* Messages d'alerte */
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .alert-success {
      background-color: #dff0d8;
      color: #3c763d;
      border: 1px solid #d6e9c6;
    }
    
    .alert-error {
      background-color: #f2dede;
      color: #a94442;
      border: 1px solid #ebccd1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .main-container {
        flex-direction: column;
      }
      
      .sidebar {
        width: 100%;
        position: static;
        height: auto;
      }
      
      .main-content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="header-container">
      <div class="logo">
        <img src="assets/img/Logo2.png" alt="BoostUp">
      </div>
      <!-- Navigation et autres éléments du header -->
    </div>
  </header>

  <!-- Contenu principal -->
  <div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h1 class="sidebar-title">BOOSTAP</h1>
      <ul class="sidebar-menu">
        <li><a href="#">Projects</a></li>
        <li><a href="#">Taches</a></li>
        <li><a href="#">Ressources</a></li>
        <li><a href="#">Evénements</a></li>
        <li><a href="question1.php" class="active">Communauté</a></li>
      </ul>
    </aside>

    <!-- Contenu -->
    <main class="main-content">
      <?php if (isset($message)): ?>
        <div class="alert <?php echo $messageClass; ?>">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>
      
      <form class="question-form" method="POST" action="question1.php">
        <h2 class="form-title">Poser une nouvelle question</h2>
        
        <div class="form-group">
          <label for="titre">Titre de la question</label>
          <input type="text" id="titre" name="titre" placeholder="Ex: Comment gérer les délais dans un projet complexe?" required>
          <p class="form-hint">Soyez précis et concis (50-150 caractères)</p>
        </div>
        
        <div class="form-group">
          <label for="contenu">Détails de la question</label>
          <textarea id="contenu" name="contenu" placeholder="Décrivez votre problème ou question en détails..." required></textarea>
          <p class="form-hint">Incluez tous les détails nécessaires pour obtenir des réponses pertinentes</p>
        </div>
        
        <div class="form-actions">
          <button type="button" class="btn btn-cancel">Annuler</button>

          <button type="submit" name="submit" class="btn btn-submit">Publier la question</button>
        </div>
        <!-- Dans la section main-content, avant ou après le formulaire -->
<div class="view-questions">
    <a href="afficherquestion.php" class="btn btn-view">Voir les questions</a>
</div>
      </form>
    </main>
  </div>

  <script>
    // Gestion de l'annulation
    document.querySelector('.btn-cancel').addEventListener('click', function() {
      if(confirm('Voulez-vous vraiment annuler? Votre question ne sera pas enregistrée.')) {
        window.location.href = 'communaute.html';
      }
    });
  </script>
  <script>
// Gestion de l'annulation
document.querySelector('.btn-cancel').addEventListener('click', function() {
  if(confirm('Voulez-vous vraiment annuler? Votre question ne sera pas enregistrée.')) {
    window.location.href = 'communaute.html';
  }
});

// Contrôle de saisie
document.querySelector('.question-form').addEventListener('submit', function(e) {
  const titre = document.getElementById('titre').value.trim();
  const contenu = document.getElementById('contenu').value.trim();
  
  // Validation du titre
  if (titre.length < 10 || titre.length > 150) {
    alert('Le titre doit contenir entre 10 et 150 caractères');
    e.preventDefault();
    return false;
  }
  
  // Validation du contenu
  if (contenu.length < 20) {
    alert('Le détail de la question doit contenir au moins 20 caractères');
    e.preventDefault();
    return false;
  }
  
  // Validation des balises HTML (optionnel)
  if (/<[a-z][\s\S]*>/i.test(contenu)) {
    alert('Les balises HTML ne sont pas autorisées dans le contenu');
    e.preventDefault();
    return false;
  }
  
  return true;
});

// Validation en temps réel pour le titre
document.getElementById('titre').addEventListener('input', function() {
  const titre = this.value.trim();
  const hint = this.nextElementSibling.nextElementSibling; // cible le paragraphe .form-hint
  
  if (titre.length < 10) {
    hint.textContent = `Trop court (${titre.length}/10 caractères minimum)`;
    hint.style.color = '#dc3545';
  } else if (titre.length > 150) {
    hint.textContent = `Trop long (${titre.length}/150 caractères maximum)`;
    hint.style.color = '#dc3545';
  } else {
    hint.textContent = 'Longueur correcte';
    hint.style.color = '#28a745';
  }
});

// Validation en temps réel pour le contenu
document.getElementById('contenu').addEventListener('input', function() {
  const contenu = this.value.trim();
  const hint = this.nextElementSibling.nextElementSibling; // cible le paragraphe .form-hint
  
  if (contenu.length < 20) {
    hint.textContent = `Trop court (${contenu.length}/20 caractères minimum)`;
    hint.style.color = '#dc3545';
  } else {
    hint.textContent = 'Longueur suffisante';
    hint.style.color = '#28a745';
  }
});
</script>
</body>
</html>