<!DOCTYPE html>
<html lang="fr">

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
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        /* Styles personnalisés */
        :root {
            --primary-color: #4e73df;
            --primary-dark: #224abe;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Navbar modernisée */
        .navbar-light {
            background: white !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            padding: 0.5rem 1rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-weight: 400;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white !important;
        }
        
        .sidebar-toggler {
            color: var(--dark-color);
            font-size: 1.25rem;
        }
        
        .search-box .form-control {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            border: 1px solid #e0e0e0;
            min-width: 300px;
        }
        
        .search-box .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .notification-badge, .message-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Styles pour le formulaire creative */
        .creative-form {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .creative-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .creative-body {
            padding: 2rem;
        }

        .section-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
            font-weight: 600;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-label label {
            position: absolute;
            top: -10px;
            left: 15px;
            padding: 0 5px;
            background: white;
            font-size: 0.85rem;
            color: var(--secondary-color);
            z-index: 1;
            font-weight: 500;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
        }

        .btn-secondary-creative {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-primary-creative {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-secondary-creative:hover, 
        .btn-primary-creative:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }
        
        .btn-primary-creative:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary-creative:hover {
            background: #5a6268;
        }

        .animate-float {
            animation: floatUp 0.5s ease-out forwards;
        }

        @keyframes floatUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Style pour les erreurs */
        .is-invalid {
            border-color: var(--danger-color) !important;
        }
        
        .is-valid {
            border-color: var(--success-color) !important;
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        /* Toast notifications */
        .toast-container {
            z-index: 1100;
        }
        
        .toast {
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
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
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
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
                    <!-- Gestionne Projets -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a> 
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a> 
                            <a href="projet.html" class="dropdown-item">Projet</a> 
                        </div>
                    </div>
                
                    <!-- Gestionne Objectifs -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="tache.html" class="dropdown-item">Tache</a>
                            <a href="objectif.html" class="dropdown-item">Objectif</a>
                        </div>
                    </div>
                
                    <!-- Gestion Ressources -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="ressource.html" class="dropdown-item">Ressource</a>
                            <a href="thematique.html" class="dropdown-item">Thématique</a>
                        </div>
                    </div>
                
                    <!-- Gestion Evenements -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i>Evenements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="evenement.html" class="dropdown-item">Evenement</a>
                            <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                        </div>
                    </div>
                
                    <!-- Gestion Communauté -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i>Communautes</a>
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
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-2">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="search-box d-none d-md-flex ms-4 position-relative">
                    <input class="form-control border-0 ps-5" type="search" placeholder="Rechercher...">
                    <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                </div>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown position-relative">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Messages</span>
                            <span class="message-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-lg shadow-sm m-0 p-0 overflow-hidden">
                            <div class="dropdown-header bg-primary text-white py-3">
                                <h6 class="m-0">Vous avez 3 nouveaux messages</h6>
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-3">
                                            <h6 class="fw-normal mb-0">Jhon vous a envoyé un message</h6>
                                            <small>Il y a 15 minutes</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-3">
                                            <h6 class="fw-normal mb-0">Sarah vous a envoyé un message</h6>
                                            <small>Il y a 25 minutes</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item py-3">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-3">
                                            <h6 class="fw-normal mb-0">L'équipe vous a envoyé un message</h6>
                                            <small>Il y a 1 heure</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="#" class="dropdown-item text-center py-3 bg-light">
                                <small>Voir tous les messages</small>
                            </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown position-relative">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notifications</span>
                            <span class="notification-badge">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-lg shadow-sm m-0 p-0 overflow-hidden">
                            <div class="dropdown-header bg-primary text-white py-3">
                                <h6 class="m-0">Vous avez 5 nouvelles notifications</h6>
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div>
                                        <h6 class="fw-normal mb-0">Profil mis à jour</h6>
                                        <small>Il y a 15 minutes</small>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div>
                                        <h6 class="fw-normal mb-0">Nouvel utilisateur ajouté</h6>
                                        <small>Il y a 25 minutes</small>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div>
                                        <h6 class="fw-normal mb-0">Mot de passe changé</h6>
                                        <small>Il y a 1 heure</small>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item border-bottom py-3">
                                    <div>
                                        <h6 class="fw-normal mb-0">Nouveau projet créé</h6>
                                        <small>Il y a 2 heures</small>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item py-3">
                                    <div>
                                        <h6 class="fw-normal mb-0">Tâche complétée</h6>
                                        <small>Il y a 3 heures</small>
                                    </div>
                                </a>
                            </div>
                            <a href="#" class="dropdown-item text-center py-3 bg-light">
                                <small>Voir toutes les notifications</small>
                            </a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="d-none d-lg-inline-flex flex-column align-items-start">
                                <span class="fw-bold">John Doe</span>
                                <small class="text-muted">Administrateur</small>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-lg shadow-sm m-0">
                            <a href="#" class="dropdown-item"><i class="fas fa-user me-2"></i> Mon Profil</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-cog me-2"></i> Paramètres</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a>
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

            <?php if (isset($_GET['modification']) && $_GET['modification'] === 'success'): ?>
                <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
                    <div id="modificationToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i> Catégorie modifiée avec succès !
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var toast = new bootstrap.Toast(document.getElementById('modificationToast'));
                        toast.show();
                    });
                </script>
            <?php endif; ?>

            <!-- Formulaire CRUD Catégorie - Version Modernisée -->
            <div class="container-fluid pt-4 px-4">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-8">
                        <div class="creative-form animate__animated animate__fadeInUp">
                            <div class="creative-header">
                                <h6 class="m-0 text-white"><i class="fas fa-tags me-2"></i> Nouvelle Catégorie</h6>
                            </div>
                            <div class="creative-body">
                                <form action="../../../../controller/ajouterCategorie.php" method="POST" id="categorieForm">
                                    <div class="form-section animate-float" style="animation-delay: 0.1s;">
                                        <h5 class="section-title"><i class="fas fa-folder-open me-2"></i> Informations de la Catégorie</h5>
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div class="floating-label">
                                                    <label for="nom_categorie">Nom de la Catégorie</label>
                                                    <input type="text" id="nom_categorie" name="nom_categorie" class="form-control" placeholder="Ex: Technologies" >
                                                    <div id="nom_categorie_error" class="error-message"></div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="floating-label">
                                                    <label for="description">Description</label>
                                                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Décrivez la catégorie en détail..."></textarea>
                                                    <div id="description_error" class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="reset" class="btn btn-secondary-creative">
                                            <i class="fas fa-undo me-1"></i> Réinitialiser
                                        </button>
                                        <button type="submit" class="btn btn-primary-creative">
                                            <i class="fas fa-save me-1"></i> Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table pour afficher les catégories -->
            <div class="container mt-5">
                <?php include_once("../../afficherCategorie.php"); ?>
            </div> 

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        </div>
        <!-- Content End -->
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
    
    <script>
        // Références aux champs et formulaire
        const form = document.getElementById('categorieForm');
        const inputs = {
            nom_categorie: document.getElementById('nom_categorie'),
            description: document.getElementById('description')
        };

        // Fonction pour effacer les erreurs
        function clearErrors() {
            Object.keys(inputs).forEach(key => {
                inputs[key].classList.remove('is-invalid', 'is-valid');
                const errorElement = document.getElementById(key + '_error');
                if (errorElement) errorElement.textContent = '';
            });
        }

        // Fonction de validation du champ nom
        function validateNomCategorie() {
            const value = inputs.nom_categorie.value.trim();
            if (value.length < 3) {
                inputs.nom_categorie.classList.add('is-invalid');
                document.getElementById('nom_categorie_error').textContent = 'Le nom de la catégorie doit contenir au moins 3 caractères.';
                return false;
            } else {
                inputs.nom_categorie.classList.remove('is-invalid');
                inputs.nom_categorie.classList.add('is-valid');
                document.getElementById('nom_categorie_error').textContent = '';
                return true;
            }
        }

        // Fonction de validation du champ description
        function validateDescription() {
            const value = inputs.description.value.trim();
            if (value.length < 6) {
                inputs.description.classList.add('is-invalid');
                document.getElementById('description_error').textContent = 'La description doit contenir au moins 6 caractères.';
                return false;
            } else {
                inputs.description.classList.remove('is-invalid');
                inputs.description.classList.add('is-valid');
                document.getElementById('description_error').textContent = '';
                return true;
            }
        }

        // Événements de validation en temps réel
        inputs.nom_categorie.addEventListener('input', validateNomCategorie);
        inputs.description.addEventListener('input', validateDescription);

        // Validation à la soumission
        form.addEventListener('submit', function(event) {
            clearErrors();
            const isNomValid = validateNomCategorie();
            const isDescValid = validateDescription();

            if (!isNomValid || !isDescValid) {
                event.preventDefault();
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
</body>
</html>