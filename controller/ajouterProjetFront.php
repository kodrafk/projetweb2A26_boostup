<?php
// Activer les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../config/database.php");
include_once("../model/projet.php");
include_once("../model/categorie.php");

$errorMessages = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_projet = $_POST['nom_projet'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $description = $_POST['description'] ?? '';
    $montant = $_POST['montant'] ?? ''; // Si vous avez un champ montant
    $montant_paye = $_POST['montant_paye'] ?? 0; // 0 par défaut si vide

    $id_categorie = $_POST['id_categorie'] ?? '';

    $errors = [];

    if (empty($nom_projet)) $errors[] = "Le nom du projet est obligatoire.";
    if (empty($date_debut)) $errors[] = "La date de début est obligatoire.";
    if (empty($date_fin)) $errors[] = "La date de fin est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";
    if (empty($montant)) $errors[] = "Le montant est obligatoire."; // Si vous avez un champ montant
    if (empty($id_categorie)) $errors[] = "La catégorie est obligatoire.";

    if (empty($errors)) {
        try {
            $projet = new Projet($nom_projet, $date_debut, $date_fin, $description, $montant, $montant_paye, $id_categorie);
            if ($projet->ajouterProjet()) {
                header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php?success=1');
                exit();
            } else {
                header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php?error=1');
                exit();
            }
        } catch (Exception $e) {
            header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php?error=' . urlencode($e->getMessage()));
            exit();
        }
    } else {
        $msg = implode(' | ', $errors);
        header('Location: /BoostUp/view/Frontoffice/TemplateFront/projetF.php?error=' . urlencode($msg));
        exit();
    }
}
?>
<?php
// Debug : Afficher les données reçues
echo "<pre>";
print_r($_POST);
echo "</pre>";
exit();