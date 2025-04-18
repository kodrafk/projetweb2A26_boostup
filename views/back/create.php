<?php 
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}
require_once BASE_PATH . '/views/layouts/header.php'; 
?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Create New Event</h2>
                        <a href="index.php?action=index" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Events
                        </a>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?action=create" id="eventForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback">The title must contain at least 3 characters.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                            <div class="invalid-feedback">The location must contain at least 3 characters.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date:</label>
                            <input type="date" class="form-control" id="event_date" name="event_date" required>
                            <div class="invalid-feedback">Please select a valid date.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (optional):</label>
                            <input type="url" class="form-control" id="link" name="link">
                            <div class="invalid-feedback">Please enter a valid URL.</div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Active Event</label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <select class="form-select" id="category" name="category">
                                <option value="Conference">Conference</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Social">Social</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Create Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('eventForm');

    // Real-time validation
    document.getElementById('title').addEventListener('input', function () {
        const titleRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'You must fill out this field.';
        } else if (!titleRegex.test(this.value.trim())) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'The title must contain at least 3 alphabetic characters.';
        } else {
            this.classList.remove('is-invalid');
            this.nextElementSibling.textContent = '';
        }
    });

    document.getElementById('location').addEventListener('input', function () {
        const locationRegex = /^[a-zA-Z]{3,}/; // At least 3 alphabetic characters
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'You must fill out this field.';
        } else if (!locationRegex.test(this.value.trim())) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'The location must contain at least 3 alphabetic characters.';
        } else {
            this.classList.remove('is-invalid');
            this.nextElementSibling.textContent = '';
        }
    });

    document.getElementById('event_date').addEventListener('change', function () {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        this.classList.toggle('is-invalid', selectedDate < today);
        if (selectedDate < today) {
            this.nextElementSibling.textContent = "Event date must be in the future.";
        }
    });

    document.getElementById('link').addEventListener('input', function () {
        const urlRegex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        if (this.value.trim() && !urlRegex.test(this.value.trim())) {
            this.classList.add('is-invalid');
            this.nextElementSibling.textContent = 'Please enter a valid URL.';
        } else {
            this.classList.remove('is-invalid');
            this.nextElementSibling.textContent = '';
        }
    });

    // Form submission validation
    form.addEventListener('submit', function (e) {
        let isValid = true;

        // Validate title
        const title = document.getElementById('title');
        const titleRegex = /^[a-zA-Z]{3,}/;
        if (!title.value.trim()) {
            title.classList.add('is-invalid');
            title.nextElementSibling.textContent = 'You must fill out this field.';
            isValid = false;
        } else if (!titleRegex.test(title.value.trim())) {
            title.classList.add('is-invalid');
            title.nextElementSibling.textContent = 'The title must contain at least 3 alphabetic characters.';
            isValid = false;
        } else {
            title.classList.remove('is-invalid');
            title.nextElementSibling.textContent = '';
        }

        // Validate location
        const location = document.getElementById('location');
        const locationRegex = /^[a-zA-Z]{3,}/;
        if (!location.value.trim()) {
            location.classList.add('is-invalid');
            location.nextElementSibling.textContent = 'You must fill out this field.';
            isValid = false;
        } else if (!locationRegex.test(location.value.trim())) {
            location.classList.add('is-invalid');
            location.nextElementSibling.textContent = 'The location must contain at least 3 alphabetic characters.';
            isValid = false;
        } else {
            location.classList.remove('is-invalid');
            location.nextElementSibling.textContent = '';
        }

        // Validate date
        const eventDate = document.getElementById('event_date');
        const selectedDate = new Date(eventDate.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            eventDate.classList.add('is-invalid');
            eventDate.nextElementSibling.textContent = "Event date must be in the future.";
            isValid = false;
        }

        // Validate link
        const link = document.getElementById('link');
        const urlRegex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        if (link.value.trim() && !urlRegex.test(link.value.trim())) {
            link.classList.add('is-invalid');
            link.nextElementSibling.textContent = 'Please enter a valid URL.';
            isValid = false;
        } else {
            link.classList.remove('is-invalid');
            link.nextElementSibling.textContent = '';
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>

<?php 
require_once BASE_PATH . '/views/layouts/footer.php'; 
?>