<?php
session_start();

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../../../controller/UserC.php');

if (!isset($_SESSION['user'])) {
    header('Location: ../../FrontOffice/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
            throw new Exception("ID utilisateur invalide");
        }

        $id = (int)$_POST['id'];
        $firstName = trim(htmlspecialchars($_POST['firstName']));
        $lastName = trim(htmlspecialchars($_POST['lastName']));
        $email = trim(htmlspecialchars($_POST['email']));
        $numtel = trim(htmlspecialchars($_POST['numtel']));
        $type = $_POST['type'];

        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide");
        }

        $userController = new UserC();
        $result = $userController->modifierProfil(
            $id,
            $firstName,
            $lastName,
            $email,
            $numtel,
            $type
            
        );

        if ($result) {
            // Mise à jour de la session sans toucher au mot de passe
            $_SESSION['user']['firstName'] = $firstName;
            $_SESSION['user']['lastName'] = $lastName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['numtel'] = $numtel;
            $_SESSION['user']['type'] = $type;

            $_SESSION['success_message'] = "Profil mis à jour avec succès!";
        } else {
            $_SESSION['error_message'] = "Aucune modification effectuée";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur: ".$e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>