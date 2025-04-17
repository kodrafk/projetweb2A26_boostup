<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

try {
    $sql = "SELECT * FROM Projet";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Projets - Vue Moderne</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #333;
            font-size: 2rem;
            position: relative;
            display: inline-block;
        }

        .header h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background-color: #6c63ff;
            margin: 8px auto 0;
            border-radius: 2px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .card {
            background-color: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            transition: 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            font-size: 1.25rem;
            color: #6c63ff;
        }

        .card p {
            color: #444;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .date {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 12px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .btn-edit {
            background: #6c63ff;
            color: white;
        }

        .btn-edit:hover {
            background: #5a52d4;
        }

        .btn-delete {
            background: #ff6b6b;
            color: white;
        }

        .btn-delete:hover {
            background: #e35656;
        }

        .add-btn {
            display: inline-block;
            margin-top: 30px;
            background: #00c896;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
        }

        .add-btn:hover {
            background: #00ae82;
        }

        .message {
            text-align: center;
            color: #28a745;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1><i class="bi bi-folder2-open"></i> Mes Projets</h1>
    </div>

    <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime'): ?>
        <div class="message">✅ Projet supprimé avec succès !</div>
    <?php endif; ?>

    <?php if (count($projets) > 0): ?>
        <div class="grid">
            <?php foreach ($projets as $projet): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($projet['nom_projet']) ?></h3>
                    <div class="date">
                        📅 Du <?= htmlspecialchars($projet['date_debut']) ?> au <?= htmlspecialchars($projet['date_fin']) ?>
                    </div>
                    <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
                    <div class="actions">
                        <a href="/BoostUp/view/Frontoffice/modifierProjetF.php?id=<?= $projet['id_projet'] ?>" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </a>
                        <a href="/BoostUp/view/Frontoffice/supprimerProjetF.php?id=<?= $projet['id_projet'] ?>" class="btn btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="message">Aucun projet disponible pour le moment.</div>
    <?php endif; ?>

    <div style="text-align: center;">
        <a href="index.php?action=ajouter" class="add-btn"><i class="bi bi-plus-circle"></i> Ajouter un projet</a>
    </div>
</div>

</body>
</html>
