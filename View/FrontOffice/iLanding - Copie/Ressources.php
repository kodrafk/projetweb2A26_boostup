<?php
require_once('C:/xampp/htdocs/Ressources/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Ressources/Controller/ThematiqueC.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'signaler') {
    $id = $_POST['id_thematique'];
    $conn = Config::getConnexion(); 
    $stmt = $conn->prepare("UPDATE thematique SET signalee = 1 WHERE id_thematique = ?");
    $stmt->execute([$id]);
    exit;
}

$conn = Config::getConnexion();

// Récupération des thématiques avec statut flouté
$stmt = $conn->query("SELECT *, 
    (CASE WHEN flouter = 1 THEN 1 ELSE 0 END) AS est_floute 
    FROM thematique");
$thematiques = $stmt->fetchAll();

// Gestion des notifications
$notifMessage = "";
$notifThematiqueFile = "../../BackOffice/Template de backOffice Luna/notification.txt";
$notifRessourceFile = "../../BackOffice/Template de backOffice Luna/notificationR.txt";

// Vérification des dates de modification
$timeThematique = file_exists($notifThematiqueFile) ? filemtime($notifThematiqueFile) : 0;
$timeRessource = file_exists($notifRessourceFile) ? filemtime($notifRessourceFile) : 0;

// Détermination de la dernière notification
if ($timeThematique > $timeRessource) {
    $contenuThematique = trim(file_get_contents($notifThematiqueFile));
    if (!empty($contenuThematique)) {
        $notifMessage = "📢 Nouvelle thématique : $contenuThematique";
    }
} elseif ($timeRessource > 0) {
    $contenuRessource = trim(file_get_contents($notifRessourceFile));
    if (!empty($contenuRessource)) {
        $notifMessage = $contenuRessource;
    }
}

// Nettoyage des fichiers de notification après lecture
if (!empty($notifMessage)) {
  file_put_contents($notifThematiqueFile, "");
  file_put_contents($notifRessourceFile, "");
}

// Gestion du filtre
$filtre = $_POST['titre_filtre'] ?? '';

if(!empty($filtre)) {
    $thematiqueController = new ThematiqueC();
    $thematiques = $thematiqueController->filtrerParTitre($filtre);
} else {
    $stmt = $conn->query("SELECT *, 
        (CASE WHEN flouter = 1 THEN 1 ELSE 0 END) AS est_floute 
        FROM thematique");
    $thematiques = $stmt->fetchAll();
}

// Gestion des notifications de ressources
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && isset($_POST['type_acces'])) {
    $type = $_POST['type'];
    $type_acces = $_POST['type_acces'];
    $titre_ressource = $_POST['titre'] ?? '';

    if (($type == 'Cour' || $type == 'Evenement') && ($type_acces == 'En ligne' || $type_acces == 'Live')) {
        $message = "Le $type $type_acces '$titre_ressource' a démarré - Durée 20min";
        file_put_contents($notifRessourceFile, $message);
    }
}
?> 


<!-- Le reste du HTML reste inchangé -->
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
  
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chatbot IA</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded&family=Material+Symbols+Outlined">
  <style>
    /* Chatbot Style */
    .chatbot-popup {
      display: none;
      position: fixed;
      bottom: 80px;
      right: 40px;
      width: 350px;
      height: 500px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      z-index: 1000;
    }
    .chatbot-popup.show-chatbot {
      display: block;
    }
    .chat-header {
      background: #007bff;
      color: white;
      padding: 10px;
      text-align: center;
      border-radius: 10px 10px 0 0;
    }
    #close-chatbot {
      background: none;
      border: none;
      color: white;
      float: right;
    }
  </style>

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

    .feature-box.orange { background: #1e90ff; }
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


    /* Ajouter ces styles */
.btn-like, .btn-dislike {
  position: relative;
  transition: all 0.3s ease;
}

.btn-like.active {
  background-color: #28a745 !important;
  border-color: #28a745 !important;
  color: white !important;
}

.btn-dislike.active {
  background-color: #dc3545 !important;
  border-color: #dc3545 !important;
  color: white !important;
}


.active {
  background-color: #0d6efd;
  color: white;
}

/* d'un notification */
/* Style de la cloche de notification */
.bi-bell {
  transition: transform 0.3s ease;
}

.bi-bell:hover {
  transform: scale(1.1);
}

/* Cercle rouge de notification */
.notif-circle {
  position: absolute;
  top: -5px;
  right: -5px;
  width: 12px;
  height: 12px;
  background: #ff4757;
  border-radius: 50%;
  display: none;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { transform: scale(0.95); }
  70% { transform: scale(1.1); }
  100% { transform: scale(0.95); }
}

/* Conteneur de notification */
#notifBox {
  position: absolute;
  top: 50px;
  right: 20px;
  width: 320px;
  background: linear-gradient(135deg, #8000ff 0%);
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  padding: 20px;
  display: none;
  z-index: 1000;
}

/* En-tête de notification */
.notif-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.notif-title {
  font-weight: 700;
  color: #2d3436;
  font-size: 1.2em;
}

/* Bouton fermer */
.close-btn {
  cursor: pointer;
  color: #2d3436;
  font-weight: bold;
  padding: 5px;
  transition: transform 0.3s ease;
}

.close-btn:hover {
  transform: rotate(90deg);
  color: #ff4757;
}

/* Corps de notification */
.notif-body {
  background: white;
  padding: 15px;
  border-radius: 10px;
  font-size: 0.95em;
  position: relative;
}

/* Triangle décoratif */
.notif-body::before {
  content: "";
  position: absolute;
  top: -10px;
  right: 20px;
  border-width: 0 10px 10px 10px;
  border-style: solid;
  border-color: transparent transparent white transparent;
}

.floutee {
    filter: blur(5px);
    position: relative;
}
.floutee::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    z-index: 1;
}
.custom-card {
    position: relative; /* Ajoutez cette ligne */
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
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
          <a href="#" class="text-dark position-relative" onclick="toggleNotif()" id="bellIcon">
            <i class="bi bi-bell" style="font-size: 22px;"></i>
            <span id="notif-circle" class="notif-circle"></span>
          </a>

        <div id="notifBox">
            <div class="notif-header">
               <div class="notif-title">Nouveauté ! 🎉</div>
               <div class="close-btn" onclick="closeNotif()">×</div>
            </div>
            <div class="notif-body">
               <p id="notifMessage"></p>
                <small class="text-muted">Cliquez pour explorer</small>
            </div>
          </div>
       </div>


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

  <!-- Section des thématiques -->
    <section class="section">
      <div class="container">
        <h2 class="text-center mb-5"style="color: #8000ff;">Nos Thématiques</h2>
        
        <div class="d-flex justify-content-center mt-5 mb-4">
          <form method="POST" action="" style="width: 60%; max-width: 600px;">
             <div class="input-group input-group-md shadow animate__animated animate__fadeIn">
                <input type="text" 
                   name="titre_filtre" 
                   class="form-control border-2"
                   placeholder="Rechercher une thématique..."
                   value="<?= htmlspecialchars($filtre) ?>"
                   style="border-radius: 30px 0 0 30px; height: 45px; font-size: 16px;">
            
                  <button type="submit" class="btn px-4"
                    style="border-radius: 0 30px 30px 0; background-color: #8000ff; color: white; height: 45px;">
                     <i class="bi bi-search me-2"></i>Rechercher
                  </button>
              </div>
              <?php if(!empty($filtre)): ?>
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Résultats pour : <span class="fst-italic">"<?= htmlspecialchars($filtre) ?>"</span>
                        <a href="Ressources.php" class="ms-2 text-danger text-decoration-none">
                           <i class="bi bi-x-circle"></i> Effacer le filtre
                        </a>
                    </small>
                  </div>
              <?php endif; ?>
           </form>
         </div>

        <?php if ($thematiques): ?>
          <div class="row">
          <?php
             // --- PAGINATION ---
             $thematiquesParPage =6;
             $pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
             $debut = ($pageActuelle - 1) * $thematiquesParPage;
             $totalThematiques = count($thematiques);
             $totalPages = ceil($totalThematiques / $thematiquesParPage);

             // Découpe des thématiques à afficher pour la page actuelle
             $thematiquesPage = array_slice($thematiques, $debut, $thematiquesParPage);
            ?>
          <?php foreach ($thematiquesPage as $thematique): ?>
            <div class="col-md-4 mb-4">
            <div class="card shadow-sm custom-card <?= isset($thematique['est_floute']) && $thematique['est_floute'] == 1 ? 'floutee' : '' ?>">
            <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($thematique['titre']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($thematique['description']) ?></p>

              <a href="playlist.php?id_thematique=<?= $thematique['id_thematique'] ?>" class="btn btn-primary mb-2"  style="background-color: #8000ff; color: white;">
               Voir Playlist
               </a>

               <div data-id="<?= $thematique['id_thematique'] ?>">
                  <button class="btn btn-outline-success btn-like me-2">
                   👍 <span class="like-count"><?= $thematique['likes'] ?></span>
                  </button>

                  <button class="btn btn-outline-danger btn-dislike">
                    👎 <span class="dislike-count"><?= $thematique['dislikes'] ?></span>
                  </button>

                  <!-- Bouton Signaler -->
                  <button class="btn btn-outline-warning btn-sm btn-signaler" data-id="<?= $thematique['id_thematique'] ?>">
                     ⚠️ Signaler
                  </button>
              </div>

             
           </div>
        </div>
      </div>
     <?php endforeach; ?>
    </div>
    <?php if ($totalPages > 1): ?>
       <nav>
           <ul class="pagination justify-content-center mt-4">
              <!-- Flèche page précédente -->
               <li class="page-item <?= ($pageActuelle <= 1) ? 'disabled' : '' ?>">
                  <a class="page-link text-primary" href="?page=<?= $pageActuelle - 1 ?>">
                        &laquo;
                  </a>
                </li>

                 <!-- Texte format 1 / 2 -->
                <li class="page-item disabled">
                   <span class="page-link"><?= $pageActuelle ?> / <?= $totalPages ?></span>
                </li>

                 <!-- Flèche page suivante -->
                <li class="page-item <?= ($pageActuelle >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link text-primary" href="?page=<?= $pageActuelle + 1 ?>">
                         &raquo;
                    </a>
                </li>
             </ul>
           </nav>
      <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-info text-center">Aucune thématique disponible pour le moment.</div>
        <?php endif; ?>
      </div>
    </section>
  
  <!-- fin section-->
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

  <!-- script chatbot IA -->
   <!--script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script-->
  <!--script src="/Ressources/View/FrontOffice/iLanding - Copie/AI-Chatbot-main/script.js"></script-->
  <!-- jQuery et Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


  <!-- Ton contenu HTML ici -->

<script>
 document.querySelectorAll('.btn-like, .btn-dislike').forEach(btn => {
  btn.addEventListener('click', function () {
    const card = this.closest('[data-id]');
    const id = card.dataset.id;
    const isLike = this.classList.contains('btn-like');
    const likeBtn = card.querySelector('.btn-like');
    const dislikeBtn = card.querySelector('.btn-dislike');

    fetch('like_dislike_handle.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}&action=${isLike ? 'like' : 'dislike'}`
    })
      .then(res => res.json())
      .then(data => {
        // Mise à jour des valeurs affichées
        likeBtn.querySelector('.like-count').textContent = data.likes;
        dislikeBtn.querySelector('.dislike-count').textContent = data.dislikes;

        // Style actif ou non actif (optionnel)
        if (data.likes === 1) {
          likeBtn.classList.add('active');
          dislikeBtn.classList.remove('active');
        } else if (data.dislikes === 1) {
          dislikeBtn.classList.add('active');
          likeBtn.classList.remove('active');
        } else {
          likeBtn.classList.remove('active');
          dislikeBtn.classList.remove('active');
        }
      })
      .catch(error => console.error('Erreur:', error));
  });
});
</script>

<script>
function toggleNotif() {
    const notifBox = document.getElementById("notifBox");
    const bellIcon = document.getElementById("bellIcon");
  
    if (notifBox.style.display === "block") {
        notifBox.style.display = "none";
        bellIcon.style.transform = "rotate(0deg)";
    } else {
        notifBox.style.display = "block";
        bellIcon.style.transform = "rotate(-20deg)";
        document.getElementById("notif-circle").style.display = "none";
        document.getElementById("notifSound").play();
    }
}

function closeNotif() {
    document.getElementById("notifBox").style.display = "none";
    document.getElementById("bellIcon").style.transform = "rotate(0deg)";
}

// Vérifier les nouvelles notifications au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Notification de thématique
    <?php if(!empty($notifMessage)): ?>
        document.getElementById("notif-circle").style.display = "block";
        document.getElementById("notifMessage").innerHTML = "<?php echo addslashes($notifMessage); ?>";
    <?php endif; ?>

    // Notification de ressource
    <?php if(!empty($notifRessourceMessage)): ?>
        // Ajouter le message de ressource à la notification existante
        const notifBody = document.getElementById("notifMessage");
        notifBody.innerHTML += "<br><strong>Ressource :</strong> <?php echo $notifRessourceMessage; ?>";
    <?php endif; ?>
});

