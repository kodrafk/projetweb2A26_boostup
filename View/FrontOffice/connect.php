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

        // Récupération de l'IP de l'utilisateur
        $ip = $_SERVER['REMOTE_ADDR'];
        $token = '717019b9340c72'; // Remplace par ta clé API IPinfo.io

        // Appel API IPinfo pour obtenir la localisation de l'IP
        $url = "https://ipinfo.io/{$ip}?token={$token}";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        // Extraction des informations
        $ville = isset($data['city']) ? $data['city'] : 'Inconnue';
        $pays = isset($data['country']) ? $data['country'] : 'Inconnu';
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // Insertion dans la table connexions
        $conn = config::getConnexion();
        $query = "INSERT INTO connexions (user_id, ip, user_agent, ville, pays, is_successful) 
                  VALUES (:user_id, :ip, :user_agent, :ville, :pays, 1)";
        $stmt = $conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':user_id', $_SESSION['user']['id']);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':user_agent', $user_agent);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':pays', $pays);

        // Exécution de la requête
        $stmt->execute();

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
