<?php
require_once('C:/xampp/htdocs/website1.0/config.php');

$type = $_GET['type'] ?? '';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM objectif WHERE type = ?");
    $stmt->execute([$type]);
    $objectifs = $stmt->fetchAll();
} catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Objectifs - BoostUp</title>
  <meta name="description" content="Liste des objectifs">
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
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
      body {
          background: #f7f9fc;
          font-family: 'Segoe UI', sans-serif;
      }
      .card {
          transition: 0.3s ease-in-out;
      }
      .card:hover {
          transform: scale(1.03);
          box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      }
  </style>
</head>
<body>
<!-- Header -->
<header id="header" class="header fixed-top py-2">
    <div class="container d-flex align-items-center justify-content-between">

      <!-- Logo -->
      <div class="me-auto">
        <a href="index.html" class="d-inline-block">
          <img src="assets/img/Logo2.png" alt="Site Logo" class="img-fluid" style="height: 35px; width: auto;">
        </a>
      </div>

      <!-- Navigation -->
      <nav id="navmenu" class="navmenu mx-auto">
        <ul class="d-flex align-items-center justify-content-center mb-0" style="font-size: 14px; gap: 20px;">
          <li><a href="projet.html">Projets</a></li>
          <li><a href="tache.html">Tâches</a></li>
          <li><a href="ressources.html">Ressources</a></li>
          <li><a href="evenements.html">Événements</a></li>
          <li><a href="objectif.php">Objectifs</a></li>
        </ul>
      </nav>
    </div>
</header>

<main class="main">
    <div class="container py-5">
        <h2 class="text-center mb-4">Objectifs: <?php echo ucfirst($type); ?></h2>

        <?php if (count($objectifs) > 0): ?>
            <div class="row">
                <?php foreach ($objectifs as $objectif): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($objectif['titre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($objectif['description']); ?></p>
                                <a href="#" class="btn btn-outline-primary">Voir les détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">Aucun objectif trouvé pour ce type.</div>
        <?php endif; ?>
    </div>
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
</html>
