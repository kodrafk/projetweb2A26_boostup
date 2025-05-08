<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../../config.php');
include_once(__DIR__ . '/../../model/User.php');
require_once(__DIR__ . '/../../controller/UserC.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../phpmailer/src/Exception.php';
require_once __DIR__ . '/../../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../phpmailer/src/SMTP.php';



$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     // === Vérification reCAPTCHA ===
     if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = '6Leq1CorAAAAAFVh3ZB1pKAZL7RrnB5Q9KGQdkzW'; // Remplace avec ta vraie clé secrète

        $data = [
            'secret'   => $secretKey,
            'response' => $captcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $captchaSuccess = json_decode($verify);

        if (!$captchaSuccess->success) {
            $errorMessages[] = "Échec de la vérification CAPTCHA.";
        }
    } else {
        $errorMessages[] = "Veuillez valider le CAPTCHA.";
    }


    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $type = trim($_POST['type'] ?? 'client'); // valeur par défaut
    $numtel = trim($_POST['numtel'] ?? '');

    // Vérification des champs obligatoires
    if (empty($email)) $errorMessages[] = "L'email est obligatoire.";
    if (empty($password)) $errorMessages[] = "Le mot de passe est obligatoire.";
    if (empty($type)) $errorMessages[] = "Le type est obligatoire.";
    if (empty($numtel)) $errorMessages[] = "Le numéro de téléphone est obligatoire.";
    if (empty($firstName)) $errorMessages[] = "Le prénom est obligatoire.";
    if (empty($lastName)) $errorMessages[] = "Le nom est obligatoire.";

    if (empty($errorMessages)) {
        $otp = rand(100000, 999999);
        $signup_time = date("Y-m-d H:i:s");

        // Créer l'objet utilisateur avec l'OTP
        $user = new User(
            null,
            $email,
            $password,
            $type,
            $numtel,
            $firstName,
            $lastName,
            $signup_time,
            $otp,
            0, // status: inactif
           
        );
        

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fakraouikodra@gmail.com';
            $mail->Password   = 'ypdh bgyi iqpe ggmj';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('fakraouikodra@gmail.com', 'Verification OTP');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Code de vérification';
            $mail->Body    = "Bonjour <b>$firstName $lastName</b>,<br><br>Voici votre code de vérification : <b>$otp</b><br><br>Merci de l'entrer pour activer votre compte.";

            $mail->send();

            // Enregistrement en base seulement si le mail a bien été envoyé
            $userC = new UserC();
            $userC->ajouterUser($user);

            header('Location: email_verify.php?email=' . urlencode($email));
            exit;

        } catch (Exception $e) {
            $errorMessages[] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
        }
    }
}
?>

<!-- Affichage du formulaire (HTML + erreurs) -->
<?php foreach ($errorMessages as $err) : ?>
    <p style="color: red;"><?php echo htmlspecialchars($err); ?></p>
<?php endforeach; ?>

<!-- Tu peux insérer ton formulaire HTML ici -->
