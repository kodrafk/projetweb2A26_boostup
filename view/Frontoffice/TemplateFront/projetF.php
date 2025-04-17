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
    .button{
      background-color: pink;
    }

    .btn-danger, .btn-warning {
      margin-right: 10px;
    }

    /* Validation styles */
    .is-valid {
      border-color: #28a745 !important;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right calc(0.375em + 0.1875rem) center;
      background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .is-invalid {
      border-color: #dc3545 !important;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right calc(0.375em + 0.1875rem) center;
      background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .text-success {
      color: #28a745;
      font-size: 0.875rem;
    }

    .text-danger {
      color: #dc3545;
      font-size: 0.875rem;
    }

    .validation-message {
      display: block;
      margin-top: 0.25rem;
      font-size: 0.875rem;
    }

    /* Navbar Styling */
    .header {
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
      height: 70px;
    }

    .header.scrolled {
      height: 60px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .nav-link {
      color: #333;
      font-weight: 500;
      padding: 0.5rem 1rem;
      margin: 0 0.25rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link:hover, 
    .nav-link:focus {
      color: #4a00e0;
      background: rgba(74, 0, 224, 0.05);
    }

    .nav-link:after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #8e2de2, #4a00e0);
      transition: width 0.3s ease;
    }

    .nav-link:hover:after {
      width: 60%;
    }

    .dropdown-menu {
      border: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      padding: 0.5rem 0;
      margin-top: 0.5rem;
    }

    .dropdown-item {
      padding: 0.5rem 1.5rem;
      transition: all 0.2s ease;
    }

    .dropdown-item:hover {
      background: linear-gradient(90deg, rgba(142, 45, 226, 0.1), rgba(74, 0, 224, 0.1));
      color: #4a00e0;
    }

    .notification-icon, 
    .message-icon {
      color: #666;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .notification-icon:hover, 
    .message-icon:hover {
      color: #4a00e0;
      transform: translateY(-2px);
    }

    .user-profile {
      color: #333;
      transition: all 0.3s ease;
    }

    .user-profile:hover {
      color: #4a00e0;
    }

    .badge {
      font-size: 0.5rem;
      padding: 0.25rem;
      min-width: 16px;
      height: 16px;
    }

    /* Mobile toggle button */
    .mobile-nav-toggle {
      color: #4a00e0;
      font-size: 1.5rem;
      cursor: pointer;
      display: none;
    }

    /* Gradient animation for logo on hover */
    .logo:hover .logo-img {
      transform: rotate(10deg) scale(1.1);
    }

    .logo:hover .logo-text {
      background: linear-gradient(90deg, #8e2de2, #4a00e0);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }
  </style>
</head>

<body class="index-page">

  <!-- Header -->
  <header id="header" class="header fixed-top">
    <div class="container-fluid px-4">
      <div class="row align-items-center">
        
        <!-- Logo with animation -->
        <div class="col-md-2">
          <a href="index.html" class="logo d-flex align-items-center">
            <img src="assets/img/Logo2.png" alt="BoostUp Logo" class="img-fluid logo-img" style="height: 90px; transition: all 0.3s ease;">
          </a>
        </div>

        <!-- Main Navigation -->
        <div class="col-md-8">
          <nav id="navmenu" class="navmenu">
            <ul class="d-flex justify-content-center mb-0">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#">
                  <i class="bi bi-kanban-fill me-1"></i>
                  <span>Projets</span>
                  <i class="bi bi-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="projet.html"><i class="bi bi-kanban me-2"></i>Projet</a></li>
                  <li><a class="dropdown-item" href="categorie.html"><i class="bi bi-tags me-2"></i>Catégorie Projet</a></li>
                </ul>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link" href="#">
                  <i class="bi bi-list-task me-1"></i>
                  <span>Tâches</span>
                  <i class="bi bi-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-check-circle me-2"></i>Tâche</a></li>
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-bullseye me-2"></i>Objectif</a></li>
                </ul>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="#Ressources">
                  <i class="bi bi-collection-play-fill me-1"></i>
                  <span>Ressources</span>
                </a>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link" href="#">
                  <i class="bi bi-calendar-event-fill me-1"></i>
                  <span>Événements</span>
                  <i class="bi bi-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-calendar3 me-2"></i>Événement</a></li>
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-lightning-charge me-2"></i>Opportunité</a></li>
                </ul>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link" href="#">
                  <i class="bi bi-people-fill me-1"></i>
                  <span>Communauté</span>
                  <i class="bi bi-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-question-circle me-2"></i>Question</a></li>
                  <li><a class="dropdown-item" href="#cleaning"><i class="bi bi-chat-left-text me-2"></i>Réponse</a></li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>

        <!-- User Actions -->
        <div class="col-md-2 d-flex justify-content-end">
          <div class="user-actions d-flex align-items-center">
            <a href="#" class="notification-icon me-3 position-relative">
              <i class="bi bi-bell-fill"></i>
              <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle">3</span>
            </a>
            
            <a href="#" class="message-icon me-3 position-relative">
              <i class="bi bi-envelope-fill"></i>
              <span class="badge bg-primary rounded-circle position-absolute top-0 start-100 translate-middle">5</span>
            </a>
            
            <div class="dropdown">
              <a href="#" class="user-profile d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown">
                <img src="assets/img/avatar-1.webp" alt="User" class="rounded-circle me-2" width="36" height="36">
                <span class="d-none d-md-inline">Ela akchi</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.html#about"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
              </ul>
            </div>
          </div>
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
                <span class="accent-text">Welcome to BoostUp</span> <br>
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
      </div>
    </section>

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

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Besoin d'aide ? N'hésitez pas à nous contacter ou à nous envoyer un message directement via ce formulaire.</p>
      </div>
    
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
              <h3>Ajouter un Projet</h3>
              <p>Remplissez les détails de votre projet ici.</p>
              <form action="../../../controller/ajouterProjetFront.php" method="POST" id="projectForm">
                <div class="row gy-4">
                  <div class="col-12">
                    <input type="text" name="nom_projet" id="nom_projet" class="form-control" placeholder="Nom du Projet" required>
                    <div id="nom_projet_error" class="validation-message"></div>
                  </div>
    
                  <div class="col-md-6">
                    <input type="date" class="form-control" name="date_debut" id="date_debut" placeholder="Date de début" required>
                    <div id="date_debut_error" class="validation-message"></div>
                  </div>
    
                  <div class="col-md-6">
                    <input type="date" class="form-control" name="date_fin" id="date_fin" placeholder="Date de fin" required>
                    <div id="date_fin_error" class="validation-message"></div>
                  </div>
    
                  <div class="col-12">
                    <textarea class="form-control" name="description" id="description" rows="5" placeholder="Description du projet" required></textarea>
                    <div id="description_error" class="validation-message"></div>
                  </div>
    
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Affichage des Projets -->
    <section id="projects" class="section light-background mt-5">
      <div class="container" data-aos="fade-up">
        <h2 class="text-center mb-4">Liste des Projets</h2>
        <div class="table-responsive">
          <?php include_once("../afficherProjetF.php"); ?>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/script.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('projectForm');
    
    if (form) {
      const nomProjet = document.getElementById('nom_projet');
      const description = document.getElementById('description');
      const dateDebut = document.getElementById('date_debut');
      const dateFin = document.getElementById('date_fin');
      
      // Fonction pour afficher les messages de validation
      const showValidationMessage = (element, isValid, message) => {
        const errorElement = document.getElementById(element.id + '_error');
        if (errorElement) {
          errorElement.textContent = message;
          errorElement.className = 'validation-message ' + (isValid ? 'text-success' : 'text-danger');
          
          // Ajout des icônes de validation
          if (isValid) {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
          } else {
            element.classList.remove('is-valid');
            element.classList.add('is-invalid');
          }
        }
      };
      
      // Fonction de validation générique
      const validateField = (value, rules) => {
        if (rules.required && value.trim() === '') {
          return { isValid: false, message: 'Ce champ est obligatoire' };
        }
        
        if (rules.minLength && value.trim().length < rules.minLength) {
          return { isValid: false, message: `Minimum ${rules.minLength} caractères` };
        }
        
        if (rules.regex && !rules.regex.test(value)) {
          return { isValid: false, message: rules.customError || 'Format invalide' };
        }
        
        return { isValid: true, message: '✓ Valide' };
      };
      
      // Règles de validation
      const validationRules = {
        nom_projet: { required: true, minLength: 3 },
        description: { required: true, minLength: 10 },
        date_debut: { required: true },
        date_fin: { required: true }
      };
      
      // Validation en temps réel
      nomProjet.addEventListener('input', () => {
        const result = validateField(nomProjet.value, validationRules.nom_projet);
        showValidationMessage(nomProjet, result.isValid, result.message);
      });
      
      description.addEventListener('input', () => {
        const result = validateField(description.value, validationRules.description);
        showValidationMessage(description, result.isValid, result.message);
      });
      
      // Validation des dates
      const validateDates = () => {
        if (dateDebut.value && dateFin.value) {
          const startDate = new Date(dateDebut.value);
          const endDate = new Date(dateFin.value);
          
          if (startDate > endDate) {
            showValidationMessage(dateFin, false, 'La date de fin doit être après la date de début');
            return false;
          } else {
            showValidationMessage(dateFin, true, '✓ Valide');
            return true;
          }
        }
        return true;
      };
      
      dateDebut.addEventListener('change', () => {
        if (!dateDebut.value) {
          showValidationMessage(dateDebut, false, 'Veuillez sélectionner une date');
        } else {
          showValidationMessage(dateDebut, true, '✓ Valide');
          validateDates();
        }
      });
      
      dateFin.addEventListener('change', () => {
        if (!dateFin.value) {
          showValidationMessage(dateFin, false, 'Veuillez sélectionner une date');
        } else {
          showValidationMessage(dateFin, true, '✓ Valide');
          validateDates();
        }
      });
      
      // Validation avant soumission
      form.addEventListener('submit', (e) => {
        let isValid = true;
        
        // Valider chaque champ
        const nomResult = validateField(nomProjet.value, validationRules.nom_projet);
        const descResult = validateField(description.value, validationRules.description);
        const dateStartValid = dateDebut.value !== '';
        const dateEndValid = dateFin.value !== '';
        const datesOrderValid = validateDates();
        
        // Afficher les erreurs
        showValidationMessage(nomProjet, nomResult.isValid, nomResult.message);
        showValidationMessage(description, descResult.isValid, descResult.message);
        
        if (!dateStartValid) {
          showValidationMessage(dateDebut, false, 'Veuillez sélectionner une date');
        }
        
        if (!dateEndValid) {
          showValidationMessage(dateFin, false, 'Veuillez sélectionner une date');
        }
        
        // Vérifier la validité globale
        if (!nomResult.isValid || !descResult.isValid || !dateStartValid || !dateEndValid || !datesOrderValid) {
          e.preventDefault();
          isValid = false;
        }
        
        return isValid;
      });
    }
  });
  </script>
</body>
</html>