<?php
include 'config.php';

if (!isset($_GET['id'])) {
    echo "ID manquant.";
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM objectif WHERE id = ?");
$stmt->execute([$id]);
$objectif = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$objectif) {
    echo "Objectif introuvable.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $date_limite = $_POST['date_limite'];

    $sql = "UPDATE objectif SET nom = ?, description = ?, status = ?, date_limite = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $status, $date_limite, $id]);

    header("Location: objectif.php");
    exit;
}
?>

<h2>Modifier Objectif</h2>
<form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($objectif['nom']) ?>" required><br><br>
    <textarea name="description"><?= htmlspecialchars($objectif['description']) ?></textarea><br><br>
    <input type="text" name="status" value="<?= htmlspecialchars($objectif['status']) ?>"><br><br>
    <input type="date" name="date_limite" value="<?= $objectif['date_limite'] ?>"><br><br>
    <button type="submit">Enregistrer les modifications</button>
</form>
