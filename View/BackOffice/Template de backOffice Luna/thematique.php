<?php
session_start();
include '../../../Controller/ThematiqueC.php';

// Créer une instance du contrôleur
$cont = new ThematiqueC();
$filtre = '';
$thematiques = [];

// Gestion de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_thematique'])) {
        $result = $cont->updateThematique(
            $_POST['id_thematique'],
            $_POST['titre'],
            $_POST['description']
        );

        if ($result) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Thématique mise à jour avec succès!'
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la mise à jour'
            ];
        }
        header('Location: thematique.php');
        exit();
    } elseif (isset($_POST['trier_thematique'])) {
        $thematiques = $cont->trierParTitre();
    } elseif (isset($_POST['titre_filtre'])) {
        $filtre = $_POST['titre_filtre'];
        $thematiques = $cont->filtrerParTitre($filtre);
    } else {
        $thematiques = $cont->afficherThematique();
    }
} else {
    $thematiques = $cont->afficherThematique();
}




// Récupérer les données de la thématique à modifier si l'ID est passé en paramètre
$thematiqueToEdit = null;
if (isset($_GET['edit_id'])) {
    $thematiqueToEdit = $cont->getThematiqueById($_GET['edit_id']);
}

// Ajouter ici le code d'affichage des alertes
if (isset($_SESSION['alert'])) {
    echo '<div class="alert alert-'.$_SESSION['alert']['type'].' alert-dismissible fade show" role="alert">
        '.$_SESSION['alert']['message'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    unset($_SESSION['alert']);
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

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .is-invalid {
    border-color: red;
     }

    .form-text {
    color: red;
    font-size: 0.875rem;
}

  /*tableau*/
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
                    <a href="statistique.html" class="dropdown-item">Statistique</a>
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
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
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
        <form id="travelOfferForm" method="post" action="ajouterThematique.php">
            <input type="hidden" id="id_ressource" name="id_ressource">
            
            <div class="form-section mb-4">
                <h5 class="section-title text-primary mb-3">
                    <i class="fas fa-folder-open me-2"></i>Informations de thematique
                </h5>
                
                <div class="row g-3">

                    <!-- Titre -->
                    <div class="col-md-6">
                        <label for="titre" class="form-label fw-bold">Titre</label>
                        <input type="text" id="titre" name="titre" 
                               class="form-control shadow-sm" 
                               placeholder="Ex: marketing">
                        <div id="titre_error" class="form-text text-danger small"></div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea id="description" name="description" 
                                  class="form-control shadow-sm" 
                                  rows="4"
                                  placeholder="Décrivez la thematique en détail..."></textarea>
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

<!-- tableau d'affichage -->
<div class="container mt-5">
    <h2 class="fw-bold text-primary mb-4">
        <i class="bi bi-diagram-3-fill"></i> Liste des Thematiques
    </h2>

    <!-- Formulaire de filtrage -->
    <form method="POST" action="" class="mb-4">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <input type="text" 
                   name="titre_filtre" 
                   class="form-control shadow-sm w-auto" 
                   placeholder="Rechercher par titre..."
                   value="<?= htmlspecialchars($filtre) ?>">
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel-fill me-2"></i>Filtrer
            </button>

            <!-- Bouton Trier -->
            <button type="submit" name="trier_thematique" class="btn btn-info">
                <i class="bi bi-sort-alpha-down"></i> Trier par Thématique
            </button>

            <!-- Espace flexible -->
          <div class="flex-grow-1"></div>

               <!-- Bouton Statistique à droite -->
               <a href="statistique.php" class="btn btn-warning">
                  <i class="bi bi-pie-chart-fill"></i> Statistique
                </a>
         </div> 
        </div>                
    </form>
    <?php
    // --- PAGINATION ---
    $thematiquesParPage = 5;
    $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $debut = ($pageActuelle - 1) * $thematiquesParPage;
    $thematiquesPage = array_slice($thematiques, $debut, $thematiquesParPage);
    $totalPages = ceil(count($thematiques) / $thematiquesParPage);
    ?>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="text-white" style="background-color: #6c63ff;">
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Actions</th>
                    <th>Etat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($thematiques)): ?>
                    <?php foreach ($thematiquesPage as $thematique): ?>
                        <tr>
                            <td><?= htmlspecialchars($thematique['titre']) ?></td>
                            <td><?= htmlspecialchars($thematique['description']) ?></td>
                            <td>
                            <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-primary btn-sm edit-btn" 
                                            data-id="<?= $thematique['id_thematique'] ?>"
                                            data-titre="<?= htmlspecialchars($thematique['titre']) ?>"
                                            data-description="<?= htmlspecialchars($thematique['description']) ?>">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </button>
                                    
                                    <form action="deleteThematique.php" method="POST" 
                                           onsubmit="return confirm('Voulez-vous vraiment supprimer cette thématique ?')">
                                            <input type="hidden" name="id_thematique" 
                                               value="<?= $thematique['id_thematique'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                    </form>
                                </div>
                            </td>

                            <td>
                                    <div>
                                       <?php if ($thematique['signalee'] == 1): ?>
                                          <form action="actionSignalement.php" method="POST">
                                             <input type="hidden" name="id_thematique" value="<?= $thematique['id_thematique'] ?>">
                                                   <button type="submit" name="action" value="flooter" class="btn btn-warning btn-sm">Flooter</button>
                                                   <button type="submit" name="action" value="refuser" class="btn btn-secondary btn-sm">Refuser</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune thématique trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
     <!-- Pagination -->
     <?php if ($totalPages > 1): ?>
     <nav>
        <ul class="pagination justify-content-center">
            <!-- Flèche précédente -->
            <li class="page-item <?= ($pageActuelle <= 1) ? 'disabled' : '' ?>">
                <a class="page-link text-skyblue" href="?page=<?= $pageActuelle - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                </a>
            </li>

            <!-- Affichage page actuelle et nombre total de pages -->
            <li class="page-item disabled">
                <span class="page-link"><?= $pageActuelle ?> / <?= $totalPages ?></span>
            </li>

            <!-- Flèche suivante -->
            <li class="page-item <?= ($pageActuelle >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link text-skyblue" href="?page=<?= $pageActuelle + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&gt;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>
 </div>
