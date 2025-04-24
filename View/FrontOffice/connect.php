<?php
// Démarrage de la session
session_start();

include_once(__DIR__ . '/../../config.php');
include_once(__DIR__ . '/../../model/User.php');
require_once(__DIR__ . '/../../controller/UserC.php');

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation des données
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: login.php');
        exit();
    }

    // Création d'une instance du contrôleur User
    $userController = new UserC();

    // Tentative de connexion
    $user = $userController->connecterUser($email, $password);

    if ($user) {
        // Stockage des informations utilisateur en session
        $_SESSION['user'] = [
            'id' => $user->getIdUser(), // Utiliser getIdUser()
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(), // Utiliser getFirstName()
            'lastName' => $user->getLastName(),
            'numtel' => $user->getNumTel(),    
            'type' => $user->getType()
        ];

        // Redirection selon le type d'utilisateur
        switch ($user->getType()) {
            case 'admin':
                header('Location: ../../View/BackOffice/Template_de_backOffice_Luna/index.php');
                break;
            case 'entrepreneur':
            case 'investor':
                header('Location: ../../View/BackOffice/afterLogin/index.php');
                break;
            // ...
        }
        exit();
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: login.php');
        exit();
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers la page de login
    header('Location: login.php');
    exit();
}
?>