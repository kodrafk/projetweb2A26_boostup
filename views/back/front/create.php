<?php
// create.php
require_once 'config/database.php';
require_once 'models/Event.php';

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event->title = $_POST['title'];
    $event->event_date = $_POST['event_date'];
    $event->location = $_POST['location'];
    $event->link = $_POST['link'] ?? null;

    if($event->create()) {
        header("Location: display.php?success=created");
        exit();
    } else {
        $error = "Failed to create event";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Create New Event</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="mb-3">
                                <label for="link" class="form-label">Registration Link (optional)</label>
                                <input type="url" class="form-control" id="link" name="link">
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="display.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            let isValid = true;

            // Title validation
            const title = document.getElementById('title').value.trim();
            const titleRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!titleRegex.test(title)) {
                alert('The title must contain at least 3 alphabetic characters.');
                isValid = false;
            }

            // Location validation
            const location = document.getElementById('location').value.trim();
            const locationRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
            if (!locationRegex.test(location)) {
                alert('The location must contain at least 3 alphabetic characters.');
                isValid = false;
            }

            // Link validation (optional)
            const link = document.getElementById('link').value.trim();
            if (link && !isValidUrl(link)) {
                alert('Please enter a valid URL.');
                isValid = false;
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
    </script>
</body>
</html>