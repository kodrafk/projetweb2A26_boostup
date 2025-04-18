<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

// Database connection (example - adjust to your setup)
require_once BASE_PATH . '/config/database.php';

// Helper functions
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

function validateText($input, $fieldName, $minLength = 3, $maxLength = 255, $allowNumbers = false) {
    if (empty($input)) {
        return "$fieldName is required.";
    }
    if (strlen($input) < $minLength) {
        return "$fieldName must be at least $minLength characters.";
    }
    if (strlen($input) > $maxLength) {
        return "$fieldName cannot exceed $maxLength characters.";
    }
    if (!$allowNumbers && preg_match('/\d/', $input)) {
        return "$fieldName cannot contain numbers.";
    }
    return null;
}

function validateDate($date, $minDays = 1, $maxYears = 2) {
    if (empty($date)) {
        return "Date is required.";
    }
    
    $selectedDate = new DateTime($date);
    $today = new DateTime('today');
    $maxDate = new DateTime("+$maxYears years");
    
    if ($selectedDate < $today->modify("+$minDays day")) {
        return "Date must be at least $minDays day(s) in the future.";
    }
    if ($selectedDate > $maxDate) {
        return "Date cannot be more than $maxYears years in the future.";
    }
    return null;
}

function validateURL($url) {
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Please enter a valid URL (must start with http:// or https://).";
    }
    return null;
}

