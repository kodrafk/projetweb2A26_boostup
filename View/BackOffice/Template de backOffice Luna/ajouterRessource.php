<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure PHPMailer
require_once('../../../vendor/PHPMailer-master/src/Exception.php');
require_once('../../../vendor/PHPMailer-master/src/PHPMailer.php');
require_once('../../../vendor/PHPMailer-master/src/SMTP.php');

include_once("../../../controller/ThematiqueC.php");
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
    $id_thematique = $_POST['id_thematique'] ?? '';

    $errors = [];

    if (empty($type)) $errors[] = "Le type de ressource est obligatoire.";
    if (empty($titre)) $errors[] = "Le titre est obligatoire.";
    if (empty($lien)) $errors[] = "Le lien est obligatoire.";
    if (empty($description)) $errors[] = "La description est obligatoire.";
    if (empty($id_thematique)) $errors[] = "La thématique est obligatoire.";

    if (empty($errors)) {
        $ressource = new Ressource(null, $type, $titre, $lien, $description, $id_thematique);
        $ressourceC = new RessourceC();
        $ressourceC->ajouterRessource($ressource);

         // Récupérer le titre de la thématique
            $thematiqueC = new ThematiqueC(); // Créer une instance de ThematiqueC
            $thematique = $thematiqueC->getThematiqueById($id_thematique); // Appeler la méthode
            $thematiqueTitre = $thematique['titre']; // Extraire le titre


          // Envoi de l'email
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'bouthynalabidi@gmail.com'; // Ton adresse e-mail
            $mail->Password = 'noog tveo yfdg fkch'; // Ton mot de passe ou mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinataire
            $mail->setFrom('ton-email@gmail.com', 'Nom de ton entreprise');
            $mail->addAddress('bouthynalabidi@gmail.com', 'Bouthyna Labidi'); // Adresse du destinataire

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Nouvelle ressource ajoutée';
            $mail->Body = '
               <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 10px; padding: 20px; max-width: 600px; margin: auto;">
               <h2 style="color: #00aaff;">🎉 Nouvelle Ressource Ajoutée</h2>
               <p style="color: #111111;"><strong>Type :</strong> ' . htmlspecialchars($type) . '</p>
               <p style="color: #111111;"><strong>Titre :</strong> ' . htmlspecialchars($titre) . '</p>
               <p style="color: #111111;"><strong>Lien :</strong> <a href="' . htmlspecialchars($lien) . '" style="color: #3498db;">' . htmlspecialchars($lien) . '</a></p>

               <p style="color: #111111;"><strong>Thématique :</strong> ' . htmlspecialchars($thematiqueTitre) . '</p>
               <p style="color: #111111;"><strong>Description :</strong></p>
               <div style="background-color: #fff; border: 1px solid #ccc; padding: 10px; border-radius: 5px; color: #111111;">
               ' . nl2br(htmlspecialchars($description)) . '
               </div>
                <p style="margin-top: 20px; color: #888;">Merci de vérifier la nouvelle ressource ajoutée.</p>
               </div>';


            // Envoi de l'e-mail
            $mail->send();

            echo 'L\'e-mail a été envoyé avec succès.';
        } catch (Exception $e) {
            echo "L'email n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
        }

        // Redirection APRES l'envoi de l'email
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

