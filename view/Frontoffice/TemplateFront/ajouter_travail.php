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

    error_log("Task $tache_id: PDF count = $pdfCount, Photo count = $photoCount, Calculated percentage = $totalPercentage");
    return min($totalPercentage, 100); // Cap at 100%
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reconnect if the connection is lost
    global $dbConfig;
    if (!$pdo || !$pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS)) {
        $pdo = reconnectDatabase($pdo, $dbConfig);
    }

    // Retrieve form data
    $tache_id = isset($_POST['tache_id']) ? (int)$_POST['tache_id'] : 0;
    $commentaire = isset($_POST['commentaire']) ? trim($_POST['commentaire']) : '';

    // Validate required fields
    if ($tache_id <= 0) {
        die("Veuillez sélectionner une tâche.");
    }

    // Handle file upload
    if (!isset($_FILES['piece_jointe']) || $_FILES['piece_jointe']['error'] === UPLOAD_ERR_NO_FILE) {
        die("Veuillez sélectionner une pièce jointe.");
    }

    $file = $_FILES['piece_jointe'];
    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedTypes)) {
        die("Seuls les fichiers PDF, JPG, JPEG, PNG sont autorisés.");
    }

    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxFileSize) {
        die("Le fichier est trop volumineux. La taille maximale est de 5 Mo.");
    }

    $uploadDir = __DIR__ . '/../../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = uniqid('pj_', true) . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        die("Erreur lors du téléchargement du fichier.");
    }

    $date_creation = date('Y-m-d H:i:s');

    // Check total attachments for this task
    $queryCount = $pdo->prepare("SELECT COUNT(*) as total FROM travaux WHERE tache_id = :tache_id");
    $queryCount->execute([':tache_id' => $tache_id]);
    $totalAttachments = $queryCount->fetchColumn();

    if ($totalAttachments >= 3) {
        die("Limite maximale de 3 pièces jointes atteinte pour cette tâche.");
    }

    // Calculate new validation percentage (before inserting new attachment)
    $currentPercentage = calculateValidationPercentage($pdo, $tache_id);

    // Adjust percentage based on the new attachment
    $newPercentage = $currentPercentage;
    if ($fileExtension === 'pdf') {
        $newPercentage += 40; // Add 40% for a PDF
    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
        $newPercentage += 20; // Add 20% for a photo
    }
    $newPercentage = min($newPercentage, 100); // Cap at 100%
    error_log("Task $tache_id: New attachment type = $fileExtension, Updated percentage = $newPercentage");

    // Begin transaction
    try {
        $pdo->beginTransaction();

        // Insert into travaux table
        $stmt = $pdo->prepare("INSERT INTO travaux (commentaire, piece_jointe, date_creation, tache_id) VALUES (:commentaire, :piece_jointe, :date_creation, :tache_id)");
        $stmt->execute([
            ':commentaire' => $commentaire,
            ':piece_jointe' => $fileName,
            ':date_creation' => $date_creation,
            ':tache_id' => $tache_id
        ]);

        // Update validation_percentage in tache table
        $stmtUpdate = $pdo->prepare("UPDATE tache SET validation_percentage = :validation_percentage WHERE id = :tache_id");
        $affectedRows = $stmtUpdate->execute([
            ':validation_percentage' => $newPercentage,
            ':tache_id' => $tache_id
        ]);
        error_log("Task $tache_id updated with validation_percentage = $newPercentage, Affected rows: $affectedRows");

        // Verify the update by fetching the new value
        $verifyStmt = $pdo->prepare("SELECT validation_percentage FROM tache WHERE id = :tache_id");
        $verifyStmt->execute([':tache_id' => $tache_id]);
        $updatedPercentage = $verifyStmt->fetchColumn();
        error_log("Task $tache_id: After update, validation_percentage in DB = " . ($updatedPercentage !== false ? $updatedPercentage : 'NULL'));

        $pdo->commit();
        header("Location: tacheF.php?refresh=true");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erreur SQL : " . $e->getMessage());
        if ($e->getCode() === '2002' || $e->getCode() === 'HY000') {
            $pdo = reconnectDatabase($pdo, $dbConfig);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO travaux (commentaire, piece_jointe, date_creation, tache_id) VALUES (:commentaire, :piece_jointe, :date_creation, :tache_id)");
            $stmt->execute([
                ':commentaire' => $commentaire,
                ':piece_jointe' => $fileName,
                ':date_creation' => $date_creation,
                ':tache_id' => $tache_id
            ]);

            $stmtUpdate = $pdo->prepare("UPDATE tache SET validation_percentage = :validation_percentage WHERE id = :tache_id");
            $affectedRows = $stmtUpdate->execute([
                ':validation_percentage' => $newPercentage,
                ':tache_id' => $tache_id
            ]);
            error_log("Task $tache_id updated with validation_percentage = $newPercentage after reconnect, Affected rows: $affectedRows");

            $verifyStmt = $pdo->prepare("SELECT validation_percentage FROM tache WHERE id = :tache_id");
            $verifyStmt->execute([':tache_id' => $tache_id]);
            $updatedPercentage = $verifyStmt->fetchColumn();
            error_log("Task $tache_id: After update (reconnect), validation_percentage in DB = " . ($updatedPercentage !== false ? $updatedPercentage : 'NULL'));

            $pdo->commit();
            header("Location: tacheF.php?refresh=true");
            exit;
        } else {
            die("Erreur lors de l'ajout dans la base de données : " . $e->getMessage());
        }
    }
} else {
    header("Location: tacheF.php");
    exit;
}
?>