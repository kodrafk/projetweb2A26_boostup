<?php
error_reporting(0); // Désactive tous les rapports d'erreurs
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../FrontOffice/login.php');
    exit();
}

// Pour les pages admin seulement
if ($_SESSION['user']['type'] !== 'admin') {
    header('Location: ../../View/BackOffice/afterLogin/index.php');
    exit();
}

// Traitement de la génération d'avatar si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avatarSeed'])) {
    $userId = $_SESSION['user']['iduser'];
    $seed = $_POST['avatarSeed'];
    $style = $_POST['avatarStyle'] ?? 'avataaars';
    $bgColor = isset($_POST['bgColor']) ? substr($_POST['bgColor'], 1) : '4e73df';

    // Chemin où sauvegarder l'avatar
    $avatarDir = __DIR__ . '/../../View/BackOffice/Template_de_backOffice_Luna/avatars/';
    if (!file_exists($avatarDir)) {
        if (!mkdir($avatarDir, 0777, true)) {
            $_SESSION['avatar_error'] = "Impossible de créer le dossier avatars";
            header('Location: '.$_SERVER['PHP_SELF']);
            exit();
        }
    }

    $avatarFile = $avatarDir . 'avatar_' . $userId . '.svg';

    // URL de l'API DiceBear
    $apiUrl = "https://api.dicebear.com/6.x/$style/svg?seed=$seed&backgroundColor=$bgColor";

    // Télécharger l'avatar
    $avatarContent = file_get_contents($apiUrl);

    if ($avatarContent !== false) {
        // Sauvegarder le fichier
        file_put_contents($avatarFile, $avatarContent);
        $_SESSION['avatar_updated'] = true;
    } else {
        $_SESSION['avatar_error'] = "Erreur lors de la génération de l'avatar";
    }
    
    // Rediriger pour éviter la soumission multiple du formulaire
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}

// Contrôleur utilisateur
include '../../../Controller/UserC.php';
$cont = new UserC();
$users = $cont->afficherUsers();

// Récupérer les données de l'utilisateur à modifier si l'ID est passé en paramètre
$userToEdit = null;
if (isset($_GET['edit_id'])) {
    $userToEdit = $cont->getUserById($_GET['edit_id']);
}

// Connexion à la base de données pour récupérer les alertes
include_once("../../../config.php");
$conn = new mysqli("localhost", "root", "", "projetweb");

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

