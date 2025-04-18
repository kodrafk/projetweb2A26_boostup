<!DOCTYPE html>
<html>
<head>
    <title><?= isset($tache) ? 'Modifier' : 'Ajouter' ?> une Tâche</title>
</head>
<body>
    <h1><?= isset($tache) ? 'Modifier' : 'Ajouter' ?> une Tâche</h1>
    <form method="post" action="index.php?action=<?= isset($tache) ? 'update&id=' . $tache['id'] : 'store' ?>">
        <label>Titre :</label><br>
        <input type="text" name="titre" value="<?= $tache['titre'] ?? '' ?>" required><br><br>
        <label>Description :</label><br>
        <textarea name="description" required><?= $tache['description'] ?? '' ?></textarea><br><br>
        <input type="submit" value="<?= isset($tache) ? 'Modifier' : 'Ajouter' ?>">
    </form>
    <br>
    <a href="index.php?action=index">← Retour à la liste</a>
</body>
</html>
