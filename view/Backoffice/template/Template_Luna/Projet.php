<?php
include_once("../../../../config/database.php");
include_once("../../../../model/categorie.php");
$categories = Categorie::afficherCategories(); // méthode qui récupère toutes les catégories
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<!-- Bootstrap Bundle avec Popper -->

<style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #4d44db;
            --accent-color: #00c896;
            --light-bg: #f8f9ff;
            --dark-text: #2a2a72;
        }
        
        .creative-form {
            background: linear-gradient(135deg, #f8f9ff 0%, #eef2ff 100%);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(108, 99, 255, 0.1);
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
        }
        
        .creative-form:hover {
            box-shadow: 0 20px 40px rgba(92, 85, 222, 0.15);
        }
        
        .creative-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 20px 20px 0 0 !important;
        }
        
        .creative-header h6 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
            font-size: 1.25rem;
        }
        
        .creative-body {
            padding: 2rem;
            background-color: white;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: var(--light-bg);
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
        }
        
        .section-title {
            color: var(--dark-text);
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0ff;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            background-color: #fafbff;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
            background-color: white;
        }
        
        .btn-creative {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-creative i {
            margin-right: 8px;
        }
        
        .btn-primary-creative {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .btn-primary-creative:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-secondary-creative {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .btn-secondary-creative:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(108, 117, 125, 0.3);
        }
        
        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .floating-label label {
            position: absolute;
            top: -10px;
            left: 15px;
            background: white;
            padding: 0 5px;
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Animation */
        @keyframes floatIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        .animate-float {
            animation: floatIn 0.6s ease-out forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .creative-body {
                padding: 1.5rem;
            }
            
            .form-section {
                padding: 1rem;
            }
        }
    </style>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
<div class="sidebar p-4" style="background-color: #fff; border-right: 1px solid #eee; min-height: 100vh; border-top-right-radius: 20px; border-bottom-right-radius: 20px;">
    <nav class="navbar navbar-light">
        <!-- Logo + Nom -->
        <a href="index.html" class="navbar-brand d-flex align-items-center mb-5">
            <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; background-color: #6c63ff;">
                <i class="fa fa-hashtag text-white"></i>
            </div>
            <span class="fs-4 fw-bold ms-3" style="color: #6c63ff;">DASHMIN</span>
        </a>

        <!-- Profil -->
       

        <!-- Menu -->
        <div class="navbar-nav w-100">
            <a href="index.html" class="nav-item nav-link sidebar-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>

            <!-- Projets -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle sidebar-link" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a>
                <div class="dropdown-menu">
                    <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a>
                    <a href="projet.html" class="dropdown-item">Projet</a>
                </div>
            </div>

            <!-- Objectifs -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle sidebar-link" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                <div class="dropdown-menu">
                    <a href="tache.html" class="dropdown-item">Tâche</a>
                    <a href="objectif.html" class="dropdown-item">Objectif</a>
                </div>
            </div>

            <!-- Ressources -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle sidebar-link" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                <div class="dropdown-menu">
                    <a href="ressource.html" class="dropdown-item">Ressource</a>
                    <a href="thematique.html" class="dropdown-item">Thématique</a>
                </div>
            </div>

            <!-- Evenements -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle sidebar-link" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i>Événements</a>
                <div class="dropdown-menu">
                    <a href="evenement.html" class="dropdown-item">Événement</a>
                    <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                </div>
            </div>

            <!-- Communautés -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle sidebar-link" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i>Communautés</a>
                <div class="dropdown-menu">
                    <a href="questionne.html" class="dropdown-item">Questionnaire</a>
                    <a href="reponse.html" class="dropdown-item">Réponse</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->

<!-- Custom Style -->
<style>
    .sidebar-link {
        color: #333;
        padding: 12px 18px;
        margin-bottom: 10px;
        border-radius: 16px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .sidebar-link:hover {
        background-color: #f0f0ff;
        color: #6c63ff;
    }

    .dropdown-menu {
        border: none;
        border-radius: 12px;
        padding: 10px 0;
        background-color: #fff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .dropdown-item {
        padding: 10px 20px;
        border-radius: 10px;
        transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f0f0ff;
        color: #6c63ff;
    }
</style>

<!-- Sidebar End -->

<style>
    .hover-bg:hover {
        background-color: #f1f1f1 !important;
        transition: 0.3s;
    }
</style>



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
                            <span class="d-none d-lg-inline-flex">Notification</span>
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
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?> 
                <!-- Notification Toast Centrée en haut pour ajout -->
                <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
                    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i> Catégorie ajoutée avec succès !
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var toast = new bootstrap.Toast(document.getElementById('successToast'));
                        toast.show();
                    });
                </script>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <!-- ✅ Toast de succès centré en haut -->
    <style>
        .toast-container {
            z-index: 1055; /* Assure que la toast est au-dessus des autres éléments */
        }
    </style>

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> Projet ajouté avec succès !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
        </div>
    </div>

    <!-- ✅ Script d'affichage du toast -->
    <script>
        window.addEventListener("DOMContentLoaded", function () {
            var toastEl = document.getElementById('successToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 3000 // Durée d'affichage en ms
                });
                toast.show();
            }
        });
    </script>
<?php endif; ?>

            <!-- Chart Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                </div>
            </div>
            <!-- Chart End -->
  <!-- Formulaire CRUD Projet - Version Creative -->
  <div class="container-fluid pt-4 px-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="creative-form animate__animated animate__fadeInUp">
                            <div class="creative-header">
                                <h6 class="m-0"><i class="fas fa-project-diagram me-2"></i> Nouveau Projet</h6>
                            </div>
                            <div class="creative-body">
                                <form action="../../../../controller/ajouterProjet.php" method="POST">
                                    <div class="form-section animate-float" style="animation-delay: 0.1s;">
                                        <h5 class="section-title"><i class="fas fa-folder-open me-2"></i> Informations du Projet</h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="floating-label">
                                                    <label for="nom_projet">Nom du Projet</label>
                                                    <input type="text" id="nom_projet" name="nom_projet" class="form-control" placeholder="Ex: Application Mobile" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="floating-label">
                                                    <label for="id_categorie">Catégorie</label>
                                                    <select name="id_categorie" id="id_categorie" class="form-select" required>
                                                        <option value="">-- Sélectionner une catégorie --</option>
                                                        <?php foreach ($categories as $cat): ?>
                                                            <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="floating-label">
                                                    <label for="date_debut">Date de début</label>
                                                    <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="floating-label">
                                                    <label for="date_fin">Date de fin</label>
                                                    <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="floating-label">
                                                    <label for="description">Description</label>
                                                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez le projet en détail..." required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="reset" class="btn btn-secondary-creative">
                                            <i class="fas fa-undo me-1"></i> Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-primary-creative">
                                            <i class="fas fa-save me-1"></i> Enregistrer le projet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des projets -->
            <div class="container mt-5">
                <?php include_once("../../afficherProjets.php") ?>
            </div>
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/script.js"></script>

    <script>
        // Animation au chargement
        $(document).ready(function() {
            $('.animate-float').each(function(i) {
                $(this).css('animation-delay', (i * 0.1) + 's');
            });
        });
    </script>
</body>
</html>