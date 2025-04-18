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
                            <div class="invalid-feedback">The title must start with at least 3 letters.</div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                            <div class="invalid-feedback">The location must start with at least 3 letters.</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('eventForm');

    const startsWithThreeLetters = value => /^[A-Za-z]{3}/.test(value);

    // Real-time validation
    document.getElementById('title').addEventListener('input', function() {
        this.classList.toggle('is-invalid', !startsWithThreeLetters(this.value));
    });

    document.getElementById('location').addEventListener('input', function() {
        this.classList.toggle('is-invalid', !startsWithThreeLetters(this.value));
    });

    document.getElementById('event_date').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const isPast = selectedDate < today;
        this.classList.toggle('is-invalid', isPast);
        if (isPast) {
            this.nextElementSibling.textContent = "Event date must be in the future.";
        }
    });

    document.getElementById('link').addEventListener('input', function() {
        if (this.value) {
            const isValid = this.value.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/);
            this.classList.toggle('is-invalid', !isValid);
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;

        const title = document.getElementById('title');
        if (!startsWithThreeLetters(title.value)) {
            title.classList.add('is-invalid');
            isValid = false;
        }

        const location = document.getElementById('location');
        if (!startsWithThreeLetters(location.value)) {
            location.classList.add('is-invalid');
            isValid = false;
        }

        const eventDate = document.getElementById('event_date');
        const selectedDate = new Date(eventDate.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            eventDate.classList.add('is-invalid');
            eventDate.nextElementSibling.textContent = "Event date must be in the future.";
            isValid = false;
        }

        const link = document.getElementById('link');
        if (link.value && !link.value.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)) {
            link.classList.add('is-invalid');
            isValid = false;
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
