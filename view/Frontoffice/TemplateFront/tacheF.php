<?php
// Inclure le fichier de connexion
require_once __DIR__ . '/../../../config/db.php';

// Force refresh if requested
if (isset($_GET['refresh'])) {
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
}

// Récupérer les paramètres de recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Récupérer toutes les taches pour les statistiques
$queryStats = $pdo->query("SELECT * FROM tache");
$allTaches = $queryStats->fetchAll(PDO::FETCH_ASSOC);

// Calcul des statistiques
$totalTaches = count($allTaches);
$enCourCount = count(array_filter($allTaches, function ($tache) {
    return isset($tache['status']) && !empty($tache['status']) && $tache['status'] === 'en cour';
}));
$formationCount = count(array_filter($allTaches, function ($tache) {
    return isset($tache['status']) && !empty($tache['status']) && $tache['status'] === 'formation';
}));
$atteinteCount = count(array_filter($allTaches, function ($tache) {
    return isset($tache['status']) && !empty($tache['status']) && $tache['status'] === 'atteinte';
}));

// Calculer les taches proches de l'échéance
$tachesProches = array_filter($allTaches, function ($tache) {
    if (!isset($tache['date_echeance']) || empty($tache['date_echeance'])) {
        return false; // Ignorer les tâches sans date d'échéance
    }
    $dateEcheance = strtotime($tache['date_echeance']);
    $aujourdhui = strtotime(date('Y-m-d'));
    return $dateEcheance >= $aujourdhui && $dateEcheance <= strtotime('+7 days', $aujourdhui);
});

// Récupérer les taches pour l'affichage (avec recherche et tri)
$query = $pdo->query("SELECT * FROM tache");
$taches = $query->fetchAll(PDO::FETCH_ASSOC);

// Filtrer les taches par nom si une recherche est effectuée
if ($action === 'search' && !empty($search)) {
    $taches = array_filter($taches, function ($tache) use ($search) {
        return stripos($tache['nom'], $search) !== false;
    });
}

// Trier les taches selon le critère sélectionné
if ($action === 'sort') {
    if (isset($_GET['sort']) && $_GET['sort'] === 'name') {
        usort($taches, function ($a, $b) {
            return strcmp($a['nom'], $b['nom']);
        });
    } elseif (isset($_GET['sort']) && $_GET['sort'] === 'date') {
        usort($taches, function ($a, $b) {
            $dateA = isset($a['date_echeance']) ? strtotime($a['date_echeance']) : 0;
            $dateB = isset($b['date_echeance']) ? strtotime($b['date_echeance']) : 0;
            return $dateA - $dateB;
        });
    } elseif (isset($_GET['sort']) && $_GET['sort'] === 'status') {
        usort($taches, function ($a, $b) {
            return strcmp($a['status'], $b['status']);
        });
    }
}

// Récupérer les taches terminées pour l'historique
$queryHistorique = $pdo->query("SELECT * FROM tache WHERE status = 'atteinte'");
$historiqueTaches = $queryHistorique->fetchAll(PDO::FETCH_ASSOC);

