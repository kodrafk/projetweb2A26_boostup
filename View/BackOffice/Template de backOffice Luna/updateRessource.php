<?php
// Initialisation des messages d'erreur
$errorMessages = [];

// Inclusion des fichiers nécessaires
include_once("../../../config.php");
require_once("../../../controller/RessourceC.php");
require_once("../../../model/Ressource.php");

// Récupérer l'ID de la ressource depuis l'URL ou autre méthode
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Vérifier si l'ID est valide
if ($id) {
    // Récupérer les informations de la ressource
    $ressourceC = new RessourceC();
    $ressource = $ressourceC->getRessourceById($id);
} else {
    // Rediriger si l'ID n'est pas valide
    header('Location: /Ressources/View/BackOffice/Template de backOffice Luna/ressource.php');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises
    $type = $_POST['type'];
    $titre = $_POST['titre'];
    $lien = $_POST['lien'];
    $description = $_POST['description'];

    // Validation des données
    if (empty($type) || empty($titre) || empty($lien) || empty($description)) {
        $errorMessages[] = "Tous les champs sont requis.";
    }

    // Si pas d'erreurs, mettre à jour la ressource
    if (empty($errorMessages)) {
        $ressourceC = new RessourceC();
        $updated = $ressourceC->updateRessource($id, $type, $titre, $lien, $description);
        if ($updated) {
            header('Location: /Ressources/View/BackOffice/Template de backOffice Luna/ressource.php');
            exit();
        } else {
            $errorMessages[] = "Une erreur s'est produite lors de la mise à jour.";
        }
    }

    if ($result) {
        // Si la mise à jour est réussie, rediriger vers la page principale (Projet.php)
        header('Location: /Ressources/View/BackOffice/Template de backOffice Luna/ressource.php');
        exit(); // N'oubliez pas d'utiliser exit après header() pour éviter que du code ne soit exécuté après la redirection
    } else {
        echo "Erreur lors de la mise à jour du projet.";
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Ressource</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (pour l'icône dans le bouton) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Style personnalisé -->
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            font-family: 'Segoe UI', sans-serif;
            padding: 40px 0;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 2px solid #6c63ff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-success {
            background-color: #6c63ff;
            border-color: #6c63ff;
        }

        .btn-success:hover {
            background-color: #5548d9;
            border-color: #5548d9;
        }

        .btn-secondary:hover {
            background-color: #aaa;
            border-color: #aaa;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card mx-auto" style="max-width: 800px;">
        <h2 class="text-center mb-4">Modifier la Ressource</h2>

        <?php if (!empty($errorMessages)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errorMessages as $message): ?>
                        <li><?php echo $message; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form  id="ressourceForm" action="updateRessource.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-section">
                <h5 class="section-title">Informations de la Ressource</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-control" required>
                               <option value="">-- Choisir un type --</option>
                               <option value="Cour" <?php if(isset($ressource['type']) && $ressource['type'] == 'Cour') echo 'selected'; ?>>Cour</option>
                               <option value="Vedio" <?php if(isset($ressource['type']) && $ressource['type'] == 'Vedio') echo 'selected'; ?>>Vedio</option>
                               <option value="Article" <?php if(isset($ressource['type']) && $ressource['type'] == 'Article') echo 'selected'; ?>>Article</option>
                               <option value="Evenement" <?php if(isset($ressource['type']) && $ressource['type'] == 'Evenement') echo 'selected'; ?>>Evenement</option>
                         </select>
                    </div>

                    <div class="col-md-6">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" id="titre" name="titre" class="form-control" value="<?php echo isset($ressource['titre']) ? $ressource['titre'] : ''; ?>" required>
                        <small id="titre_error" class="form-text text-danger"></small>
                    </div>

                    <div class="col-md-12">
                        <label for="lien" class="form-label">Lien</label>
                        <input type="url" id="lien" name="lien" class="form-control" value="<?php echo isset($ressource['lien']) ? $ressource['lien'] : ''; ?>" required>
                        <small id="lien_error" class="form-text text-danger"></small>

                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required><?php echo isset($ressource['description']) ? $ressource['description'] : ''; ?></textarea>
                        <small id="description_error" class="form-text text-danger"></small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="ressource.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Enregistrer
                    </button>
                </div>
            </div>

            <input type="hidden" name="id_ressource" value="<?php echo $id; ?>">
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


<script>
    function validerFormulaire(event) {
        event.preventDefault();

        const lienRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/;

        const titre = document.getElementById('titre');
        const lien = document.getElementById('lien');
        const description = document.getElementById('description');

        // Réinitialisation des erreurs
        document.querySelectorAll('.form-text').forEach(el => el.textContent = '');
        [titre, lien, description].forEach(input => input.classList.remove('is-invalid'));

        let valid = true;

        // validation de titre  : doit contenir au moins 3 caracteres 
        if (titre.value.length < 3) {
          document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères.';
          titre.classList.add('is-invalid');
          valid = false;
        }

        // Validation pour le champ 'lien' : Doit être un lien valide
        
        if (lien.value && !lienRegex.test(lien.value)) {
        document.getElementById('lien_error').textContent = 'Veuillez entrer un lien valide.';
        lien.classList.add('is-invalid');
        valid = false;
    }

    if (!valid) {
        event.preventDefault(); // Utilise le bon nom du paramètre
         return; // Empêche d'aller plus loin si invalide
    }

        // Validation pour le champ 'description' : Minimum 10 caractères
        if (description.value.length < 10) {
            document.getElementById('description_error').textContent = 'La description doit comporter au moins 10 caractères.';
            description.classList.add('is-invalid');
            valid = false;
        }

        if (valid) {
            document.getElementById("ressourceForm").submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("ressourceForm");
        if (form) {
            form.addEventListener("submit", validerFormulaire);
        }
    });
</script>

</body>
</html>
