<?php
// index.php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$db_host = 'localhost';
$db_name = 'projetweb';
$db_user = 'root'; // Change to your MySQL username
$db_pass = '';     // Change to your MySQL password

try {
    // Create PDO connection
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['create'])) {
        // Create new event
        $stmt = $db->prepare("INSERT INTO events (title, event_date, location, link) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([
            $_POST['title'],
            $_POST['event_date'],
            $_POST['location'],
            $_POST['link'] ?? null
        ]);
        
        if($success) {
            header("Location: index.php?action=created");
            exit();
        }
    } elseif(isset($_POST['update'])) {
        // Update existing event
        $stmt = $db->prepare("UPDATE events SET title=?, event_date=?, location=?, link=? WHERE id=?");
        $success = $stmt->execute([
            $_POST['title'],
            $_POST['event_date'],
            $_POST['location'],
            $_POST['link'] ?? null,
            $_POST['id']
        ]);
        
        if($success) {
            header("Location: index.php?action=updated");
            exit();
        }
    }
}

// Handle delete action
if(isset($_GET['delete_id'])) {
    $stmt = $db->prepare("DELETE FROM events WHERE id=?");
    $success = $stmt->execute([$_GET['delete_id']]);
    
    if($success) {
        header("Location: index.php?action=deleted");
        exit();
    }
}

