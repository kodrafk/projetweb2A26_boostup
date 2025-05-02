<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

try {
    // Récupérer toutes les catégories pour la liste déroulante
    $sqlCategories = "SELECT * FROM Categorie";
    $stmtCategories = $db->prepare($sqlCategories);
    $stmtCategories->execute();
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

    // Requête SQL de base
    $sql = "SELECT p.*, c.nom_categorie 
            FROM Projet p
            LEFT JOIN Categorie c ON p.id_categorie = c.id_categorie 
            WHERE 1=1";
    
    $params = [];
    
    // Filtrage par catégorie si sélectionné
    if (!empty($_POST['categorie'])) {
        $sql .= " AND p.id_categorie = :categorie";
        $params[':categorie'] = $_POST['categorie'];
    }
    
    // Filtrage par date de début si spécifiée
    if (!empty($_POST['date_debut'])) {
        $sql .= " AND p.date_debut >= :date_debut";
        $params[':date_debut'] = $_POST['date_debut'];
    }
    
    // Filtrage par date de fin si spécifiée
    if (!empty($_POST['date_fin'])) {
        $sql .= " AND p.date_fin <= :date_fin";
        $params[':date_fin'] = $_POST['date_fin'];
    }
    
    // Tri des résultats si sélectionné
    $orderBy = " ORDER BY p.date_debut DESC"; // Par défaut: tri par date récente
    if (!empty($_POST['tri'])) {
        switch ($_POST['tri']) {
            case 'date_ancienne':
                $orderBy = " ORDER BY p.date_debut ASC";
                break;
            case 'date_recente':
                $orderBy = " ORDER BY p.date_debut DESC";
                break;
            case 'fin_ancienne':
                $orderBy = " ORDER BY p.date_fin ASC";
                break;
            case 'fin_recente':
                $orderBy = " ORDER BY p.date_fin DESC";
                break;
        }
    }
    
    $sql .= $orderBy;
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
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

        .thematique {
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

        /* Styles pour les filtres */
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #555;
        }

        select, input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
        }

        .filter-btn {
            background: #6c63ff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s;
        }

        .filter-btn:hover {
            background: #5a52d4;
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

    <!-- Formulaire de filtrage -->
    <div class="filters">
        <form method="post">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="categorie">Catégorie</label>
                    <select id="categorie" name="categorie">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['id_categorie'] ?>" <?= (!empty($_POST['categorie']) && $_POST['categorie'] == $categorie['id_categorie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="date_debut">Date de début</label>
                    <input type="date" id="date_debut" name="date_debut" value="<?= !empty($_POST['date_debut']) ? htmlspecialchars($_POST['date_debut']) : '' ?>">
                </div>
                
                <div class="filter-item">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" id="date_fin" name="date_fin" value="<?= !empty($_POST['date_fin']) ? htmlspecialchars($_POST['date_fin']) : '' ?>">
                </div>
            </div>
            
            <button type="submit" class="filter-btn">
                <i class="bi bi-search"></i> Rechercher
            </button>
        </form>
    </div>

    <!-- Formulaire de tri -->
    <div class="filters" style="margin-top: 20px;">
        <form method="post">
            <div class="filter-group">
                <div class="filter-item">
                    <label for="tri">Trier par</label>
                    <select id="tri" name="tri" onchange="this.form.submit()">
                        <option value="date_debut" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'date_debut') ? 'selected' : '' ?>>Date de début </option>
                        <option value="date_fin" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'date_fin') ? 'selected' : '' ?>>Date de fin </option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <?php if (count($projets) > 0): ?>
        <div class="grid">
            <?php foreach ($projets as $projet): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($projet['nom_projet']) ?></h3>
                    <div class="date">
                        📅 Du <?= htmlspecialchars($projet['date_debut']) ?> au <?= htmlspecialchars($projet['date_fin']) ?>
                    </div>
                    <div class="thematique">
                        🎯 Catégorie du projet: <?= !empty($projet['nom_categorie']) ? htmlspecialchars($projet['nom_categorie']) : 'Non spécifiée' ?>
                    </div>
                    <div class="description">
                        <strong>Description du projet :</strong>
                        <p><?= nl2br(htmlspecialchars($projet['description'])) ?></p>
                    </div>
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
    </div>
</div>

</body>
</html>