<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once('../../config/database.php');
include_once('../../model/projet.php');

// Récupérer l'ID du projet depuis l'URL ou autre méthode
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Vérifier si l'ID est valide
if ($id) {
    // Récupérer les informations du projet
    $projet = Projet::getProjetById($id);
} else {
    // Rediriger si l'ID n'est pas valide
    header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises
    $nom_projet = $_POST['nom_projet'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $description = $_POST['description'];
    $montant = $_POST['montant']; 

    // Validation des données
    if (empty($nom_projet) || empty($date_debut) || empty($date_fin) || empty($description) || empty($montant)) {
        $errorMessages[] = "Tous les champs sont requis.";
    }

    // Vérifier si la date de fin est après la date de début
    if ($date_debut > $date_fin) {
        $errorMessages[] = "La date de début ne peut pas être après la date de fin.";
    }

    // Si pas d'erreurs, mettre à jour le projet
    if (empty($errorMessages)) {
        $updated = Projet::updateProjet($id, $nom_projet, $date_debut, $date_fin, $description, $montant);
        if ($updated) {
            header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php');
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #4d44db;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .project-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            background: white;
        }
        
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .is-invalid {
            border-color: var(--danger-color) !important;
        }
        
        .is-valid {
            border-color: var(--success-color) !important;
        }
        
        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .floating-label label {
            position: absolute;
            top: -10px;
            left: 15px;
            background: white;
            padding: 0 5px;
            color: var(--primary-color);
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .animated-border {
            position: relative;
        }
        
        .animated-border::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .animated-border:focus-within::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="project-card">
                    <div class="card-header bg-white border-0 pt-4">
                        <h2 class="text-center mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-edit me-2"></i>Modifier le Projet
                        </h2>
                    </div>
                    
                    <div class="card-body px-4 py-3">
                        <?php if (!empty($errorMessages)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errorMessages as $message): ?>
                                        <li><?php echo $message; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="projetForm" action="modifierProjet.php?id=<?php echo $id; ?>" method="POST" novalidate>
                            <div class="form-section animated-border">
                                <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Informations du Projet</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 floating-label">
                                        <label for="nom_projet">Nom du Projet</label>
                                        <input type="text" id="nom_projet" name="nom_projet" class="form-control" 
                                               value="<?php echo htmlspecialchars($projet['nom_projet'] ?? ''); ?>" required>
                                        <div id="nom_projet_error" class="error-message"></div>
                                    </div>

                                    <div class="col-md-6 floating-label">
                                        <label for="date_debut">Date de début</label>
                                        <input type="date" id="date_debut" name="date_debut" class="form-control" 
                                               value="<?php echo htmlspecialchars($projet['date_debut'] ?? ''); ?>" required>
                                        <div id="date_debut_error" class="error-message"></div>
                                    </div>

                                    <div class="col-md-6 floating-label">
                                        <label for="date_fin">Date de fin</label>
                                        <input type="date" id="date_fin" name="date_fin" class="form-control" 
                                               value="<?php echo htmlspecialchars($projet['date_fin'] ?? ''); ?>" required>
                                        <div id="date_fin_error" class="error-message"></div>
                                    </div>

                                    <div class="col-12 floating-label">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($projet['description'] ?? ''); ?></textarea>
                                        <div id="description_error" class="error-message"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
    <label for="montant" class="form-label">Montant du Projet (€)</label>
    <input type="number" name="montant" id="montant" class="form-control" value="<?= htmlspecialchars($projet['montant'] ?? ''); ?>" required min="0">
    <div id="montant_error" class="error-message"></div>
</div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="/BoostUp/view/Backoffice/template/Template_Luna/Projet.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = {
        nom_projet: document.getElementById('nom_projet'),
        description: document.getElementById('description'),
        date_debut: document.getElementById('date_debut'),
        date_fin: document.getElementById('date_fin'),
        montant: document.getElementById('montant')
    };

    // Fonctions de validation
    function validateNomProjet() {
        let isValid = true;
        const nomProjet = inputs.nom_projet.value;
        const nomError = document.getElementById('nom_projet_error');
        
        if (nomProjet === '') {
            showError(inputs.nom_projet, nomError, 'Le nom du projet est requis');
            isValid = false;
        } else if (nomProjet.length < 3) {
            showError(inputs.nom_projet, nomError, 'Le nom doit contenir au moins 3 caractères');
            isValid = false;
        } else {
            showSuccess(inputs.nom_projet, nomError);
        }
        
        return isValid;
    }

    function validateDescription() {
        let isValid = true;
        const description = inputs.description.value;
        const descError = document.getElementById('description_error');
        
        if (description === '') {
            showError(inputs.description, descError, 'La description est requise');
            isValid = false;
        } else if (description.length < 10) {
            showError(inputs.description, descError, 'La description doit contenir au moins 10 caractères');
            isValid = false;
        } else {
            showSuccess(inputs.description, descError);
        }
        
        return isValid;
    }

    function validateDates() {
        let isValid = true;
        const dateDebut = inputs.date_debut.value;
        const dateFin = inputs.date_fin.value;
        const debutError = document.getElementById('date_debut_error');
        const finError = document.getElementById('date_fin_error');
        
        // Validation date de début
        if (dateDebut === '') {
            showError(inputs.date_debut, debutError, 'La date de début est requise');
            isValid = false;
        } else {
            showSuccess(inputs.date_debut, debutError);
        }
        
        // Validation date de fin
        if (dateFin === '') {
            showError(inputs.date_fin, finError, 'La date de fin est requise');
            isValid = false;
        } else {
            showSuccess(inputs.date_fin, finError);
        }
        
        // Validation comparaison des dates
        if (dateDebut && dateFin && dateDebut > dateFin) {
            showError(inputs.date_debut, debutError, 'La date de début doit être avant la date de fin');
            showError(inputs.date_fin, finError, 'La date de fin doit être après la date de début');
            isValid = false;
        }
        
        return isValid;
    }

    function validateMontant() {
        let isValid = true;
        const montant = inputs.montant.value;
        const montantError = document.getElementById('montant_error');
        
        if (montant === '') {
            showError(inputs.montant, montantError, 'Le montant est requis');
            isValid = false;
        } else if (parseFloat(montant) <= 0) {
            showError(inputs.montant, montantError, 'Le montant doit être supérieur à 0');
            isValid = false;
        } else {
            showSuccess(inputs.montant, montantError);
        }
        
        return isValid;
    }

    function showError(input, errorElement, message) {
        errorElement.textContent = message;
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }

    function showSuccess(input, errorElement) {
        errorElement.textContent = '';
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }

    function clearErrors() {
        for (const key in inputs) {
            const input = inputs[key];
            const errorElement = document.getElementById(`${key}_error`);
            
            if (errorElement) {
                input.classList.remove('is-invalid', 'is-valid');
                errorElement.textContent = '';
            }
        }
    }

    // Événements de validation en temps réel
    inputs.nom_projet.addEventListener('input', validateNomProjet);
    inputs.description.addEventListener('input', validateDescription);
    inputs.date_debut.addEventListener('change', validateDates);
    inputs.date_fin.addEventListener('change', validateDates);
    inputs.montant.addEventListener('input', validateMontant);

    // Validation à la soumission
    form.addEventListener('submit', function(event) {
        clearErrors();
        const isNomValid = validateNomProjet();
        const isDescValid = validateDescription();
        const isDatesValid = validateDates();
        const isMontantValid = validateMontant();
        
        if (!isNomValid || !isDescValid || !isDatesValid || !isMontantValid) {
            event.preventDefault();
            // Faire défiler jusqu'à la première erreur
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
    </script>
</body>
</html>