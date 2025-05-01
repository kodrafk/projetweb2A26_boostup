<?php
// Connexion à la base de données
include 'config.php';

// Récupérer toutes les tâches
$stmt = $pdo->prepare("SELECT * FROM tache");
$stmt->execute();
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire (ajout ou modification)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $erreurs = [];

    $nom = trim($_POST['nom']);
    $status = $_POST['status'];
    $date_echeance = $_POST['date_limite'];
    $description = trim($_POST['description']);

    if (empty($nom)) $erreurs[] = "Le nom est requis.";
    if (empty($status)) $erreurs[] = "Le status est requis.";
    if (empty($date_echeance)) $erreurs[] = "La date limite est requise.";
    if (empty($description)) $erreurs[] = "La description est requise.";

    // Date limite dans le passé
    if (!empty($date_echeance) && $date_echeance < date('Y-m-d')) {
        $erreurs[] = "La date limite ne peut pas être dans le passé.";
    }

    if (empty($erreurs)) {
        if (isset($_POST['id_tache']) && !empty($_POST['id_tache'])) {
            // Mise à jour
            $id_tache = $_POST['id_tache'];
            $sql = "UPDATE tache SET nom = ?, description = ?, status = ?, date_echeance = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $status, $date_echeance, $id_tache]);
        } else {
            // Insertion
            $id_projet = 1;
            $sql = "INSERT INTO tache (nom, description, status, date_echeance, id_projet) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $status, $date_echeance, $id_projet]);
        }

        header("Location: tache.php");
        exit;
    }
}


// Récupération des données de tâche pour le formulaire (mode édition)
$editMode = false;
$tacheData = ['id' => '', 'nom' => '', 'status' => '', 'date_echeance' => '', 'description' => ''];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tache WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $tacheData = $stmt->fetch(PDO::FETCH_ASSOC);
    $editMode = true;
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

            <?php if (!empty($erreurs)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($erreurs as $erreur): ?>
                            <li><?= htmlspecialchars($erreur) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Formulaire de modification ou d'ajout -->
                <div id="formulaire" class="glass-card card mb-4 animate__animated animate__fadeIn">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-project-diagram me-2"></i> 
                        <?= isset($tacheData['id']) && $tacheData['id'] ? 'Modifier' : 'Ajouter' ?> la Tâche</h6>
                    </div>
                    <div   div class="card-body p-4" style="background-color: #F3F6F9;">
                    <form method="POST" action="tache.php">
                    <!-- ID caché pour modification -->
                    <input type="hidden" name="id_tache" value="<?= isset($tacheData['id']) ? htmlspecialchars($tacheData['id']) : '' ?>">

                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-folder-open me-2"></i> Informations de la Tâche</h5>
                        <div class="row g-3">

                            <!-- Champ Nom -->
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" 
                                value="<?= isset($tacheData['nom']) ? htmlspecialchars($tacheData['nom']) : '' ?>" 
                                placeholder="Nom de la tâche..." >
                            </div>

                            <!-- Champ Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-control" >
                                    <option value="">Sélectionner un type</option>
                                    <option value="en cour" <?= isset($tacheData['status']) && $tacheData['status'] == 'en cour' ? 'selected' : '' ?>>En cours</option>
                                    <option value="atteinte" <?= isset($tacheData['status']) && $tacheData['status'] == 'atteinte' ? 'selected' : '' ?>>Atteinte</option>
                                    <option value="formation" <?= isset($tacheData['status']) && $tacheData['status'] == 'formation' ? 'selected' : '' ?>>Formation</option>
                                </select>
                            </div>

                            <!-- Champ Date Limite -->
                            <div class="col-md-6">
                                <label for="date_limite" class="form-label">Date Limite</label>
                                <input type="date" id="date_limite" name="date_limite" class="form-control" 
                                value="<?= isset($tacheData['date_echeance']) ? htmlspecialchars($tacheData['date_echeance']) : '' ?>" >
                            </div>

                            <!-- Champ Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez la tâche en détail..." ><?= isset($tacheData['description']) ? htmlspecialchars($tacheData['description']) : '' ?></textarea>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="reset" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> <?= isset($tacheData['id']) && $tacheData['id'] ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>



                <!-- Tableau des tâches -->
        <div class="container mt-5">
            <h5 class="text-primary mb-3"><i class="bi bi-list"></i> Liste des Tâches</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Status</th>
                            <th>Date Limite</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($taches as $tache): ?>
                            <tr>
                                <td><?= htmlspecialchars($tache['nom']) ?></td>
                                <td><?= htmlspecialchars($tache['status']) ?></td>
                                <td><?= $tache['date_echeance'] ?></td>
                                <td><?= htmlspecialchars($tache['description']) ?></td>
                                <td>
                                    <a href="supprimer_tache.php?id=<?= $tache['id'] ?>" class="btn btn-supprimer btn-sm">🗑 Supprimer</a>
                                    <a href="tache.php?id=<?= $tache['id'] ?>#formulaire" class="btn btn-modifier btn-sm">✏️ Modifier</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
</body>

<!-- Controle de saisie -->
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const nom = document.getElementById("nom").value.trim();
    const status = document.getElementById("status").value;
    const dateLimite = document.getElementById("date_limite").value;
    const description = document.getElementById("description").value.trim();

    if (!nom || !status || !dateLimite || !description) {
        alert("Veuillez remplir tous les champs.");
        e.preventDefault(); // bloque l'envoi du formulaire
        return;
    }
    // Vérification que le champ Nom contient uniquement des lettres alphabétiques
    if (!/^[a-zA-Z]+$/.test(nom)) {
        alert("Le champ Nom doit contenir uniquement des lettres (sans espaces ni caractères spéciaux).");
        e.preventDefault(); // bloque l'envoi du formulaire
        return;
    }

   

    // Optionnel : on vérifie que la date limite n’est pas dans le passé
    const today = new Date().toISOString().split('T')[0];
    if (dateLimite < today) {
        alert("La date limite ne peut pas être dans le passé.");
        e.preventDefault();
        return;
    }
});
</script>

</html>