$result = $conn->query("
    SELECT a.id, u.email, a.type_alerte, a.date_alerte
    FROM alertes a
    JOIN user u ON a.user_id = u.iduser
    ORDER BY a.date_alerte DESC
");

// Chemin de l'avatar actuel
$avatarPath = "../../View/BackOffice/Template_de_backOffice_Luna/avatars/avatar_" . @$_SESSION['user']['iduser'] . ".svg";
$defaultSeed = md5($_SESSION['user']['email']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
        .invalid {
            border-color: red;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin: 0 auto;
        }
        
        .form-section {
            padding: 20px;
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #4e73df;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background-color: #4e73df;
            color: white;
        }
        
        .search-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .glass-card-hover {
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.glass-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.counter {
    font-weight: 700;
    font-size: 2rem;
}

.search-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.input-group-text {
    border: none;
    font-weight: 600;
}

.table {
    --bs-table-bg: transparent;
}

.table th {
    position: sticky;
    top: 0;
    background-color: #4e73df;
    color: white;
    z-index: 10;
}

.table-responsive {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
        <nav class="navbar bg-light navbar-light">
            <a href="index.html" class="navbar-brand mx-4 mb-3">
                <h3 class="text-primary"><i class="fa fa-rocket me-2"></i>Boostup</h3>
            </a>
            <div class="d-flex flex-column align-items-center ms-4 mb-4">
            <div class="d-flex flex-column align-items-center ms-4 mb-4">
    <div class="position-relative">
        <?php if(file_exists($avatarPath)): ?>
            <img class="rounded-circle" src="<?= str_replace('../../../', '../../', $avatarPath) ?>?<?= filemtime($avatarPath) ?>" alt="Avatar" style="width: 40px; height: 40px;">
        <?php else: ?>
            <img class="rounded-circle" src="https://api.dicebear.com/6.x/avataaars/svg?seed=<?= $defaultSeed ?>" alt="Avatar" style="width: 40px; height: 40px;">
        <?php endif; ?>
        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
    </div>
    <div class="mt-2 text-center">
        <span class="text-muted small">Admin</span>
    </div>
</div>
</div>
            <div class="navbar-nav w-100">
                <a href="index.html" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
               
               <!-- Projets -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-project-diagram me-2"></i>Projets</a> 
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a> 
                        <a href="projet.html" class="dropdown-item">Projet</a> 
                    </div>
                </div>
            
                <!-- Gestion Objectifs -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-bullseye me-2"></i>Objectifs</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="tache.html" class="dropdown-item">Tache</a>
                        <a href="objectif.html" class="dropdown-item">Objectif</a>
                    </div>
                </div>
            
                <!-- Gestion ressources-->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-boxes me-2"></i>Ressources</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="ressource.html" class="dropdown-item">Ressource</a>
                        <a href="thematique.html" class="dropdown-item">Thématique</a>
                    </div>
                </div>
            
                <!-- Gestion Evennements -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-calendar-alt me-2"></i>Evenements</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="evenement.html" class="dropdown-item">Evenement</a>
                        <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                    </div>
                </div>
            
                <!-- Communauté -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-users me-2"></i>Communautes</a>
                    <div class="dropdown-menu bg-transparent border-0">
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
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <!-- Icône assistant IA dans la navbar -->
<!-- Icône assistant IA dans la navbar -->
<div class="navbar-nav align-items-center ms-auto">
    <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="aiAssistantDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-robot me-lg-2"></i>
            <span class="d-none d-lg-inline-flex">Recommandations</span>
            <span id="aiNotificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                0
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="aiAssistantDropdown" style="width: 350px;">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fa fa-robot me-2"></i>Conseiller IA
                    </h6>
                    <small class="badge bg-white text-primary">Live</small>
                </div>
                <div class="card-body p-0" id="aiDropdownContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="mb-0">Cliquez pour charger les recommandations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
<div class="modal fade" id="recommendationDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommendationModalTitle">Détails de la recommandation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="recommendationModalBody">
                Chargement...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="applyRecommendationBtn">Appliquer</button>
            </div>
        </div>
    </div>
</div>

<!-- Container pour les toasts -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>

<script>
// Fonction principale - Chargement des recommandations
function loadAIRecommendations() {
    const container = document.getElementById('aiDropdownContent');
    container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch('api_advisor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            api_key: "a7e8bf0c-1ce0-45ec-982d-c15ba8035cdf",
            days: 7
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        if(data.status === 'success' && data.recommendations?.length > 0) {
            renderDropdownRecommendations(data.recommendations);
            updateNotificationBadge(data.recommendations.length);
        } else {
            showNoResultsInDropdown();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorInDropdown();
    });
}

// Affichage des recommandations dans le dropdown
function renderDropdownRecommendations(recommendations) {
    const container = document.getElementById('aiDropdownContent');
    
    let html = `
    <div class="list-group list-group-flush">
        <div class="list-group-item bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <strong>${recommendations.length} recommandations</strong>
                <small class="text-muted">${new Date().toLocaleTimeString()}</small>
            </div>
        </div>`;
    
    recommendations.slice(0, 5).forEach(rec => {
        html += `
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <span class="badge bg-${getPriorityColor(rec.priority)} me-2">${rec.priority}</span>
                <small class="text-muted">${rec.type}</small>
            </div>
            <h6 class="mb-1">${rec.title}</h6>
            <p class="mb-2 text-muted small">${truncateText(rec.description, 60)}</p>
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-outline-primary" 
                        onclick="showRecommendationDetail('${rec.id}')">
                    <i class="fa fa-info-circle me-1"></i> Détails
                </button>
            </div>
        </div>`;
    });
    
    if (recommendations.length > 5) {
        html += `
        <div class="list-group-item text-center">
            <small class="text-muted">+ ${recommendations.length - 5} autres recommandations</small>
        </div>`;
    }
    
    html += `</div>`;
    container.innerHTML = html;
}

// Affichage des détails d'une recommandation
function showRecommendationDetail(recId) {
    const modalBody = document.getElementById('recommendationModalBody');
    modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

    fetch('api_advisor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            api_key: "a7e8bf0c-1ce0-45ec-982d-c15ba8035cdf",
            action: "get_details",
            rec_id: recId
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        if(data.status === 'success' && data.recommendation) {
            renderRecommendationDetails(data.recommendation);
        } else {
            showErrorInModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorInModal();
    });
}

// Rendu des détails dans le modal
function renderRecommendationDetails(recommendation) {
    document.getElementById('recommendationModalTitle').textContent = recommendation.title;
    
    const modalBody = document.getElementById('recommendationModalBody');
    modalBody.innerHTML = `
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <i class="fa fa-exclamation-circle me-2"></i>Analyse
                </div>
                <div class="card-body">
                    <p>${recommendation.title}</p>
                    <div class="alert alert-warning p-2 small mb-0">
                        <i class="fa fa-chart-line me-2"></i>
                        Priorité: ${recommendation.priority} (Confiance: ${recommendation.confidence}%)
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <i class="fa fa-lightbulb me-2"></i>Action recommandée
                </div>
                <div class="card-body">
                    <p>${recommendation.description}</p>
                    <div class="alert alert-info p-2 small mb-0">
                        <i class="fa fa-tag me-2"></i>
                        Type: ${recommendation.type}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <h6 class="text-muted">Informations techniques</h6>
        <div class="bg-light p-3 small rounded">
            <p class="mb-1"><strong>Généré le:</strong> ${formatDate(recommendation.timestamp)}</p>
            <p class="mb-1"><strong>ID:</strong> ${recommendation.id}</p>
            <p class="mb-0"><strong>Période analysée:</strong> ${recommendation.days || 7} jours</p>
        </div>
    </div>`;
    
    document.getElementById('applyRecommendationBtn').setAttribute('data-rec-id', recommendation.id);
    
    const modal = new bootstrap.Modal(document.getElementById('recommendationDetailModal'));
    modal.show();
}

// Application d'une recommandation
function implementSuggestion(id) {
    const btn = document.getElementById('applyRecommendationBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Application...';

    fetch('api_advisor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            api_key: "a7e8bf0c-1ce0-45ec-982d-c15ba8035cdf",
            action: "implement",
            rec_id: id
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        if(data.status === 'success') {
            showToast('success', 'Succès', 'Recommandation appliquée avec succès');
            loadAIRecommendations();
        } else {
            showToast('error', 'Erreur', data.message || "Échec de l'application");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Erreur', "Une erreur est survenue");
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Appliquer';
        bootstrap.Modal.getInstance(document.getElementById('recommendationDetailModal')).hide();
    });
}

// Fonctions utilitaires
function updateNotificationBadge(count) {
    const badge = document.getElementById('aiNotificationBadge');
    if (count > 0) {
        badge.style.display = 'block';
        badge.textContent = count > 9 ? '9+' : count;
    } else {
        badge.style.display = 'none';
    }
}

function showNoResultsInDropdown() {
    document.getElementById('aiDropdownContent').innerHTML = `
    <div class="text-center py-4">
        <i class="fa fa-info-circle text-muted fs-4 mb-3"></i>
        <p class="mb-0">Aucune recommandation actuellement</p>
    </div>`;
    updateNotificationBadge(0);
}

function showErrorInDropdown() {
    document.getElementById('aiDropdownContent').innerHTML = `
    <div class="alert alert-danger m-3">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <p class="mb-0 small">Service temporairement indisponible</p>
    </div>`;
}

function showErrorInModal() {
    document.getElementById('recommendationModalBody').innerHTML = `
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-triangle me-2"></i>
        Impossible de charger les détails de cette recommandation
    </div>`;
}

function getPriorityColor(priority) {
    const colors = {
        'high': 'danger',
        'medium': 'warning',
        'low': 'success'
    };
    return colors[priority.toLowerCase()] || 'primary';
}

function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

function formatDate(dateString) {
    if (!dateString) return 'Non spécifié';
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function showToast(type, title, message) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const toastHTML = `
    <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <strong>${title}</strong><br>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>`;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('aiAssistantDropdown').addEventListener('show.bs.dropdown', function() {
        loadAIRecommendations();
    });
    
    document.getElementById('applyRecommendationBtn').addEventListener('click', function() {
        const recId = this.getAttribute('data-rec-id');
        if (recId) {
            implementSuggestion(recId);
        }
    });
});
</script>

<style>
.dropdown-menu {
    border: none;
    padding: 0;
}

#aiDropdownContent {
    max-height: 400px;
    overflow-y: auto;
}

#aiNotificationBadge {
    font-size: 0.6rem;
    padding: 0.25em 0.4em;
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

#recommendationDetailModal .modal-body pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}

.toast {
    margin-bottom: 0.5rem;
}
</style>
                  
             <!-- Notifications -->
<div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-bell me-lg-2"></i>
        <span class="d-none d-lg-inline-flex">Notifications</span>
    </a>
    <div class="dropdown-menu dropdown-menu-end bg-white shadow rounded-4 mt-2 p-2">
        <!-- Un seul lien vers les alertes -->
        <a href="#" class="dropdown-item fw-semibold text-danger text-center"
           data-bs-toggle="modal" data-bs-target="#alertesModal">
            🚨 Voir les alertes de sécurité
        </a>
    </div>
</div>

<!-- Modal d'alertes -->
<div class="modal fade" id="alertesModal" tabindex="-1" aria-labelledby="alertesModalLabel" aria-hidden="true"
     data-bs-backdrop="false" data-bs-keyboard="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="alertesModalLabel">🚨 Alertes de Sécurité</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-white rounded-bottom-4">
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center align-middle">
              <thead class="table-danger">
                  <tr>
                      <th>ID</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Date</th>
                  </tr>
              </thead>
              <tbody>
                  <?php while ($row = $result->fetch_assoc()): ?>
                      <tr>
                          <td><?= htmlspecialchars($row['id']) ?></td>
                          <td><?= htmlspecialchars($row['email']) ?></td>
                          <td><?= htmlspecialchars($row['type_alerte']) ?></td>
                          <td><?= htmlspecialchars($row['date_alerte']) ?></td>
                      </tr>
                  <?php endwhile; ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>






<div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" id="userDropdown" onclick="toggleDropdown()">
        <?php if(file_exists($avatarPath)): ?>
            <img class="rounded-circle me-lg-2" src="<?= str_replace('../../../', '../../', $avatarPath) ?>?<?= filemtime($avatarPath) ?>" alt="Avatar" style="width: 40px; height: 40px;">
        <?php else: ?>
            <img class="rounded-circle me-lg-2" src="https://api.dicebear.com/6.x/avataaars/svg?seed=<?= $defaultSeed ?>" alt="Avatar" style="width: 40px; height: 40px;">
        <?php endif; ?>
        <span class="d-none d-lg-inline-flex"><?= htmlspecialchars($_SESSION['user']['firstName'] ?? 'John Doe') ?></span>
    </a>
    <div class="dropdown-menu" id="userDropdownMenu">
        <a href="#" class="dropdown-item" onclick="openAvatarModal()">
            <i class="fas fa-user-edit me-1"></i> Personnaliser l'avatar
        </a>
        <a href="#" class="dropdown-item">
            <i class="fas fa-cog me-1"></i> Paramètres
        </a>
        <div class="dropdown-divider"></div>
        <a href="../../../View/FrontOffice/login.php" class="dropdown-item text-danger">
    <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
</a>
    </div>
</div>

<!-- Modal pour personnaliser l'avatar -->
<div class="custom-modal" id="avatarModal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">
                <i class="fas fa-user-circle me-2"></i>Personnaliser votre avatar
            </h5>
            <span class="custom-close" onclick="closeAvatarModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <?php if(isset($_SESSION['avatar_updated'])): ?>
                <div class="custom-alert success">
                    Avatar mis à jour avec succès!
                    <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
                </div>
                <?php unset($_SESSION['avatar_updated']); ?>
            <?php elseif(isset($_SESSION['avatar_error'])): ?>
                <div class="custom-alert error">
                    <?= $_SESSION['avatar_error'] ?>
                    <span class="alert-close" onclick="this.parentElement.style.display='none'">&times;</span>
                </div>
                <?php unset($_SESSION['avatar_error']); ?>
            <?php endif; ?>
            
            <div class="avatar-preview-container">
                <img id="avatarPreview" src="<?= file_exists($avatarPath) ? str_replace('../../../', '../../', $avatarPath).'?'.filemtime($avatarPath) : 'https://api.dicebear.com/6.x/avataaars/svg?seed='.$defaultSeed ?>" 
                     class="avatar-preview">
                <button type="button" class="btn-random" onclick="randomizeAvatar()">
                    <i class="fas fa-random me-1"></i> Aléatoire
                </button>
            </div>
            
            <form id="avatarForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="userId" value="<?= isset($_SESSION['user']['iduser']) ? htmlspecialchars($_SESSION['user']['iduser']) : '' ?>">
                <input type="hidden" id="avatarSeed" name="avatarSeed" value="<?= $defaultSeed ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Style</label>
                        <select class="form-select" id="avatarStyle" name="avatarStyle" onchange="updateAvatarPreview()">
                            <option value="avataaars">Humain (Avataaars)</option>
                            <option value="identicon">Géométrique</option>
                            <option value="bottts">Robot</option>
                            <option value="micah">Illustration</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Couleur de fond</label>
                        <input type="color" class="form-color" id="bgColor" 
                               name="bgColor" value="#4e73df" onchange="updateAvatarPreview()">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeAvatarModal()">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles pour le dropdown */
.dropdown-menu {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 0;
    border-radius: 4px;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
}

.dropdown-divider {
    height: 1px;
    background-color: #ddd;
    margin: 4px 0;
}

/* Styles pour le modal */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.custom-modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.custom-modal-header {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.custom-close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.custom-close:hover {
    color: black;
}

.custom-modal-body {
    padding: 15px 0;
}

/* Styles supplémentaires */
.avatar-preview-container {
    text-align: center;
    margin-bottom: 20px;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.btn-random {
    background: none;
    border: 1px solid #4e73df;
    color: #4e73df;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-random:hover {
    background-color: #f8f9fa;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-select, .form-color {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn-primary, .btn-secondary {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.btn-primary {
    background-color: #4e73df;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.custom-alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    position: relative;
}

.custom-alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.custom-alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-close {
    position: absolute;
    right: 10px;
    top: 5px;
    cursor: pointer;
    font-weight: bold;
}
</style>

<script>
// Fonctions pour le dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('userDropdownMenu');
    dropdown.classList.toggle('show');
}

// Fermer le dropdown si on clique ailleurs
window.onclick = function(event) {
    if (!event.target.matches('.nav-link.dropdown-toggle')) {
        const dropdowns = document.getElementsByClassName('dropdown-menu');
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Fonctions pour le modal
function openAvatarModal() {
    document.getElementById('avatarModal').style.display = 'block';
    updateAvatarPreview();
}

function closeAvatarModal() {
    document.getElementById('avatarModal').style.display = 'none';
}

// Fonction pour mettre à jour l'aperçu de l'avatar
function updateAvatarPreview() {
    const style = document.getElementById('avatarStyle').value;
    const bgColor = document.getElementById('bgColor').value.substring(1); // Retire le #
    const seed = Math.random().toString(36).substring(2); // Nouveau seed aléatoire
    
    document.getElementById('avatarSeed').value = seed;
    document.getElementById('avatarPreview').src = 
        `https://api.dicebear.com/6.x/${style}/svg?seed=${seed}&backgroundColor=${bgColor}`;
}

// Fonction pour générer un avatar aléatoire
function randomizeAvatar() {
    const styles = ['avataaars', 'identicon', 'bottts', 'micah'];
    document.getElementById('avatarStyle').value = styles[Math.floor(Math.random() * styles.length)];
    
    // Générer une couleur aléatoire
    const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
    document.getElementById('bgColor').value = randomColor;
    
    updateAvatarPreview();
}

// Fermer le modal si on clique en dehors
window.onclick = function(event) {
    const modal = document.getElementById('avatarModal');
    if (event.target === modal) {
        closeAvatarModal();
    }
}
</script>

            </nav>

            <!-- Navbar End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <!-- Total Utilisateurs -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-white rounded p-4 shadow-sm d-flex align-items-center justify-content-between glass-card-hover">
                <div class="ms-3">
                    <p class="mb-2 text-secondary">Total Utilisateurs</p>
                    <h3 class="mb-0 text-primary counter" data-target="<?= count($users ?? []) ?>">13</h3>
                    <small class="text-success"><i class="fa fa-arrow-up me-1"></i> 12% vs hier</small>
                </div>
                <i class="fas fa-users fa-3x text-primary opacity-25"></i>
            </div>
        </div>

        <!-- Administrateurs -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-white rounded p-4 shadow-sm d-flex align-items-center justify-content-between glass-card-hover">
                <div class="ms-3">
                    <p class="mb-2 text-secondary">Administrateurs</p>
                    <h3 class="mb-0 text-danger counter" data-target="<?= count(array_filter($users ?? [], fn($u) => strtolower($u['type']) === 'admin')) ?>">4</h3>
                    <small class="text-success"><i class="fa fa-arrow-up me-1"></i> 5% vs hier</small>
                </div>
                <i class="fas fa-user-shield fa-3x text-danger opacity-25"></i>
            </div>
        </div>

        <!-- Entrepreneurs -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-white rounded p-4 shadow-sm d-flex align-items-center justify-content-between glass-card-hover">
                <div class="ms-3">
                    <p class="mb-2 text-secondary">Entrepreneurs</p>
                    <h3 class="mb-0 text-success counter" data-target="<?= count(array_filter($users ?? [], fn($u) => strtolower($u['type']) === 'entrepreneur')) ?>">4</h3>
                    <small class="text-danger"><i class="fa fa-arrow-down me-1"></i> 2% vs hier</small>
                </div>
                <i class="fas fa-user-tie fa-3x text-success opacity-25"></i>
            </div>
        </div>

        <!-- Investisseurs -->
        <div class="col-sm-6 col-xl-3">
            <div class="bg-white rounded p-4 shadow-sm d-flex align-items-center justify-content-between glass-card-hover">
                <div class="ms-3">
                    <p class="mb-2 text-secondary">Investisseurs</p>
                    <h3 class="mb-0 text-warning counter" data-target="<?= count(array_filter($users ?? [], fn($u) => strtolower($u['type']) === 'investor')) ?>">5</h3>
                    <small class="text-success"><i class="fa fa-arrow-up me-1"></i> 8% vs hier</small>
                </div>
                <i class="fas fa-chart-line fa-3x text-warning opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- Graphique Statistiques -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <!-- Graphique circulaire -->
        <div class="col-sm-12 col-xl-6">
            <div class="bg-white rounded p-4 shadow-sm glass-card-hover d-flex flex-column align-items-center justify-content-center" style="height: 400px;">
                <h6 class="mb-4 text-primary">Répartition des Utilisateurs</h6>
                <div style="width: 80%; height: 80%;"> <!-- Conteneur réduit pour le canvas -->
                    <canvas id="userPieChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
        <!-- Graphique à barres -->
        <div class="col-sm-12 col-xl-6">
            <div class="bg-white rounded p-4 shadow-sm glass-card-hover" style="height: 350px;">
                <h6 class="mb-4 text-primary">Inscriptions Mensuelles</h6>
                <canvas id="userBarChart" style="height: calc(100% - 40px); width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        renderPieChart();
        renderBarChart();
    });

    // Graphique circulaire
    function renderPieChart() {
        const ctx = document.getElementById('userPieChart').getContext('2d');
        const userTypes = ['Administrateurs', 'Entrepreneurs', 'Investisseurs'];
        const userCounts = [
            <?= count(array_filter($users, fn($u) => $u['type'] === 'admin')) ?>,
            <?= count(array_filter($users, fn($u) => $u['type'] === 'entrepreneur')) ?>,
            <?= count(array_filter($users, fn($u) => $u['type'] === 'investor')) ?>
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: userTypes,
                datasets: [{
                    data: userCounts,
                    backgroundColor: ['#dc3545', '#28a745', '#ffc107'],
                    hoverBackgroundColor: ['#c82333', '#218838', '#e0a800'],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = Math.round((value / total) * 100);
                                return `${context.label}: ${value} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Graphique à barres (inchangé)
    function renderBarChart() {
        const ctx = document.getElementById('userBarChart').getContext('2d');
        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        const registrations = [5, 8, 12, 6, 10, 15, 20, 22, 18, 25, 30, 28];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: "Nouvelles Inscriptions",
                    data: registrations,
                    backgroundColor: '#4e73df',
                    hoverBackgroundColor: '#2e59d9',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.raw} inscriptions`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    }
</script>

            <!-- Sale & Revenue End -->

          <!-- Formulaire CRUD Modernisé -->
          <div class="glass-card card mb-4 animate__animated animate__fadeIn" style="width: 1240px;"> <!-- Augmentez cette valeur selon vos besoins -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-user-edit me-2"></i> Gestion Utilisateur</h6>
    </div>
    
    <div class="card-body p-4" style="background-color: #f8f9fa;">
        <form id="userForm" method="post" action="ajouterUser.php" class="needs-validation" novalidate>
            <input type="hidden" id="iduser" name="iduser">
            
            <div class="row g-3">
                <!-- Prénom avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstName" class="form-label">
                            <i class="fas fa-user-circle me-2 text-primary"></i>Prénom
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Ex: Jean" required>
                        </div>
                        <div class="invalid-feedback" id="firstName_error">Veuillez saisir un prénom valide</div>
                    </div>
                </div>

                <!-- Nom avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastName" class="form-label">
                            <i class="fas fa-signature me-2 text-primary"></i>Nom
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Ex: Dupont" required>
                        </div>
                        <div class="invalid-feedback" id="lastName_error">Veuillez saisir un nom valide</div>
                    </div>
                </div>

                <!-- Email avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-at me-2 text-primary"></i>Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="exemple@domaine.com" required>
                        </div>
                        <div class="invalid-feedback" id="email_error">Veuillez saisir un email valide</div>
                    </div>
                </div>

                <!-- Mot de passe avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-2 text-primary"></i>Mot de passe
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="password_error">8 caractères minimum</div>
                    </div>
                </div>

                <!-- Type d'utilisateur avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type" class="form-label">
                            <i class="fas fa-user-tag me-2 text-primary"></i>Type d'utilisateur
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                            <select id="type" name="type" class="form-select" required>
                                <option value="" selected disabled hidden>Sélectionner un type</option>
                                <option value="admin"><i class="fas fa-user-shield me-2"></i> Administrateur</option>
                                <option value="client"><i class="fas fa-user-tie me-2"></i> Entrepreneur</option>
                                <option value="formateur"><i class="fas fa-user-graduate me-2"></i> Investisseur</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Téléphone avec icône -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="numtel" class="form-label">
                            <i class="fas fa-phone-alt me-2 text-primary"></i>Téléphone
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                            <input type="tel" id="numtel" name="numtel" class="form-control" placeholder="Ex: 21234567" required>
                        </div>
                        <div class="invalid-feedback" id="numtel_error">Veuillez saisir un numéro valide</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="reset" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-eraser me-1"></i> Effacer
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher/masquer mot de passe
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });

    // Validation en temps réel
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    });
});
</script>

<style>
.glass-card {
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.form-group {
    margin-bottom: 1.5rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control, .form-select {
    border-left: none;
    padding-left: 0;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    border-color: #4e73df;
}

.is-valid {
    border-color: #28a745 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.is-invalid {
    border-color: #dc3545 !important;
}

.btn-outline-secondary {
    transition: all 0.3s;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
}

.form-label i {
    width: 20px;
    text-align: center;
}
</style>

           <!-- Remplacer la section search-container existante par ceci -->
           <div class="search-container mb-4">
    <div class="row g-3 align-items-center">
        <!-- Zone de recherche améliorée -->
        <div class="col-md-8">
            <div class="search-box">
                <div class="search-combo">
                    <select id="searchType" class="search-type">
                        <option value="nom">Nom</option>
                        <option value="email">Email</option>
                        <option value="telephone">Téléphone</option>
                    </select>
                    <i class="fas fa-caret-down"></i>
                </div>
                <input type="text" id="searchInput" class="search-input" placeholder="Rechercher...">
                <button class="search-btn" id="searchButton">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </div>

        <!-- Zone de tri améliorée -->
        <div class="col-md-4">
            <div class="sort-box">
                <select id="sortSelect" class="sort-select">
                    <option value="prenom-asc">Prénom (A-Z)</option>
                    <option value="prenom-desc">Prénom (Z-A)</option>
                    <option value="type">Type (Admin > Entrepreneur > Investor)</option>
                </select>
                <i class="fas fa-sort"></i>
            </div>
        </div>
    </div>
</div>

<style>
.search-container {
    padding: 1rem;
}

.search-box, .sort-box {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 50px;
    border: 1px solid #e0e0e0;
    height: 50px;
    overflow: hidden;
}

.search-box {
    padding: 0;
}

.search-combo {
    position: relative;
    min-width: 120px;
    height: 100%;
    background: #e9ecef;
}

.search-type {
    width: 100%;
    height: 100%;
    border: none;
    background: transparent;
    padding: 0 2rem 0 1rem;
    appearance: none;
    outline: none;
    cursor: pointer;
}

.search-combo i {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.search-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0 1rem;
    outline: none;
    height: 100%;
}

.search-btn {
    height: 100%;
    border: none;
    background: #4e73df;
    color: white;
    padding: 0 1.5rem;
    cursor: pointer;
    transition: background 0.3s;
    white-space: nowrap;
}

.search-btn:hover {
    background: #3a5ab8;
}

.sort-box {
    position: relative;
    padding: 0;
    cursor: pointer;
}

.sort-select {
    width: 100%;
    height: 100%;
    border: none;
    background: transparent;
    padding: 0 2rem 0 1rem;
    appearance: none;
    outline: none;
    cursor: pointer;
}

.sort-box i {
    position: absolute;
    right: 1rem;
    color: #6c757d;
    pointer-events: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction de tri
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        const sortValue = this.value;
        const tbody = document.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            if (sortValue === 'prenom-asc') {
                return a.cells[0].textContent.localeCompare(b.cells[0].textContent);
            } 
            else if (sortValue === 'prenom-desc') {
                return b.cells[0].textContent.localeCompare(a.cells[0].textContent);
            }
            else if (sortValue === 'type') {
                // Ordre spécifique: admin > client > formateur
                const typeOrder = { 'admin': 1, 'entrepreneur': 2, 'investor': 3 };
                const aType = a.cells[3].textContent.trim().toLowerCase();
                const bType = b.cells[3].textContent.trim().toLowerCase();
                return typeOrder[aType] - typeOrder[bType];
            }
            return 0;
        });
        
        // Réinsérer les lignes triées
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    });

    // Fonction de recherche
    const searchButton = document.getElementById('searchButton');
    searchButton.addEventListener('click', function() {
        const searchType = document.getElementById('searchType').value;
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            let cellIndex;
            switch(searchType) {
                case 'nom':
                    cellIndex = 1; // Colonne Nom
                    break;
                case 'email':
                    cellIndex = 2; // Colonne Email
                    break;
                case 'telephone':
                    cellIndex = 4; // Colonne Téléphone
                    break;
            }
            
            const cellValue = row.cells[cellIndex].textContent.toLowerCase();
            if (cellValue.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
</script>

<script>
// Animation pour la barre de recherche
document.getElementById('searchInput').addEventListener('focus', function() {
    this.parentElement.style.boxShadow = '0 0 0 3px rgba(78, 115, 223, 0.2)';
    this.parentElement.style.background = 'white';
});

document.getElementById('searchInput').addEventListener('blur', function() {
    this.parentElement.style.boxShadow = 'none';
    this.parentElement.style.background = '#f8f9fa';
});

// Animation pour le bouton de recherche
document.getElementById('searchButton').addEventListener('mouseenter', function() {
    this.style.transform = 'translateX(2px)';
});

document.getElementById('searchButton').addEventListener('mouseleave', function() {
    this.style.transform = 'translateX(0)';
});

// Animation pour le bouton de tri
document.getElementById('sortButton').addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-1px)';
    this.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
});

