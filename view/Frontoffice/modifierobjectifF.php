<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once('../../config/database.php');
include_once('../../model/objectif.php');

// Récupérer l'ID de l'objectif depuis l'URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Vérifier si l'ID est valide
if ($id) {
    // Récupérer les informations de l'objectif
    $db = getDB();
    $sql = "SELECT * FROM objectif WHERE id_objectif = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $objectif = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Rediriger si l'ID est manquant
    header('Location: /website1.0/view/Frontoffice/afficherobjectifF.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $date_limite = $_POST['date_limite'];
    $description = $_POST['description'];

    // Validation
    if (empty($titre) || empty($date_limite) || empty($description)) {
        $errorMessages[] = "Tous les champs sont requis.";
    }

    if (empty($errorMessages)) {
        $updateSql = "UPDATE objectif SET titre = :titre, date_limite = :date_limite, description = :description WHERE id_objectif = :id";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->bindParam(':titre', $titre);
        $updateStmt->bindParam(':date_limite', $date_limite);
        $updateStmt->bindParam(':description', $description);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: objectifF.php?message=modifie');
            exit();
        } else {
            $errorMessages[] = "Une erreur s'est produite lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Objectif</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error-message {
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 0.25rem;
      padding-left: 1rem;
    }
    .is-invalid {
      border-color: #dc3545 !important;
    }
    .form-control, .form-select {
      border-radius: 50px !important;
      padding: 0.5rem 1.5rem !important;
    }
    textarea.form-control {
      border-radius: 20px !important;
    }
  </style>
</head>
<body style="background: linear-gradient(to right, #e0eafc, #cfdef3); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

  <div class="card shadow-lg rounded-4" style="max-width: 700px; width: 100%;">
    <div class="card-body p-5">
      <h3 class="card-title text-center mb-4 fw-bold text-primary">✏️ Modifier l'Objectif</h3>

      <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errorMessages as $message): ?>
              <li><?php echo $message; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form id="objectifForm" action="modifierobjectifF.php?id=<?php echo $id; ?>" method="POST" novalidate>
        <input type="hidden" name="id" value="<?= $objectif['id_objectif'] ?>">
        <div class="mb-3">
          <label for="titre" class="form-label">Titre de l'Objectif</label>
          <input type="text" name="titre" id="titre" class="form-control" placeholder="Entrez le titre de l'objectif" value="<?php echo htmlspecialchars($objectif['titre'] ?? ''); ?>" required>
          <div id="titre_error" class="error-message"></div>
        </div>

        <div class="mb-3">
          <label for="date_limite" class="form-label">Date Limite</label>
          <input type="date" name="date_limite" id="date_limite" class="form-control" value="<?php echo htmlspecialchars($objectif['date_limite'] ?? ''); ?>" required>
          <div id="date_limite_error" class="error-message"></div>
        </div>

        <div class="mb-4">
          <label for="description" class="form-label">Description de l'Objectif</label>
          <textarea name="description" id="description" rows="4" class="form-control" placeholder="Décrivez l'objectif ici..." required><?php echo htmlspecialchars($objectif['description'] ?? ''); ?></textarea>
          <div id="description_error" class="error-message"></div>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary rounded-pill px-4">💾 Enregistrer</button>
          <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">❌ Annuler</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('objectifForm');
      
      form.addEventListener('submit', function(event) {
        // Réinitialiser les erreurs précédentes
        clearErrors();
        
        let isValid = validateForm();
        
        if (!isValid) {
          event.preventDefault();
        }
      });
      
      function clearErrors() {
        // Supprimer toutes les classes d'erreur et messages
        const errorInputs = document.querySelectorAll('.is-invalid');
        errorInputs.forEach(input => {
          input.classList.remove('is-invalid');
        });
        
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(message => {
          message.textContent = '';
        });
      }
      
      function validateForm() {
        let isValid = true;
        
        // Validation du titre
        const titre = document.getElementById('titre');
        if (titre.value.trim() === '') {
          document.getElementById('titre_error').textContent = 'Le titre est requis';
          titre.classList.add('is-invalid');
          isValid = false;
        } else if (titre.value.trim().length < 3) {
          document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères';
          titre.classList.add('is-invalid');
          isValid = false;
        }
        
        // Validation de la date limite
        const dateLimite = document.getElementById('date_limite');
        if (dateLimite.value === '') {
          document.getElementById('date_limite_error').textContent = 'La date limite est requise';
          dateLimite.classList.add('is-invalid');
          isValid = false;
        }
        
        // Validation de la description
        const description = document.getElementById('description');
        if (description.value.trim() === '') {
          document.getElementById('description_error').textContent = 'La description est requise';
          description.classList.add('is-invalid');
          isValid = false;
        } else if (description.value.trim().length < 10) {
          document.getElementById('description_error').textContent = 'La description doit contenir au moins 10 caractères';
          description.classList.add('is-invalid');
          isValid = false;
        }
        
        return isValid;
      }
    });
  </script>
</body>
</html>