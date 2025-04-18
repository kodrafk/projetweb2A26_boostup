<?php
// delete.php
require_once 'config/database.php';
require_once 'models/Event.php';

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);

$event->id = $_GET['id'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($event->delete()) {
        header("Location: display.php?success=deleted");
        exit();
    } else {
        $error = "Failed to delete event";
    }
}

// Read the event first to show confirmation
$event->readOne();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Delete Event</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <div class="alert alert-warning">
                            <h4>Are you sure you want to delete this event?</h4>
                            <p><strong>Title:</strong> <?= htmlspecialchars($event->title) ?></p>
                            <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event->event_date)) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($event->location) ?></p>
                        </div>

                        <form method="POST">
                            <div class="d-flex justify-content-between">
                                <a href="display.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-danger">Confirm Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>