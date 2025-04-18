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
        
        /* Validation Styles */
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
    </style>
</head>
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
                                <path fill="currentColor" d="M10.277 16.515c.005-.11.187-.154.24-.058c.254.45.686 1.111 1.177 1.412c.49.3 1.275.386 1.791.408c.11.005.154.186.058.24c-.45.254-1.111.686-1.412 1.176s-.386 1.276-.408 1.792c-.005.11-.187.153-.24.057c-.254-.45-.686-1.11-1.176-1.411s-1.276-.386-1.792-.408c-.11-.005-.153-.187-.057-.24c.45-.254 1.11-.686 1.411-1.177c.301-.49.386-1.276.408-1.791m8.215-1c-.008-.11-.2-.156-.257-.062c-.172.283-.421.623-.697.793s-.693.236-1.023.262c-.11.008-.155.2-.062.257c.283.172.624.42.793.697s.237.693.262 1.023c.009.11.2.155.258.061c.172-.282.42-.623.697-.792s.692-.237 1.022-.262c.11-.009.156-.2.062-.258c-.283-.172-.624-.42-.793-.697s-.236-.692-.262-1.022M14.704 4.002l-.242-.306c-.937-1.183-1.405-1.775-1.95-1.688c-.545.088-.806.796-1.327 2.213l-.134.366c-.149.403-.223.604-.364.752c-.143.148-.336.225-.724.38l-.353.141l-.248.1c-1.2.48-1.804.753-1.881 1.283c-.082.565.49 1.049 1.634 2.016l.296.25c.325.275.488.413.58.6c.094.187.107.403.134.835l.024.393c.093 1.52.14 2.28.634 2.542s1.108-.147 2.336-.966l.318-.212c.35-.233.524-.35.723-.381c.2-.032.402.024.806.136l.368.102c1.422.394 2.133.591 2.52.188c.388-.403.196-1.14-.19-2.613l-.099-.381c-.11-.419-.164-.628-.134-.835s.142-.389.365-.752l.203-.33c.786-1.276 1.179-1.914.924-2.426c-.254-.51-.987-.557-2.454-.648l-.379-.024c-.417-.026-.625-.039-.806-.135c-.18-.096-.314-.264-.58-.6m-5.869 9.324C6.698 14.37 4.919 16.024 4.248 18c-.752-4.707.292-7.747 1.965-9.637c.144.295.332.539.5.73c.35.396.852.82 1.362 1.251l.367.310l.170.145c.005.064.010.14.015.237l.030.485c.040.655.080 1.294.178 1.805"/>
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
            <form id="createForm" method="POST" action="index.php">
                <h2>Create New Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="createTitle" required>
                    <div class="validation-message error" id="titleValidation"></div>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="createDate" required>
                    <div class="validation-message error" id="dateValidation"></div>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="createLocation" required>
                    <div class="validation-message error" id="locationValidation"></div>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link" id="createLink">
                    <div class="validation-message error" id="linkValidation"></div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('createModal')">
                        Cancel
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                    <button type="submit" class="btn-submit" name="create" id="createSubmit">
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
            <form id="editForm" method="POST" action="index.php">
                <input type="hidden" name="id" id="editId">
                <h2>Edit Event</h2>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="editTitle" required>
                    <div class="validation-message error" id="editTitleValidation"></div>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="event_date" id="editDate" required>
                    <div class="validation-message error" id="editDateValidation"></div>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="editLocation" required>
                    <div class="validation-message error" id="editLocationValidation"></div>
                </div>
                <div class="form-group">
                    <label>Link (optional)</label>
                    <input type="url" name="link" id="editLink">
                    <div class="validation-message error" id="editLinkValidation"></div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('editModal')">
                        Cancel
                        <div class="arrow-wrapper">
                            <div class="arrow"></div>
                        </div>
                    </button>
                    <button type="submit" class="btn-submit" name="update" id="editSubmit">
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
            
            // Reset validation messages
            document.querySelectorAll('#editForm .validation-message').forEach(el => {
                el.textContent = '';
                el.className = 'validation-message';
            });
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

        // Form validation for create form
        document.getElementById('createForm').addEventListener('submit', function(e) {
            let isValid = validateForm('create');
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        // Form validation for edit form
        document.getElementById('editForm').addEventListener('submit', function(e) {
            let isValid = validateForm('edit');
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        // Helper function to validate forms
        function validateForm(formPrefix) {
            let isValid = true;
            const prefix = formPrefix === 'create' ? 'create' : 'edit';

            // Title validation
            const title = document.getElementById(`${prefix}Title`).value.trim();
            const titleRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!titleRegex.test(title)) {
                document.getElementById(`${prefix}TitleValidation`).textContent = 'The title must contain at least 3 alphabetic characters.';
                document.getElementById(`${prefix}TitleValidation`).className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById(`${prefix}TitleValidation`).textContent = '✓ Valid';
                document.getElementById(`${prefix}TitleValidation`).className = 'validation-message success';
            }

            // Date validation
            const eventDate = document.getElementById(`${prefix}Date`).value;
            if (!eventDate) {
                document.getElementById(`${prefix}DateValidation`).textContent = 'Please select a date.';
                document.getElementById(`${prefix}DateValidation`).className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById(`${prefix}DateValidation`).textContent = '✓ Valid';
                document.getElementById(`${prefix}DateValidation`).className = 'validation-message success';
            }

            // Location validation
            const location = document.getElementById(`${prefix}Location`).value.trim();
            const locationRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!locationRegex.test(location)) {
                document.getElementById(`${prefix}LocationValidation`).textContent = 'The location must contain at least 3 alphabetic characters.';
                document.getElementById(`${prefix}LocationValidation`).className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById(`${prefix}LocationValidation`).textContent = '✓ Valid';
                document.getElementById(`${prefix}LocationValidation`).className = 'validation-message success';
            }

            // Link validation (optional)
            const link = document.getElementById(`${prefix}Link`).value.trim();
            if (link && !isValidUrl(link)) {
                document.getElementById(`${prefix}LinkValidation`).textContent = 'Please enter a valid URL.';
                document.getElementById(`${prefix}LinkValidation`).className = 'validation-message error';
                isValid = false;
            } else if (link) {
                document.getElementById(`${prefix}LinkValidation`).textContent = '✓ Valid';
                document.getElementById(`${prefix}LinkValidation`).className = 'validation-message success';
            } else {
                document.getElementById(`${prefix}LinkValidation`).textContent = '';
                document.getElementById(`${prefix}LinkValidation`).className = 'validation-message';
            }

            return isValid;
        }

        // Helper function to validate URL
        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }

        // Show success message if redirected with action
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            
            if(action === 'created') {
                alert('Event created successfully!');
            } else if(action === 'updated') {
                alert('Event updated successfully!');
            } else if(action === 'deleted') {
                alert('Event deleted successfully!');
            }
        };
    </script>
</body>
</html>