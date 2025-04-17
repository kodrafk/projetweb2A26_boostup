<?php ob_start(); ?>

<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers nécessaires
include_once("../config/database.php");
include_once("../model/projet.php");

// Initialisation des messages
$errorMessages = [];
$successMessage = "";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_projet = $_POST['nom_projet'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $description = $_POST['description'] ?? '';

    $errors = [];

    // Validation des champs
    if (empty($nom_projet)) $errors[] = "Le nom du projet est obligatoire.";
    if (empty($date_debut)) $errors[] = "La date de début est obligatoire.";
    if (empty($date_fin)) $errors[] = "La date de fin est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";

   // Si aucune erreur, procéder à l'ajout
   if (empty($errors)) {
        try {
            // Création de l'objet Projet
            $projet = new Projet($nom_projet, $date_debut, $date_fin, $description);

            //Appel à la méthode pour ajouter le projet
            if ($projet->ajouterProjet()) {
                // Message de succès
                //$successMessage = "Projet ajouté avec succès!";
                header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php');
                
            } //else {
            //     // Échec
            //     $errorMessages[] = "❌ Une erreur est survenue lors de l'ajout du projet.";
            // }
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

// Récupérer tous les projets pour afficher sur la même page
$projets = Projet::afficherProjets(); 
header('Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php');


?>

<!-- Affichage des messages -->
<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?php echo $successMessage; ?></p>
<?php endif; ?>

<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo $err; ?></p>
<?php endforeach; ?>

