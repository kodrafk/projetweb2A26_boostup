<?php
// view/liste.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Tâches</title>

    <!-- Lien vers le CSS dans le dossier view -->
    <link rel="stylesheet" href="/website1.0/view/BackOffice/Template de backOffice Luna/css/style.css">
</head>
<body>

    <h1>Liste des Tâches</h1>

    <a href="/website1.0/controller/TacheController.php?action=ajouter">➕ Ajouter une tâche</a>

    <ul>
        <?php foreach ($taches as $tache): ?>
            <li>
                <?= htmlspecialchars($tache['titre']) ?> - 
                <a href="/website1.0/controller/TacheController.php?action=modifier&id=<?= $tache['id'] ?>">✏️ Modifier</a> | 
                <a href="/website1.0/controller/TacheController.php?action=supprimer&id=<?= $tache['id'] ?>" onclick="return confirm('Supprimer cette tâche ?')">🗑️ Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Lien vers ton script JS -->
    <script src="/website1.0/view/BackOffice/Template de backOffice Luna/js/script.js"></script>

</body>
</html>

