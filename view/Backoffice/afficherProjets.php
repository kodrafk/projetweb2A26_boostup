<?php
// Afficher toutes les erreurs PHP pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure la connexion à la base de données
include_once('C:/xampp/htdocs/BoostUp/config/database.php');

// Connexion à la base de données
$db = getDB();

// Requête SQL pour récupérer les projets
try {
    $sql = "SELECT * FROM Projet"; // Récupérer toutes les données de la table Projet
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les projets sous forme de tableau associatif
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des projets : " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Projets</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="js/script.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h5 class="text-primary mb-3"><i class="bi bi-list"></i> Liste des Projets</h5>
        
        <?php if (isset($_GET['message']) && $_GET['message'] == 'supprime'): ?>
            <div class="alert alert-success">Projet supprimé avec succès !</div>
        <?php endif; ?>

        <!-- Vérifier s'il y a des projets avant d'afficher le tableau -->
        <?php if (count($projets) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nom du Projet</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projets as $projet): ?>
                            <tr>
                                <td><?= isset($projet['nom_projet']) ? htmlspecialchars($projet['nom_projet']) : 'N/A' ?></td>
                                <td><?= isset($projet['date_debut']) ? htmlspecialchars($projet['date_debut']) : 'N/A' ?></td>
                                <td><?= isset($projet['date_fin']) ? htmlspecialchars($projet['date_fin']) : 'N/A' ?></td>
                                <td><?= isset($projet['description']) ? htmlspecialchars($projet['description']) : 'N/A' ?></td>
                                <td>
                                    <a href="/BoostUp/view/Backoffice/modifierProjet.php?id=<?= $projet['id_projet'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </a>
                                    <a href="/BoostUp/view/Backoffice/supprimerProjet.php?id=<?= $projet['id_projet'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Voulez-vous vraiment supprimer ce projet ?')">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Aucun projet trouvé.</div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="index.php?action=ajouter" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter un projet
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>