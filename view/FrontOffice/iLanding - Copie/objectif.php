<?php
require_once __DIR__ . '/../../../config.php';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->query("SELECT * FROM objectif");
    $objectifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Objectifs - BoostUp</title>
  <meta name="description" content="Liste des objectifs">
  <meta name="keywords" content="objectifs, gestion, BoostUp">

  <!-- Favicons -->
  <link href="assets/img/bot.jpg" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

    .custom-card {
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
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
                  <!-- Lien vers la page Projet -->
                  <li><a href="projet.html">Projet</a></li>
                  <!-- Lien vers la page Catégorie Projet -->
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

              <div class="customers-badge">
                <div class="customer-avatars">
                  <img src="assets/img/avatar-1.webp" alt="Customer 1" class="avatar">
                  <img src="assets/img/avatar-2.webp" alt="Customer 2" class="avatar">
                  <img src="assets/img/avatar-3.webp" alt="Customer 3" class="avatar">
                  <img src="assets/img/avatar-4.webp" alt="Customer 4" class="avatar">
                  <img src="assets/img/avatar-5.webp" alt="Customer 5" class="avatar">
                  <span class="avatar more">12+</span>
                </div>
                <p class="mb-0 mt-2">12,000+ of investors and entrepreneurs are using this site</p>
              </div>
            </div>
          </div>
        </div>

    
    <!-- Features Cards Section -->
    <section id="features-cards" class="features-cards section">
    <div class="container">
      <div class="row gy-4">
  
        <div class="col-xl-3 col-md-6">
          <div class="feature-box orange hover-zoom">
            <i class="bi bi-code-slash"></i>
            <h4>20 Projets Web</h4>
            <p>Des plateformes dynamiques conçues pour les besoins des startups modernes.</p>
          </div>
        </div>
  
        <div class="col-xl-3 col-md-6">
          <div class="feature-box blue hover-zoom">
            <i class="bi bi-phone"></i>
            <h4>15 Projets Mobile</h4>
            <p>Applications innovantes pour Android et iOS adaptées aux jeunes entreprises.</p>
          </div>
        </div>
  
        <div class="col-xl-3 col-md-6">
          <div class="feature-box green hover-zoom">
            <i class="bi bi-bar-chart"></i>
            <h4>10 Projets Data</h4>
            <p>Solutions d'analyse de données pour aider à la prise de décision stratégique.</p>
          </div>
        </div>
  
        <div class="col-xl-3 col-md-6">
          <div class="feature-box red hover-zoom">
            <i class="bi bi-people"></i>
            <h4>8 Ressources Partagées</h4>
            <p>Mentors, investisseurs et collaborateurs disponibles pour chaque projet.</p>
          </div>
        </div>
  
      </div>
    </div>
    </section>
    <!-- /Features Cards Section -->


     <!-- Contact Section -->
     <section id="contact" class="contact section light-background">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p>Besoin d’aide ? N'hésitez pas à nous contacter ou à nous envoyer un message directement via ce formulaire.</p>
    </div><!-- End Section Title -->
  
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4 g-lg-5">
  
        <!-- Contact Info -->
        <div class="col-lg-5">
          <div class="info-box" data-aos="fade-up" data-aos-delay="200">
            <h3>Informations de Contact</h3>
            <p>Nous sommes là pour répondre à toutes vos questions.</p>
  
            <div class="info-item" data-aos="fade-up" data-aos-delay="300">
              <div class="icon-box">
                <i class="bi bi-geo-alt"></i>
              </div>
              <div class="content">
                <h4>Adresse</h4>
                <p>A108 Freedom Street</p>
                <p>Seliana, Tunisia</p>
              </div>
            </div>
  
            <div class="info-item" data-aos="fade-up" data-aos-delay="400">
              <div class="icon-box">
                <i class="bi bi-telephone"></i>
              </div>
              <div class="content">
                <h4>Téléphone</h4>
                <p>+216 77 908 908</p>
                <p>+216 77 760 760</p>
              </div>
            </div>
  
            <div class="info-item" data-aos="fade-up" data-aos-delay="500">
              <div class="icon-box">
                <i class="bi bi-envelope"></i>
              </div>
              <div class="content">
                <h4>Email</h4>
                <p>boostup@gmail.com</p>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Contact Form -->
        <div class="col-lg-7">
          <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
            <h3>Ajouter un Categorie</h3>
            <p>Remplissez les détails de votre projet ici.</p>
  
            <form action="forms/Categorie.php" method="post" class="php-email-form">
              <div class="row gy-4">
  
                <div class="col-12">
                  <input type="text" name="nom_categorie" class="form-control" placeholder="Nom du categorie" required="">
                </div>
  
                <div class="col-12">
                  <textarea class="form-control" name="description" rows="5" placeholder="Description du categorie" required=""></textarea>
                </div>
  
                <div class="col-12 text-center">
                  <div class="loading">Chargement...</div>
                  <div class="error-message"></div>
                  <div class="sent-message">categorie ajouté avec succès !</div>
  
                  <button type="submit" class="btn btn-primary">Enregistrer</button>
                  <button type="reset" class="btn btn-secondary">Annuler</button>
                </div>
  
              </div>
            </form>
  
          </div>
        </div><!-- End Form -->
  
      </div>
    </div>
  
     </section>
     <!-- /Contact Section -->
  

     <!-- Section Affichage des Projets -->
      <section id="projects" class="section light-background mt-5">

    <div class="container" data-aos="fade-up">
      <h2 class="text-center mb-4">Liste des Categorie </h2>
  
      <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm rounded-3">
          <thead class="table-light text-center align-middle">
            <tr>
              <th>Nom du categorie</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="text-center align-middle">
  
            <!-- Exemple d’une ligne -->
            <tr>
              <td>Application Mobile</td>
              <td>Développement d’une app de gestion des tâches.</td>
              <td>
                <button class="btn btn-info btn-sm text-white me-2" style="background-color: #00BFFF; border: none; border-radius: 8px;">
                  <i class="bi bi-pencil-square"></i> Modifier
                </button>
                <button class="btn btn-danger btn-sm" style="background-color: #dc3545; border: none; border-radius: 8px;">
                  <i class="bi bi-trash3"></i> Supprimer
                </button>
              </td>
            </tr>
  
            <!-- Ajoute ici d'autres lignes dynamiquement depuis la base de données ou script -->
  
          </tbody>
        </table>
      </div>
    </div>
  
      </section>
     <!-- /fin tableau -->

     <div class="container py-5">
      <h2 class="text-center mb-4">Liste des Objectifs</h2>

      <?php if (count($objectifs) > 0): ?>
        <div class="row">
          <?php foreach ($objectifs as $objectif): ?>
            <div class="col-md-4 mb-4">
              <div class="card custom-card">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($objectif['titre']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($objectif['description']); ?></p>
                  <a href="#" class="btn btn-primary">Voir les détails</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning text-center">Aucun objectif trouvé.</div>
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