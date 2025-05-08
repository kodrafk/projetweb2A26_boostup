<?php
// Active les erreurs pour debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charger PHPMailer
require '../../vendor/PHPMailer-master/src/Exception.php';
require '../../vendor/PHPMailer-master/src/PHPMailer.php';
require '../../vendor/PHPMailer-master/src/SMTP.php';

// Récupérer l'id du projet
if (isset($_GET['id_projet'])) {
    $idProjet = $_GET['id_projet'];
} else {
    die('Projet non spécifié.');
}

// Adresse destinataire
$destinataire = 'ala.akchi25838693@gmail.com';

// Sujet et message
$sujet = "Investissement dans un projet";
$message = "Bonjour,\n\nJ'ai apprécié votre projet (ID: $idProjet) et je souhaite investir.\n\nCordialement.";

$mail = new PHPMailer(true);

try {
    // Paramètres SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ala.akchi25838693@gmail.com';
    $mail->Password   = 'npdd uqki anqp nask';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Destinataire et contenu
    $mail->setFrom('ala.akchi25838693@gmail.com', 'Boostup Investisseur');
    $mail->addAddress($destinataire);
    $mail->Subject = $sujet;
    $mail->Body    = $message;

    $mail->send();

    // Afficher une alerte JS et rediriger
    echo "<script>
            alert('✅ Votre message a été envoyé avec succès.');
            window.location.href = '/BoostUp/view/Frontoffice/TemplateFront/ProjetF.php';
          </script>";
} catch (Exception $e) {
    echo "<script>
            alert('❌ Erreur lors de l\\'envoi du message. Veuillez réessayer.');
            window.location.href = '/BoostUp/view/Frontoffice/TemplateFront/ProjetF.php';
          </script>";
}
?>
