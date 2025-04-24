<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../FrontOffice/login.php');
    exit();
}

// Pour les pages admin seulement
if ($_SESSION['user']['type'] !== 'admin') {
    header('Location: ../../View/BackOffice/afterLogin/index.php'); // Chemin complet recommandé
    exit();
}


include '../../../Controller/UserC.php';
$cont = new UserC();
$users = $cont->afficherUsers(); // Récupérer tous les utilisateurs

// Récupérer les données de l'utilisateur à modifier si l'ID est passé en paramètre
$userToEdit = null;
if (isset($_GET['edit_id'])) {
    $userToEdit = $cont->getUserById($_GET['edit_id']);
}
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
                   <!-- Projets -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a> 
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a> 
                            <a href="projet.html" class="dropdown-item">Projet</a> 
                        </div>
                    </div>
                
                    <!-- Gstionne Objectifs -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="tache.html" class="dropdown-item">Tache</a>
                            <a href="objectif.html" class="dropdown-item">Objectif</a>
                        </div>
                    </div>
                
                    <!-- Gstionne ressources-->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="ressource.html" class="dropdown-item">Ressource</a>
                            <a href="thematique.html" class="dropdown-item">Thématique</a>
                        </div>
                    </div>
                
                    <!-- Gstionne Evennements -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i>Evenements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="evenement.html" class="dropdown-item">Evenement</a>
                            <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                        </div>
                    </div>
                
                    <!-- Communauté -->
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


            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Pojets</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Sale</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Revenue</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Revenue</p>
                                <h6 class="mb-0">$1234</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sale & Revenue End -->

        <!-- Formulaire CRUD -->
<div class="glass-card card mb-4 animate__animated animate__fadeIn" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-user me-2"></i> Gestion Utilisateur</h6>
    </div>
    <div class="card-body p-3" style="background-color: #F3F6F9;">
        <form id="userForm" method="post" action="ajouterUser.php">
            <input type="hidden" id="iduser" name="iduser">
            <div class="form-section">
                <h5 class="section-title"><i class="fas fa-folder-open me-2"></i>Informations de l'utilisateur</h5>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Ex: Jean" required>
                        <div id="firstName_error" class="form-text"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="lastName" class="form-label">Nom</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Ex: Dupont" required>
                        <div id="lastName_error" class="form-text"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" id="email" name="email" class="form-control" placeholder="exemple@domaine.com" required>
                        <div id="email_error" class="form-text"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
                        <div id="password_error" class="form-text"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="admin">Admin</option>
                            <option value="client">Entrepreneur</option>
                            <option value="formateur">Investor</option>
                        </select>
                        
                    </div>

                    <div class="col-md-6">
                        <label for="numtel" class="form-label">Numéro de téléphone</label>
                        <input type="text" id="numtel" name="numtel" class="form-control" placeholder="Ex: 21234567" required>
                        <div id="numtel_error" class="form-text"></div>
                    </div>

                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="container-fluid pt-4 px-4">

<div class="container mt-5">
    <h5 class="text-primary mb-3"><i class="bi bi-people-fill"></i> La liste des utilisateurs</h5>

    <?php if (count($users) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['firstName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['lastName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['numtel'] ?? '') ?></td>
                            <td>
                            <a href="modifierUser.php?id=<?= $user['iduser']; ?>" class="btn btn-info btn-sm me-2">✏️ Modifier </a>
                               
                                <form action="supprimerUser.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ressource ?')">
                                    <input type="hidden" name="iduser" value="<?= $user['iduser']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Supprimer</button>
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
</div>
</div>

</div>
    
</div>





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

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('userForm');
    if (form) {
        form.addEventListener('submit', validerInscription);
    }
});

</script>

</body>

</html>