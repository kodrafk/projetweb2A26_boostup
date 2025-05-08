<form method="POST">
    <label>Code de vérification :</label>
    <input type="text" name="otp" required>
    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
    <button type="submit" name="verifier">Vérifier</button>
</form>

<?php
if (isset($_POST['verifier'])) {
    include_once '../../controller/UserC.php';
    $userC = new UserC();

    $email = $_POST['email'];
    $otp = $_POST['otp'];

    if ($userC->verifierOTP($email, $otp)) {
        echo "✅ Compte activé avec succès !";
        // Redirection vers page de login
        header("Location: login.php");
    } else {
        echo "❌ Code incorrect.";
    }
}
?>