//pour le button signaler
document.querySelectorAll('.btn-signaler').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        fetch('Ressources.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=signaler&id_thematique=' + id
        })
        .then(response => response.text())
        .then(data => {
            alert("Thématique signalée avec succès !");
            location.reload(); 
        });
    });
});
</script>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant Ressources</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Autres styles précédemment définis pour le chatbot */
    .chatbot-launcher {
        position: fixed;
        bottom: 20px;
        right: 10px;
        background: #8000ff;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        cursor: pointer;
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .chat-container {
        border-radius: 10px;
        max-width: 450px;
        height: 70vh;
    }

    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 15px;
        background: #8000ff;
        color: white;
        border-radius: 5px 5px 0 0;
    }

    .chat-avatar {
        display: flex;
        align-items: center;
    }

    .chat-avatar img {
        width: 40px;
        height: 40px;
        border-radius: 20%;
        margin-right: 10px;
    }

    .chat-body {
        flex: 1;
        padding: 15px;
        background: #ECE5DD;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-height: 300px;
    }

    .message {
        max-width: 80%;
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .user-message {
        margin-left: auto;
    }

    .bot-message .bubble {
        background: white;
        border-radius: 15px 15px 15px 4px;
    }

    .user-message .bubble {
        background: #8000ff;
        border-radius: 15px 15px 4px 15px;
    }

    .message-input {
        display: flex;
        gap: 10px;
        align-items: center;
        background: white;
        padding: 10px;
        border-radius: 25px;
        margin-top: 10px;
    }

    .typing-indicator {
        display: inline-flex;
        gap: 3px;
        padding: 8px 12px;
    }

    .typing-indicator span {
        width: 6px;
        height: 6px;
        background: #666;
        border-radius: 50%;
        animation: typing 1s infinite ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bubble {
    position: relative;
    background-color: #f0f0f0;
    border-radius: 10px;
    padding: 10px 30px 10px 10px;
    margin: 5px 0;
    max-width: 80%;
    word-wrap: break-word;
}

.btn-ecouter {
    position: absolute;
    right: 5px;
    bottom: 5px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

.btn-ecouter img {
    width: 10px;
    height: 10px;
}

.emoji-picker {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 5px;
            padding: 10px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 10px;
            display: none;
        }
</style>
</head>
<body>

<!-- Bouton pour ouvrir le chatbot -->
<button id="askAIButton" class="chatbot-launcher">💬 Assistant Ressources</button>

<!-- Modal chatbot -->
<div class="modal fade" id="aiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content chat-container">
            <div class="chat-header">
                <div class="chat-avatar">
                    <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" alt="Bot">
                </div>
                <h5>ChatBot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="chat-body" id="chatWindow"></div>

            <div class="chat-footer" style="padding: 15px;">
                <div class="message-input">
                    <button class="btn btn-light" onclick="toggleEmojiPicker()">😊</button>
                    <input type="text" id="questionInput" class="form-control" placeholder="Écrivez votre message...">
                    <button class="btn btn-secondary" id="micButton">🎤</button>
                    <button class="btn btn-success" id="askButton">
                        <svg width="24" height="24" viewBox="0 0 24 24" style="fill: white;">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </div>
                <div class="emoji-picker" id="emojiPicker">
                    <span onclick="addEmoji('😀')">😀</span>
                    <span onclick="addEmoji('😊')">😊</span>
                    <span onclick="addEmoji('👍')">👍</span>
                    <span onclick="addEmoji('❤️')">❤️</span>
                    <span onclick="addEmoji('🎉')">🎉</span>
                    <span onclick="addEmoji('🙏')">🙏</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
const API_KEY = 'AIzaSyC_-H3O3VD9hrpZqRv3gpc40qxDIAbYILo';
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

// Ouvrir le chatbot
document.getElementById('askAIButton').addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('aiModal')).show();
});

