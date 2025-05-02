<?php
// Afficher toutes les erreurs PHP pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la connexion à la base de données
include_once('C:/xampp/htdocs/BoostUp/config/database.php');

// Connexion à la base de données
$db = getDB();

// Récupérer toutes les catégories pour la liste déroulante
try {
    $sqlAll = "SELECT * FROM categorie";
    $stmtAll = $db->prepare($sqlAll);
    $stmtAll->execute();
    $allCategories = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des catégories : " . $e->getMessage();
    die();
}

// Initialiser la variable pour les catégories à afficher
$categories = $allCategories;

// Si une recherche est effectuée
if (isset($_POST['rechercher']) && !empty($_POST['categorie_id'])) {
    $categorieId = $_POST['categorie_id'];
    try {
        $sql = "SELECT * FROM categorie WHERE id_categorie = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $categorieId, PDO::PARAM_INT);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la recherche de la catégorie : " . $e->getMessage();
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
      margin-bottom: 1.5rem;
    }

    .table-title i {
      margin-right: 10px;
    }

    /* Filtres */
    .filter-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .filter-select {
      padding: 0.7rem 1rem;
      background: #f1f4ff;
      border: 2px solid #e0e0ff;
      border-radius: 12px;
      font-weight: 500;
      color: #444;
      transition: all 0.3s ease-in-out;
      font-family: 'Poppins', sans-serif;
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
      font-family: 'Poppins', sans-serif;
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

    /* Badge catégorie */
    .badge-category {
      background: #00c896;
      padding: 0.4rem 0.9rem;
      border-radius: 50px;
      color: #fff;
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-block;
    }

    /* Boutons d'action */
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
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .btn-edit {
      background: #4d44db;
      color: #fff;
    }

    .btn-delete {
      background: #ff6b6b;
      color: #fff;
    }

    .btn-add {
      background: #00c896;
      color: #fff;
      padding: 0.7rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
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

    /* Message vide */
    .empty-message {
      text-align: center;
      padding: 3rem;
      color: #888;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .creative-table-container {
        padding: 1.5rem;
      }
      
      .creative-table thead {
        display: none;
      }
      
      .creative-table tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #eaeaea;
      }
      
      .creative-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
      }
      
      .creative-table td:before {
        content: attr(data-label);
        font-weight: 600;
        color: #6c63ff;
        margin-right: 1rem;
      }
      
      .action-btns {
        display: flex;
        flex-wrap: wrap;
      }
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="creative-table-container">
            <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime'): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle me-2"></i> Catégorie supprimée avec succès !
                </div>
            <?php endif; ?>
            
            <div class="table-header">
                <h2 class="table-title"><i class="fas fa-list-alt me-2"></i> Liste des Catégories</h2>
                
                <div class="filter-controls">
                    <form method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                        <select name="categorie_id" class="filter-select">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($allCategories as $cat): ?>
                                <option value="<?= $cat['id_categorie'] ?>" <?= (isset($categorieId) && $categorieId == $cat['id_categorie']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nom_categorie']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="rechercher" class="filter-btn">
                            <i class="fas fa-search me-1"></i> Rechercher
                        </button>
                        <?php if (isset($_POST['rechercher'])): ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="filter-btn" style="background: #ff6b6b;">
                                <i class="fas fa-times me-1"></i> Réinitialiser
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <?php if (count($categories) > 0): ?>
                <div class="table-responsive">
                    <table class="creative-table">
                        <thead>
                            <tr>
                                <th>Nom de la catégorie</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $categorie): ?>
                                <tr>
                                    <td data-label="Nom de la catégorie">
                                        <span class="badge-category">
                                            <?= isset($categorie['nom_categorie']) ? htmlspecialchars($categorie['nom_categorie']) : 'N/A' ?>
                                        </span>
                                    </td>
                                    <td data-label="Description">
                                        <?= isset($categorie['description']) ? nl2br(htmlspecialchars(substr($categorie['description'], 0, 100))) : 'N/A' ?>
                                        <?= isset($categorie['description']) && strlen($categorie['description']) > 100 ? '...' : '' ?>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-btns">
                                            <a href="/BoostUp/view/Backoffice/modifierCategorie.php?id=<?= $categorie['id_categorie'] ?>" class="btn-table btn-edit">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <a href="/BoostUp/view/Backoffice/supprimerCategorie.php?id=<?= $categorie['id_categorie'] ?>" class="btn-table btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?')">
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
                    <h4>Aucune catégorie disponible</h4>
                    <p>Commencez par ajouter une nouvelle catégorie</p>
                </div>
            <?php endif; ?>
           
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Responsive table labels
    document.addEventListener('DOMContentLoaded', function() {
        const ths = document.querySelectorAll('.creative-table thead th');
        const tds = document.querySelectorAll('.creative-table tbody td');
        
        if (window.innerWidth <= 768) {
            tds.forEach((td, index) => {
                const thIndex = index % ths.length;
                if (ths[thIndex]) {
                    td.setAttribute('data-label', ths[thIndex].textContent);
                }
            });
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                tds.forEach((td, index) => {
                    const thIndex = index % ths.length;
                    if (ths[thIndex]) {
                        td.setAttribute('data-label', ths[thIndex].textContent);
                    }
                });
            } else {
                tds.forEach(td => {
                    td.removeAttribute('data-label');
                });
            }
        });
    });
    </script>
</body>
</html>