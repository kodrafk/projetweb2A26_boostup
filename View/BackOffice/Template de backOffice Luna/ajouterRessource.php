<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../../../config.php");
include_once("../../../model/Ressource.php");
require_once("../../../controller/RessourceC.php");

//  Initialisation AVANT tout traitement
$errorMessages = [];
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'] ?? '';
    $titre = $_POST['titre'] ?? '';
    $lien = $_POST['lien'] ?? '';
    $description = $_POST['description'] ?? '';

    $errors = [];

    if (empty($type)) $errors[] = "Le type de ressource est obligatoire.";
    if (empty($titre)) $errors[] = "Le titre est obligatoire.";
    if (empty($lien)) $errors[] = "Le lien est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";

    if (empty($errors)) {
        $ressource = new Ressource(null, $type, $titre, $lien, $description);
        $ressourceC = new RessourceC();
        $ressourceC->ajouterRessource($ressource);

        // ✅ Redirection vers la page ressource.php pour actualiser l'affichage
          header("Location: ressource.php");
          exit();
        //echo "<p style='color: green;'>✅ La ressource a été ajoutée avec succès.</p>";
    } else {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>$err</p>";
        }
    }
}


?>


<!-- Partie HTML ici (exemple simplifié) -->
<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?php echo $successMessage; ?></p>
<?php endif; ?>

<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo $err; ?></p>
<?php endforeach; ?>

