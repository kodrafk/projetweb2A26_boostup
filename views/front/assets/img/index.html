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
            color: #334155;
            padding: 2rem;
        }
        
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
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 90%;
            max-width: 600px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
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
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .form-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-submit {
            background: #725AC1;
            color: white;
        }
        
        .btn-submit:hover {
            background: #5e4baa;
        }
        
        .btn-cancel {
            background: #e2e8f0;
            color: #334155;
        }
        
        .btn-cancel:hover {
            background: #cbd5e1;
        }
        
        /* Confirmation Dialog */
        .confirmation-dialog {
            text-align: center;
        }
        
        .confirmation-message {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .confirmation-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .btn-confirm {
            background: #EF4444;
            color: white;
        }
        
        .btn-confirm:hover {
            background: #dc2626;
        }
        
        .btn-deny {
            background: #e2e8f0;
            color: #334155;
        }
        
        .btn-deny:hover {
            background: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Events Management</h1>
            <button class="btn btn-create" onclick="showCreateForm()">CREATE EVENT</button>
        </div>

        <div id="eventsList">
            <?php if(count($events) > 0): ?>
                <?php foreach($events as $event): ?>
                    <div class="billing-card">
                        <h2><?= htmlspecialchars($event['title']) ?></h2>
                        <div class="billing-detail">
                            <span class="detail-label">Event Date:</span>
                            <span class="detail-value"><?= date('F j, Y', strtotime($event['event_date'])) ?></span>
                        </div>
                        <div class="billing-detail">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value"><?= htmlspecialchars($event['location']) ?></span>
                        </div>
                        <div class="billing-detail">
                            <span class="detail-label">Link:</span>
                            <span class="detail-value link-value">
                                <?= $event['link'] ? '<a href="'.htmlspecialchars($event['link']).'" target="_blank">'.htmlspecialchars($event['link']).'</a>' : 'No link provided' ?>
                            </span>
                        </div>
                        <div class="billing-detail">
                            <span class="detail-label">Created At:</span>
                            <span class="detail-value"><?= date('F j, Y, g:i a', strtotime($event['created_at'])) ?></span>
                        </div>
                        
                        <div class="card-footer">
                            <button class="btn btn-delete" onclick="confirmDelete(<?= $event['id'] ?>)">DELETE</button>
                            <button class="btn btn-edit" onclick="showEditForm(
                                <?= $event['id'] ?>, 
                                '<?= addslashes($event['title']) ?>', 
                                '<?= $event['event_date'] ?>', 
                                '<?= addslashes($event['location']) ?>', 
                                '<?= addslashes($event['link']) ?>'
                            )">EDIT</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="billing-card">
                    <h2>No Events Found</h2>
                    <p>Create your first event using the button above.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Event Form Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Event</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="eventForm" method="POST" action="index.php">
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
                
                <div class="form-actions">
                    <button type="button" class="form-btn btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="form-btn btn-submit" id="submitBtn" name="create">Save Event</button>
                    <button type="submit" class="form-btn btn-submit" id="updateBtn" name="update" style="display:none;">Update Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="confirmation-dialog">
                <h2>Confirm Deletion</h2>
                <p class="confirmation-message">Are you sure you want to delete this event?</p>
                <div class="confirmation-actions">
                    <button class="form-btn btn-deny" onclick="closeDeleteModal()">No, Keep It</button>
                    <a id="confirmDeleteLink" href="#"><button class="form-btn btn-confirm">Yes, Delete</button></a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show create form
        function showCreateForm() {
            document.getElementById('modalTitle').textContent = 'Add New Event';
            document.getElementById('eventId').value = '';
            document.getElementById('eventForm').reset();
            document.getElementById('submitBtn').style.display = 'block';
            document.getElementById('updateBtn').style.display = 'none';
            document.getElementById('eventModal').style.display = 'flex';
            
            // Reset validation messages
            document.querySelectorAll('.validation-message').forEach(el => {
                el.textContent = '';
                el.className = 'validation-message';
            });
        }

        // Show edit form
        function showEditForm(id, title, event_date, location, link) {
            document.getElementById('modalTitle').textContent = 'Edit Event';
            document.getElementById('eventId').value = id;
            document.getElementById('title').value = title;
            document.getElementById('event_date').value = event_date;
            document.getElementById('location').value = location;
            document.getElementById('link').value = link;
            document.getElementById('submitBtn').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'block';
            document.getElementById('eventModal').style.display = 'flex';
            
            // Reset validation messages
            document.querySelectorAll('.validation-message').forEach(el => {
                el.textContent = '';
                el.className = 'validation-message';
            });
        }

        // Show delete confirmation
        function confirmDelete(id) {
            document.getElementById('confirmDeleteLink').href = 'index.php?delete_id=' + id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        // Close modal
        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Form validation
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            let isValid = true;

            // Title validation
            const title = document.getElementById('title').value.trim();
            const titleRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!titleRegex.test(title)) {
                document.getElementById('titleValidation').textContent = 'The title must contain at least 3 alphabetic characters.';
                document.getElementById('titleValidation').className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById('titleValidation').textContent = '✓ Valid';
                document.getElementById('titleValidation').className = 'validation-message success';
            }

            // Date validation
            const eventDate = document.getElementById('event_date').value;
            if (!eventDate) {
                document.getElementById('dateValidation').textContent = 'Please select a date.';
                document.getElementById('dateValidation').className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById('dateValidation').textContent = '✓ Valid';
                document.getElementById('dateValidation').className = 'validation-message success';
            }

            // Location validation
            const location = document.getElementById('location').value.trim();
            const locationRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!locationRegex.test(location)) {
                document.getElementById('locationValidation').textContent = 'The location must contain at least 3 alphabetic characters.';
                document.getElementById('locationValidation').className = 'validation-message error';
                isValid = false;
            } else {
                document.getElementById('locationValidation').textContent = '✓ Valid';
                document.getElementById('locationValidation').className = 'validation-message success';
            }

            // Link validation (optional)
            const link = document.getElementById('link').value.trim();
            if (link && !isValidUrl(link)) {
                document.getElementById('linkValidation').textContent = 'Please enter a valid URL.';
                document.getElementById('linkValidation').className = 'validation-message error';
                isValid = false;
            } else if (link) {
                document.getElementById('linkValidation').textContent = '✓ Valid';
                document.getElementById('linkValidation').className = 'validation-message success';
            } else {
                document.getElementById('linkValidation').textContent = '';
                document.getElementById('linkValidation').className = 'validation-message';
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

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