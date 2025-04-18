<!DOCTYPE html>
<html>
<head>
    <title>Liste des Tâches</title>
</head>
<body>
    <h1>Liste des Tâches</h1>
    <a href="index.php?action=create">Ajouter une tâche</a>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($taches as $tache): ?>
        <tr>
            <td><?= $tache['id'] ?></td>
            <td><?= $tache['titre'] ?></td>
            <td><?= $tache['description'] ?></td>
            <td>
                <a href="index.php?action=edit&id=<?= $tache['id'] ?>">Modifier</a>
                <a href="index.php?action=delete&id=<?= $tache['id'] ?>" onclick="return confirm('Supprimer cette tâche ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
