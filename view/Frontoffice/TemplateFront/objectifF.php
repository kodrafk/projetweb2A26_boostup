<?php
// Inclure le fichier de connexion
require_once __DIR__ . '/../../../config/db.php';

// Récupérer les paramètres de recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Récupérer tous les objectifs pour les statistiques
$queryStats = $pdo->query("SELECT * FROM objectif");
$allObjectifs = $queryStats->fetchAll(PDO::FETCH_ASSOC);

// Calcul des statistiques
$totalObjectifs = count($allObjectifs);
$freelanceCount = count(array_filter($allObjectifs, function ($objectif) {
    return isset($objectif['status']) && !empty($objectif['status']) && $objectif['status'] === 'freelance';
}));
$stageCount = count(array_filter($allObjectifs, function ($objectif) {
    return isset($objectif['status']) && !empty($objectif['status']) && $objectif['status'] === 'stage';
}));
$projetCollaboratifCount = count(array_filter($allObjectifs, function ($objectif) {
    return isset($objectif['status']) && !empty($objectif['status']) && $objectif['status'] === 'projet collaboratif';
}));

// Calculer les objectifs proches de l'échéance
$objectifsProches = array_filter($allObjectifs, function ($objectif) {
    if (!isset($objectif['date_limite']) || empty($objectif['date_limite'])) {
        return false; // Ignorer les objectifs sans date limite
    }
    $dateLimite = strtotime($objectif['date_limite']);
    $aujourdhui = strtotime(date('Y-m-d'));
    return $dateLimite >= $aujourdhui && $dateLimite <= strtotime('+7 days', $aujourdhui);
});

// Récupérer les objectifs pour l'affichage (avec recherche et tri)
$query = $pdo->query("SELECT * FROM objectif");
$objectifs = $query->fetchAll(PDO::FETCH_ASSOC);

// Filtrer les objectifs par nom si une recherche est effectuée
if ($action === 'search' && !empty($search)) {
    $objectifs = array_filter($objectifs, function ($objectif) use ($search) {
        return stripos($objectif['nom'], $search) !== false;
    });
}

// Trier les objectifs selon le critère sélectionné
if ($action === 'sort') {
    if (isset($_GET['sort']) && $_GET['sort'] === 'name') {
        usort($objectifs, function ($a, $b) {
            return strcmp($a['nom'], $b['nom']);
        });
    } elseif (isset($_GET['sort']) && $_GET['sort'] === 'date') {
        usort($objectifs, function ($a, $b) {
            return strtotime($a['date_limite']) - strtotime($b['date_limite']);
        });
    } elseif (isset($_GET['sort']) && $_GET['sort'] === 'status') {
        usort($objectifs, function ($a, $b) {
            return strcmp($a['status'], $b['status']);
        });
    }
}

