<?php ob_start(); ?>

<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers nécessaires
include_once("../config/database.php");
include_once("../model/categorie.php");

// Initialisation des messages
$errorMessages = [];
$successMessage = "";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_categorie = $_POST['nom_categorie'] ?? '';
    $description = $_POST['description'] ?? '';

    $errors = [];

    // Validation des champs
    if (empty($nom_categorie)) $errors[] = "Le nom de la catégorie est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";

   // Si aucune erreur, procéder à l'ajout
   if (empty($errors)) {
        try {
            // Création de l'objet Categorie
            $categorie = new Categorie($nom_categorie, $description);

            // Appel à la méthode pour ajouter la catégorie
            if ($categorie->ajouterCategorie()) {
                // Message de succès
                $successMessage = "Catégorie ajoutée avec succès!";
                header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Categorie.php?success=1');
                exit; // Important pour stopper l'exécution ici après la redirection
            }
        } catch (Exception $e) {
            // Erreur lors de l'ajout
            $errorMessages[] = "❌ Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        // Si des erreurs de validation existent, les afficher directement
        foreach ($errors as $err) {
            $errorMessages[] = $err;
        }
    }
}

// Récupérer toutes les catégories pour afficher sur la même page
$categories = Categorie::afficherCategories(); 

?>

<!-- Affichage des messages -->
<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?php echo $successMessage; ?></p>
<?php endif; ?>

<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo $err; ?></p>
<?php endforeach; ?>

