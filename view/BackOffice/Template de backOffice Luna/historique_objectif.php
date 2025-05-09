<?php
// Database connection
include 'config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch all objectives
$stmt = $pdo->query("SELECT * FROM objectif");
$objectifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch attachments for each objective
$attachmentsByObjectif = [];
foreach ($objectifs as $objectif) {
    $objectif_id = $objectif['id'];
    // Fetch tasks associated with this objective
    $stmt = $pdo->prepare("SELECT id, nom FROM tache WHERE id_objectif = ?");
    $stmt->execute([$objectif_id]);
    $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $attachments = [];
    foreach ($taches as $tache) {
        // Fetch attachments for each task
        $stmt = $pdo->prepare("SELECT id, piece_jointe, date_creation FROM travaux WHERE tache_id = ?");
        $stmt->execute([$tache['id']]);
        $tache_attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tache_attachments as $attachment) {
            $attachment['tache_nom'] = $tache['nom'];
            $attachments[] = $attachment;
        }
    }
    $attachmentsByObjectif[$objectif_id] = $attachments;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Historique des Pièces Jointes - Boostup</title>
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a> 
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a> 
                            <a href="projet.html" class="dropdown-item">Projet</a> 
                        </div>
                    </div>
                
                    <!-- Gestionne Objectifs -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="tache.html" class="dropdown-item">Tache</a>
                            <a href="objectif.php" class="dropdown-item">Objectif</a>
                            <a href="historique_objectif.php" class="dropdown-item">Historique Pièces Jointes</a>
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

            <!-- Historique Pièces Jointes -->
            <div class="container-fluid pt-4 px-4">
                <div class="glass-card card mb-4 animate__animated animate__fadeIn">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-history me-2"></i> Historique des Pièces Jointes par Objectif</h6>
                    </div>
                    <div class="card-body p-4" style="background-color: #F3F6F9;">
                        <?php if (!empty($objectifs)): ?>
                            <?php foreach ($objectifs as $objectif): ?>
                                <div class="mb-4">
                                    <h5 class="text-primary"><?= htmlspecialchars($objectif['nom']) ?> (ID: <?= $objectif['id'] ?>)</h5>
                                    <?php $attachments = $attachmentsByObjectif[$objectif['id']]; ?>
                                    <?php if (!empty($attachments)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Tâche</th>
                                                        <th>Pièce Jointe</th>
                                                        <th>Date d'Ajout</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($attachments as $attachment): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($attachment['tache_nom']) ?></td>
                                                            <td><?= htmlspecialchars($attachment['piece_jointe']) ?></td>
                                                            <td><?= htmlspecialchars($attachment['date_creation']) ?></td>
                                                            <td>
                                                                <a href="/website1.0/uploads/<?= htmlspecialchars($attachment['piece_jointe']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye me-1"></i> Voir
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p>Aucune pièce jointe pour cet objectif.</p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun objectif trouvé.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Fin Historique -->

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
</body>
</html>