/* 1 ere methode il repondre sur toute les questionne */
// Envoyer une question
/*document.getElementById('askButton').addEventListener('click', sendQuestion);

function sendQuestion() {
    const input = document.getElementById('questionInput');
    const question = input.value.trim();
    const chatWindow = document.getElementById('chatWindow');

    if (!question) return;

    chatWindow.innerHTML += `
        <div class="message user-message">
            <div class="bubble">${question}</div>
        </div>
        <div class="message bot-message" id="typing">
            <div class="bubble typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>`;
    input.value = '';
    chatWindow.scrollTop = chatWindow.scrollHeight;

    fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            contents: [{ parts: [{ text: question }] }]
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('typing').remove();
        const reply = data.candidates?.[0]?.content?.parts?.[0]?.text || 'Je n’ai pas compris.';
        afficherReponseBot(reply);
    })
    .catch(() => {
        document.getElementById('typing').remove();
        afficherReponseBot("Erreur lors de la réponse.");
    });
}*/
/* fin methode 1*/

/* 2 eme methode */

// Envoyer une question
document.getElementById('askButton').addEventListener('click', sendQuestion);

async function sendQuestion() {
    const input = document.getElementById('questionInput');
    const question = input.value.trim();
    const chatWindow = document.getElementById('chatWindow');

    if (!question) return;

    // Message utilisateur
    chatWindow.innerHTML += `
        <div class="message user-message">
            <div class="bubble">${question}</div>
        </div>
        <div class="message bot-message" id="typing">
            <div class="bubble typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>`;
    input.value = '';
    chatWindow.scrollTop = chatWindow.scrollHeight;

    try {
        const systemPrompt = `Tu es un assistant pour une plateforme de ressources éducatives liées à l'entrepreneuriat. 
        Réponds uniquement si la question concerne : contenus d'apprentissage, vidéos, événements enregistrés, 
        guides ou thématiques liées aux projets et startups. Sinon, réponds :
        "Je suis un assistant dédié à la gestion de ressources entrepreneuriales uniquement." 
        La question est : ${question}`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                contents: [{ parts: [{ text: systemPrompt }] }]
            })
        });

        const data = await response.json();
        document.getElementById('typing').remove();

        const reply = data?.candidates?.[0]?.content?.parts?.[0]?.text || 'Je n’ai pas compris.';
        afficherReponseBot(reply);
    } catch (error) {
        document.getElementById('typing').remove();
        afficherReponseBot("Erreur lors de la réponse.");
    }
}