// Read all events
$stmt = $db->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <meta charset="UTF-8">
  <title>Events Management</title>
    <meta charset="UTF-8">
    <title>Events Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            background: linear-gradient(90deg, #725AC1, #EF4444, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        /* Card Styles */
        .card-container {
            background: linear-gradient(to top right, #975af4, #2f7cf8 40%, #78aafa 65%, #934cff 100%);
            padding: 4px;
            border-radius: 32px;
        }
        
        .title-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            color: #fff;
        }
        
        .title-card p {
            font-size: 14px;
            font-weight: 600;
            font-style: italic;
            text-shadow: 2px 2px 6px #2975ee;
        }
        
        .card-content {
            background-color: #161a20;
            border-radius: 30px;
            color: #838383;
            font-size: 12px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        
        .event-detail {
            display: flex;
        }
        
        .detail-label {
            font-weight: 500;
            min-width: 60px;
            color: #bab9b9;
        }
        
        .detail-value {
            color: #fff;
        }
        
        .event-link {
            color: #78aafa;
            text-decoration: none;
        }
        
        .card-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        /* Button Styles */
        button {
            --primary-color: #645bff;
            --secondary-color: #fff;
            --hover-color: #111;
            --arrow-width: 10px;
            --arrow-stroke: 2px;
            box-sizing: border-box;
            border: 0;
            border-radius: 20px;
            color: var(--secondary-color);
            padding: 0.6em 1.2em;
            background: var(--primary-color);
            display: flex;
            transition: 0.2s background, 0.2s color;
            align-items: center;
            gap: 0.6em;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
        }

        button .arrow-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        button .arrow {
            margin-top: 1px;
            width: var(--arrow-width);
            background: var(--primary-color);
            height: var(--arrow-stroke);
            position: relative;
            transition: 0.2s;
        }

        button .arrow::before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            border: solid var(--secondary-color);
            border-width: 0 var(--arrow-stroke) var(--arrow-stroke) 0;
            display: inline-block;
            top: -3px;
            right: 3px;
            transition: 0.2s;
            padding: 3px;
            transform: rotate(-45deg);
        }

        button:hover {
            background-color: var(--hover-color);
        }

        button:hover .arrow {
            background: var(--secondary-color);
        }

        button:hover .arrow:before {
            right: 0;
        }

        /* Button Variations */
        .btn-create {
            --primary-color: #10B981;
            --hover-color: #047857;
        }

        .delete-btn {
            --primary-color: #EF4444;
            --hover-color: #B91C1C;
        }

        .edit-btn {
            --primary-color: #725AC1;
            --hover-color: #4C3D80;
        }

        .btn-submit {
            --primary-color: #725AC1;
            --hover-color: #4C3D80;
        }

        .btn-confirm {
            --primary-color: #EF4444;
            --hover-color: #B91C1C;
        }

        .btn-cancel {
            --primary-color: #64748b;
            --hover-color: #475569;
            --secondary-color: #fff;
        }

        /* Clicked state */
        button.clicked {
            background-color: white;
            color: black;
        }

        button.clicked .arrow {
            background: black;
        }

        button.clicked .arrow::before {
            border-color: black;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
    </style>
</head>

<body class="index-page">
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Viewer</title>


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


   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            background: linear-gradient(90deg, #725AC1, #EF4444, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        /* Card Styles */
        .card-container {
            background: linear-gradient(to top right, #975af4, #2f7cf8 40%, #78aafa 65%, #934cff 100%);
            padding: 4px;
            border-radius: 32px;
        }
        
        .title-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            color: #fff;
        }
        
        .title-card p {
            font-size: 14px;
            font-weight: 600;
            font-style: italic;
            text-shadow: 2px 2px 6px #2975ee;
        }
        
        .card-content {
            background-color: #161a20;
            border-radius: 30px;
            color: #838383;
            font-size: 12px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        
        .event-detail {
            display: flex;
        }
        
        .detail-label {
            font-weight: 500;
            min-width: 60px;
            color: #bab9b9;
        }
        
        .detail-value {
            color: #fff;
        }
        
        .event-link {
            color: #78aafa;
            text-decoration: none;
        }
        
        .card-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        /* Button Styles */
        button {
            --primary-color: #645bff;
            --secondary-color: #fff;
            --hover-color: #111;
            --arrow-width: 10px;
            --arrow-stroke: 2px;
            box-sizing: border-box;
            border: 0;
            border-radius: 20px;
            color: var(--secondary-color);
            padding: 0.6em 1.2em;
            background: var(--primary-color);
            display: flex;
            transition: 0.2s background, 0.2s color;
            align-items: center;
            gap: 0.6em;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
        }

        button .arrow-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        button .arrow {
            margin-top: 1px;
            width: var(--arrow-width);
            background: var(--primary-color);
            height: var(--arrow-stroke);
            position: relative;
            transition: 0.2s;
        }

        button .arrow::before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            border: solid var(--secondary-color);
            border-width: 0 var(--arrow-stroke) var(--arrow-stroke) 0;
            display: inline-block;
            top: -3px;
            right: 3px;
            transition: 0.2s;
            padding: 3px;
            transform: rotate(-45deg);
        }

        button:hover {
            background-color: var(--hover-color);
        }

        button:hover .arrow {
            background: var(--secondary-color);
        }

        button:hover .arrow:before {
            right: 0;
        }

        /* Button Variations */
        .btn-create {
            --primary-color: #10B981;
            --hover-color: #047857;
        }

        .delete-btn {
            --primary-color: #EF4444;
            --hover-color: #B91C1C;
        }

        .edit-btn {
            --primary-color: #725AC1;
            --hover-color: #4C3D80;
        }

        .btn-submit {
            --primary-color: #725AC1;
            --hover-color: #4C3D80;
        }

        .btn-confirm {
            --primary-color: #EF4444;
            --hover-color: #B91C1C;
        }

        .btn-cancel {
            --primary-color: #64748b;
            --hover-color: #475569;
            --secondary-color: #fff;
        }

        /* Clicked state */
        button.clicked {
            background-color: white;
            color: black;
        }

        button.clicked .arrow {
            background: black;
        }

        button.clicked .arrow::before {
            border-color: black;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
    </style>
<body>
    <div class="container">
        <div class="header">
            <h1>Events Management</h1>
            <button class="btn-create" id="create-btn" onclick="showModal('createModal')">
                CREATE EVENT
                <div class="arrow-wrapper">
                    <div class="arrow"></div>
                </div>
            </button>
        </div>
        <div class="events-grid">
            <?php if(count($events) > 0): ?>
                <?php foreach($events as $event): ?>
                    <div class="card-container">
                        <div class="title-card">
                            <p><?= htmlspecialchars($event['title']) ?></p>
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M10.277 16.515c.005-.11.187-.154.24-.058c.254.45.686 1.111 1.177 1.412c.49.3 1.275.386 1.791.408c.11.005.154.186.058.24c-.45.254-1.111.686-1.412 1.176s-.386 1.276-.408 1.792c-.005.11-.187.153-.24.057c-.254-.45-.686-1.11-1.176-1.411s-1.276-.386-1.792-.408c-.11-.005-.153-.187-.057-.24c.45-.254 1.11-.686 1.411-1.177c.301-.49.386-1.276.408-1.791m8.215-1c-.008-.11-.2-.156-.257-.062c-.172.283-.421.623-.697.793s-.693.236-1.023.262c-.11.008-.155.2-.062.257c.283.172.624.42.793.697s.237.693.262 1.023c.009.11.2.155.258.061c.172-.282.42-.623.697-.792s.692-.237 1.022-.262c.11-.009.156-.2.062-.258c-.283-.172-.624-.42-.793-.697s-.236-.692-.262-1.022M14.704 4.002l-.242-.306c-.937-1.183-1.405-1.775-1.95-1.688c-.545.088-.806.796-1.327 2.213l-.134.366c-.149.403-.223.604-.364.752c-.143.148-.336.225-.724.38l-.353.141l-.248.1c-1.2.48-1.804.753-1.881 1.283c-.082.565.49 1.049 1.634 2.016l.296.25c.325.275.488.413.58.6c.094.187.107.403.134.835l.024.393c.093 1.52.14 2.28.634 2.542s1.108-.147 2.336-.966l.318-.212c.35-.233.524-.35.723-.381c.2-.032.402.024.806.136l.368.102c1.422.394 2.133.591 2.52.188c.388-.403.196-1.14-.19-2.613l-.099-.381c-.11-.419-.164-.628-.134-.835s.142-.389.365-.752l.203-.33c.786-1.276 1.179-1.914.924-2.426c-.254-.51-.987-.557-2.454-.648l-.379-.024c-.417-.026-.625-.039-.806-.135c-.18-.096-.314-.264-.58-.6m-5.869 9.324C6.698 14.37 4.919 16.024 4.248 18c-.752-4.707.292-7.747 1.965-9.637c.144.295.332.539.5.73c.35.396.852.82 1.362 1.251l.367.31l.17.145c.005.064.01.14.015.237l.03.485c.04.655.08 1.294.178 1.805"/>
                            </svg>
                        </div>
                        <div class="card-content">
                            <div class="event-detail">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value"><?= date('F j, Y', strtotime($event['event_date'])) ?></span>
                            </div>
                            <div class="event-detail">
                                <span class="detail-label">Location:</span>
                                <span class="detail-value"><?= htmlspecialchars($event['location']) ?></span>
                            </div>
                            <div class="event-detail">
                                <span class="detail-label">Link:</span>
                                <span class="detail-value">
                                    <?= $event['link'] ? '<a href="'.htmlspecialchars($event['link']).'" class="event-link" target="_blank">View Link</a>' : 'None' ?>
                                </span>
                            </div>
                            <div class="card-actions">
                                <button class="delete-btn" onclick="confirmDelete(<?= $event['id'] ?>)">
                                    DELETE
                                    <div class="arrow-wrapper">
                                        <div class="arrow"></div>
                                    </div>
                                </button>
                                <button class="edit-btn" onclick="showEditForm(<?= $event['id'] ?>, '<?= addslashes($event['title']) ?>', '<?= $event['event_date'] ?>', '<?= addslashes($event['location']) ?>', '<?= addslashes($event['link']) ?>')">
                                    EDIT
                                    <div class="arrow-wrapper">
                                        <div class="arrow"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No events found. Create your first event.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <form method="POST" onsubmit="return validateForm()">
                <h2>Create New Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="createTitle" required>
                    <div id="titleError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="createDate" required>
                    <div id="dateError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="createLocation" required>
                    <div id="locationError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link" id="createLink">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('createModal')">
                        Cancel
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                    <button type="submit" class="btn-submit" name="create">
                        Create
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <form method="POST" onsubmit="return validateEditForm()">
                <input type="hidden" name="id" id="editId">
                <h2>Edit Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="editTitle" required>
                    <div id="editTitleError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="editDate" required>
                    <div id="editDateError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="editLocation" required>
                    <div id="editLocationError" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link" id="editLink">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('editModal')">
                        Cancel
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                    <button type="submit" class="btn-submit" name="update">
                        Update
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Validate create form
        function validateForm() {
            let isValid = true;
            clearErrors();  // Clear any previous error messages

            const title = document.getElementById('createTitle').value;
            const date = document.getElementById('createDate').value;
            const location = document.getElementById('createLocation').value;

            // Title validation
            if (!/^[a-zA-Z]{3,}/.test(title)) {
                document.getElementById('titleError').textContent = 'First 3 chars must be letters';
                isValid = false;
            }

            // Date validation
            if (!date) {
                document.getElementById('dateError').textContent = 'You need to write the date';
                isValid = false;
            }

            // Location validation
            if (!/^[a-zA-Z]{3,}/.test(location)) {
                document.getElementById('locationError').textContent = 'First 3 chars must be letters';
                isValid = false;
            }

            return isValid;
        }

        // Validate edit form
        function validateEditForm() {
            let isValid = true;
            clearErrors();  // Clear any previous error messages

            const title = document.getElementById('editTitle').value;
            const date = document.getElementById('editDate').value;
            const location = document.getElementById('editLocation').value;

            // Title validation
            if (!/^[a-zA-Z]{3,}/.test(title)) {
                document.getElementById('editTitleError').textContent = 'First 3 chars must be letters';
                isValid = false;
            }

            // Date validation
            if (!date) {
                document.getElementById('editDateError').textContent = 'You need to write the date';
                isValid = false;
            }

            // Location validation
            if (!/^[a-zA-Z]{3,}/.test(location)) {
                document.getElementById('editLocationError').textContent = 'First 3 chars must be letters';
                isValid = false;
            }

            return isValid;
        }

        // Clear previous error messages
        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach((error) => {
                error.textContent = '';
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">BoostUp</strong> <span>All Rights Reserved</span></p>
    </div>
</body>


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