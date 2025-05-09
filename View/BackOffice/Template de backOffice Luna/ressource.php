<?php
date_default_timezone_set('Africa/Tunis');
include '../../../Controller/RessourceC.php';
require_once(__DIR__ . '/../../../Controller/ThematiqueC.php');

// Historique - début
$cheminLog = 'C:/xampp/htdocs/Ressources/historique.log'; // Chemin du fichier historique

// Fonction pour lire l'historique depuis le fichier
function lireHistorique($cheminLog) {
    if (!file_exists($cheminLog)) return [];
    $logs = file($cheminLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_reverse($logs); // Afficher les dernières actions en haut
}

// Fonction pour obtenir le titre de la ressource par ID
function getTitreRessource($id) {
    $ressourceC = new RessourceC();
    $ressource = $ressourceC->getRessourceById($id);
    return $ressource ? $ressource['titre'] : 'Titre inconnu';
}

// Fonction pour ajouter une entrée dans l'historique
/*function ajouterHistorique($cheminLog, $actionDetails) {
    $currentDateTime = date('Y-m-d H:i:s');
    $log = "[$currentDateTime] $actionDetails\n";
    file_put_contents($cheminLog, $log, FILE_APPEND | LOCK_EX);
}*/

function ajouterHistorique($cheminLog, $actionDetails) {
    $currentDateTime = date('Y-m-d H:i:s');
    $log = "[$currentDateTime] $actionDetails\n";
    file_put_contents($cheminLog, $log, FILE_APPEND | LOCK_EX);
}

function supprimerHistorique($cheminLog, $ligneASupprimer) {
    $logs = file($cheminLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $indexDansFichier = count($logs) - 1 - $ligneASupprimer; // Corrige l'index basé sur l'affichage inversé

    if ($indexDansFichier >= 0 && $indexDansFichier < count($logs)) {
        unset($logs[$indexDansFichier]);
        $logs = array_values($logs); // Réindexer
        file_put_contents($cheminLog, implode(PHP_EOL, $logs) . PHP_EOL, LOCK_EX);
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $index = (int) $_GET['delete'];
    $logsOriginaux = file($cheminLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $ligne = $logsOriginaux[$index] ?? null;

    if ($ligne) {
        preg_match('/ID: (\d+)/', $ligne, $matches);
        $ressourceId = $matches[1] ?? null;
        $titreRessource = $ressourceId ? getTitreRessource($ressourceId) : 'Inconnu';
        supprimerHistorique($cheminLog, $index);
    }

    header("Location: ressource.php"); 
    exit;
}


// Récupérer l'historique et le filtrer
$historique = lireHistorique($cheminLog);
$historique = array_filter($historique, 'trim'); // Supprimer les lignes vides
// Historique - fin


$cont = new RessourceC();
$ressources = $cont->afficherRessource();

// récupérer toutes les thématiques pour la liste déroulante
$thematiqueC = new ThematiqueC();
$thematiques = $thematiqueC->afficherThematique();

// Nouveau code pour les statistiques
$stats = $cont->getStatsByType();
$labels = [];
$data = [];
$colors = ['#6c63ff', '#00d09c', '#ff6384', '#ffcd56'];

foreach ($stats as $index => $item) {
    $labels[] = $item['type'];
    $data[] = $item['count'];
    $bgColors[] = $colors[$index % count($colors)];
}

// Stats pour le bar chart
$conn = config::getConnexion();
$sql = "SELECT t.titre AS thematique, r.type_acces, COUNT(*) AS total 
        FROM ressources r
        JOIN thematique t ON r.id_thematique = t.id_thematique
        GROUP BY t.titre, r.type_acces
        ORDER BY t.titre";

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();

$thematiquesBar = array_unique(array_column($results, 'thematique'));
$typesAcces = ['En ligne', 'Live', 'Pdf', 'Video'];

$datasets = [];
foreach ($typesAcces as $type) {
    $dataBar = [];
    foreach ($thematiquesBar as $th) {
        $count = 0;
        foreach ($results as $row) {
            if ($row['thematique'] === $th && $row['type_acces'] === $type) {
                $count = $row['total'];
                break;
            }
        }
        $dataBar[] = $count;
    }
    
    $datasets[] = [
        'label' => $type,
        'data' => $dataBar,
        'backgroundColor' => randomColor($type),
        'borderColor' => 'rgba(255,255,255,0.8)',
        'borderWidth' => 1
    ];
}

function randomColor($type) {
    switch ($type) {
        case 'En ligne': return '#6c63ff';
        case 'Live':     return '#00d09c';
        case 'Pdf':      return '#ff6384';
        case 'Video':    return '#ffcd56';
        default:         return '#cccccc';
    }
}

// Vérifier s'il y a un filtre par type
$typeFiltre = $_GET['type'] ?? '';

if (!empty($typeFiltre)) {
    $ressources = $cont->filtrerParType($typeFiltre);
} else {
    $ressources = $cont->afficherRessource();
}

$trierThematique = isset($_GET['trier_thematique']);

// Logique combinée
if ($trierThematique) {
    $ressources = $cont->trierParNomThematique();
} elseif (!empty($typeFiltre)) {
    $ressources = $cont->filtrerParType($typeFiltre);
} else {
    $ressources = $cont->afficherRessource();
}

// Récupérer les données de la ressource à modifier si l'ID est passé en paramètre
$ressourceToEdit = null;
if (isset($_GET['edit_id'])) {
    $ressourceToEdit = $cont->getRessourceById($_GET['edit_id']);
}
?>



<?php
// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_ressource'])) {
    $id = $_POST['id_ressource'];
    $type = $_POST['type'];
    $titre = $_POST['titre'];
    $lien = $_POST['lien'];
    $description = $_POST['description'];
    $id_thematique = $_POST['id_thematique'];

    $cont->updateRessource($id, $type, $titre, $lien, $description, $id_thematique);
    header('Location: ressource.php'); // Redirection pour éviter la resoumission
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Boostup</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="images/favicon.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    
    

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Dans la section <head> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"-->

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="formulaire.css" rel="stylesheet">

    <style>
        .is-invalid {
        border-color: red;
     }

    .form-text {
    color: red;
    font-size: 0.875rem;
   }

.btn-primary {
        background-color: #6c63ff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #574fe1;
    }

    .table thead {
        background-color: #6c63ff;
    }

    .badge.bg-success {
        background-color: #00d09c;
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 15px;
    }

    .form-select, .btn {
        border-radius: 10px;
        padding: 0.5em 1em;
    }

    .filter-controls select,
    .filter-controls button {
        margin-right: 10px;
    }

    .filter-select {
      padding: 0.7rem 1rem;
      background:rgba(241, 244, 255, 0.65);
      border: 2px solidrgba(224, 224, 255, 0.73);
      border-radius: 12px;
      font-weight: 500;
      color: #444;
      transition: all 0.3s ease-in-out;
      font-family: 'Poppins', sans-serif;
    }  



    /* Styles généraux */
.glass-card {
    max-width: 700px;
    margin: 0 auto;
    background: #e0e0ff;
    backdrop-filter: blur(10px);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
}

.card-header.bg-primary {
    background-color:  #6c63ff !important;
    border-radius: 15px 15px 0 0;
}

.card-body {
    background-color: #F3F6F9;
    padding: 2rem !important;
}

.section-title {
    color: #2e59d9;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 0.5rem;
    font-size: 1.1rem;
}

.form-control {
    border: 1px solidrgb(209, 211, 226);
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-lg {
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
    border-radius: 0.35rem;
}


/* Custom CSS */
.sidebar {
        background-color: #6c63ff !important;
    }
    
    .navbar-brand h3,
    .ms-3 h6,
    .ms-3 span,
    .nav-link,
    .dropdown-item {
        color: white !important;
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-radius: 5px;
    }

    .dropdown-menu {
        background-color: #7d76ff !important;
    }

    /*les couleur de fleches */
    .text-skyblue {
    color: #00bfff;  /* Bleu ciel */
}

.text-skyblue:hover {
    color: #1e90ff;  /* Bleu plus foncé au survol */
}

body.modal-open {
    overflow: auto; /* Au lieu de hidden */
    padding-right: 0 !important;
}
 
</style>
<style>
    /* Modification du conteneur des graphiques */
.chart-container {
    width: 100%;
    height: 400px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Ajustement responsive */
@media (max-width: 768px) {
    .chart-container {
        height: 300px;
    }
}

/* Espacement entre les graphiques */
.col-md-5 {
    margin: 0 1%;
    flex: 0 0 48%;
    max-width: 48%;
}
/* historique*/
        h2 {
            color: #6a0dad; /* Violet pour le titre */
        }
        .history-entry {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f9f9ff; /* Fond clair et légèrement violet pour chaque entrée */
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .history-entry:hover {
            background-color: #e6e6ff; /* Fond légèrement plus foncé lors du survol */
        }
        .history-entry .time {
            width: 200px;
            color: #aaa;
        }
        .history-entry .title {
            flex: 1;
            padding-left: 10px;
            color: #333;
        }
</style>
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
    <div class="sidebar pe-4 pb-3">
    <nav class="navbar navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-white"><i class="fa fa-hashtag me-2"></i>Boostup</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Jhon Doe</h6>
                        <span>Admin</span>
                    </div>
                </div>
        <div class="navbar-nav w-100">
            <a href="index.html" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            
            <!-- Projets -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a>
                <div class="dropdown-menu">
                    <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a>
                    <a href="projet.html" class="dropdown-item">Projet</a>
                </div>
            </div>

            <!-- Objectifs -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                <div class="dropdown-menu">
                    <a href="tache.html" class="dropdown-item">Tache</a>
                    <a href="objectif.html" class="dropdown-item">Objectif</a>
                </div>
            </div>

            <!-- Ressources (Active) -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                <div class="dropdown-menu">
                    <a href="ressource.html" class="dropdown-item">Ressource</a>
                    <a href="thematique.html" class="dropdown-item">Thématique</a>
                    <a href="statistique.php" class="dropdown-item">Statistique</a>
                </div>
            </div>

            <!-- Evenements -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i>Evenements</a>
                <div class="dropdown-menu">
                    <a href="evenement.html" class="dropdown-item">Evenement</a>
                    <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                </div>
            </div>

            <!-- Communautes -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i>Communautes</a>
                <div class="dropdown-menu">
                    <a href="questionne.html" class="dropdown-item">Questionne</a>
                    <a href="reponse.html" class="dropdown-item">Reponse</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown no-arrow mx-1">
                   <a class="nav-link dropdown-toggle" href="#" id="historiqueDropdown" role="button"
                      data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-history fa-fw"></i>
                      <span class="d-none d-lg-inline-flex">Historique</span>
                   </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                       aria-labelledby="historiqueDropdown">
                        <h6 class="dropdown-header">Historique</h6>
                           <!-- Nouveau lien qui ouvre le modal -->
                           <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#historiqueModal">
                               <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                      Historique des Ressources
                                </a>
                      </div>
                </li>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">John Doe</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


            <!-- Chart Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                </div>
            </div>
            <!-- Chart End -->
            
<!-- Formulaire CRUD -->

<div class="glass-card card mb-4 animate__animated animate__fadeIn" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-project-diagram me-2"></i> Gestion Ressource</h6>
    </div>
    
    <div class="card-body p-3" style="background-color: #F3F6F9;">
        <form id="travelOfferForm" method="post" action="ajouterRessource.php">
            <input type="hidden" id="id_ressource" name="id_ressource">
            
            <div class="form-section mb-4">
                <h5 class="section-title text-primary mb-3">
                    <i class="fas fa-folder-open me-2"></i>Informations de Ressource
                </h5>
                
                <div class="row g-3">
                    <!-- Type -->
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-bold">Type</label>
                        <select id="type" name="type" class="form-control shadow-sm">
                            <option value="">Sélectionner un type</option>
                            <option value="Cour">Cour</option>
                            <option value="Vedio">Vedio</option>
                            <option value="Article">Article</option>
                            <option value="Evenement">Evenement</option>
                        </select>
                    </div>

                    <!-- Titre -->
                    <div class="col-md-6">
                        <label for="titre" class="form-label fw-bold">Titre</label>
                        <input type="text" id="titre" name="titre" 
                               class="form-control shadow-sm" 
                               placeholder="Ex: Développement Web">
                        <div id="titre_error" class="form-text text-danger small"></div>
                    </div>

                    <!-- Lien -->
                    <div class="col-md-6">
                        <label for="lien" class="form-label fw-bold">Lien</label>
                        <input type="text" id="lien" name="lien" 
                               class="form-control shadow-sm" 
                               placeholder="Ex: https://...">
                        <div id="lien_error" class="form-text text-danger small"></div>
                    </div>

                    <!-- Thématique -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Thématique</label>
                        <select name="id_thematique" class="form-control shadow-sm">
                            <option value="">-- Choisir une thématique --</option>
                            <?php foreach ($thematiques as $them): ?>
                            <option value="<?= $them['id_thematique'] ?>">
                                <?= htmlspecialchars($them['titre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Type_Acces -->
                    <div class="col-md-6">
                        <label for="type_acces" class="form-label fw-bold">Type Acces</label>
                        <select id="type_acces" name="type_acces" class="form-control shadow-sm">
                            <option value="">Sélectionner un type</option>
                            <option value="En ligne">En ligne</option>
                            <option value="Live">Live</option>
                            <option value="Pdf">Pdf</option>
                            <option value="Video">Video</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea id="description" name="description" 
                                  class="form-control shadow-sm" 
                                  rows="4"
                                  placeholder="Décrivez la ressource en détail..."></textarea>
                        <div id="description_error" class="form-text text-danger small"></div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-end mt-4 gap-2">
                <button type="reset" class="btn btn-lg btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="submit" class="btn btn-lg btn-success">
                    <i class="fas fa-check me-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Form End -->

<!-- tableau d'affichage des ressources -->
<div class="container mt-5">
    <h2 class="fw-bold text-primary mb-4">
        <i class="bi bi-diagram-3-fill"></i> Liste des Ressources
    </h2>
    <div class="d-flex flex-wrap gap-3 mb-4">
            <!-- Formulaire de filtrage -->
        <form method="GET" action="">
        <div class="d-flex flex-wrap gap-3 mb-4">
            <!-- Filtre par type -->
            <select name="type" class="form-select w-auto">
                <option value="">Tous les types</option>
                <option value="Cour" <?= $typeFiltre === 'Cour' ? 'selected' : '' ?>>Cour</option>
                <option value="Vedio" <?= $typeFiltre === 'Vedio' ? 'selected' : '' ?>>Vedio</option>
                <option value="Article" <?= $typeFiltre === 'Article' ? 'selected' : '' ?>>Article</option>
                <option value="Evenement" <?= $typeFiltre === 'Evenement' ? 'selected' : '' ?>>Evenement</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel-fill"></i> Filtrer
            </button>


            <!-- Bouton Trier -->
            <button type="submit" name="trier_thematique" class="btn btn-info">
                <i class="bi bi-sort-alpha-down"></i> Trier par Thématique
            </button>
     </form>
    </div>
    <?php
    // --- PAGINATION ---
    $ressourcesParPage = 5;
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $debut = ($pageActuelle - 1) * $ressourcesParPage;
    $ressourcesPage = array_slice($ressources, $debut, $ressourcesParPage);
    $totalPages = ceil(count($ressources) / $ressourcesParPage);
    ?>
    <?php if (count($ressources) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="text-white" style="background-color: #6c63ff;">
                    <tr>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Lien</th>
                        <th>Thématique</th>
                        <th>Description</th>
                        <th>Type Acces</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($ressourcesPage as $ressource): ?>
                        <tr>
                            <td><?= htmlspecialchars($ressource['type']) ?></td>
                            <td><?= htmlspecialchars($ressource['titre']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($ressource['lien']) ?>" target="_blank" class="text-decoration-underline text-primary">
                                    Voir lien
                                </a>
                            </td>
                            <td>
                            <span class="badge bg-success"><?= htmlspecialchars($ressource['thematique'] ?? 'Non spécifiée') ?></span>
                            </td>
                            <td><?= htmlspecialchars($ressource['description']) ?></td>
                            <td><?= htmlspecialchars($ressource['type_acces']) ?></td>
                            <td>
                            
                            <button class="btn btn-primary btn-sm me-1 edit-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-id="<?= $ressource['id_ressource'] ?>"
                                data-type="<?= htmlspecialchars($ressource['type']) ?>"
                                data-titre="<?= htmlspecialchars($ressource['titre']) ?>"
                                data-lien="<?= htmlspecialchars($ressource['lien']) ?>"
                                data-description="<?= htmlspecialchars($ressource['description']) ?>"
                                data-thematique="<?= $ressource['id_thematique'] ?>">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>

                                <form action="deleteRessource.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ressource ?')">
                                    <input type="hidden" name="id_ressource" value="<?= $ressource['id_ressource']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>

                                 <!-- Bouton SMS conditionnel -->
                                 <?php if (
                                   ($ressource['type'] === 'Cour' || $ressource['type'] === 'Evenement') &&
                                   ($ressource['type_acces'] === 'En ligne' || $ressource['type_acces'] === 'Live')
                                     ): ?>
                                    <form method="POST" action="sms.php" style="display:inline-block; margin-top:5px;">
                                       <input type="hidden" name="id_ressource" value="<?= $ressource['id_ressource'] ?>">
                                       <button type="submit" class="btn btn-success btn-sm">
                                         <i class="bi bi-chat-dots-fill"></i> SMS
                                       </button>
                                    </form>
                                  <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<!-- PAGINATION STYLE <1/4> AVEC FLÈCHES -->
<?php if ($totalPages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center align-items-center">

            <!-- Flèche précédente -->
            <li class="page-item <?= ($pageActuelle <= 1) ? 'disabled' : '' ?>">
                <a class="page-link text-skyblue" href="?<?= http_build_query(array_merge($_GET, ['page' => $pageActuelle - 1])) ?>">
                    &lt;
                </a>
            </li>

            <!-- Affichage style <1/4> -->
            <li class="page-item disabled">
                <span class="page-link">
                    &lt;<?= $pageActuelle ?>/<?= $totalPages ?>&gt;
                </span>
            </li>

            <!-- Flèche suivante -->
            <li class="page-item <?= ($pageActuelle >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link text-skyblue" href="?<?= http_build_query(array_merge($_GET, ['page' => $pageActuelle + 1])) ?>">
                    &gt;
                </a>
            </li>

        </ul>
    </nav>
<?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">Aucune ressource trouvée.</div>
    <?php endif; ?>
</div>

<!-- fin de tableau -->
<!-- Section des statistiques -->
<div class="container mt-5">
    <div class="row justify-content-between">
        <!-- Graphique Circulaire -->
        <div class="col-md-5 mb-4">
            <h2 class="stat-title text-center mb-4">Répartition par Type</h2>
            <div class="chart-container">
                <canvas id="typeChart1"></canvas>
            </div>
        </div>

        <!-- Graphique en Barres -->
        <div class="col-md-6 mb-4">
            <h2 class="stat-title text-center mb-4">Répartition par Accès</h2>
            <div class="chart-container">
                <canvas id="typeChart2"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">Modifier la Ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="editForm" method="POST" action="ressource.php">
                    <input type="hidden" name="id_ressource" id="edit_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_type" class="form-label">Type</label>
                            <select id="edit_type" name="type" class="form-control" >
                                <option value="">-- Choisir un type --</option>
                                <option value="Cour">Cour</option>
                                <option value="Vedio">Vedio</option>
                                <option value="Article">Article</option>
                                <option value="Evenement">Evenement</option>
                            </select>
                            <div id="type_error" class="form-text text-danger small"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_titre" class="form-label">Titre</label>
                            <input type="text" id="edit_titre" name="titre" class="form-control" >
                            <div id="titre_error" class="form-text text-danger small"></div>
                        </div>

                        <div class="col-12">
                            <label for="edit_lien" class="form-label">Lien</label>
                            <input type="url" id="edit_lien" name="lien" class="form-control">
                            <div id="lien_error" class="form-text text-danger small"></div>
                        </div>


                        <div class="col-md-6">
                           <label for="edit_thematique" class="form-label">Thématique</label>
                           <select id="edit_thematique" name="id_thematique" class="form-control">
                           <option value="">-- Choisir une thématique --</option>
                            <?php foreach ($thematiques as $them): ?>
                              <option value="<?= $them['id_thematique'] ?>">
                            <?= htmlspecialchars($them['titre']) ?>
                            </option>
                            <?php endforeach; ?>
                         </select>
                         <div id="thematique_error" class="form-text text-danger small"></div>
                        </div>

                        <div class="col-12">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="4"></textarea>
                            <div id="description_error" class="form-text text-danger small"></div>
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--FIN model-->

<!-- Modal Historique -->
<div class="modal fade" id="historiqueModal" tabindex="-1" aria-labelledby="historiqueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="historiqueModalLabel">Historique des Ressources</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
            <div class="container py-4">
        <h2 class="mb-4">Historique des Ressources</h2>
        <input type="text" id="search" class="search-bar form-control mb-3" placeholder="Rechercher dans l'historique...">

        <?php
        if (!empty($historique)) {
            foreach ($historique as $index => $log) {
                if (empty($log)) continue;
                if (preg_match('/^\[(.*?)\] (.*)/', $log, $matches)) {
                    $date = $matches[1];
                    $actionDetails = $matches[2];
        ?>
        <!--Ici chaque ligne a bien la classe 'history-entry' -->
        <div class="history-entry">
            <div>
                <div class="text-muted small"><?= htmlspecialchars($date) ?></div>
                <div class="fw-bold"><?= htmlspecialchars($actionDetails) ?></div>
            </div>
            <a href="?delete=<?= $index ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Supprimer cette entrée ?')">🗑️</a>
        </div>
        <?php
                }
            }
        } else {
            echo "<p class='text-muted'>Aucun historique disponible.</p>";
        }
        ?>
    </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin du Modal Historique -->


<!-- Fin du Modal Historique -->

            </div>

        </div>
            
    </div-->


     
<!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

   <!-- fin body -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script src="script.js"></script>

     <!-- Ajout de Chart.js -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    const ctx1 = document.getElementById('typeChart1').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data: <?= json_encode($data) ?>,
                backgroundColor: <?= json_encode($bgColors) ?>,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Répartition par type de ressource'
                }
            }
        }
    });

    // Bar Chart
     
    const ctx2 = document.getElementById('typeChart2').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_values($thematiquesBar)) ?>,
            datasets: <?= json_encode($datasets) ?>
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Thématiques'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de ressources'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Répartition par type d\'accès et thématique'
                }
            }
        }
    });
});
</script>
<script>
    const search = document.getElementById("search");
    search.addEventListener("keyup", function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.history-entry').forEach(entry => {
            entry.style.display = entry.textContent.toLowerCase().includes(query) ? 'flex' : 'none';
        });
    });
