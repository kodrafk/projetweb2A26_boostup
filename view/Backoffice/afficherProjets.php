<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

// Récupération des catégories
try {
    $stmtCat = $db->query("SELECT id_categorie, nom_categorie FROM Categorie");
    $categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur catégories : " . $e->getMessage();
}

// Filtrage et tri
$selectedCategorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$selectedSort = isset($_GET['sort']) ? $_GET['sort'] : '';

$sql = "SELECT p.*, c.nom_categorie 
        FROM Projet p
        LEFT JOIN Categorie c ON p.id_categorie = c.id_categorie";

$whereClauses = [];
$params = [];

if (!empty($selectedCategorie)) {
    $whereClauses[] = "c.id_categorie = :categorie";
    $params[':categorie'] = $selectedCategorie;
}

if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

if (!empty($selectedSort)) {
    $sql .= " ORDER BY p.$selectedSort";
}

try {
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    }
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}
?>

<style>
/* Polices & couleurs de base */
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f4f6fc;
  color: #333;
  margin: 0;
  padding: 0;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Container principal */
.creative-table-container {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
  padding: 2.5rem;
  margin-top: 3rem;
  animation: fadeIn 1s ease-in-out;
}

/* Titre */
.table-title {
  display: flex;
  align-items: center;
  font-size: 2rem;
  font-weight: 600;
  color: #4d44db;
}

.table-title i {
  margin-right: 10px;
}

/* Filtres */
.filter-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.filter-select {
  padding: 0.7rem 1rem;
  background: #f1f4ff;
  border: 2px solid #e0e0ff;
  border-radius: 12px;
  font-weight: 500;
  color: #444;
  transition: all 0.3s ease-in-out;
}

.filter-select:focus {
  border-color: #6c63ff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
}

.filter-btn {
  padding: 0.6rem 1.5rem;
  border: none;
  border-radius: 12px;
  background: linear-gradient(135deg, #6c63ff, #4d44db);
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.3s;
}

.filter-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
}

/* Table */
.creative-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 2rem;
}

.creative-table thead {
  background: #6c63ff;
  color: #fff;
}

.creative-table th,
.creative-table td {
  padding: 1rem;
  text-align: left;
}

.creative-table tbody tr {
  border-bottom: 1px solid #eaeaea;
  transition: background 0.3s;
}

.creative-table tbody tr:hover {
  background-color: #f1f4ff;
}

/* Badge générique */
.badge {
  display: inline-block;
  background-color: #00c9a7;
  color: white;
  padding: 8px 12px;
  border-radius: 20px;
  font-family: 'Poppins', sans-serif;
  line-height: 1.4;
  max-width: 120px;
  white-space: normal;
  word-break: break-word;
  text-align: center;
  font-size: 0.9rem;
}

/* Badge catégorie */
.badge-category {
  background: #00c896;
  padding: 0.4rem 0.9rem;
  border-radius: 50px;
  color: #fff;
  font-size: 0.85rem;
  font-weight: 500;
}

/* Boutons d’action */
.btn-table {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 1.2rem;
  border-radius: 10px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.3s ease-in-out;
  text-decoration: none;
}

.btn-edit {
  background: #4d44db;
  color: #fff;
}

.btn-delete {
  background: #ff6b6b;
  color: #fff;
}

.btn-table:hover {
  transform: translateY(-2px);
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
}

/* Message de succès */
.success-message {
  background: #00c896;
  color: #fff;
  padding: 1rem;
  border-radius: 12px;
  text-align: center;
  font-weight: 600;
  margin-bottom: 1.5rem;
}
</style>



<div class="creative-table-container animate__animated animate__fadeIn">
    <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime'): ?>
        <div class="success-message">
            <i class="fas fa-check-circle me-2"></i> Projet supprimé avec succès !
        </div>
    <?php endif; ?>
    
    <div class="table-header">
        <h2 class="table-title"><i class="fas fa-project-diagram me-2"></i> Liste des Projets</h2>
        
        <div class="filter-controls">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="categorie" class="filter-select">
                    <option value="">Toutes catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>" <?= ($cat['id_categorie'] == $selectedCategorie) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter me-1"></i> Filtrer
                </button>
            </form>
            
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="sort" class="filter-select">
                    <option value="">Trier par</option>
                    <option value="date_debut" <?= ($selectedSort == 'date_debut') ? 'selected' : '' ?>>Date début</option>
                    <option value="date_fin" <?= ($selectedSort == 'date_fin') ? 'selected' : '' ?>>Date fin</option>
                </select>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-sort me-1"></i> Trier
                </button>
            </form>
        </div>
    </div>
    
    <?php if (count($projets) > 0): ?>
        <div class="table-responsive">
            <table class="creative-table">
                <thead>
                    <tr>
                        <th>Nom du projet</th>
                        <th>Dates</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projets as $projet): ?>
                        <tr>
                            <td data-label="Nom du projet">
                                <strong><?= htmlspecialchars($projet['nom_projet']) ?></strong>
                            </td>
                            <td data-label="Dates">
                                <div><small class="text-muted">Début:</small> <?= htmlspecialchars($projet['date_debut']) ?></div>
                                <div><small class="text-muted">Fin:</small> <?= htmlspecialchars($projet['date_fin']) ?></div>
                            </td>
                            <td data-label="Catégorie">
                                <span class="badge-category">
                                    <?= !empty($projet['nom_categorie']) ? htmlspecialchars($projet['nom_categorie']) : 'Non spécifiée' ?>
                                </span>
                            </td>
                            <td data-label="Description">
                                <?= nl2br(htmlspecialchars(substr($projet['description'], 0, 100))) ?>
                                <?= strlen($projet['description']) > 100 ? '...' : '' ?>
                            </td>
                            <td data-label="Actions">
                                <div class="action-btns">
                                    <a href="/BoostUp/view/Backoffice/modifierProjet.php?id=<?= $projet['id_projet'] ?>" class="btn-table btn-edit">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="/BoostUp/view/Backoffice/supprimerProjet.php?id=<?= $projet['id_projet'] ?>" class="btn-table btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-message">
            <i class="fas fa-folder-open fa-2x mb-3" style="color: #e0e0ff;"></i>
            <h4>Aucun projet disponible</h4>
            <p>Commencez par ajouter un nouveau projet</p>
        </div>
    <?php endif; ?>