document.getElementById('sortButton').addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0)';
    this.style.boxShadow = 'none';
});
</script>
<h5 class="text-primary mb-3"><i class="bi bi-people-fill"></i> La liste des utilisateurs</h5>

<?php if (count($users) > 0): ?>
    <div class="table-responsive mb-5" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover table-bordered align-middle text-center shadow-sm">
            <thead class="table-light sticky-top">
                <tr>
                    <th><i class="bi bi-person-bounding-box"></i> Prénom</th>
                    <th><i class="bi bi-person"></i> Nom</th>
                    <th><i class="bi bi-envelope-at-fill"></i> Email</th>
                    <th><i class="bi bi-person-badge"></i> Type</th>
                    <th><i class="bi bi-telephone-fill"></i> Téléphone</th>
                    <th><i class="bi bi-tools"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="hover-row">
                        <td><?= htmlspecialchars($user['firstName'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['lastName'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['type'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['numtel'] ?? '') ?></td>
                        <td>
                            <a href="modifierUser.php?id=<?= $user['iduser']; ?>" class="btn btn-outline-primary btn-sm me-2">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="supprimerUser.php" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ressource ?')">
                                <input type="hidden" name="iduser" value="<?= $user['iduser']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Aucun utilisateur trouvé.</div>
<?php endif; ?>

<!-- Système de badges et classement sous le tableau -->
<!-- Système d'activité récente sous le tableau -->
<div class="row">
 <!-- ACTIVITÉ RÉCENTE -->
<!-- ACTIVITÉ RÉCENTE -->
<div class="col-md-6 mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient bg-primary text-white d-flex align-items-center">
            <i class="bi bi-activity me-2"></i> Flux d'activité récente
        </div>
        <div class="card-body activity-scroll-container">
            <div class="activity-feed">
                <?php
                // Récupérer les 8 dernières activités (connexions et alertes)
                $sql = "
                    (SELECT u.iduser, u.firstName, u.lastName, 'connexion' as type, c.date_connexion as activity_time, c.ip, NULL as extra_info
                    FROM user u
                    JOIN connexions c ON u.iduser = c.user_id
                    WHERE c.is_successful = 1)
                    UNION
                    (SELECT u.iduser, u.firstName, u.lastName, 'alerte' as type, a.date_alerte as activity_time, NULL as ip, a.type_alerte as extra_info
                    FROM user u
                    JOIN alertes a ON u.iduser = a.user_id)
                    ORDER BY activity_time DESC
                    LIMIT 8";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $activity_type = $row['type'];
                        $user_name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                        $initials = strtoupper(substr($row['firstName'], 0, 1) . substr($row['lastName'], 0, 1));
                        $activity_time = date('d/m/Y H:i', strtotime($row['activity_time']));
                        $icon = $activity_type === 'connexion' ? 'bi-box-arrow-in-right' : 'bi-bell-fill';
                        $color = $activity_type === 'connexion' ? 'bg-success' : 'bg-warning';
                        $details = $activity_type === 'connexion' ? 'IP: ' . htmlspecialchars($row['ip']) : 'Type: ' . htmlspecialchars($row['extra_info']);

                        echo '
                        <div class="activity-item d-flex align-items-start mb-3 animate__animated animate__fadeIn">
                            <div class="avatar rounded-circle me-3 ' . $color . '" style="width: 40px; height: 40px; line-height: 40px; text-align: center; color: white; font-size: 1rem;">
                                ' . $initials . '
                            </div>
                            <div class="activity-content flex-grow-1" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $details . '">
                                <h6 class="mb-1">' . $user_name . '</h6>
                                <p class="text-muted small mb-1"><i class="bi ' . $icon . ' me-1"></i>' . ucfirst($activity_type) . '</p>
                                <p class="text-muted small">' . $activity_time . '</p>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="text-muted text-center">Aucune activité récente.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- CLASSEMENT -->
<div class="col-md-6 mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient bg-success text-white d-flex align-items-center justify-content-between">
            <div><i class="bi bi-trophy-fill me-2"></i> Podium des utilisateurs</div>
            
        </div>
        <div class="card-body">
            <div class="podium-container text-center mb-4">
                <?php
                // Récupérer les 5 utilisateurs les plus actifs
                $sql = "
                    SELECT u.iduser, u.firstName, u.lastName, COUNT(c.id) as connexions
                    FROM user u
                    LEFT JOIN connexions c ON u.iduser = c.user_id
                    WHERE c.is_successful = 1
                    GROUP BY u.iduser
                    ORDER BY connexions DESC
                    LIMIT 5";
                $result = $conn->query($sql);
                $topUsers = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $topUsers[] = $row;
                    }
                }

                // Afficher le podium pour les 3 premiers
                if (!empty($topUsers)) {
                    $podium = array_slice($topUsers, 0, 3);
                    echo '<div class="d-flex justify-content-center align-items-end">';
                    foreach ($podium as $index => $user) {
                        $height = [200, 150, 100][$index]; // Hauteurs du podium
                        $color = ['gold', 'silver', 'bronze'][$index];
                        $medal = ['bi-trophy-fill', 'bi-award-fill', 'bi-award-fill'][$index];
                        echo '
                        <div class="podium-item mx-3 animate__animated animate__bounceIn" style="height: ' . $height . 'px;">
                            <div class="podium-badge ' . $color . ' rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <i class="bi ' . $medal . ' fs-4"></i>
                            </div>
                            <h6>' . htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) . '</h6>
                            <div class="bubble animate__animated animate__pulse" style="width: 60px; height: 60px; line-height: 60px;">
                                ' . $user['connexions'] . '
                            </div>
                        </div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <!-- Liste des autres utilisateurs -->
            <div class="ranking-list">
                <?php
                foreach (array_slice($topUsers, 3) as $index => $user) {
                    echo '
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">' . ($index + 4) . '</span>
                        <span>' . htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) . '</span>
                        <span class="ms-auto bubble small animate__animated animate__pulse">' . $user['connexions'] . '</span>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Activité récente */
    .activity-scroll-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .activity-feed .activity-item {
        transition: background-color 0.3s ease;
        border-radius: 8px;
        padding: 10px;
    }

    .activity-feed .activity-item:hover {
        background-color: #f8f9fa;
    }

    .activity-feed .avatar {
        font-weight: bold;
    }

    /* Podium */
    .podium-container {
        min-height: 250px;
    }

    .podium-item {
        width: 120px;
        background: linear-gradient(180deg, #e0e0e0, #ffffff);
        border-radius: 10px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
    }

    .podium-badge.gold {
        background: linear-gradient(135deg, #FFD700, #D4AF37);
    }

    .podium-badge.silver {
        background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
    }

    .podium-badge.bronze {
        background: linear-gradient(135deg, #CD7F32, #8B4513);
    }

    .bubble {
        background: #007bff;
        color: white;
        border-radius: 50%;
        text-align: center;
        font-size: 0.9rem;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .bubble.small {
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 0.8rem;
    }

    .ranking-list {
        max-height: 150px;
        overflow-y: auto;
        padding-right: 10px;
    }

    /* Animation d’apparition */
    .animate__animated.animate__fadeIn {
        animation: fadeIn 0.5s ease-in;
    }

    .animate__animated.animate__bounceIn {
        animation: bounceIn 0.7s ease;
    }

    .animate__animated.animate__pulse {
        animation: pulse 1.5s infinite;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    /* Scrollbar */
    .activity-scroll-container::-webkit-scrollbar,
    .ranking-list::-webkit-scrollbar {
        width: 8px;
    }

    .activity-scroll-container::-webkit-scrollbar-track,
    .ranking-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .activity-scroll-container::-webkit-scrollbar-thumb,
    .ranking-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .activity-scroll-container::-webkit-scrollbar-thumb:hover,
    .ranking-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<script>
    // Activer les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Filtre de période (simulation côté client)
    function filterRanking() {
        // Pour une implémentation complète, vous devriez faire une requête AJAX vers le serveur
        // Ici, on simule en masquant les éléments (à remplacer par une vraie logique serveur)
        console.log('Filtre changé : ' + document.getElementById('periodFilter').value);
    }
</script>
   
<!-- STATISTIQUES -->
<div class="col-md-12 mb-4">
    <div class="card border-0 shadow-sm animate__animated animate__fadeIn">
        <div class="card-header bg-gradient bg-info text-white d-flex align-items-center justify-content-between rounded-top">
            <div><i class="bi bi-bar-chart-fill me-2"></i> Statistiques des Connexions</div>
            <select id="statsPeriod" class="form-select form-select-sm w-auto bg-dark text-white border-0" onchange="updateCharts()">
                <option value="7">7 derniers jours</option>
                <option value="30">30 derniers jours</option>
                <option value="90">3 derniers mois</option>
                <option value="365">1 an</option>
            </select>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Graphique 1 : Connexions par jour -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm chart-card animate__animated animate__slideInLeft animate__delay-1s" data-bs-toggle="tooltip" title="Nombre de connexions quotidiennes">
                        <div class="card-header bg-light text-dark fw-bold">
                            Connexions par Jour
                        </div>
                        <div class="card-body p-3">
                            <img id="dailyConnectionsChart" src="" alt="Graphique des connexions quotidiennes" class="img-fluid chart-img">
                        </div>
                    </div>
                </div>
                
                <!-- Graphique 2 : Répartition par pays -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm chart-card animate__animated animate__slideInRight animate__delay-1s" data-bs-toggle="tooltip" title="Répartition des connexions par pays">
                        <div class="card-header bg-light text-dark fw-bold">
                            Répartition par Pays
                        </div>
                        <div class="card-body p-3">
                            <img id="countriesChart" src="" alt="Graphique par pays" class="img-fluid chart-img">
                        </div>
                    </div>
                </div>
                
                <!-- Graphique 3 : Heures de connexion -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm chart-card animate__animated animate__slideInLeft animate__delay-2s" data-bs-toggle="tooltip" title="Heures les plus actives pour les connexions">
                        <div class="card-header bg-light text-dark fw-bold">
                            Heures de Connexion
                        </div>
                        <div class="card-body p-3">
                            <img id="hoursChart" src="" alt="Graphique par heure" class="img-fluid chart-img">
                        </div>
                    </div>
                </div>
                
                <!-- Graphique 4 : Types d'utilisateurs -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm chart-card animate__animated animate__slideInRight animate__delay-2s" data-bs-toggle="tooltip" title="Répartition par type d'utilisateur">
                        <div class="card-header bg-light text-dark fw-bold">
                            Types d'Utilisateurs
                        </div>
                        <div class="card-body p-3">
                            <img id="userTypesChart" src="" alt="Graphique par type" class="img-fluid chart-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style général pour une apparence moderne et rectangulaire */
    .card {
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        border-radius: 10px 10px 0 0;
        font-weight: 500;
    }

    .chart-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border: none;
    }

    .chart-img {
        max-height: 230px; /* Réduit légèrement la hauteur */
        width: 100%;
        object-fit: contain;
        transform: scale(0.95); /* Zoom réduit pour une apparence plus compacte */
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .chart-img.loading {
        opacity: 0.5;
        transform: scale(0.9); /* Effet de zoom réduit pendant le chargement */
    }

    .chart-img:not(.loading) {
        animation: bounceIn 0.5s ease forwards;
    }

    /* Style du sélecteur de période */
    #statsPeriod {
        background-color: rgba(0, 0, 0, 0.3);
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    #statsPeriod:hover, #statsPeriod:focus {
        background-color: rgba(0, 0, 0, 0.5);
        transform: scale(1.05);
    }

    /* Animations personnalisées */
    @keyframes bounceIn {
        0% { transform: scale(0.9); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(0.95); opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chart-img {
            max-height: 180px;
        }
    }
</style>

<script>
// Activer les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Générer les graphiques au chargement
    generateCharts();
});

// Fonction pour générer les graphiques
function generateCharts(days = 7) {
    // Ajouter l'état de chargement
    document.querySelectorAll('.chart-img').forEach(img => img.classList.add('loading'));

    // Requête AJAX pour récupérer les données
    fetch(`getStatsData.php?days=${days}`)
        .then(response => response.json())
        .then(data => {
            // 1. Graphique des connexions quotidiennes
            const dailyConnectionsChartUrl = `https://quickchart.io/chart?c=${
                encodeURIComponent(JSON.stringify({
                    type: 'line',
                    data: {
                        labels: data.daily.labels.length ? data.daily.labels : ['Aucune donnée'],
                        datasets: [{
                            label: 'Connexions',
                            data: data.daily.data.length ? data.daily.data : [0],
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: false }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.1)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                }))
            }`;
            document.getElementById('dailyConnectionsChart').src = dailyConnectionsChartUrl;
            
            // 2. Graphique des pays
            const countriesChartUrl = `https://quickchart.io/chart?c=${
                encodeURIComponent(JSON.stringify({
                    type: 'doughnut',
                    data: {
                        labels: data.countries.labels.length ? data.countries.labels : ['Aucune donnée'],
                        datasets: [{
                            data: data.countries.data.length ? data.countries.data : [1],
                            backgroundColor: data.countries.labels.length ? [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)'
                            ] : ['rgba(200, 200, 200, 0.8)'],
                            borderColor: ['#fff'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { 
                                position: 'right',
                                labels: { font: { size: 12 } }
                            },
                            title: { display: false }
                        }
                    }
                }))
            }`;
            document.getElementById('countriesChart').src = countriesChartUrl;
            
            // 3. Graphique des heures de connexion
            const hoursChartUrl = `https://quickchart.io/chart?c=${
                encodeURIComponent(JSON.stringify({
                    type: 'bar',
                    data: {
                        labels: data.hours.labels.length ? data.hours.labels : ['Aucune donnée'],
                        datasets: [{
                            label: 'Connexions',
                            data: data.hours.data.length ? data.hours.data : [0],
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: false }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.1)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                }))
            }`;
            document.getElementById('hoursChart').src = hoursChartUrl;
            
            // 4. Graphique des types d'utilisateurs
            const userTypesChartUrl = `https://quickchart.io/chart?c=${
                encodeURIComponent(JSON.stringify({
                    type: 'pie',
                    data: {
                        labels: data.userTypes.labels.length ? data.userTypes.labels : ['Aucune donnée'],
                        datasets: [{
                            data: data.userTypes.data.length ? data.userTypes.data : [1],
                            backgroundColor: data.userTypes.labels.length ? [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)'
                            ] : ['rgba(200, 200, 200, 0.8)'],
                            borderColor: ['#fff'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { 
                                position: 'right',
                                labels: { font: { size: 12 } }
                            },
                            title: { display: false }
                        }
                    }
                }))
            }`;
            document.getElementById('userTypesChart').src = userTypesChartUrl;

            // Supprimer l'état de chargement
            document.querySelectorAll('.chart-img').forEach(img => img.classList.remove('loading'));
        })
        .catch(error => {
            console.error('Erreur lors du chargement des graphiques:', error);
            document.querySelectorAll('.chart-img').forEach(img => {
                img.src = 'https://via.placeholder.com/300x200?text=Erreur+de+chargement';
                img.classList.remove('loading');
            });
        });
}

// Fonction pour mettre à jour les graphiques
function updateCharts() {
    const days = document.getElementById('statsPeriod').value;
    generateCharts(days);
}
</script>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

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
    <script>
        function validerInscription(event) {
            event.preventDefault();

            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const numtel = document.getElementById('numtel');

            // Réinitialiser les messages d'erreur et les classes invalides
            document.querySelectorAll('.form-text').forEach(el => el.textContent = '');
            document.querySelectorAll('input, select, textarea').forEach(input => input.classList.remove('invalid'));

            let valid = true;

            // Prénom : lettres uniquement
            if (!/^[a-zA-Z]+$/.test(firstName.value)) {
                const error = document.getElementById('firstName_error');
                error.textContent = 'Le prénom ne doit contenir que des lettres.';
                error.classList.add('error-message');
                firstName.classList.add('invalid');
                valid = false;
            }

            // Nom : lettres uniquement
            if (!/^[a-zA-Z]+$/.test(lastName.value)) {
                const error = document.getElementById('lastName_error');
                error.textContent = 'Le nom ne doit contenir que des lettres.';
                error.classList.add('error-message');
                lastName.classList.add('invalid');
                valid = false;
            }

            // Téléphone : 8 chiffres
            if (!/^\d{8}$/.test(numtel.value)) {
                const error = document.getElementById('numtel_error');
                error.textContent = 'Le numéro de téléphone doit comporter 8 chiffres.';
                error.classList.add('error-message');
                numtel.classList.add('invalid');
                valid = false;
            }

            // Email : format valide
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                const error = document.getElementById('email_error');
                error.textContent = 'Veuillez entrer une adresse email valide.';
                error.classList.add('error-message');
                email.classList.add('invalid');
                valid = false;
            }

            // Mot de passe : au moins 6 caractères
            if (password.value.length < 6) {
                const error = document.getElementById('password_error');
                error.textContent = 'Le mot de passe doit contenir au moins 6 caractères.';
                error.classList.add('error-message');
                password.classList.add('invalid');
                valid = false;
            }

            if (valid) {
                document.getElementById('userForm').submit();
            }
        }
// Animation des compteurs
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200;
    
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = target / speed;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(animateCounters, 1);
        } else {
            counter.innerText = target;
        }
    });
}

// Graphique circulaire


        
    </script>
    


</body>
</html>