// Process form submission
$error = null;
$event = []; // Initialize empty event array

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit') {
    // Sanitize all inputs
    $title = sanitizeInput($_POST['title'] ?? '');
    $event_date = sanitizeInput($_POST['event_date'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $link = sanitizeInput($_POST['link'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $category = sanitizeInput($_POST['category'] ?? '');

    // Validate all fields
    $error = validateText($title, 'Title', 3, 255, false);
    if (!$error) $error = validateDate($event_date);
    if (!$error) $error = validateText($location, 'Location', 3, 255, true); // Allow numbers in location
    if (!$error) $error = validateURL($link);
    if (!$error && empty($category)) $error = "Please select a category.";

    // If validation passes, update the event
    if (!$error) {
        try {
            // Example database update - replace with your actual code
            $stmt = $pdo->prepare("UPDATE events SET 
                                  title = :title, 
                                  event_date = :event_date, 
                                  location = :location, 
                                  link = :link, 
                                  is_active = :is_active, 
                                  category = :category 
                                  WHERE id = :id");
            
            $stmt->execute([
                ':title' => $title,
                ':event_date' => $event_date,
                ':location' => $location,
                ':link' => $link,
                ':is_active' => $is_active,
                ':category' => $category,
                ':id' => $_GET['id']
            ]);
            
            // Redirect to prevent form resubmission
            header("Location: index.php?action=index");
            exit;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
} elseif (isset($_GET['id'])) {
    // Load existing event data for editing
    try {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$event) {
            header("Location: index.php?action=index");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

require_once BASE_PATH . '/views/layouts/header.php';
?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><?= isset($event['id']) ? 'Edit' : 'Create' ?> Event</h2>
                        <a href="index.php?action=index" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Events
                        </a>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?action=edit&id=<?= $event['id'] ?? '' ?>" id="eventForm" novalidate>
                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= $event['title'] ?? ($_POST['title'] ?? '') ?>" required
                                   pattern="^[A-Za-zÀ-ÿ\s\-\'\,\.]{3,}$"
                                   title="Only letters, spaces, and basic punctuation allowed">
                            <div class="invalid-feedback">
                                Title must be 3-255 characters with only letters and basic punctuation.
                            </div>
                        </div>
                        
                        <!-- Date Field -->
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Date:</label>
                            <input type="date" class="form-control" id="event_date" name="event_date" 
                                   value="<?= $event['event_date'] ?? ($_POST['event_date'] ?? '') ?>" required
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   max="<?= date('Y-m-d', strtotime('+2 years')) ?>">
                            <div class="invalid-feedback">
                                Date must be between tomorrow and 2 years from now.
                            </div>
                        </div>
                        
                        <!-- Location Field -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?= $event['location'] ?? ($_POST['location'] ?? '') ?>" required
                                   pattern="^[A-Za-zÀ-ÿ0-9\s\-\'\,\.]{3,255}$"
                                   title="Letters, numbers, spaces, and basic punctuation allowed">
                            <div class="invalid-feedback">
                                Location must be 3-255 characters (letters, numbers, or basic punctuation).
                            </div>
                        </div>
                        
                        <!-- Link Field -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (optional):</label>
                            <input type="url" class="form-control" id="link" name="link" 
                                   value="<?= $event['link'] ?? ($_POST['link'] ?? '') ?>"
                                   pattern="https?://.+\..+" 
                                   title="Must be a valid URL starting with http:// or https://">
                            <div class="invalid-feedback">
                                Please enter a complete URL (e.g., https://example.com).
                            </div>
                        </div>
                        
                        <!-- Active Checkbox -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   <?= ($event['is_active'] ?? ($_POST['is_active'] ?? true)) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active Event</label>
                        </div>
                        
                        <!-- Category Select -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" disabled <?= empty($event['category'] ?? ($_POST['category'] ?? '')) ? 'selected' : '' ?>>Select a category</option>
                                <option value="Conference" <?= ($event['category'] ?? ($_POST['category'] ?? '')) === 'Conference' ? 'selected' : '' ?>>Conference</option>
                                <option value="Workshop" <?= ($event['category'] ?? ($_POST['category'] ?? '')) === 'Workshop' ? 'selected' : '' ?>>Workshop</option>
                                <option value="Seminar" <?= ($event['category'] ?? ($_POST['category'] ?? '')) === 'Seminar' ? 'selected' : '' ?>>Seminar</option>
                                <option value="Social" <?= ($event['category'] ?? ($_POST['category'] ?? '')) === 'Social' ? 'selected' : '' ?>>Social</option>
                                <option value="Other" <?= ($event['category'] ?? ($_POST['category'] ?? '')) === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid category.
                            </div>
                        </div>
                        
                        <div class="confirmation-dialog">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Event
                            </button>
                            <a href="index.php?action=index" class="btn btn-outline-secondary">
                                <i class="fa fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Javascript form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('eventForm');
    
    // Validate Title
    const validateTitle = (input) => {
        const regex = /^[A-Za-zÀ-ÿ\s\-\'\,\.]{3,}$/;
        const isValid = regex.test(input.value) && input.value.length <= 255;
        input.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    // Validate Date
    const validateDate = (input) => {
        const selectedDate = new Date(input.value);
        const minDate = new Date();
        minDate.setDate(minDate.getDate() + 1); // Tomorrow
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() + 2); // 2 years from now
        const isValid = selectedDate >= minDate && selectedDate <= maxDate;
        input.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    // Validate Location
    const validateLocation = (input) => {
        const regex = /^[A-Za-zÀ-ÿ0-9\s\-\'\,\.]{3,}$/;
        const isValid = regex.test(input.value) && input.value.length <= 255;
        input.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    // Validate Link
    const validateLink = (input) => {
        if (!input.value) return true; // Optional field
        const regex = /^https?:\/\/.+\..+/i;
        const isValid = regex.test(input.value);
        input.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    // Validate Category
    const validateCategory = (input) => {
        const isValid = input.value !== "";
        input.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    // Real-time validation
    document.getElementById('title').addEventListener('input', function() {
        validateTitle(this);
    });

    document.getElementById('location').addEventListener('input', function() {
        validateLocation(this);
    });

    document.getElementById('event_date').addEventListener('change', function() {
        validateDate(this);
    });

    document.getElementById('link').addEventListener('input', function() {
        validateLink(this);
    });

    document.getElementById('category').addEventListener('change', function() {
        validateCategory(this);
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate all fields
        isValid = validateTitle(document.getElementById('title')) && isValid;
        isValid = validateDate(document.getElementById('event_date')) && isValid;
        isValid = validateLocation(document.getElementById('location')) && isValid;
        isValid = validateLink(document.getElementById('link')) && isValid;
        isValid = validateCategory(document.getElementById('category')) && isValid;

        if (!isValid) {
            e.preventDefault();
            // Scroll to the first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    });
});
</script>

<?php 
require_once BASE_PATH . '/views/layouts/footer.php';
?>