</script>
<script>
function validerFormulaire(event) {
    event.preventDefault();

    // Récupérer les champs du formulaire
    const type = document.getElementById('type');
    const titre = document.getElementById('titre');
    const lien = document.getElementById('lien');
    const thematique = document.querySelector('select[name="id_thematique"]');
    const description = document.getElementById('description');

    // Réinitialiser les messages d'erreur et classes invalides
    document.querySelectorAll('.form-text').forEach(el => el.textContent = '');
    document.querySelectorAll('input, select, textarea').forEach(input => input.classList.remove('is-invalid'));

    let valid = true;

    // Type
    if (type.value === "") {
        type.classList.add('is-invalid');
        valid = false;
    }

    //  Titre : au moins 3 caractères
    if (titre.value.trim().length < 3) {
        document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères.';
        titre.classList.add('is-invalid');
        valid = false;
    }

    // Lien : doit commencer par http ou https
    const urlRegex = /^(https?:\/\/)[^\s]+$/;
    if (!urlRegex.test(lien.value.trim())) {
        document.getElementById('lien_error').textContent = 'Veuillez entrer un lien valide (ex: https://...)';
        lien.classList.add('is-invalid');
        valid = false;
    }

    // Thématique
    if (thematique.value === "") {
        thematique.classList.add('is-invalid');
        valid = false;
    }

    // Description : au moins 10 caractères
    if (description.value.trim().length < 10) {
        document.getElementById('description_error').textContent = 'La description doit comporter au moins 10 caractères.';
        description.classList.add('is-invalid');
        valid = false;
    }

    //  Soumission finale
    if (valid) {
        document.getElementById("travelOfferForm").submit();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("travelOfferForm");
    if (form) {
        form.addEventListener("submit", validerFormulaire);
    }
});
// Gestion des clics sur le bouton Modifier
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const type = this.getAttribute('data-type');
        const titre = this.getAttribute('data-titre');
        const lien = this.getAttribute('data-lien');
        const description = this.getAttribute('data-description');
        const thematiqueId = this.getAttribute('data-thematique');

        // Remplissage du formulaire modal
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_type').value = type;
        document.getElementById('edit_titre').value = titre;
        document.getElementById('edit_lien').value = lien;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_thematique').value = thematiqueId;

        // Affichage du modal
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});

