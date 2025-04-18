
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
    <meta charset="UTF-8">
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
    </head>
    
    <body class="index-page">
      <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Events Viewer</title>
    
    
    <body class="index-page">
    
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
              <li class="dropdown"><a href="#"><span>Projets</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#feature1">Projet</a></li>
                  <li><a href="#feature1">Catégorie Projet</a></li>
                </ul>
              </li>
    
              <li class="dropdown"><a href="#"><span>Taches</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#cleaning">Tache</a></li>
                  <li><a href="#cleaning">Objectif</a></li>
                </ul>
              </li>
    
              <li><a href="#Ressources">Ressources</a></li>
    
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
    
          <!-- Right Side: Bell, Message, and Profile Dropdown -->
          <div class="d-flex align-items-center gap-3">
            <!-- Bell -->
            <div class="notification">
              <div class="bell-container">
                <div class="bell"></div>
              </div>
            </div>
    
            <!-- Message Button -->
            <button id="btn-message" class="button-message">
              <div class="content-avatar">
                <div class="status-user"></div>
                <div class="avatar">
                  <svg class="user-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12,12.5c-3.04,0-5.5,1.73-5.5,3.5s2.46,3.5,5.5,3.5,5.5-1.73,5.5-3.5-2.46-3.5-5.5-3.5Zm0-.5c1.66,0,3-1.34,3-3s-1.34-3-3-3-3,1.34-3,3,1.34,3,3,3Z"></path>
                  </svg>
                </div>
              </div>
              <div class="notice-content">
                <div class="username">Jessica Sanders</div>
                <div class="lable-message">
                  Message<span class="number-message">3</span>
                </div>
                <div class="user-id">@jessisanders</div>
              </div>
            </button>
    
            <!-- Profile Dropdown -->
            <label class="popup">
              <input type="checkbox" />
              <div tabindex="0" class="burger">
                <svg viewBox="0 0 24 24" fill="white" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2c2.757 0 5 2.243 5 5.001 0 2.756-2.243 5-5 5s-5-2.244-5-5c0-2.758 2.243-5.001 5-5.001zm0-2c-3.866 0-7 3.134-7 7.001 0 3.865 3.134 7 7 7s7-3.135 7-7c0-3.867-3.134-7.001-7-7.001zm6.369 13.353c-.497.498-1.057.931-1.658 1.302 2.872 1.874 4.378 5.083 4.972 7.346h-19.387c.572-2.29 2.058-5.503 4.973-7.358-.603-.374-1.162-.811-1.658-1.312-4.258 3.072-5.611 8.506-5.611 10.669h24c0-2.142-1.44-7.557-5.631-10.647z"></path>
                </svg>
              </div>
              <nav class="popup-window">
                <legend>Quick Start</legend>
                <ul>
                  <li>
                    <button>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 4v6.406l-3.753 3.741-6.463-6.462 3.7-3.685h6.516zm2-2h-12.388l1.497 1.5-4.171 4.167 9.291 9.291 4.161-4.193 1.61 1.623v-12.388zm-5 4c.552 0 1 .449 1 1s-.448 1-1 1-1-.449-1-1 .448-1 1-1zm0-1c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6.708.292l-.708.708v3.097l2-2.065-1.292-1.74zm-12.675 9.294l-1.414 1.414h-2.619v2h-2v2h-2v-2.17l5.636-5.626-1.417-1.407-6.219 6.203v5h6v-2h2v-2h2l1.729-1.729-1.696-1.685z"></path>
                      </svg>
                      <span>Profil</span>
                    </button>
                  </li>
                  <li>
                    <button>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.598 9h-1.055c1.482-4.638 5.83-8 10.957-8 6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5c-5.127 0-9.475-3.362-10.957-8h1.055c1.443 4.076 5.334 7 9.902 7 5.795 0 10.5-4.705 10.5-10.5s-4.705-10.5-10.5-10.5c-4.568 0-8.459 2.923-9.902 7zm12.228 3l-4.604-3.747.666-.753 6.112 5-6.101 5-.679-.737 4.608-3.763h-14.828v-1h14.826z"></path>
                      </svg>
                      <span>Sign Out</span>
                    </button>
                  </li>
                </ul>
              </nav>
            </label>
         
        
      
    
    
          </div>
        </div>
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
<body>
  
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

<head>
  <style>
        :root {
            --primary-color: #975af4;
            --secondary-color: #2f7cf8;
            --accent-color: #78aafa;
            --dark-bg: #161a20;
            --text-light: #ffffff;
            --text-muted: #838383;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .event-card {
            background: linear-gradient(
                to top right,
                var(--primary-color),
                var(--secondary-color) 40%,
                var(--accent-color) 65%,
                #934cff 100%
            );
            padding: 4px;
            border-radius: 32px;
            transition: transform 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
        }
        
        .card-content {
            background-color: var(--dark-bg);
            border-radius: 30px;
            padding: 1.5rem;
            height: 100%;
            color: var(--text-muted);
        }
        
        .event-title {
            color: var(--text-light);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-title svg {
            width: 20px;
            height: 20px;
        }
        
        .event-detail {
            display: flex;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .detail-label {
            font-weight: 500;
            min-width: 80px;
            color: #bab9b9;
        }
        
        .detail-value {
            color: var(--text-light);
            word-break: break-word;
        }
        
        .event-link {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .event-link:hover {
            color: #a7c6ff;
            text-decoration: underline;
        }
        
        .no-events {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .error-message {
            grid-column: 1 / -1;
            padding: 2rem;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
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
            <form method="POST">
                <h2>Create New Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link">
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
            <form method="POST">
                <input type="hidden" name="id" id="editId">
                <h2>Edit Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="editDate" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="editLocation" required>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="id" id="deleteId">
                <h2>Confirm Deletion</h2>
                <p>Are you sure you want to delete this event?</p>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">
                        Cancel
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                    <button type="submit" class="btn-confirm" name="delete">
                        Delete
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

        function showEditForm(id, title, date, location, link) {
            document.getElementById('editId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDate').value = date;
            document.getElementById('editLocation').value = location;
            document.getElementById('editLink').value = link;
            showModal('editModal');
        }

        function confirmDelete(id) {
            document.getElementById('deleteId').value = id;
            showModal('deleteModal');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }

        // Add clicked state to all buttons with arrows
        document.querySelectorAll('button').forEach(button => {
            if (button.querySelector('.arrow-wrapper')) {
                button.addEventListener('click', function() {
                    this.classList.toggle('clicked');
                    // Remove the class after animation completes
                    setTimeout(() => {
                        this.classList.remove('clicked');
                    }, 300);
                });
            }
        });
    </script>
    
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
  <div class="text-center mt-3">
    <button class="btn btn-outline-primary" id="loadMoreBtn">Afficher plus</button>
  </div>
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