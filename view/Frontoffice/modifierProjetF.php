<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once('../../config/database.php');
include_once('../../model/projet.php');

// Récupérer l'ID du projet depuis l'URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Vérifier si l'ID est valide
if ($id) {
  // Récupérer les informations du projet
  $projet = Projet::getProjetById($id);
} else {
  // Rediriger si l'ID est manquant
  header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php');
  exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom_projet = $_POST['nom_projet'];
  $date_debut = $_POST['date_debut'];
  $date_fin = $_POST['date_fin'];
  $description = $_POST['description'];
  $montant = $_POST['montant'] ?? '';

  // Validation
  if (empty($nom_projet) || empty($date_debut) || empty($date_fin) || empty($description) || empty($montant)) {
    $errorMessages[] = "Tous les champs sont requis.";
  }

  if ($date_debut > $date_fin) {
    $errorMessages[] = "La date de début ne peut pas être après la date de fin.";
  }

  if (empty($errorMessages)) {
    $updated = Projet::updateProjet($id, $nom_projet, $date_debut, $date_fin, $description, $montant);
    if ($updated) {
      header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php');
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
  <title>Modifier Projet</title>
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

    .form-control,
    .form-select {
      border-radius: 50px !important;
      padding: 0.5rem 1.5rem !important;
    }

    textarea.form-control {
      border-radius: 20px !important;
    }
  </style>
</head>

<body
  style="background: linear-gradient(to right, #e0eafc, #cfdef3); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

  <div class="card shadow-lg rounded-4" style="max-width: 700px; width: 100%;">
    <div class="card-body p-5">
      <h3 class="card-title text-center mb-4 fw-bold text-primary">✏️ Modifier le Projet</h3>

      <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errorMessages as $message): ?>
              <li><?php echo $message; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form id="projetForm" action="modifierProjetF.php?id=<?php echo $id; ?>" method="POST" novalidate>
        <div class="mb-3">
          <label for="nom_projet" class="form-label">Nom du Projet</label>
          <input type="text" name="nom_projet" id="nom_projet" class="form-control"
            placeholder="Entrez le nom du projet" value="<?php echo htmlspecialchars($projet['nom_projet'] ?? ''); ?>"
            required>
          <div id="nom_projet_error" class="error-message"></div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="date_debut" class="form-label">Date de Début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control"
              value="<?php echo htmlspecialchars($projet['date_debut'] ?? ''); ?>" required>
            <div id="date_debut_error" class="error-message"></div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="date_fin" class="form-label">Date de Fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control"
              value="<?php echo htmlspecialchars($projet['date_fin'] ?? ''); ?>" required>
            <div id="date_fin_error" class="error-message"></div>
          </div>
        </div>

        <div class="mb-4">
          <label for="description" class="form-label">Description du Projet</label>
          <textarea name="description" id="description" rows="4" class="form-control"
            placeholder="Décrivez le projet ici..."><?php echo htmlspecialchars($projet['description'] ?? ''); ?></textarea>
          <div id="description_error" class="error-message"></div>
        </div>
        
        <div class="col-md-6 mb-3">
          <label for="montant" class="form-label">Montant du Projet (€)</label>
          <input type="number" name="montant" id="montant" class="form-control"
            value="<?= htmlspecialchars($projet['montant'] ?? ''); ?>" required min="0">
          <div id="montant_error" class="error-message"></div>
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
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('projetForm');

      form.addEventListener('submit', function (event) {
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

        // Validation du nom du projet
        const nomProjet = document.getElementById('nom_projet');
        if (nomProjet.value.trim() === '') {
          document.getElementById('nom_projet_error').textContent = 'Le nom du projet est requis';
          nomProjet.classList.add('is-invalid');
          isValid = false;
        } else if (nomProjet.value.trim().length < 3) {
          document.getElementById('nom_projet_error').textContent = 'Le nom doit contenir au moins 3 caractères';
          nomProjet.classList.add('is-invalid');
          isValid = false;
        }

        // Validation de la date de début
        const dateDebut = document.getElementById('date_debut');
        if (dateDebut.value === '') {
          document.getElementById('date_debut_error').textContent = 'La date de début est requise';
          dateDebut.classList.add('is-invalid');
          isValid = false;
        }

        // Validation de la date de fin
        const dateFin = document.getElementById('date_fin');
        if (dateFin.value === '') {
          document.getElementById('date_fin_error').textContent = 'La date de fin est requise';
          dateFin.classList.add('is-invalid');
          isValid = false;
        }

        // Validation des dates (début avant fin)
        if (dateDebut.value && dateFin.value && dateDebut.value > dateFin.value) {
          document.getElementById('date_debut_error').textContent = 'La date de début doit être avant la date de fin';
          document.getElementById('date_fin_error').textContent = 'La date de fin doit être après la date de début';
          dateDebut.classList.add('is-invalid');
          dateFin.classList.add('is-invalid');
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
        const montant = document.getElementById('montant');
        if (montant.value.trim() === '') {
          document.getElementById('montant_error').textContent = 'Le montant est requis';
          montant.classList.add('is-invalid');
          isValid = false;
        } else if (parseFloat(montant.value) <= 0) {
          document.getElementById('montant_error').textContent = 'Le montant doit être supérieur à 0';
          montant.classList.add('is-invalid');
          isValid = false;
        }
        return isValid;
      }
    });
  </script>
</body>

</html>