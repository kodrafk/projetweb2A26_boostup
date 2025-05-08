<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('C:/xampp/htdocs/BoostUp/config/database.php');
$db = getDB();

// Paramètres de pagination
$projetsParPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $projetsParPage;

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

// Requête pour compter le nombre total de projets
$sqlCount = "SELECT COUNT(*) as total FROM Projet p";
$whereClauses = [];
$params = [];

if (!empty($selectedCategorie)) {
    $whereClauses[] = "p.id_categorie = :categorie";
    $params[':categorie'] = $selectedCategorie;
}

if (count($whereClauses) > 0) {
    $sqlCount .= " WHERE " . implode(' AND ', $whereClauses);
}

try {
    $stmtCount = $db->prepare($sqlCount);
    foreach ($params as $key => $value) {
        $stmtCount->bindValue($key, $value, PDO::PARAM_INT);
    }
    $stmtCount->execute();
    $totalProjets = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalProjets / $projetsParPage);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}

// Requête pour récupérer les projets avec pagination
$sql = "SELECT p.*, c.nom_categorie 
        FROM Projet p
        LEFT JOIN Categorie c ON p.id_categorie = c.id_categorie";

$whereClauses = [];
$params = [];

if (!empty($selectedCategorie)) {
    $whereClauses[] = "p.id_categorie = :categorie";
    $params[':categorie'] = $selectedCategorie;
}

if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

if (!empty($selectedSort)) {
    $sql .= " ORDER BY p.$selectedSort";
}

// Ajout de la pagination
$sql .= " LIMIT :limit OFFSET :offset";
$params[':limit'] = $projetsParPage;
$params[':offset'] = $offset;

try {
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        if ($key === ':limit' || $key === ':offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
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
:root {
  --primary-color: #6c63ff;
  --secondary-color: #4d44db;
  --accent-color: #00c896;
  --light-bg: #f8f9ff;
  --dark-text: #2a2a72;
  --success-color: #00c896;
  --danger-color: #ff6b6b;
  --warning-color: #ffc107;
  --info-color: #17a2b8;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f4f6fc;
  color: var(--dark-text);
  margin: 0;
  padding: 0;
}

/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Container principal */
.projects-container {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  padding: 2rem;
  margin: 2rem auto;
  max-width: 1200px;
  animation: fadeIn 0.5s ease-in-out;
}

/* Titre */
.projects-title {
  display: flex;
  align-items: center;
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--primary-color);
  margin-bottom: 1.5rem;
}

.projects-title i {
  margin-right: 12px;
  font-size: 1.5rem;
}

/* Filtres */
.filter-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.filter-group {
  display: flex;
  align-items: center;
  background: var(--light-bg);
  border-radius: 12px;
  padding: 0.5rem;
}

.filter-select {
  padding: 0.7rem 1rem;
  background: #fff;
  border: 2px solid #e0e0ff;
  border-radius: 10px;
  font-weight: 500;
  color: var(--dark-text);
  transition: all 0.3s ease;
  min-width: 200px;
}

.filter-select:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
}

.filter-btn {
  padding: 0.7rem 1.5rem;
  border: none;
  border-radius: 10px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
}

/* Articles projets */
.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.project-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  border: 1px solid #e0e0ff;
  display: flex;
  flex-direction: column;
}

.project-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(108, 99, 255, 0.1);
}

.project-header {
  padding: 1.5rem 1.5rem 0;
}

.project-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--dark-text);
  margin-bottom: 0.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.project-category {
  display: inline-block;
  padding: 0.3rem 0.8rem;
  background-color: var(--primary-color);
  color: white;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 500;
}

.project-body {
  padding: 0 1.5rem 1rem;
  flex-grow: 1;
}

