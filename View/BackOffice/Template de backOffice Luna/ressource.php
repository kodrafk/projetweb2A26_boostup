<?php
include '../../../Controller/RessourceC.php';
$cont = new RessourceC();
$ressources = $cont->afficherRessource();

// Récupérer les données de la ressource à modifier si l'ID est passé en paramètre
$ressourceToEdit = null;
if (isset($_GET['edit_id'])) {
    $ressourceToEdit = $cont->getRessourceById($_GET['edit_id']);
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
    <link href="formulaire.css" rel="stylesheet">

    <style>
        .is-invalid {
    border-color: red;
     }

.form-text {
    color: red;
    font-size: 0.875rem;
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
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Boostup</h3>
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


            <!-- Chart Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                </div>
            </div>
            <!-- Chart End -->
            
            <!-- Formulaire CRUD -->
            <!--div class="glass-card card mb-4 animate__animated animate__fadeIn">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-project-diagram me-2"></i> Gestion Ressource</h6>
                </div>
                <div class="card-body p-4" style="background-color: #F3F6F9;">
                    <form id="ajouterRessourceForm" method="post" action="ajouterRessource.php">
                        <input type="hidden" id="id_ressource" name="id_ressource">
                        <div class="form-section">
                            <h5 class="section-title"><i class="fas fa-folder-open me-2"></i>Informations de Ressource</h5>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <select id="type" name="type" class="form-control" required>
                                    <option value="">Sélectionner un type</option>
                                        <option value="Cour">Cour</option>
                                        <option value="Vedio">Vedio</option>
                                        <option value="Article">Article</option>
                                        <option value="Evenement">Evenement</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="titre" class="form-label">Titre</label>
                                    <input type="text" id="titre" name="titre" class="form-control" placeholder="Ex: Développement Web" required>
                                    <div id="titre_error" class="form-text"></div>
                                </div>
            
                                <div class="col-md-6">
                                    <label for="lien" class="form-label">Lien</label>
                                    <input type="text" id="lien" name="lien" class="form-control" placeholder="Ex: https://...">
                                    <div id="lien_error" class="form-text"></div>
                                </div>
                    
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez ressource en détail..." required></textarea>
                                    <div id="description_error" class="form-text"></div>
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
            </div-->

            <div class="glass-card card mb-4 animate__animated animate__fadeIn" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-project-diagram me-2"></i> Gestion Ressource</h6>
    </div>
    <div class="card-body p-3" style="background-color: #F3F6F9;">
        <form id="travelOfferForm" method="post" action="ajouterRessource.php">
            <input type="hidden" id="id_ressource" name="id_ressource">
            <div class="form-section">
                <h5 class="section-title"><i class="fas fa-folder-open me-2"></i>Informations de Ressource</h5>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Cour">Cour</option>
                            <option value="Vedio">Vedio</option>
                            <option value="Article">Article</option>
                            <option value="Evenement">Evenement</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" id="titre" name="titre" class="form-control" placeholder="Ex: Développement Web" required>
                        <div id="titre_error" class="form-text"></div>
                    </div>
        
                    <div class="col-md-6">
                        <label for="lien" class="form-label">Lien</label>
                        <input type="text" id="lien" name="lien" class="form-control" placeholder="Ex: https://...">
                        <div id="lien_error" class="form-text"></div>
                    </div>
        
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez ressource en détail..." required></textarea>
                        <div id="description_error" class="form-text"></div>
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
            <!-- Form End -->

            <div class="container-fluid pt-4 px-4">
                <!--div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">📄 Informations de la Ressource</h6>
                    <form id="ajouterRessourceForm" method="post" action="ajouterRessource.php">
                        <input type="hidden" id="id_ressource" name="id_ressource">
                        <div class="form-section">
                            <h5 class="section-title"><i class="fas fa-folder-open me-2"></i>Informations de Ressource</h5>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type</label>
                                    <select id="type" name="type" class="form-control" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="Cour">Cour</option>
                                        <option value="Vedio">Vedio</option>
                                        <option value="Article">Article</option>
                                        <option value="Evenement">Evenement</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="titre" class="form-label">Titre</label>
                                    <input type="text" id="titre" name="titre" class="form-control" placeholder="Ex: Développement Web" required>
                                    <div id="titre_error" class="form-text"></div>
                                </div>
            
                                <div class="col-md-6">
                                    <label for="lien" class="form-label">Lien</label>
                                    <input type="text" id="lien" name="lien" class="form-control" placeholder="Ex: https://...">
                                    <div id="lien_error" class="form-text"></div>
                                </div>
                    
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez ressource en détail..." required></textarea>
                                    <div id="description_error" class="form-text"></div>
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
                </div-->

 
<!-- tableau d'affichage des ressources -->

<div class="container mt-5">
    <h5 class="text-primary mb-3"><i class="bi bi-journal-bookmark"></i> La liste des ressources</h5>

    <?php if (count($ressources) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Lien</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ressources as $ressource): ?>
                        <tr>
                            <td><?= htmlspecialchars($ressource['type']) ?></td>
                            <td><?= htmlspecialchars($ressource['titre']) ?></td>
                            <td><a href="<?= htmlspecialchars($ressource['lien']) ?>" target="_blank"><?= htmlspecialchars($ressource['lien']) ?></a></td>
                            <td><?= htmlspecialchars($ressource['description']) ?></td>
                            <td>
                                <!--form action="updateRessource.php" method="POST" style="display:inline-block;" class="me-2">
                                    <input type="hidden" name="id_ressource" value="<?= $ressource['id_ressource']; ?>">
                                    <button type="submit" class="btn btn-info btn-sm">✏️ Modifier</button>
                                </form-->

                                <a href="updateRessource.php?id=<?= $ressource['id_ressource']; ?>" class="btn btn-info btn-sm me-2">
                                    ✏️ Modifier
                                </a>

                                <form action="deleteRessource.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ressource ?')">
                                    <input type="hidden" name="id_ressource" value="<?= $ressource['id_ressource']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucune ressource trouvée.</div>
    <?php endif; ?>
</div>
<!-- fin de tableau -->




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

<script>
// Fonction de validation du formulaire
function validerFormulaire(event) {
    event.preventDefault();

    // Récupérer les champs du formulaire
    const titre = document.getElementById('titre');
    const lien = document.getElementById('lien');
    const description = document.getElementById('description');

    // Réinitialiser les messages d'erreur et les classes invalides
    document.querySelectorAll('.form-text').forEach(el => el.textContent = '');
    document.querySelectorAll('input, select, textarea').forEach(input => input.classList.remove('is-invalid'));

    let valid = true;

    // Validation pour le champ 'titre' : Doit contenir seulement des lettres, chiffres et espaces
    // Validation pour le champ 'titre'
    if (titre.value.length < 3) {
        document.getElementById('titre_error').textContent = 'Le titre doit contenir au moins 3 caractères.';
        titre.classList.add('is-invalid');
        valid = false;
    }

    // Validation pour le champ 'lien' : Doit être un lien valide
    const lienRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/;
    if (lien.value && !lienRegex.test(lien.value)) {
        document.getElementById('lien_error').textContent = 'Veuillez entrer un lien valide.';
        lien.classList.add('is-invalid');
        valid = false;
    }

    // Validation pour le champ 'description' : Minimum 10 caractères
    if (description.value.length < 10) {
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
});
</script>

</body>

</html>