// Fetch attachments for each task
$attachmentsByTask = [];
foreach ($taches as $tache) {
    $stmt = $pdo->prepare("SELECT id, piece_jointe FROM travaux WHERE tache_id = :tache_id");
    $stmt->execute([':tache_id' => $tache['id']]);
    $attachmentsByTask[$tache['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Taches - BoostUp</title>
  <meta name="description" content="Gestion des taches">
  <meta name="keywords" content="taches, gestion, BoostUp">

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
          <li><a href="#taches">Liste des Taches</a></li>
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
                Gérez vos Taches efficacement !
              </h1>
              <p class="mb-4 mb-md-5">
                Planifiez, suivez et atteignez vos taches avec notre plateforme intuitive.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Taches Section -->
    <section id="taches" class="taches section">
      <div class="container" data-aos="fade-up">
        <h2 class="text-center mb-4">Liste des Taches</h2>

        <!-- Recherche et tri -->
        <div class="mb-4">
          <form method="GET" action="#resultats" class="d-flex justify-content-between align-items-center">
            <!-- Recherche par nom -->
            <div class="d-flex gap-2">
              <input type="text" name="search" class="form-control" placeholder="Rechercher une tache..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
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

        <!-- Liste des taches -->
        <div id="resultats" class="row gy-4">
          <?php if (!empty($taches)): ?>
            <?php foreach ($taches as $tache): ?>
              <?php error_log("Task ID: {$tache['id']}, Validation Percentage from DB: " . (isset($tache['validation_percentage']) ? $tache['validation_percentage'] : 'NULL')); ?>
              <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($tache['nom']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($tache['description']) ?></p>
                    <p class="card-text"><small class="text-muted">Date limite : 
                        <?= isset($tache['date_echeance']) ? htmlspecialchars($tache['date_echeance']) : 'Non définie' ?>
                    </small></p>
                    <p class="card-text"><small class="text-muted">Statut : 
                        <?= isset($tache['status']) && !empty($tache['status']) ? htmlspecialchars($tache['status']) : 'Non défini' ?>
                    </small></p>
                    <div class="progress" style="height: 20px;">
                      <div class="progress-bar" role="progressbar" style="width: <?= isset($tache['validation_percentage']) ? (int)$tache['validation_percentage'] : 0 ?>%;" aria-valuenow="<?= isset($tache['validation_percentage']) ? (int)$tache['validation_percentage'] : 0 ?>" aria-valuemax="100">
                        <?= isset($tache['validation_percentage']) ? (int)$tache['validation_percentage'] : 0 ?>%
                      </div>
                    </div>
                    <!-- Display attachments -->
                    <div class="mt-3">
                      <h6>Pièces Jointes :</h6>
                      <?php if (!empty($attachmentsByTask[$tache['id']])): ?>
                        <ul>
                          <?php foreach ($attachmentsByTask[$tache['id']] as $attachment): ?>
                            <li>
                              <a href="../../../uploads/<?= htmlspecialchars($attachment['piece_jointe']) ?>" target="_blank"><?= htmlspecialchars($attachment['piece_jointe']) ?></a>
                              <a href="supprimer_piece_jointe.php?id=<?= $attachment['id'] ?>" class="text-danger ms-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?');">
                                <i class="bi bi-trash"></i> Supprimer
                              </a>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      <?php else: ?>
                        <p>Aucune pièce jointe.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center">
              <p>Aucune tache trouvée.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Statistiques -->
    <section id="statistiques" class="statistiques section">
      <div class="container" data-aos="fade-up">
        <h2 class="text-center mb-4">Statistiques</h2>
        <div class="row">
          <div class="col-md-6">
            <div class="row text-center">
              <div class="col-md-6">
                <h5>Total Taches</h5>
                <p><?= $totalTaches ?></p>
              </div>
              <div class="col-md-6">
                <h5>En Cours</h5>
                <p><?= $enCourCount ?></p>
              </div>
              <div class="col-md-6">
                <h5>Formation</h5>
                <p><?= $formationCount ?></p>
              </div>
              <div class="col-md-6">
                <h5>Atteinte</h5>
                <p><?= $atteinteCount ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="chart-container" style="position: relative; height:300px; width:100%">
              <canvas id="tachesChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Formulaire Ajouter un Travail -->
    <section id="ajouter-travail" class="ajouter-travail section">
      <div class="container" data-aos="fade-up">
        <h2 class="text-center mb-4">Ajouter un Travail</h2>
        <form action="ajouter_travail.php" method="POST" enctype="multipart/form-data">
          <!-- Sélection de la tâche -->
          <div class="mb-3">
            <label for="tache_id" class="form-label">Nom de la tâche</label>
            <select name="tache_id" id="tache_id" class="form-select" required>
              <option value="">-- Sélectionnez une tâche --</option>
              <?php
              $stmt = $pdo->query("SELECT id, nom FROM tache");
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nom']) . "</option>";
              }
              ?>
            </select>
          </div>

          <!-- Champ Commentaire -->
          <div class="mb-3">
            <label for="commentaire" class="form-label">Commentaire</label>
            <textarea name="commentaire" id="commentaire" class="form-control" rows="4" placeholder="Ajoutez un commentaire..."></textarea>
          </div>

          <!-- Champ Import de Pièce Jointe -->
          <div class="mb-3">
            <label for="piece_jointe" class="form-label">Pièce Jointe</label>
            <input type="file" name="piece_jointe" id="piece_jointe" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
          </div>

          <!-- Bouton Soumettre -->
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-upload me-1"></i> Ajouter
          </button>
        </form>
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
  <script src="js/script.js"></script>

  <!-- Chart.js for Pie Chart -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('tachesChart').getContext('2d');
      const enCourCount = <?= $enCourCount ?>;
      const formationCount = <?= $formationCount ?>;
      const atteinteCount = <?= $atteinteCount ?>;
      const tachesChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['En Cours', 'Formation', 'Atteinte'],
          datasets: [{
            data: [enCourCount, formationCount, atteinteCount],
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
            legend: { position: 'bottom' },
            title: {
              display: true,
              text: 'Répartition des Taches par Statut',
              font: { size: 16 }
            }
          }
        }
      });
    });
  </script>
</body>

</html>