<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../../../config.php");
include_once("../../../model/Thematique.php");
require_once("../../../controller/ThematiqueC.php");

$errorMessages = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';

    $errors = [];

    if (empty($titre)) $errors[] = "Le titre est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";

    if (empty($errors)) {
        $thematique = new Thematique(null, $titre, $description);
        $thematiqueC = new ThematiqueC();
        $thematiqueC->ajouterThematique($thematique);

        // Enregistrer le titre dans notification.txt après l'ajout
        $notifFile = "notification.txt";
        file_put_contents($notifFile, $titre);

        header("Location: thematique.php");
        exit();
    } else {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>$err</p>";
        }
    }
}
?>

<!-- Messages d'erreur ou de succès -->
<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?php echo $successMessage; ?></p>
<?php endif; ?>

<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo $err; ?></p>
<?php endforeach; ?>