<!-- fin de tableau -->
<!-- Modal d'édition -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-edit me-2"></i>Modifier Thématique</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="updateThematique.php">
                    <input type="hidden" name="id_thematique" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_titre" class="form-label fw-bold">Titre</label>
                        <input type="text" class="form-control" id="edit_titre" name="titre" >
                        <div id="titre_error_edit" class="form-text text-danger small"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4" ></textarea>
                        <div id="description_error_edit" class="form-text text-danger small"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- fin modele-->

<!-- Content End -->


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
</body>
<script>
// Fonction de validation du formulaire
/*function validerFormulaire(event) {
    event.preventDefault();

    // Récupérer les champs du formulaire
    const titre = document.getElementById('titre');
    const description = document.getElementById('description');

    // Réinitialiser les messages d'erreur et les classes invalides
    document.querySelectorAll('.form-text').forEach(el => el.textContent = '');
    document.querySelectorAll('input, select, textarea').forEach(input => input.classList.remove('is-invalid'));

    let valid = true;

    // Validation pour le champ 'titre' : minimum 3 caractères
    if (titre.value.trim().length < 3) {
        document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères.';
        titre.classList.add('is-invalid');
        valid = false;
    }

    // Validation pour le champ 'description' : minimum 10 caractères
    if (description.value.trim().length < 10) {
        document.getElementById('description_error').textContent = 'La description doit comporter au moins 10 caractères.';
        description.classList.add('is-invalid');
        valid = false;
    }

    // Si toutes les validations sont passées, soumettre le formulaire
    if (valid) {
        document.getElementById("travelOfferForm").submit();
    }
}

// Ajout de l'écouteur d'événements au formulaire lorsque le DOM est prêt
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("travelOfferForm");
    if (form) {
        form.addEventListener("submit", validerFormulaire);
    }
});*/
// Validation formulaire principal
document.getElementById("travelOfferForm")?.addEventListener("submit", function(e) {
    e.preventDefault();
    validateForm(this);
});

// Gestion de la modale d'édition
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const titre = btn.dataset.titre;
        const description = btn.dataset.description;
        
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_titre').value = titre;
        document.getElementById('edit_description').value = description;
        
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});

// Validation formulaire d'édition
document.getElementById('editForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    validateForm(this);
});

function validateForm(form) {
    const inputs = form.querySelectorAll('input, textarea');
    let isValid = true;

    // Reset des erreurs
    form.querySelectorAll('.form-text').forEach(el => el.textContent = '');
    inputs.forEach(input => input.classList.remove('is-invalid'));

    // Validation spécifique
    if (form.id === 'travelOfferForm' || form.id === 'editForm') {
        const titre = form.querySelector('[name="titre"]');
        const description = form.querySelector('[name="description"]');

        if (titre.value.trim().length < 3) {
            form.querySelector('#titre_error' + (form.id === 'editForm' ? '_edit' : '')).textContent = 'Minimum 3 caractères';
            titre.classList.add('is-invalid');
            isValid = false;
        }

        if (description.value.trim().length < 10) {
            form.querySelector('#description_error' + (form.id === 'editForm' ? '_edit' : '')).textContent = 'Minimum 10 caractères';
            description.classList.add('is-invalid');
            isValid = false;
        }
    }

    if (isValid) form.submit();
}
</script>


</html>