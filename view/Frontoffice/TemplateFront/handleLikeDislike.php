<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projetId = $_POST['projet_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$projetId || !$action) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit;
    }

    try {
        if ($action === 'like') {
            $sql = "UPDATE Projet SET likes = likes + 1 WHERE id_projet = :projet_id";
        } elseif ($action === 'dislike') {
            $sql = "UPDATE Projet SET likes = likes - 1 WHERE id_projet = :projet_id AND likes > 0";
        } else {
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            exit;
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':projet_id', $projetId, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les nouveaux totaux
        $sql = "SELECT likes FROM Projet WHERE id_projet = :projet_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':projet_id', $projetId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'likes' => $result['likes']
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
