<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once __DIR__ . '/../../../config/db.php';

// Function to reconnect to the database
function reconnectDatabase($pdo, $dbConfig) {
    try {
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};port=3306;dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
            $dbConfig['username'],
            $dbConfig['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => false]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Reconnexion échouée : " . $e->getMessage());
        die("Impossible de se reconnecter à la base de données : " . $e->getMessage());
    }
}

// Function to calculate validation percentage
function calculateValidationPercentage($pdo, $tache_id) {
    $query = $pdo->prepare("SELECT piece_jointe FROM travaux WHERE tache_id = :tache_id");
    $query->execute([':tache_id' => $tache_id]);
    $attachments = $query->fetchAll(PDO::FETCH_ASSOC);

    $pdfCount = 0;
    $photoCount = 0;
    $maxPdfs = 2;
    $maxPhotos = 1;

    foreach ($attachments as $attachment) {
        $extension = strtolower(pathinfo($attachment['piece_jointe'], PATHINFO_EXTENSION));
        if (in_array($extension, ['pdf'])) {
            $pdfCount = min($pdfCount + 1, $maxPdfs); // Limit to 2 PDFs
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $photoCount = min($photoCount + 1, $maxPhotos); // Limit to 1 photo
        }
    }

    $totalPercentage = 0;
    if ($photoCount > 0) {
        $totalPercentage += 20; // 20% for 1 photo
    }
    $totalPercentage += $pdfCount * 40; // 40% per PDF, up to 2 PDFs (80%)

    error_log("Task $tache_id: After deletion - PDF count = $pdfCount, Photo count = $photoCount, Calculated percentage = $totalPercentage");
    return min($totalPercentage, 100); // Cap at 100%
}

// Check if the request method is GET and id is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Reconnect if the connection is lost
    global $dbConfig;
    if (!$pdo || !$pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS)) {
        $pdo = reconnectDatabase($pdo, $dbConfig);
    }

    $travail_id = (int)$_GET['id'];

    // Fetch the piece_jointe and tache_id before deletion
    $stmt = $pdo->prepare("SELECT piece_jointe, tache_id FROM travaux WHERE id = :id");
    $stmt->execute([':id' => $travail_id]);
    $travail = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$travail) {
        die("Pièce jointe non trouvée.");
    }

    $tache_id = $travail['tache_id'];
    $fileName = $travail['piece_jointe'];
    $filePath = __DIR__ . '/../../../uploads/' . $fileName;

    // Begin transaction
    try {
        $pdo->beginTransaction();

        // Delete the piece_jointe from travaux table
        $stmtDelete = $pdo->prepare("DELETE FROM travaux WHERE id = :id");
        $stmtDelete->execute([':id' => $travail_id]);

        // Delete the file from the server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Recalculate the validation percentage
        $newPercentage = calculateValidationPercentage($pdo, $tache_id);

        // Update validation_percentage in tache table
        $stmtUpdate = $pdo->prepare("UPDATE tache SET validation_percentage = :validation_percentage WHERE id = :tache_id");
        $affectedRows = $stmtUpdate->execute([
            ':validation_percentage' => $newPercentage,
            ':tache_id' => $tache_id
        ]);
        error_log("Task $tache_id updated with validation_percentage = $newPercentage after deletion, Affected rows: $affectedRows");

        // Verify the update
        $verifyStmt = $pdo->prepare("SELECT validation_percentage FROM tache WHERE id = :tache_id");
        $verifyStmt->execute([':tache_id' => $tache_id]);
        $updatedPercentage = $verifyStmt->fetchColumn();
        error_log("Task $tache_id: After deletion update, validation_percentage in DB = " . ($updatedPercentage !== false ? $updatedPercentage : 'NULL'));

        $pdo->commit();
        header("Location: tacheF.php?refresh=true");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erreur SQL : " . $e->getMessage());
        if ($e->getCode() === '2002' || $e->getCode() === 'HY000') {
            $pdo = reconnectDatabase($pdo, $dbConfig);
            $pdo->beginTransaction();

            $stmtDelete = $pdo->prepare("DELETE FROM travaux WHERE id = :id");
            $stmtDelete->execute([':id' => $travail_id]);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $newPercentage = calculateValidationPercentage($pdo, $tache_id);
            $stmtUpdate = $pdo->prepare("UPDATE tache SET validation_percentage = :validation_percentage WHERE id = :tache_id");
            $affectedRows = $stmtUpdate->execute([
                ':validation_percentage' => $newPercentage,
                ':tache_id' => $tache_id
            ]);
            error_log("Task $tache_id updated with validation_percentage = $newPercentage after deletion (reconnect), Affected rows: $affectedRows");

            $verifyStmt = $pdo->prepare("SELECT validation_percentage FROM tache WHERE id = :tache_id");
            $verifyStmt->execute([':tache_id' => $tache_id]);
            $updatedPercentage = $verifyStmt->fetchColumn();
            error_log("Task $tache_id: After deletion update (reconnect), validation_percentage in DB = " . ($updatedPercentage !== false ? $updatedPercentage : 'NULL'));

            $pdo->commit();
            header("Location: tacheF.php?refresh=true");
            exit;
        } else {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }
} else {
    header("Location: tacheF.php");
    exit;
}
?>