// Validation du formulaire de modification
document.getElementById('editForm').addEventListener('submit', function(e) {
    // Empêcher l'envoi par défaut
    e.preventDefault();
    
    // Récupération des champs
    const type = document.getElementById('edit_type');
    const titre = document.getElementById('edit_titre');
    const lien = document.getElementById('edit_lien');
    const description = document.getElementById('edit_description');
    const thematique = document.getElementById('edit_thematique');

    // Réinitialiser les erreurs
    document.querySelectorAll('#editForm .form-text').forEach(el => el.textContent = '');
    document.querySelectorAll('#editForm input, #editForm select, #editForm textarea').forEach(el => el.classList.remove('is-invalid'));

    let isValid = true;

    // Validation du type
    if (type.value === "") {
        document.getElementById('type_error').textContent = 'Veuillez sélectionner un type';
        type.classList.add('is-invalid');
        isValid = false;
    }

    // Validation du titre (minimum 3 caractères)
    if (titre.value.trim().length < 3) {
        document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères';
        titre.classList.add('is-invalid');
        isValid = false;
    }

    // Validation du lien (format URL)
    const urlRegex = /^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/;
    if (!urlRegex.test(lien.value.trim())) {
        document.getElementById('lien_error').textContent = 'Veuillez entrer une URL valide';
        lien.classList.add('is-invalid');
        isValid = false;
    }

    // Validation de la thématique
    if (thematique.value === "") {
        document.getElementById('thematique_error').textContent = 'Veuillez sélectionner une thématique';
        thematique.classList.add('is-invalid');
        isValid = false;
    }

    // Validation de la description (minimum 10 caractères)
    if (description.value.trim().length < 10) {
        document.getElementById('description_error').textContent = 'La description doit contenir au moins 10 caractères';
        description.classList.add('is-invalid');
        isValid = false;
    }

    // Si tout est valide, soumettre le formulaire
    if (isValid) {
        this.submit();
    }
});

// Correction de la fermeture du modal
document.getElementById('editModal').addEventListener('hidden.bs.modal', function () {
    // Réinitialiser les erreurs lors de la fermeture
    document.querySelectorAll('#editForm .form-text').forEach(el => el.textContent = '');
    document.querySelectorAll('#editForm input, #editForm select, #editForm textarea').forEach(el => el.classList.remove('is-invalid'));
    
    // Nettoyer le backdrop de Bootstrap
    const backdrops = document.getElementsByClassName('modal-backdrop');
    while(backdrops.length > 0) {
        backdrops[0].parentNode.removeChild(backdrops[0]);
    }
    document.body.classList.remove('modal-open');
    document.body.style.paddingRight = '';
});
</script>
</body>

</html>