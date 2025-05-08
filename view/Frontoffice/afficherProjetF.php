<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

// Paramètres de pagination
$projetsParPage = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $projetsParPage;

try {
    // Récupérer toutes les catégories pour la liste déroulante
    $sqlCategories = "SELECT * FROM Categorie";
    $stmtCategories = $db->prepare($sqlCategories);
    $stmtCategories->execute();
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

    // Requête SQL pour compter le nombre total de projets (avec filtres)
    $sqlCount = "SELECT COUNT(*) as total FROM Projet p WHERE 1=1";
    $params = [];
    
    // Filtrage par catégorie si sélectionné
    if (!empty($_POST['categorie'])) {
        $sqlCount .= " AND p.id_categorie = :categorie";
        $params[':categorie'] = $_POST['categorie'];
    }
    
    // Filtrage par date de début si spécifiée
    if (!empty($_POST['date_debut'])) {
        $sqlCount .= " AND p.date_debut >= :date_debut";
        $params[':date_debut'] = $_POST['date_debut'];
    }
    
    // Filtrage par date de fin si spécifiée
    if (!empty($_POST['date_fin'])) {
        $sqlCount .= " AND p.date_fin <= :date_fin";
        $params[':date_fin'] = $_POST['date_fin'];
    }
    
    // Exécuter la requête de comptage
    $stmtCount = $db->prepare($sqlCount);
    foreach ($params as $key => $value) {
        $stmtCount->bindValue($key, $value);
    }
    $stmtCount->execute();
    $totalProjets = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProjets / $projetsParPage);

    // Requête SQL pour récupérer les projets avec pagination
    $sql = "SELECT p.*, c.nom_categorie 
            FROM Projet p
            LEFT JOIN Categorie c ON p.id_categorie = c.id_categorie 
            WHERE 1=1";
    
    // Ajouter les mêmes conditions de filtrage
    if (!empty($_POST['categorie'])) {
        $sql .= " AND p.id_categorie = :categorie";
    }
    
    if (!empty($_POST['date_debut'])) {
        $sql .= " AND p.date_debut >= :date_debut";
    }
    
    if (!empty($_POST['date_fin'])) {
        $sql .= " AND p.date_fin <= :date_fin";
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
    $sql .= " LIMIT :limit OFFSET :offset";
    
    $stmt = $db->prepare($sql);
    
    // Bind des paramètres de filtrage
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    // Bind des paramètres de pagination
    $stmt->bindValue(':limit', $projetsParPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            max-width: 1400px; 
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
            padding: 30px; /* Padding accru */
            transition: 0.3s ease;
            position: relative;
            min-height: 500px; /* Hauteur minimale augmentée */
            display: flex;
            flex-direction: column;
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
        
        .btn-investir {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-investir:hover {
            background-color: #218838;
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
        
        .btn-like.active {
            background-color: #28a745;
            color: white;
        }

        .btn-dislike.active {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-like, .btn-dislike {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 5px 10px;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .btn-like:hover {
            background-color: #28a745;
            color: white;
        }

        .btn-dislike:hover {
            background-color: #dc3545;
            color: white;
        }

        .like-dislike-container {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        .like-dislike-form {
            display: flex;
            gap: 10px;
        }
        
        /* Styles pour la pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }
        
        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .pagination a.active {
            background-color: #6c63ff;
            color: white;
        }
        
        .pagination a:not(.active) {
            background-color: #f1f1f1;
            color: black;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
        
        .pagination .disabled {
            color: #aaa;
            pointer-events: none;
            cursor: default;
        }

        /* Styles pour la barre de progression */
        .progress-container {
            margin: 15px 0;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.85rem;
            color: #555;
        }

        .progress-bar {
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #6c63ff;
            border-radius: 5px;
            transition: width 0.3s ease;
        }

        .btn-pay {
            background-color: #6c63ff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s;
        }

        .btn-pay:hover {
            background-color: #5a52d4;
        }

        .montant-error {
            color: red;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
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
                        <option value="date_recente" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'date_recente') ? 'selected' : '' ?>>Date de début (récente)</option>
                        <option value="date_ancienne" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'date_ancienne') ? 'selected' : '' ?>>Date de début (ancienne)</option>
                        <option value="fin_recente" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'fin_recente') ? 'selected' : '' ?>>Date de fin (récente)</option>
                        <option value="fin_ancienne" <?= (!empty($_POST['tri']) && $_POST['tri'] == 'fin_ancienne') ? 'selected' : '' ?>>Date de fin (ancienne)</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <?php if (count($projets) > 0): ?>
        <div class="grid">
            <?php foreach ($projets as $projet): 
                // Calcul du pourcentage payé
                $montantTotal = (float)$projet['montant'];
                $montantPaye = (float)$projet['montant_paye'];
                $pourcentagePaye = $montantTotal > 0 ? min(100, ($montantPaye / $montantTotal) * 100) : 0;
                $montantRestant = $montantTotal - $montantPaye;
            ?>
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
                    <p><strong>Montant total :</strong> <?= number_format($montantTotal, 2, ',', ' ') ?> €</p>
                    <p><strong>Montant payé :</strong> <?= number_format($montantPaye, 2, ',', ' ') ?> €</p>
                    <p><strong>Montant restant :</strong> <?= number_format($montantRestant, 2, ',', ' ') ?> €</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress-container">
                        <div class="progress-info">
                            <span>Progression du paiement</span>
                            <span><?= number_format($pourcentagePaye, 2) ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $pourcentagePaye ?>%;"></div>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <a href="/BoostUp/view/Frontoffice/modifierProjetF.php?id=<?= $projet['id_projet'] ?>" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </a>
                        <a href="/BoostUp/view/Frontoffice/supprimerProjetF.php?id=<?= $projet['id_projet'] ?>" class="btn btn-delete"
                           onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                        <a href="/BoostUp/view/Frontoffice/investirProjetF.php?id_projet=<?= $projet['id_projet'] ?>" class="btn btn-investir">
                            <i class="bi bi-currency-dollar"></i> Investir
                        </a>
                    </div>
                    
                    <div class="like-dislike-container">
                        <form class="like-dislike-form" data-projet-id="<?= $projet['id_projet'] ?>">
                            <input type="hidden" name="projet_id" value="<?= $projet['id_projet'] ?>">
                            <button type="button" name="action" value="like" class="btn btn-like">
                                👍 Like (<span class="like-count"><?= $projet['likes'] ?? 0 ?></span>)
                            </button>
                            <button type="button" name="action" value="dislike" class="btn btn-dislike">
                                👎 Dislike (<span class="dislike-count"><?= $projet['dislikes'] ?? 0 ?></span>)
                            </button>
                        </form>
                    </div>
                    
                    <!-- Formulaire de paiement -->
                    <div class="payment-container" style="margin-top: 10px;">
                        <button onclick="document.getElementById('payment-form-<?= $projet['id_projet']; ?>').style.display='block'" class="btn btn-pay">
                            💳 Payer
                        </button>

                        <form id="payment-form-<?= $projet['id_projet']; ?>" action="/BoostUp/view/Frontoffice/payer.php" method="POST" style="display: none; margin-top: 10px;">
                            <input type="hidden" name="id_projet" value="<?= $projet['id_projet']; ?>">
                            <input type="number" id="montant-<?= $projet['id_projet']; ?>" name="montant_paye" placeholder="Entrez le montant (€)" step="0.01" class="form-control" style="margin-bottom: 5px;">
                            <div id="error-<?= $projet['id_projet']; ?>" class="montant-error"></div>
                            <button type="button" onclick="validatePayment(<?= $projet['id_projet']; ?>, <?= $montantRestant; ?>)" class="btn btn-success">Confirmer et Payer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1<?= !empty($_POST) ? '&' . http_build_query($_POST) : '' ?>">« Première</a>
                <a href="?page=<?= $page - 1 ?><?= !empty($_POST) ? '&' . http_build_query($_POST) : '' ?>">‹ Précédent</a>
            <?php else: ?>
                <span class="disabled">« Première</span>
                <span class="disabled">‹ Précédent</span>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                <a href="?page=<?= $i ?><?= !empty($_POST) ? '&' . http_build_query($_POST) : '' ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?><?= !empty($_POST) ? '&' . http_build_query($_POST) : '' ?>">Suivant ›</a>
                <a href="?page=<?= $totalPages ?><?= !empty($_POST) ? '&' . http_build_query($_POST) : '' ?>">Dernière »</a>
            <?php else: ?>
                <span class="disabled">Suivant ›</span>
                <span class="disabled">Dernière »</span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="message">Aucun projet disponible pour le moment.</div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    $('.like-dislike-form button').click(function(e) {
        e.preventDefault();
        
        const form = $(this).closest('form');
        const projetId = form.data('projet-id');
        const action = $(this).val();
        
        // Désactiver les boutons pendant la requête
        form.find('button').prop('disabled', true);
        
        $.ajax({
            url: 'handleLikeDislike.php',
            type: 'POST',
            data: {
                projet_id: projetId,
                action: action
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour les compteurs
                    form.find('.like-count').text(response.likes);
                    form.find('.dislike-count').text(response.dislikes);
                    
                    // Feedback visuel
                    const clickedBtn = form.find(`button[value="${action}"]`);
                    clickedBtn.addClass('active');
                    setTimeout(() => clickedBtn.removeClass('active'), 500);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur:", error);
            },
            complete: function() {
                form.find('button').prop('disabled', false);
            }
        });
    });
});

function validatePayment(projetId, montantRestant) {
    const input = document.getElementById('montant-' + projetId);
    const errorDiv = document.getElementById('error-' + projetId);
    const montant = parseFloat(input.value);
    
    // Réinitialiser le message d'erreur
    errorDiv.style.display = 'none';
    errorDiv.textContent = '';
    
    // Validation
    if (isNaN(montant) || montant <= 0) {
        errorDiv.textContent = "❌ Veuillez entrer un montant valide (supérieur à 0).";
        errorDiv.style.display = 'block';
        return;
    }
    
    if (montant > montantRestant) {
        errorDiv.textContent = "❌ Le montant ne peut pas dépasser le montant restant (" + montantRestant.toFixed(2) + " €).";
        errorDiv.style.display = 'block';
        return;
    }
    
    // Si tout est valide, soumettre le formulaire
    document.getElementById('payment-form-' + projetId).submit();
}
</script>

</body>
</html>