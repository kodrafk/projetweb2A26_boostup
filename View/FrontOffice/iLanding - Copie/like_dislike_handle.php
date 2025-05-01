<?php
require_once('C:/xampp/htdocs/Ressources/config.php');

$conn = Config::getConnexion();

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$id || !in_array($action, ['like', 'dislike'])) {
    die(json_encode(['error' => 'Données invalides']));
}

try {
    // Lire l'état actuel
    $stmt = $conn->prepare("SELECT likes, dislikes FROM thematique WHERE id_thematique = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch();

    if (!$current) {
        die(json_encode(['error' => 'ID introuvable']));
    }

    $likes = $current['likes'];
    $dislikes = $current['dislikes'];

    if ($action === 'like') {
        $newLikes = ($likes == 1) ? 0 : 1;
        $newDislikes = 0; // reset dislikes
    } else {
        $newDislikes = ($dislikes == 1) ? 0 : 1;
        $newLikes = 0; // reset likes
    }

    // Mise à jour
    $stmt = $conn->prepare("UPDATE thematique SET likes = ?, dislikes = ? WHERE id_thematique = ?");
    $stmt->execute([$newLikes, $newDislikes, $id]);

    echo json_encode(['likes' => $newLikes, 'dislikes' => $newDislikes]);

} catch (PDOException $e) {
    die(json_encode(['error' => 'Erreur base de données']));
}