.project-description {
  color: #666;
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.project-dates {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
  color: #888;
  margin-bottom: 1rem;
}

.project-date {
  display: flex;
  flex-direction: column;
}

.project-date-label {
  font-weight: 500;
  font-size: 0.7rem;
  color: var(--primary-color);
}

.project-footer {
  padding: 1rem 1.5rem;
  background: var(--light-bg);
  border-top: 1px solid #e0e0ff;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.project-amount {
  font-weight: 600;
  color: var(--secondary-color);
}

.project-actions {
  display: flex;
  gap: 0.5rem;
}

/* Boutons d'action */
.btn {
  padding: 0.5rem 0.8rem;
  border-radius: 8px;
  font-weight: 500;
  font-size: 0.8rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
  transition: all 0.2s ease;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background-color: var(--primary-color);
  color: white;
}

.btn-primary:hover {
  background-color: var(--secondary-color);
  transform: translateY(-2px);
  box-shadow: 0 3px 10px rgba(108, 99, 255, 0.3);
}

.btn-danger {
  background-color: var(--danger-color);
  color: white;
}

.btn-danger:hover {
  background-color: #e05a5a;
  transform: translateY(-2px);
  box-shadow: 0 3px 10px rgba(255, 107, 107, 0.3);
}

.btn-pdf {
  background-color: #ff6b6b;
  color: white;
}

.btn-pdf:hover {
  background-color: #e05a5a;
  transform: translateY(-2px);
  box-shadow: 0 3px 10px rgba(255, 107, 107, 0.3);
}

/* Message de succès */
.alert {
  padding: 1rem;
  border-radius: 10px;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.alert-success {
  background-color: rgba(0, 200, 150, 0.1);
  border-left: 4px solid var(--success-color);
  color: var(--dark-text);
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.page-item {
  list-style: none;
}

.page-link {
  padding: 0.7rem 1.2rem;
  border-radius: 8px;
  text-decoration: none;
  color: var(--primary-color);
  background: var(--light-bg);
  transition: all 0.3s;
  display: block;
  font-weight: 500;
}

.page-link:hover {
  background: var(--primary-color);
  color: white;
}

.page-item.active .page-link {
  background: var(--primary-color);
  color: white;
  font-weight: 600;
}

.page-item.disabled .page-link {
  color: #aaa;
  pointer-events: none;
  background: #f1f1f1;
}

/* Statistiques */
.stats-container {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  margin-top: 2rem;
  box-shadow: 0 5px 20px rgba(0,0,0,0.05);
  display: none;
}

.stats-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.stats-title {
  font-size: 1.5rem;
  color: var(--primary-color);
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.chart-container {
  position: relative;
  height: 400px;
  margin: 20px auto;
  max-width: 600px;
}

.stats-details {
  margin-top: 30px;
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: center;
}

.stat-item {
  background: #f8f9ff;
  border-radius: 10px;
  padding: 15px;
  min-width: 200px;
  text-align: center;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--primary-color);
  margin: 5px 0;
}

.stat-label {
  color: #666;
  font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 992px) {
  .filter-controls {
    flex-direction: column;
  }
  
  .filter-group {
    width: 100%;
  }
  
  .filter-select {
    flex-grow: 1;
  }
}

@media (max-width: 768px) {
  .projects-grid {
    grid-template-columns: 1fr;
  }
  
  .project-actions {
    flex-direction: column;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }

  .chart-container {
    height: 300px;
  }
}

@media (max-width: 576px) {
  .projects-container {
    padding: 1.5rem;
  }
  
  .projects-title {
    font-size: 1.5rem;
  }
  
  .page-link {
    padding: 0.5rem 0.8rem;
  }

  .chart-container {
    height: 250px;
  }
}
</style>

<div class="projects-container">
    <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime'): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle" style="color: var(--success-color); font-size: 1.2rem;"></i>
            <div>
                <strong>Succès !</strong> Le projet a été supprimé avec succès.
            </div>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="projects-title">
            <i class="fas fa-project-diagram"></i>
            <span>Liste des Projets</span>
        </h2>
        
        <div class="filter-controls">
            <form method="GET" class="filter-group">
                <input type="hidden" name="page" value="1">
                <select name="categorie" class="filter-select">
                    <option value="">Toutes catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id_categorie']) ?>" <?= ($cat['id_categorie'] == $selectedCategorie) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i>
                    <span>Filtrer</span>
                </button>
            </form>
            
            <form method="GET" class="filter-group">
                <input type="hidden" name="page" value="1">
                <?php if (!empty($selectedCategorie)): ?>
                    <input type="hidden" name="categorie" value="<?= htmlspecialchars($selectedCategorie) ?>">
                <?php endif; ?>
                <select name="sort" class="filter-select">
                    <option value="">Trier par</option>
                    <option value="date_debut" <?= ($selectedSort == 'date_debut') ? 'selected' : '' ?>>Date début</option>
                    <option value="date_fin" <?= ($selectedSort == 'date_fin') ? 'selected' : '' ?>>Date fin</option>
                    <option value="montant" <?= ($selectedSort == 'montant') ? 'selected' : '' ?>>Montant</option>
                </select>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-sort"></i>
                    <span>Trier</span>
                </button>
            </form>
        </div>
    </div>
    
    <?php if (count($projets) > 0): ?>
        <div class="projects-grid">
            <?php foreach ($projets as $projet): ?>
                <div class="project-card">
                    <div class="project-header">
                        <div class="project-title">
                            <?= htmlspecialchars($projet['nom_projet']) ?>
                            <span class="project-category">
                                <?= !empty($projet['nom_categorie']) ? htmlspecialchars($projet['nom_categorie']) : 'Non spécifiée' ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="project-body">
                        <div class="project-description">
                            <?= htmlspecialchars(substr($projet['description'], 0, 200)) ?>
                            <?= strlen($projet['description']) > 200 ? '...' : '' ?>
                        </div>
                        
                        <div class="project-dates">
                            <div class="project-date">
                                <span class="project-date-label">Début</span>
                                <?= htmlspecialchars($projet['date_debut']) ?>
                            </div>
                            <div class="project-date">
                                <span class="project-date-label">Fin</span>
                                <?= htmlspecialchars($projet['date_fin']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="project-footer">
                        <div class="project-amount">
                            <?= isset($projet['montant']) ? number_format($projet['montant'], 2, ',', ' ') . ' €' : 'N/A' ?>
                        </div>
                        
                        <div class="project-actions">
                            <a href="/BoostUp/controller/exporterProjet.php?id=<?= $projet['id_projet'] ?>" class="btn btn-pdf">
                                <i class="fas fa-file-pdf"></i>
                                <span class="d-none d-md-inline">PDF</span>
                            </a>
                            <a href="/BoostUp/view/Backoffice/modifierProjet.php?id=<?= $projet['id_projet'] ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                                <span class="d-none d-md-inline">Modifier</span>
                            </a>
                            <a href="/BoostUp/view/Backoffice/supprimerProjet.php?id=<?= $projet['id_projet'] ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                                <i class="fas fa-trash"></i>
                                <span class="d-none d-md-inline">Supprimer</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1<?= !empty($selectedCategorie) ? '&categorie='.htmlspecialchars($selectedCategorie) : '' ?><?= !empty($selectedSort) ? '&sort='.htmlspecialchars($selectedSort) : '' ?>">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($selectedCategorie) ? '&categorie='.htmlspecialchars($selectedCategorie) : '' ?><?= !empty($selectedSort) ? '&sort='.htmlspecialchars($selectedSort) : '' ?>">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-left"></i></span>
                    </li>
                <?php endif; ?>
                
                <?php 
                $startPage = max(1, $page - 2);
                $endPage = min($startPage + 4, $totalPages);
                
                if ($endPage - $startPage < 4 && $startPage > 1) {
                    $startPage = max(1, $endPage - 4);
                }
                
                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= !empty($selectedCategorie) ? '&categorie='.htmlspecialchars($selectedCategorie) : '' ?><?= !empty($selectedSort) ? '&sort='.htmlspecialchars($selectedSort) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($selectedCategorie) ? '&categorie='.htmlspecialchars($selectedCategorie) : '' ?><?= !empty($selectedSort) ? '&sort='.htmlspecialchars($selectedSort) : '' ?>">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $totalPages ?><?= !empty($selectedCategorie) ? '&categorie='.htmlspecialchars($selectedCategorie) : '' ?><?= !empty($selectedSort) ? '&sort='.htmlspecialchars($selectedSort) : '' ?>">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-right"></i></span>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-4x mb-4" style="color: #e0e0ff;"></i>
            <h4 class="mb-3">Aucun projet disponible</h4>
            <p class="text-muted">Commencez par ajouter un nouveau projet</p>
            <a href="/BoostUp/view/Backoffice/projet.html" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Ajouter un projet
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Section Statistiques -->
<div class="stats-container" id="statsContainer">
    <div class="stats-header">
        <h3 class="stats-title">
            <i class="fas fa-chart-pie"></i>
            <span>Statistiques par catégorie</span>
        </h3>
        <button id="closeStats" class="btn btn-sm btn-danger">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <?php
    // Récupération des statistiques par catégorie pour le diagramme circulaire
    try {
        $statsSql = "SELECT 
                        c.nom_categorie, 
                        COUNT(p.id_projet) as nombre_projets,
                        SUM(p.montant) as total_montant
                    FROM Categorie c
                    LEFT JOIN Projet p ON c.id_categorie = p.id_categorie
                    GROUP BY c.id_categorie, c.nom_categorie";
        
        $stmtStats = $db->query($statsSql);
        $stats = $stmtStats->fetchAll(PDO::FETCH_ASSOC);
        
        // Préparer les données pour le graphique
        $labels = [];
        $data = [];
        $colors = [];
        $totalProjets = 0;
        
        foreach ($stats as $stat) {
            $labels[] = $stat['nom_categorie'];
            $data[] = $stat['nombre_projets'];
            $totalProjets += $stat['nombre_projets'];
            
            // Générer une couleur aléatoire pour chaque catégorie
            $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        
        // Statistiques globales
        $statsGlobaleSql = "SELECT 
                            COUNT(*) as total_projets,
                            SUM(montant) as total_montant,
                            MIN(date_debut) as plus_ancien,
                            MAX(date_fin) as plus_recent
                          FROM Projet";
        $stmtGlobale = $db->query($statsGlobaleSql);
        $statsGlobale = $stmtGlobale->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de la récupération des statistiques: " . $e->getMessage() . "</div>";
        $stats = [];
    }
    ?>
    
    <?php if (count($stats) > 0): ?>
        <div class="chart-container">
            <canvas id="pieChart"></canvas>
        </div>
        
        <div class="stats-details">
            <div class="stat-item">
                <div class="stat-value"><?= $totalProjets ?></div>
                <div class="stat-label">Projets au total</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= isset($statsGlobale['total_montant']) ? number_format($statsGlobale['total_montant'], 2, ',', ' ') . ' €' : 'N/A' ?></div>
                <div class="stat-label">Montant total</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $statsGlobale['plus_ancien'] ?? 'N/A' ?></div>
                <div class="stat-label">Plus ancien projet</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $statsGlobale['plus_recent'] ?? 'N/A' ?></div>
                <div class="stat-label">Plus récent projet</div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-4">
            <i class="fas fa-chart-line fa-3x mb-3" style="color: #e0e0ff;"></i>
            <h5 class="mb-2">Aucune statistique disponible</h5>
            <p class="text-muted">Créez des projets pour voir les statistiques</p>
        </div>
    <?php endif; ?>
</div>

<!-- Bouton pour afficher les statistiques -->
<div class="text-center mt-4">
    <button id="statsButton" class="btn btn-primary">
        <i class="fas fa-chart-bar"></i> Afficher les statistiques
    </button>
</div>

<!-- Inclure Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton statistiques
    const statsButton = document.getElementById('statsButton');
    const statsContainer = document.getElementById('statsContainer');
    const closeStats = document.getElementById('closeStats');
    
    statsButton.addEventListener('click', function() {
        statsContainer.style.display = 'block';
        statsButton.style.display = 'none';
    });
    
    closeStats.addEventListener('click', function() {
        statsContainer.style.display = 'none';
        statsButton.style.display = 'inline-flex';
    });
    
    // Créer le diagramme circulaire si des données existent
    <?php if (count($stats) > 0): ?>
    const ctx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($data) ?>,
                backgroundColor: <?= json_encode($colors) ?>,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>