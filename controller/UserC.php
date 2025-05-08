<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/User.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

class UserC {
    private $userModel;

    public function __construct() {
        $this->userModel = new User(null, null, null, null, null, null, null, null, null, null); // Initialisation de User
    }

    // Ajouter un utilisateur
    function ajouterUser($user) {
        $sql = "INSERT INTO user (email, password, type, numtel, firstName, lastName, signup_time, otp, status)
                VALUES (:email, :password, :type, :numtel, :firstName, :lastName, :signup_time, :otp, :status)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'email'         => $user->getEmail(),
                'password'      => password_hash($user->getPassword(), PASSWORD_DEFAULT),
                'type'          => $user->getType(),
                'numtel'        => $user->getNumTel(),
                'firstName'     => $user->getFirstName(),
                'lastName'      => $user->getLastName(),
                'signup_time'   => $user->getSignupTime(),
                'otp'           => $user->getOtp(),
                'status'        => $user->getStatus(),
            ]);
        } catch (Exception $e) {
            error_log("Erreur ajout utilisateur : " . $e->getMessage());
        }
    }

    // Afficher tous les utilisateurs
    function afficherUsers() {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM user");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Supprimer un utilisateur
    function supprimerUser($ide) {
        $sql = "DELETE FROM user WHERE iduser = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $ide);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function modifierUser($id, $firstName, $lastName, $email, $password, $type, $numtel) {
        $sql = "UPDATE user SET firstName = :firstName, lastName = :lastName, email = :email, password = :password, type = :type, numtel = :numtel WHERE iduser = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':firstName', $firstName);
            $query->bindParam(':lastName', $lastName);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Hacher le mot de passe
            $query->bindParam(':type', $type);
            $query->bindParam(':numtel', $numtel);
            $query->bindParam(':id', $id);
            return $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function modifierProfil($id, $firstName, $lastName, $email, $numtel, $type) {
        $sql = "UPDATE user SET 
                firstName = :firstName, 
                lastName = :lastName, 
                email = :email, 
                numtel = :numtel ,
                type = :type
                WHERE iduser = :id";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'numtel' => $numtel,
                'type' => $type,
                'id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // Récupérer un utilisateur par son ID
    function getUserById($id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM user WHERE iduser = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }

    public function connecterUser($email, $password) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM user WHERE email = :email");
            $query->bindParam(':email', $email);
            $query->execute();
            $userData = $query->fetch(PDO::FETCH_ASSOC);

            if ($userData && password_verify($password, $userData['password'])) {
                // Créer un nouvel objet User avec les données récupérées
                $user = new User(
                    $userData['iduser'],
                    $userData['email'],
                    $userData['password'],
                    $userData['type'],
                    $userData['numtel'],
                    $userData['firstName'],
                    $userData['lastName'],
                    $userData['signup_time'] ?? null,
                    $userData['otp'] ?? null,
                    $userData['status'] ?? null
                );
                return $user;
            } else {
                echo "Utilisateur non trouvé ou mot de passe incorrect.";
                return false;
            }
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function verifierOTP($email, $otp) {
        $sql = "SELECT * FROM user WHERE email = :email AND otp = :otp";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':email', $email);
            $query->bindParam(':otp', $otp);
            $query->execute();
            if ($query->rowCount() > 0) {
                // Activer le compte
                $update = $db->prepare("UPDATE user SET status = 1, otp = NULL WHERE email = :email");
                $update->bindParam(':email', $email);
                $update->execute();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Création d'un token pour réinitialisation de mot de passe
    public function createPasswordResetToken($email, $token, $expiry) {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("UPDATE user SET token = :token, token_expire = :expiry WHERE email = :email");
            return $stmt->execute([
                ':token' => $token,
                ':expiry' => $expiry,
                ':email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Erreur création token: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserByResetToken($token) {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE token = :token AND token_expire > NOW()");
            $stmt->execute([':token' => $token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération par token: " . $e->getMessage());
            return false;
        }
    }
    
    public function updatePassword($email, $password) {
        $db = config::getConnexion();
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE user SET password = :password, token = NULL, token_expire = NULL WHERE email = :email");
            return $stmt->execute([
                ':password' => $hashedPassword,
                ':email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Erreur mise à jour mot de passe: " . $e->getMessage());
            return false;
        }
    }
    
    public function resetPassword() {
        // Récupérer le token depuis POST ou GET
        $token = $_POST['token'] ?? $_GET['token'] ?? '';
        $token = htmlspecialchars($token);
    
        if (empty($token)) {
            header('Location: ../View/FrontOffice/reset_password.php?error=invalid_token');
            exit;
        }
    
        // Vérifier le token
        $user = $this->getUserByResetToken($token);
        if (!$user) {
            header('Location: ../View/FrontOffice/reset_password.php?error=invalid_token');
            exit;
        }
    
        // Traitement du formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            // Validation
            if (strlen($newPassword) < 8) {
                header('Location: ../View/FrontOffice/reset_password.php?token='.urlencode($token).'&error=password_too_short');
                exit;
            }
    
            if ($newPassword !== $confirmPassword) {
                header('Location: ../View/FrontOffice/reset_password.php?token='.urlencode($token).'&error=password_mismatch');
                exit;
            }
    
            // Mise à jour du mot de passe
            if ($this->updatePassword($user['email'], $newPassword)) {
                header('Location: ../View/FrontOffice/password_reset_success.php');
                exit;
            } else {
                header('Location: ../View/FrontOffice/reset_password.php?token='.urlencode($token).'&error=update_failed');
                exit;
            }
        }
    
        // Affichage du formulaire si méthode GET
        include '../View/FrontOffice/reset_password.php';
    }
    
    public function sendPasswordResetEmail($email) {
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600); // 1 heure d'expiration
    
        // Sauvegarder le token en base
        if (!$this->createPasswordResetToken($email, $token, $expiry)) {
            error_log("Échec de la création du token pour $email");
            return false;
        }
    
        // Créer le lien de réinitialisation (utilisation de l'URL dynamique)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $resetLink = $protocol . $host . '/Users/controller/UserC.php?action=resetPassword&token=' . $token;
    
        // Envoyer l'email
        $mail = new PHPMailer(true);
        try {
            // Configuration SMTP (à personnaliser)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fakraouikodra@gmail.com'; // Remplacez par votre email
            $mail->Password   = 'ypdh bgyi iqpe ggmj'; // Remplacez par votre mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
    
            $mail->setFrom('fakraouikodra@gmail.com', 'Support Technique');
            $mail->addAddress($email);
    
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body    = "Bonjour,<br><br>"
                            . "Vous avez demandé à réinitialiser votre mot de passe. "
                            . "Cliquez sur le lien suivant pour définir un nouveau mot de passe:<br><br>"
                            . "<a href='$resetLink'>$resetLink</a><br><br>"
                            . "Ce lien expirera dans 1 heure.<br><br>"
                            . "Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.";
            
            $mail->AltBody = "Bonjour,\n\n"
                           . "Vous avez demandé à réinitialiser votre mot de passe. "
                           . "Copiez-collez le lien suivant dans votre navigateur pour définir un nouveau mot de passe:\n\n"
                           . "$resetLink\n\n"
                           . "Ce lien expirera dans 1 heure.\n\n"
                           . "Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.";
    
            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email à $email: " . $e->getMessage());
            return false;
        }
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: ../View/FrontOffice/forgot_password.php?error=invalid_email');
                exit;
            }
            
            // Vérifier si l'email existe (sans révéler l'information)
            $db = config::getConnexion();
            $stmt = $db->prepare("SELECT iduser FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            
            if ($stmt->fetch()) {
                if ($this->sendPasswordResetEmail($email)) {
                    error_log("Email de réinitialisation envoyé avec succès à $email");
                } else {
                    error_log("Échec d'envoi de l'email de réinitialisation à $email");
                }
            }
            
            // Toujours afficher le même message (sécurité)
            header('Location: ../View/FrontOffice/forgot_password.php?message=reset_email_sent');
            exit;
        }
        
        header('Location: ../View/FrontOffice/forgot_password.php');
        exit;
    }
}

// Gestion des actions
if (isset($_GET['action'])) {
    $userC = new UserC();
    switch ($_GET['action']) {
        case 'forgotPassword':
            $userC->forgotPassword();
            break;
        case 'resetPassword':
            $userC->resetPassword();
            break;
    }
}
?>