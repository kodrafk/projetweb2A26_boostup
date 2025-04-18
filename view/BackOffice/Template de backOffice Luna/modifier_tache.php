<?php
include 'config.php';

if (!isset($_GET['id'])) {
    echo "ID de la tâche manquant.";
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tache WHERE id = ?");
$stmt->execute([$id]);
$tache = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tache) {
    echo "Tâche introuvable.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $date_limite = $_POST['date_limite'];

    $sql = "UPDATE tache SET nom = ?, description = ?, status = ?, date_echeance = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $status, $date_limite, $id]);

    header("Location: tache.php");
    exit;
}
?>

<h2>Modifier Tâche</h2>
<form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($tache['nom']) ?>" required><br><br>
    <textarea name="description"><?= htmlspecialchars($tache['description']) ?></textarea><br><br>
    <input type="text" name="status" value="<?= htmlspecialchars($tache['status']) ?>"><br><br>
    <input type="date" name="date_limite" value="<?= $tache['date_echeance'] ?>"><br><br>
    <button type="submit">Enregistrer les modifications</button>
</form>
