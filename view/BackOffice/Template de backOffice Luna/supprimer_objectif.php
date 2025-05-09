<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM objectif WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: objectif.php");
    exit;
}
<?php
require_once 'config.php';
require_once 'controllers/ObjectifController.php';

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $objectifController = new ObjectifController();
    
    // Supprimer l'objectif (l'historique est géré dans la méthode)
    $objectifController->deleteObjectif($id);
    
    // Redirection vers la page des objectifs
    header("Location: objectif.php?deleted=success");
    exit;
} else {
    // Redirection si l'ID n'est pas fourni
    header("Location: objectif.php?error=id_missing");
    exit;
}
?>