<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once('C:/xampp/htdocs/Ressources/config.php'); // Si config.php est dans le répertoire parent

// Vérifier si un type est passé dans l'URL (par exemple, type=Cour, type=Vedio, etc.)
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Connexion à la base de données
$conn = Config::getConnexion();

// Si un type est spécifié, on récupère les ressources correspondantes
if ($type) {
    // Préparer la requête SQL pour récupérer les ressources du type spécifié
    $stmt = $conn->prepare("SELECT * FROM ressources WHERE type = :type");
    $stmt->bindParam(':type', $type);
    $stmt->execute();

    // Récupérer les résultats
    $resources = $stmt->fetchAll();

    // Affichage des ressources
    if ($resources) {
        echo "<div class='row'>"; // Début de la section des cartes

        // Parcourir les ressources et les afficher dans des cartes
        foreach ($resources as $resource) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card shadow-sm custom-card'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($resource['titre']) . "</h5>";
            echo "<p class='card-text'>" . htmlspecialchars($resource['description']) . "</p>";
            echo "<a href='" . htmlspecialchars($resource['lien']) . "' class='btn btn-primary' target='_blank'>Voir la ressource</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        echo "</div>"; // Fin de la section des cartes
    } else {
        echo "<p>Aucune ressource disponible pour ce type.</p>";
    }
} else {
    echo "<p>Aucun type de ressource spécifié.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

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

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fa;
    }

    .features-cards .feature-box {
      padding: 20px;
      border-radius: 15px;
      color: #fff;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .hover-zoom:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      z-index: 10;
    }

    .feature-box.orange { background: #ff7f50; }
    .feature-box.blue { background: #1e90ff; }
    .feature-box.green { background: #28a745; }
    .feature-box.red { background: #dc3545; }

    .hidden-section {
      display: none;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      margin-top: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .form-control, .btn {
      border-radius: 8px;
    }

    .btn-danger, .btn-warning {
      margin-right: 10px;
    }
  </style>

  <!-- Style personnalisé -->
<style>
    /* Style pour les cartes */
    .custom-card {
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
  
    .custom-card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }
  
    /* Animation au clic sur la carte */
    .custom-card:active {
      transform: translateY(3px);
    }
  
    /* Amélioration de la couleur du bouton */
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      transition: background-color 0.3s;
    }
  
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }
  
    /* Effet d'ombre et de zoom pour l'animation */
    .custom-card-body {
      padding: 20px;
      transition: transform 0.3s ease;
    }
  
    .custom-card-body:hover {
      transform: scale(1.05);
    }
  
    /* Design du bouton 'Afficher plus' */
    .btn-outline-primary {
      border-radius: 25px;
      padding: 12px 30px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
  
    .btn-outline-primary:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }
  </style>
</head>

<body class="index-page">

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

              <!--div class="customers-badge">
                <div class="customer-avatars">
                  <img src="assets/img/avatar-1.webp" alt="Customer 1" class="avatar">
                  <img src="assets/img/avatar-2.webp" alt="Customer 2" class="avatar">
                  <img src="assets/img/avatar-3.webp" alt="Customer 3" class="avatar">
                  <img src="assets/img/avatar-4.webp" alt="Customer 4" class="avatar">
                  <img src="assets/img/avatar-5.webp" alt="Customer 5" class="avatar">
                  <span class="avatar more">12+</span>
                </div>
                <p class="mb-0 mt-2">12,000+ of investors and entrepreneurs are using this site</p>
              </div-->
            </div>
          </div>
        </div>

    
    <!-- Features Cards Section -->
<section id="features-cards" class="features-cards section">
    <div class="container">
      <div class="row gy-4">
  
        <!-- Cours Card -->
<div class="col-xl-3 col-md-6">
    <div class="feature-box orange hover-zoom">
        <i class="bi bi-book"></i>
        <h4>Cours</h4>
        <p>Apprenez les compétences essentielles avec nos cours spécialisés.</p>
        <a href='playlist_view.php?type=cour' class='btn btn-primary'>View Playlist</a>
    </div>
</div>

<!-- Vidéo Card -->
<div class="col-xl-3 col-md-6">
    <div class="feature-box green hover-zoom">
        <i class="bi bi-film"></i>
        <h4>Vidéos</h4>
        <p>Découvrez nos vidéos éducatives sur divers sujets.</p>
        <a href="playlist_view.php?type=Vedio" class="btn btn-primary">View Playlist</a>
    </div>
</div>

<!-- Article Card -->
<div class="col-xl-3 col-md-6">
    <div class="feature-box blue hover-zoom">
        <i class="bi bi-file-earmark-text"></i>
        <h4>Articles</h4>
        <p>Lisez des articles sur des sujets divers et intéressants.</p>
        <a href="playlist_view.php?type=Article" class="btn btn-primary">View Playlist</a>
    </div>
</div>

<!-- Evenement Card -->
<div class="col-xl-3 col-md-6">
    <div class="feature-box red hover-zoom">
        <i class="bi bi-calendar"></i>
        <h4>Événements</h4>
        <p>Participez à nos événements et découvrez de nouvelles opportunités.</p>
        <a href="playlist_view.php?type=Evenement" class="btn btn-primary">View Playlist</a>
    </div>
</div>

  
      </div>
    </div>
  </section-->
  <!-- /Features Cards Section -->
  
<!-- Contact Section -->
 <!--section id="contact" class="contact section light-background">
    <div class="container">
      <div class="row" id="ressources-container">
        
        
        <div class="col-md-4 mb-4" id="cours">
          <div class="card shadow-sm custom-card">
            <div class="card-body">
              <h5 class="card-title">Introduction au HTML</h5>
              <p class="card-text">Une vidéo expliquant les bases du HTML pour les débutants.</p>
              <a href="https://example.com" class="btn btn-primary" target="_blank">Voir la ressource</a>
            </div>
          </div>
        </div>
    
        
        <div class="col-md-4 mb-4" id="cours">
          <div class="card shadow-sm custom-card">
            <div class="card-body">
              <h5 class="card-title">Cours sur le JavaScript</h5>
              <p class="card-text">Un cours complet pour apprendre le JavaScript depuis zéro.</p>
              <a href="https://example.com" class="btn btn-primary" target="_blank">Voir la ressource</a>
            </div>
          </div>
        </div>
    
        
        <div class="col-md-4 mb-4" id="videos">
          <div class="card shadow-sm custom-card">
            <div class="card-body">
              <h5 class="card-title">Webinaire sur le Marketing</h5>
              <p class="card-text">Enregistrement d'un webinaire sur les stratégies marketing pour startups.</p>
              <a href="https://example.com" class="btn btn-primary" target="_blank">Voir le webinaire</a>
            </div>
          </div>
        </div>
    
        
        <div class="col-md-4 mb-4" id="articles">
          <div class="card shadow-sm custom-card">
            <div class="card-body">
              <h5 class="card-title">Comment lancer une startup</h5>
              <p class="card-text">Des conseils pratiques pour bien démarrer une startup.</p>
              <a href="https://example.com" class="btn btn-primary" target="_blank">Lire l'article</a>
            </div>
          </div>
        </div>
    
        
        <div class="col-md-4 mb-4" id="articles">
          <div class="card shadow-sm custom-card">
            <div class="card-body">
              <h5 class="card-title">Guide pour lever des fonds</h5>
              <p class="card-text">Un guide complet pour aider les entrepreneurs à lever des fonds.</p>
              <a href="https://example.com" class="btn btn-primary" target="_blank">Télécharger le guide</a>
            </div>
          </div>
        </div>
    
      </div>
    
      
      <div class="text-center mt-3">
        <button class="btn btn-outline-primary" id="loadMoreBtn">Afficher plus</button>
      </div>
    </div>
  </section-->

  <!-- Bouton "Afficher plus" -->
  <!--div class="text-center mt-3">
    <button class="btn btn-outline-primary" id="loadMoreBtn">Afficher plus</button>
  </div-->
<!-- /Contact Section -->
  
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