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

// Récupérer toutes les thématiques
//$stmt = $conn->query("SELECT * FROM thematique");
$stmt = $conn->query("SELECT *, 
     (CASE WHEN flouter = 1 THEN 1 ELSE 0 END) AS est_floute 
     FROM thematique");
$thematiques = $stmt->fetchAll();

// Définir le chemin du fichier de notification
$notifMessage = "";
$notifFile = "../../BackOffice/Template de backOffice Luna/notification.txt"; // Chemin corrigé

// Vérifier si le fichier de notification existe et lire le titre
if (file_exists($notifFile)) {
    $titre = trim(file_get_contents($notifFile));
    if (!empty($titre)) {
        $notifMessage = "📢 Une nouvelle thématique a été ajoutée : $titre";
    }
}

// Récupération du filtre
$filtre = $_POST['titre_filtre'] ?? '';

// Si un filtre est appliqué
if(!empty($filtre)) {
  $thematiqueController = new ThematiqueC();
  $thematiques = $thematiqueController->filtrerParTitre($filtre);
} else {
  // Requête par défaut
  $stmt = $conn->query("SELECT *, 
       (CASE WHEN flouter = 1 THEN 1 ELSE 0 END) AS est_floute 
       FROM thematique");
  $thematiques = $stmt->fetchAll();
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
  background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
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
          <audio id="notifSound" src="assets/sounds/notification.mp3" preload="auto"></audio>
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
  <?php if(!empty($notifMessage)): ?>
    document.getElementById("notif-circle").style.display = "block";
    document.getElementById("notifMessage").innerHTML = "<?php echo $notifMessage; ?>";
  <?php endif; ?>
});

//pou le button signaler
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





</body>

</html>
