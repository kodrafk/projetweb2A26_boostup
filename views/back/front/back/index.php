<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "projetweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        // Create new event
        $title = $_POST['title'];
        $event_date = $_POST['event_date'];
        $location = $_POST['location'];
        $link = $_POST['link'];
        
        $stmt = $conn->prepare("INSERT INTO events (title, event_date, location, link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $event_date, $location, $link);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Update existing event
        $id = $_POST['id'];
        $title = $_POST['title'];
        $event_date = $_POST['event_date'];
        $location = $_POST['location'];
        $link = $_POST['link'];
        
        $stmt = $conn->prepare("UPDATE events SET title=?, event_date=?, location=?, link=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $event_date, $location, $link, $id);
        $stmt->execute();
        $stmt->close();
    }
} elseif (isset($_GET['delete'])) {
    // Delete event
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Get all events
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");

// Define base path if not already defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Function to escape HTML output
function escapeHtml($unsafe) {
    if (!$unsafe) return '';
    return htmlspecialchars($unsafe, ENT_QUOTES, 'UTF-8');
}

// Function to format date
function formatDate($dateString) {
    return date('F j, Y', strtotime($dateString));
}

// Function to format datetime
function formatDateTime($dateTimeString) {
    return date('F j, Y, g:i a', strtotime($dateTimeString));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Boostup</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="images/favicon.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        /* Modern CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            padding: 2rem;
        }
        
        /* Billing Page Styles */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        .billing-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .billing-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        
        .billing-detail {
            display: flex;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .detail-label {
            font-weight: 500;
            color: #64748b;
            min-width: 120px;
        }
        
        .detail-value {
            color: #334155;
            font-weight: 400;
        }
        
        .card-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Button Styles */
        .btn {
          position: relative;
          display: inline-block;
          padding: 12px 25px;
          text-align: center;
          font-size: 16px;
          letter-spacing: 1px;
          text-decoration: none;
          background: transparent;
          cursor: pointer;
          transition: ease-out 0.5s;
          border: 2px solid;
          border-radius: 10px;
          box-shadow: inset 0 0 0 0;
          font-weight: 500;
          margin: 0;
        }

        .btn:hover {
          color: white;
          box-shadow: inset 0 -100px 0 0;
        }

        .btn:active {
          transform: scale(0.9);
        }
        
        /* Button Variations */
        .btn-create {
          color: #10B981;
          border-color: #10B981;
        }
        
        .btn-create:hover {
          box-shadow: inset 0 -100px 0 0 #10B981;
        }
        
        .btn-delete {
          color: #EF4444;
          border-color: #EF4444;
        }
        
        .btn-delete:hover {
          box-shadow: inset 0 -100px 0 0 #EF4444;
        }
        
        .btn-edit {
          color: #725AC1;
          border-color: #725AC1;
        }
        
        .btn-edit:hover {
          box-shadow: inset 0 -100px 0 0 #725AC1;
        }
        
        /* Form Page Styles (hidden by default) */
        #eventFormPage {
            display: none;
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #475569;
        }
        
        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-group input:focus, 
        .form-group select:focus {
            outline: none;
            border-color: #725AC1;
        }
        
        .validation-message {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            height: 1.25rem;
        }
        
        .error {
            color: #dc2626;
        }
        
        .success {
            color: #16a34a;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        
        .checkbox-group input {
            width: auto;
            margin-right: 0.75rem;
        }
        
        .form-btn {
            background-color: #725AC1;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        
        .form-btn:hover {
            background-color: #5e4baa;
        }
        
        .form-btn:active {
            transform: scale(0.98);
        }
        
        .back-btn {
            background-color: #e2e8f0;
            color: #334155;
            margin-bottom: 1rem;
        }
        
        .back-btn:hover {
            background-color: #cbd5e1;
        }
        
        /* Spinner styles */
        #spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BoostUp</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Jhon Doe</h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.html" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <!-- Gestionne Projets -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Projets</a> 
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="categorieProjet.html" class="dropdown-item">Categorie Projet</a> 
                            <a href="projet.html" class="dropdown-item">Projet</a> 
                        </div>
                    </div>
                
                    <!-- Gestionne Objectifs -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-th me-2"></i>Objectifs</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="tache.html" class="dropdown-item">Tache</a>
                            <a href="objectif.html" class="dropdown-item">Objectif</a>
                        </div>
                    </div>
                
                    <!-- Gestionne Ressources -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-keyboard me-2"></i>Ressources</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="ressource.html" class="dropdown-item">Ressource</a>
                            <a href="thematique.html" class="dropdown-item">Thématique</a>
                        </div>
                    </div>
                
                    <!-- Gestionne Evenements -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-table me-2"></i> Evenements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="evenement.php" class="dropdown-item">Evenement</a>
                            <a href="opportunite.html" class="dropdown-item">Opportunité</a>
                        </div>
                    </div>
                
                    <!-- Gestionne Communauté -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i>Communautes</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="questionne.html" class="dropdown-item">Questionne</a>
                            <a href="reponse.html" class="dropdown-item">Reponse</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">John Doe</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

         <!-- Events Content -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div id="eventsPage" class="billing-container">
            <div class="billing-header">
                <button class="btn btn-create" id="showFormBtn">CREATE EVENT</button>
                <h1 class="billing-title">Events Management</h1>
            </div>

            <div id="eventsList">
                <?php if ($events->num_rows > 0): ?>
                    <?php while($event = $events->fetch_assoc()): ?>
                        <div class="billing-card">
                            <h2><?php echo escapeHtml($event['title']); ?></h2>
                            <div class="billing-detail">
                                <span class="detail-label">Event Date:</span>
                                <span class="detail-value"><?php echo formatDate($event['event_date']); ?></span>
                            </div>
                            <div class="billing-detail">
                                <span class="detail-label">Location:</span>
                                <span class="detail-value"><?php echo escapeHtml($event['location']); ?></span>
                            </div>
                            <div class="billing-detail">
                                <span class="detail-label">Link:</span>
                                <span class="detail-value link-value">
                                    <?php if (!empty($event['link'])): ?>
                                        <a href="<?php echo escapeHtml($event['link']); ?>" target="_blank"><?php echo escapeHtml($event['link']); ?></a>
                                    <?php else: ?>
                                        No link provided
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="billing-detail">
                                <span class="detail-label">Created At:</span>
                                <span class="detail-value"><?php echo formatDateTime($event['created_at']); ?></span>
                            </div>
                            
                            <div class="card-footer">
                                <a href="?delete=<?php echo $event['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">DELETE</a>
                                <button class="btn btn-edit edit-event" 
                                    data-id="<?php echo $event['id']; ?>"
                                    data-title="<?php echo escapeHtml($event['title']); ?>"
                                    data-event_date="<?php echo $event['event_date']; ?>"
                                    data-location="<?php echo escapeHtml($event['location']); ?>"
                                    data-link="<?php echo escapeHtml($event['link']); ?>">
                                    EDIT
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="billing-card">
                        <h2>No Events Found</h2>
                        <p>Create your first event using the button above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Event Form (hidden by default) -->
        <div id="eventFormPage" class="event-form-container" style="display:none;">
            <button class="form-btn back-btn" id="backToEventsBtn">← Back to Events</button>
            <h1 id="formTitle">Add New Event</h1>
            
            <form id="eventForm" method="POST">
                <input type="hidden" id="eventId" name="id" value="">
                
                <div class="form-group">
                    <label for="title">Event Title:</label>
                    <input type="text" id="title" name="title" placeholder="e.g. Annual Conference" required>
                    <div class="validation-message error" id="titleValidation"></div>
                </div>
                
                <div class="form-group">
                    <label for="event_date">Event Date:</label>
                    <input type="date" id="event_date" name="event_date" required>
                    <div class="validation-message error" id="dateValidation"></div>
                </div>
                
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" placeholder="e.g. Convention Center, New York" required>
                    <div class="validation-message error" id="locationValidation"></div>
                </div>
                
                <div class="form-group">
                    <label for="link">Registration Link (optional):</label>
                    <input type="url" id="link" name="link" placeholder="e.g. https://example.com/register">
                    <div class="validation-message error" id="linkValidation"></div>
                </div>
                
                <button type="submit" class="form-btn" name="create" id="submitBtn">Create Event</button>
                <button type="submit" class="form-btn" name="update" id="updateBtn" style="display:none;">Update Event</button>
            </form>
        </div>
    </div>
</div>

<!-- Spinner -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
</div>

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template Javascript -->
<script>
const eventsPage = document.getElementById('eventsPage');
const eventFormPage = document.getElementById('eventFormPage');
const showFormBtn = document.getElementById('showFormBtn');
const backToEventsBtn = document.getElementById('backToEventsBtn');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');
const updateBtn = document.getElementById('updateBtn');
const eventForm = document.getElementById('eventForm');
const eventId = document.getElementById('eventId');
const spinner = document.getElementById('spinner');

// Show form for creating new event
showFormBtn.addEventListener('click', function () {
    eventsPage.style.display = 'none';
    eventFormPage.style.display = 'block';
    formTitle.textContent = 'Add New Event';
    submitBtn.style.display = 'block';
    updateBtn.style.display = 'none';
    eventForm.reset();
    eventId.value = '';
});

// Back to event list
backToEventsBtn.addEventListener('click', function () {
    eventFormPage.style.display = 'none';
    eventsPage.style.display = 'block';
});

// Edit button handler
document.querySelectorAll('.edit-event').forEach(button => {
    button.addEventListener('click', function () {
        eventsPage.style.display = 'none';
        eventFormPage.style.display = 'block';
        formTitle.textContent = 'Edit Event';
        submitBtn.style.display = 'none';
        updateBtn.style.display = 'block';

        eventId.value = this.dataset.id;
        document.getElementById('title').value = this.dataset.title;
        document.getElementById('event_date').value = this.dataset.event_date;
        document.getElementById('location').value = this.dataset.location;
        document.getElementById('link').value = this.dataset.link;
    });
});

// Validation
eventForm.addEventListener('submit', function (e) {
    let isValid = true;

    const title = document.getElementById('title').value.trim();
    const eventDate = document.getElementById('event_date').value;
    const location = document.getElementById('location').value.trim();
    const link = document.getElementById('link').value.trim();

    if (title.length < 3) {
        document.getElementById('titleValidation').textContent = 'The title must contain at least 3 characters.';
        isValid = false;
    } else {
        document.getElementById('titleValidation').textContent = '';
    }

    if (!eventDate) {
        document.getElementById('dateValidation').textContent = 'Please select a date.';
        isValid = false;
    } else {
        document.getElementById('dateValidation').textContent = '';
    }

    if (location.length < 3) {
        document.getElementById('locationValidation').textContent = 'The location must contain at least 3 characters.';
        isValid = false;
    } else {
        document.getElementById('locationValidation').textContent = '';
    }

    if (link && !isValidUrl(link)) {
        document.getElementById('linkValidation').textContent = 'Please enter a valid URL.';
        isValid = false;
    } else {
        document.getElementById('linkValidation').textContent = '';
    }

    if (!isValid) {
        e.preventDefault();
    } else {
        showSpinner();
    }
});

// URL validation
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

// Spinner control
function showSpinner() {
    spinner.style.display = 'flex';
}

function hideSpinner() {
    spinner.style.display = 'none';
}

window.addEventListener('load', hideSpinner);
</script>

</body>
</html>

<?php
$conn->close();
?>
