<?php
require_once(__DIR__ . '/../../../config.php');
$conn = new mysqli("localhost", "root", "", "projetweb");
$result = $conn->query("
    SELECT a.id, u.email, a.type_alerte, a.date_alerte
    FROM alertes a
    JOIN user u ON a.user_id = u.iduser
    ORDER BY a.date_alerte DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alertes de sécurité</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>🚨 Alertes de Sécurité</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Email utilisateur</th>
            <th>Type d'alerte</th>
            <th>Date</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['type_alerte'] ?></td>
                <td><?= $row['date_alerte'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
