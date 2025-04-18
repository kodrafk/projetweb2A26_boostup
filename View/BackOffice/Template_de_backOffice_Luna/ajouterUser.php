<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../../../config.php");
include_once("../../../model/User.php");
require_once("../../../controller/UserC.php");

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des champs du formulaire
    

    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $numtel = trim($_POST['numtel'] ?? '');

    // Validation des champs
    if (empty($email)) $errorMessages[] = "L'email est obligatoire.";
    if (empty($password)) $errorMessages[] = "Le mot de passe est obligatoire.";
    if (empty($type)) $errorMessages[] = "Le type est obligatoire.";
    if (empty($numtel)) $errorMessages[] = "Le numéro de téléphone est obligatoire.";
    if (empty($firstName)) $errorMessages[] = "Le prénom est obligatoire.";
    if (empty($lastName)) $errorMessages[] = "Le nom est obligatoire.";

    // Si tout est bon
    if (empty($errorMessages)) {
        $user = new User(
            null,        // id (auto-incrémenté)
            $firstName,
            $lastName,
            $email,
            $password,
            $type,
            $numtel
        );

        $userC = new UserC(); // Ton contrôleur doit exister
        $userC->ajouterUser($user);

        // Redirection après succès
        header("Location: index.php");
        exit();
    }
}
?>

<!-- Affichage des erreurs si nécessaire -->
<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo htmlspecialchars($err); ?></p>
<?php endforeach; ?>

