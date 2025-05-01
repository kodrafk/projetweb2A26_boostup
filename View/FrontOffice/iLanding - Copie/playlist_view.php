<?php
require_once('C:/xampp/htdocs/Ressources/config.php');
$conn = Config::getConnexion();

$id_thematique = $_GET['id_thematique'] ?? 0;
$type = $_GET['type'] ?? '';

try {
    // Récupérer les données combinées
    $stmt = $conn->prepare("
        SELECT r.*, t.titre AS thematique_titre 
        FROM ressources r
        INNER JOIN thematique t ON r.id_thematique = t.id_thematique
        WHERE r.id_thematique = ? AND r.type = ?
    ");
    $stmt->execute([$id_thematique, $type]);
    $ressources = $stmt->fetchAll();

} catch (Exception $e) {
    die('Erreur: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>
<head>
    
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - BoostUp</title>
  <meta name="description" content="My great site">
  <meta name="keywords" content="">

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

    <title>Playlist - <?php echo htmlspecialchars($type); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<style>
    .text-gradient {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: bold;
        font-size: 2.5rem;
    }

    .custom-card {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .custom-btn {
        background: linear-gradient(to right, #43e97b, #38f9d7);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 25px;
        padding: 10px 20px;
        transition: background 0.3s ease;
    }

    .custom-btn:hover {
        background: linear-gradient(to right, #38f9d7, #43e97b);
        color: #000;
    }
</style>
</head>
<body>
<!-- Header -->
<header id="header" class="header fixed-top py-2">
    <div class="container d-flex align-items-center justify-content-between">

      <!-- Logo more to the left -->
      <div class="me-auto">
        <a href="index.html" class="d-inline-block">
          <img src="assets/img/Logo2.png" alt="Site Logo" class="img-fluid" style="height: 35px; width: auto;">
        </a>
      </div>

      <!-- Centered navigation -->
      <nav id="navmenu" class="navmenu mx-auto">
        <ul class="d-flex align-items-center justify-content-center mb-0" style="font-size: 14px; gap: 20px;">
          <li class="dropdown"><a href="#"><span>Projets</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="projet.html">Projet</a></li>
              <li><a href="categorie.html">Catégorie Projet</a></li>
            </ul>
          </li>

          <li class="dropdown"><a href="#"><span>Taches</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#cleaning">Tache</a></li>
              <li><a href="#cleaning">Objectif</a></li>
            </ul>
          </li>

          <li><a href="Ressources.html">Ressources</a></li>

          <li class="dropdown"><a href="#"><span>Evénements</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#cleaning">Evénement</a></li>
              <li><a href="#cleaning">Opportunité</a></li>
            </ul>
          </li>

          <li class="dropdown"><a href="#"><span>Communauté</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#cleaning">Question</a></li>
              <li><a href="#cleaning">Réponse</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <!-- Notification, Message, and Sign out -->
      <div class="d-flex align-items-center gap-3">

        <!-- Bell icon -->
        <a href="#" class="text-dark position-relative">
          <i class="bi bi-bell" style="font-size: 18px;"></i>
        </a>

        <!-- Envelope icon -->
        <a href="#" class="text-dark position-relative">
          <i class="bi bi-envelope" style="font-size: 18px;"></i>
        </a>

        <!-- Sign out button -->
        <div style="background-color: #0d6efd; height: 35px; padding: 0 10px;" class="d-flex align-items-center justify-content-center rounded">
          <a class="text-white fw-bold text-decoration-none" href="index.html#about" style="background: none; border: none; font-size: 14px;">Sign out</a>
        </div>

      </div>
    </div>
  </header> 

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-gear-fill me-2"></i>
                Working for your success
              </div>

              <h1 class="mb-4">
               
                <span class="accent-text">  Welcome to BoostUp </span> <br>
              </h1>

              <p class="mb-4 mb-md-5">
                where learning is easy and your knowledge skyrockets!
              </p>

              
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="assets/img/illustration-1.webp" alt="Hero Image" class="img-fluid">
            </div>
          </div>
        </div>

<div class="container py-5">
  <h2 class="text-center mb-5 text-gradient"><?php echo ucfirst($type); ?> Playlist</h2>

    <?php if (count($ressources) > 0): ?>
        <div class="row">
            <?php foreach ($ressources as $res): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 custom-card">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($res['titre']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($res['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($res['lien']); ?>" target="_blank" class="btn custom-btn mt-auto">🎧 Voir la ressource</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">Aucune ressource trouvée pour ce type.</div>
    <?php endif; ?>
</div>
</main>

<footer id="footer" class="footer">
  
      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <!-- Replace text with logo image -->
              <img src="assets/img/Logo2.png" alt="Logo" style="height: 35px; width: auto;">
            </a>
        
  
              <div class="footer-contact pt-3">
              <p>A108 Freedoom Street</p>
              <p>Seliana, Tunisia</p>
              <p class="mt-4"><strong>Phone:</strong> <span>+216 77 908 908</span></p>
              <p><strong>Email:</strong> <span>boostup@gmail.com</span></p>
            </div>
            <div class="social-links d-flex mt-4">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
  
          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>
  
          
  
          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Our Projets</h4>
            <ul>
              <li><a href="#">Web Design</a></li>
              <li><a href="#">Web Development</a></li>
              <li><a href="#">Product Management</a></li>
              <li><a href="#">Marketing</a></li>
              <li><a href="#">App Development</a></li>
            </ul>
          </div>
  
          
  
        </div>
      </div>
  
      <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">BoostUp</strong> <span>All Rights Reserved</span></p>
      </div>
  
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>