/*fin deuxieme methode */

let currentUtterance = null;

function afficherReponseBot(message) {
    const chatWindow = document.getElementById('chatWindow');

    const messageBot = document.createElement('div');
    messageBot.classList.add('message', 'bot-message');

    const bubble = document.createElement('div');
    bubble.classList.add('bubble');
    bubble.textContent = message;

    // Bouton écouter
    const boutonEcouter = document.createElement('button');
    boutonEcouter.classList.add('btn-ecouter');
    boutonEcouter.innerHTML = '<img src="https://cdn-icons-png.flaticon.com/512/727/727269.png" alt="Écouter">';

    bubble.appendChild(boutonEcouter);
    messageBot.appendChild(bubble);
    chatWindow.appendChild(messageBot);
    chatWindow.scrollTop = chatWindow.scrollHeight;

    boutonEcouter.addEventListener('click', function () {
        if (currentUtterance && speechSynthesis.speaking) {
            speechSynthesis.cancel(); // Arrête la lecture si déjà en cours
            currentUtterance = null;
        } else {
            currentUtterance = new SpeechSynthesisUtterance(message);
            currentUtterance.lang = 'fr-FR';
            speechSynthesis.speak(currentUtterance);
        }
    });
}


// Emoji
function toggleEmojiPicker() {
    const picker = document.getElementById('emojiPicker');
    picker.style.display = picker.style.display === 'grid' ? 'none' : 'grid';
}

function addEmoji(emoji) {
    document.getElementById('questionInput').value += emoji;
}

// 🎤 Reconnaissance vocale
const micButton = document.getElementById('micButton');
let recognition;
if ('webkitSpeechRecognition' in window) {
    recognition = new webkitSpeechRecognition();
    recognition.lang = 'fr-FR';
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onresult = event => {
        const transcript = event.results[0][0].transcript;
        document.getElementById('questionInput').value = transcript;
    };

    recognition.onerror = () => alert('Erreur de reconnaissance vocale');
    recognition.onend = () => micButton.disabled = false;

    micButton.addEventListener('click', () => {
        micButton.disabled = true;
        recognition.start();
    });
} else {
    micButton.disabled = true;
    micButton.title = "Reconnaissance vocale non supportée";
}
</script>
</body>

</html>