</div>
<!-- Ajout du bouton Statistiques et de la section des stats -->
<div style="margin-top: 30px; text-align: center;">
    <button id="statsButton" class="filter-btn" style="padding: 0.8rem 2rem; font-size: 1rem;">
        <i class="fas fa-chart-bar me-1"></i> Afficher les statistiques
    </button>
</div>

<div id="statsContainer" style="display: none; margin-top: 30px; background: #fff; border-radius: 20px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
    <h3 style="color: #4d44db; margin-bottom: 1.5rem;"><i class="fas fa-chart-pie me-2"></i> Statistiques par catégorie</h3>
    
    <?php
    // Récupération des statistiques par catégorie
    try {
        $statsSql = "SELECT 
                        c.nom_categorie, 
                        COUNT(p.id_projet) as nombre_projets,
                        MIN(p.date_debut) as plus_ancien,
                        MAX(p.date_fin) as plus_recent
                    FROM Categorie c
                    LEFT JOIN Projet p ON c.id_categorie = p.id_categorie
                    GROUP BY c.id_categorie, c.nom_categorie";
        
        $stmtStats = $db->query($statsSql);
        $stats = $stmtStats->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<div class='error-message'>Erreur lors de la récupération des statistiques: " . $e->getMessage() . "</div>";
        $stats = [];
    }
    ?>
    
    <?php if (count($stats) > 0): ?>
        <div class="table-responsive">
            <table class="creative-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Nombre de projets</th>
                        <th>Projet le plus ancien</th>
                        <th>Projet le plus récent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats as $stat): ?>
                        <tr>
                            <td><span class="badge-category"><?= htmlspecialchars($stat['nom_categorie']) ?></span></td>
                            <td><?= htmlspecialchars($stat['nombre_projets']) ?></td>
                            <td><?= $stat['plus_ancien'] ? htmlspecialchars($stat['plus_ancien']) : 'N/A' ?></td>
                            <td><?= $stat['plus_recent'] ? htmlspecialchars($stat['plus_recent']) : 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-message">
            <i class="fas fa-chart-line fa-2x mb-3" style="color: #e0e0ff;"></i>
            <h4>Aucune statistique disponible</h4>
            <p>Créez des projets pour voir les statistiques</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Responsive table labels
    document.addEventListener('DOMContentLoaded', function() {
        const ths = document.querySelectorAll('.creative-table thead th');
        const tds = document.querySelectorAll('.creative-table tbody td');
        
        if (window.innerWidth <= 768) {
            tds.forEach((td, index) => {
                if (ths[index]) {
                    td.setAttribute('data-label', ths[index].textContent);
                }
            });
        }
        
        // Gestion du bouton statistiques
        const statsButton = document.getElementById('statsButton');
        const statsContainer = document.getElementById('statsContainer');
        
        statsButton.addEventListener('click', function() {
            if (statsContainer.style.display === 'none') {
                statsContainer.style.display = 'block';
                statsButton.innerHTML = '<i class="fas fa-chart-bar me-1"></i> Masquer les statistiques';
            } else {
                statsContainer.style.display = 'none';
                statsButton.innerHTML = '<i class="fas fa-chart-bar me-1"></i> Afficher les statistiques';
            }
        });
    });
</script>
<script>
    // Responsive table labels
    document.addEventListener('DOMContentLoaded', function() {
        const ths = document.querySelectorAll('.creative-table thead th');
        const tds = document.querySelectorAll('.creative-table tbody td');
        
        if (window.innerWidth <= 768) {
            tds.forEach((td, index) => {
                if (ths[index]) {
                    td.setAttribute('data-label', ths[index].textContent);
                }
            });
        }
    });
</script>