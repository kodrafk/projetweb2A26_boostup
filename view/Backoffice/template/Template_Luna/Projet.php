
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
                
                    <!-- Gestionne Ressources -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="ressource.html" class="dropdown-item">Ressource</a>
                            <a href="thematique.html" class="dropdown-item">Thématique</a>
                        </div>
                    </div>
                
                    <!-- Gestionne Evenements -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i>Evenements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="evenement.html" class="dropdown-item">Evenement</a>
                            <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                        </div>
                    </div>
                
                    <!-- Gestionne Communauté -->
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
         <!-- Formulaire CRUD Projet -->
<div class="glass-card card mb-4 animate__animated animate__fadeIn">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-project-diagram me-2"></i> Gestion Projet</h6>
    </div>
    <div class="card-body p-4" style="background-color: #F3F6F9;">
    <form action="../../../../controller/ajouterProjet.php" method="POST">
    <div class="form-section">
                <h5 class="section-title"><i class="fas fa-folder-open me-2"></i> Informations du Projet</h5>
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label for="nom_projet" class="form-label">Nom du Projet</label>
                        <input type="text" id="nom_projet" name="nom_projet" class="form-control" placeholder="Ex: Application Mobile" required>
                        <div id="nom_projet_error" class="form-text"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez le projet en détail..." required></textarea>
                        <div id="description_error" class="form-text"></div>
                    </div> 
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                        <a href="Projet.php">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Enregistrer
                        </button></a>
                    </div>
                </div>
                <!-- Action end -->
            </div>
        </form>
    </div>
</div>
<!-- Form End -->

<!-- Formulaire End -->


<!-- Table pour afficher les projets -->
<!-- Un exemple de tableau pour afficher les projets (commenté pour le moment) -->
 
<div class="container mt-5">
   <?php 
   include_once("../../afficherProjets.php")
   ?>
</div> 


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

</div> <!-- Fin de la container -->

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>