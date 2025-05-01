<?php
require_once(__DIR__ . '/../../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = Config::getConnexion();
    
    $id = $_POST['id_thematique'];
    $action = $_POST['action'];

    if ($action === 'flooter') {
        $stmt = $conn->prepare("UPDATE thematique 
            SET flouter = 1, 
                refuser = 0,
                signalee = 0 
            WHERE id_thematique = ?");
    } elseif ($action === 'refuser') {
        $stmt = $conn->prepare("UPDATE thematique 
            SET refuser = 1,
                flouter = 0,
                signalee = 0 
            WHERE id_thematique = ?");
    }

    if ($stmt->execute([$id])) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Action effectuée avec succès!'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Erreur lors du traitement'
        ];
    }
}

header('Location: thematique.php');
exit;