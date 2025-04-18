<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $date_limite = $_POST['date_limite'];
    $id_projet = $_POST['id_projet'];

    $sql = "INSERT INTO objectif (nom, description, status, date_limite, id_projet)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $description, $status, $date_limite, $id_projet]);

    header("Location: objectif.php");
    exit;
}
?>

<form method="POST" action="">
    <input type="text" name="nom" placeholder="Nom" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="text" name="status" placeholder="Status"><br>
    <input type="date" name="date_limite"><br>
    <input type="number" name="id_projet" placeholder="ID Projet"><br>
    <button type="submit">Ajouter</button>
</form>