// Récupérer les objectifs terminés pour l'historique
$queryHistorique = $pdo->query("SELECT * FROM objectif WHERE status = 'terminé'");
$historiqueObjectifs = $queryHistorique->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Objectifs - BoostUp</title>
  <meta name="description" content="Gestion des objectifs">
  <meta name="keywords" content="objectifs, gestion, BoostUp">

  <!-- Favicons -->
  <link href="assets/img/bot.jpg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <!-- Header -->
  <header id="header" class="header fixed-top py-2" style="background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
    <div class="container d-flex align-items-center justify-content-between">
      <div class="me-auto" style="padding: 5px; border-radius: 5px;">
        <a href="index.php" class="d-inline-block">
          <img src="assets/img/Logo2.png" alt="Site Logo" class="img-fluid" style="height: 35px; width: auto;">
        </a>
      </div>
      <nav id="navmenu" class="navmenu mx-auto" style="padding: 10px; border-radius: 5px;">
        <ul class="d-flex align-items-center justify-content-center mb-0" style="font-size: 14px; gap: 20px;">
          <li><a href="#objectifs">Liste des Objectifs</a></li>
          <li><a href="#statistiques">Statistiques</a></li>
          <li><a href="#proches-echeance">Proches de l'Échéance</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="profile.php">Mon Profil</a></li>
          <li><a href="historique.php">Historique</a></li>
          <li><a href="logout.php">Déconnexion</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <h1 class="mb-4">
                <span class="accent-text">Bienvenue sur BoostUp</span><br>
                Gérez vos Objectifs efficacement !
              </h1>
              <p class="mb-4 mb-md-5">
                Planifiez, suivez et atteignez vos objectifs avec notre plateforme intuitive.
              </p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
             
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Objectifs Section -->
    <section id="objectifs" class="objectifs section">
      <div class="container" data-aos="fade-up">
        <h2 class="text-center mb-4">Liste des Objectifs</h2>

        <!-- Recherche et tri -->
        <div class="mb-4">
          <form method="GET" action="#resultats" class="d-flex justify-content-between align-items-center">
            <!-- Recherche par nom -->
            <div class="d-flex gap-2">
              <input type="text" name="search" class="form-control" placeholder="Rechercher un objectif..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
              <button type="submit" class="btn btn-primary" name="action" value="search">Rechercher</button>
            </div>

            <!-- Tri des résultats -->
            <div class="d-flex gap-2">
              <select name="sort" class="form-select">
                <option value="name">Nom</option>
                <option value="date">Date limite</option>
                <option value="status">Statut</option>
              </select>
              <button type="submit" class="btn btn-secondary" name="action" value="sort">Trier</button>
            </div>
          </form>
        </div>


        <!-- Liste des objectifs -->
        <div id="resultats" class="row gy-4">
          <?php if (!empty($objectifs)): ?>
            <?php foreach ($objectifs as $objectif): ?>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($objectif['nom']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($objectif['description']) ?></p>
                    <p class="card-text"><small class="text-muted">Date limite : <?= htmlspecialchars($objectif['date_limite']) ?></small></p>
                    <p class="card-text"><small class="text-muted">Statut : 
                        <?= isset($objectif['status']) && !empty($objectif['status']) ? htmlspecialchars($objectif['status']) : 'Non défini' ?>
                    </small></p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center">
              <p>Aucun objectif trouvé.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Dans la section Statistiques de votre objectif.php, remplacez le code actuel par ceci: -->

<!-- Statistiques -->
<section id="statistiques" class="statistiques section">
  <div class="container" data-aos="fade-up">
    <h2 class="text-center mb-4">Statistiques</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="row text-center">
          <div class="col-md-6">
            <h5>Total Objectifs</h5>
            <p><?= $totalObjectifs ?></p>
          </div>
          <div class="col-md-6">
            <h5>Freelance</h5>
            <p><?= $freelanceCount ?></p>
          </div>
          <div class="col-md-6">
            <h5>Stage</h5>
            <p><?= $stageCount ?></p>
          </div>
          <div class="col-md-6">
            <h5>Projet Collaboratif</h5>
            <p><?= $projetCollaboratifCount ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="chart-container" style="position: relative; height:300px; width:100%">
          <canvas id="objectifsChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>

  </main>

  <footer id="footer" class="footer">
    <div class="container text-center">
      <p>© BoostUp - Tous droits réservés</p>
    </div>
  </footer>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>

<!-- Avant la fermeture de la balise body, ajoutez ce script (après avoir chargé les autres scripts JS) -->
<!-- Assurez-vous d'ajouter Chart.js avant ce script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('objectifsChart').getContext('2d');
  
  // Récupérer les données depuis PHP
  const freelanceCount = <?= $freelanceCount ?>;
  const stageCount = <?= $stageCount ?>;
  const projetCollaboratifCount = <?= $projetCollaboratifCount ?>;
  
  // Créer le graphique
  const objectifsChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Freelance', 'Stage', 'Projet Collaboratif'],
      datasets: [{
        data: [freelanceCount, stageCount, projetCollaboratifCount],
        backgroundColor: [
          'rgba(255, 99, 132, 0.7)',
          'rgba(54, 162, 235, 0.7)',
          'rgba(255, 206, 86, 0.7)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Répartition des Objectifs par Statut',
          font: {
            size: 16
          }
        }
      }
    }
  });
});
</script>
</html>