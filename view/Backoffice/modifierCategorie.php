<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once('../../config/database.php');
include_once('../../model/categorie.php');

// Récupérer l'ID de la catégorie depuis l'URL ou autre méthode
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Vérifier si l'ID est valide
if ($id) {
    // Récupérer les informations de la catégorie
    $categorie = Categorie::getCategorieById($id);
} else {
    // Rediriger si l'ID n'est pas valide
    if ($updated) {
        if ($updated) {
            // Redirection avec un paramètre de succès
            header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Categorie.php?modification=success');
            exit();
        }
                exit();
    }
        exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises
    $nom_categorie = $_POST['nom_categorie'];
    $description = $_POST['description'];

    // Validation des données
    if (empty($nom_categorie) || empty($description)) {
        $errorMessages[] = "Tous les champs sont requis.";
    }

    // Si pas d'erreurs, mettre à jour la catégorie
    if (empty($errorMessages)) {
        $updated = Categorie::updateCategorie($id, $nom_categorie, $description);
        if ($updated) {
            header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Categorie.php');
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
    <title>Modifier Catégorie</title>
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

        .category-card {
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
                <div class="category-card">
                    <div class="card-header bg-white border-0 pt-4">
                        <h2 class="text-center mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-edit me-2"></i>Modifier la Catégorie
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

                        <form id="categorieForm" action="modifierCategorie.php?id=<?php echo $id; ?>" method="POST" novalidate>
                            <div class="form-section animated-border">
                                <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Informations de la Catégorie</h5>
                                <div class="row g-3">
                                    <div class="col-md-6 floating-label">
                                        <label for="nom_categorie">Nom de la Catégorie</label>
                                        <input type="text" id="nom_categorie" name="nom_categorie" class="form-control" 
                                               value="<?php echo htmlspecialchars($categorie['nom_categorie'] ?? ''); ?>" required>
                                        <div id="nom_categorie_error" class="error-message"></div>
                                    </div>

                                    <div class="col-12 floating-label">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($categorie['description'] ?? ''); ?></textarea>
                                        <div id="description_error" class="error-message"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="/BoostUp/view/Backoffice/template/Template_Luna/Categorie.php" class="btn btn-outline-secondary">
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
    // Références aux champs et formulaire
    const form = document.getElementById('categorieForm');
    const inputs = {
        nom_categorie: document.getElementById('nom_categorie'),
        description: document.getElementById('description')
    };

    // Fonction pour effacer les erreurs
    function clearErrors() {
        Object.keys(inputs).forEach(key => {
            inputs[key].classList.remove('is-invalid', 'is-valid');
            const errorElement = document.getElementById(key + '_error');
            if (errorElement) errorElement.textContent = '';
        });
    }

    // Fonction de validation du champ nom
    function validateNomCategorie() {
        const value = inputs.nom_categorie.value.trim();
        if (value.length < 3) {
            inputs.nom_categorie.classList.add('is-invalid');
            document.getElementById('nom_categorie_error').textContent = 'Le nom de la catégorie doit contenir au moins 3 caractères.';
            return false;
        } else {
            inputs.nom_categorie.classList.remove('is-invalid');
            inputs.nom_categorie.classList.add('is-valid');
            document.getElementById('nom_categorie_error').textContent = '';
            return true;
        }
    }

    // Fonction de validation du champ description
    function validateDescription() {
        const value = inputs.description.value.trim();
        if (value.length < 6) {
            inputs.description.classList.add('is-invalid');
            document.getElementById('description_error').textContent = 'La description doit contenir au moins 6 caractères.';
            return false;
        } else {
            inputs.description.classList.remove('is-invalid');
            inputs.description.classList.add('is-valid');
            document.getElementById('description_error').textContent = '';
            return true;
        }
    }

    // Événements de validation en temps réel
    inputs.nom_categorie.addEventListener('input', validateNomCategorie);
    inputs.description.addEventListener('input', validateDescription);

    // Validation à la soumission
    form.addEventListener('submit', function(event) {
        clearErrors();
        const isNomValid = validateNomCategorie();
        const isDescValid = validateDescription();

        if (!isNomValid || !isDescValid) {
            event.preventDefault();
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
