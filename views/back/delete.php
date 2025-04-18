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
                        <h2>Delete Event</h2>
                        <a href="index.php?action=index" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Events
                        </a>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <div class="event-details mb-4">
                        <h4><?= htmlspecialchars($event['title']) ?></h4>
                        <p><strong>Date:</strong> <?= date('M j, Y', strtotime($event['event_date'])) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                        <?php if ($event['link']): ?>
                            <p><strong>Link:</strong> <a href="<?= htmlspecialchars($event['link']) ?>" target="_blank">View Link</a></p>
                        <?php endif; ?>
                    </div>
                    
                    <form method="POST" action="index.php?action=delete&id=<?= $event['id'] ?>">
                        <div class="confirmation-dialog">
                            <p class="text-danger"><strong>Are you sure you want to delete this event?</strong></p>
                            <button type="submit" name="confirm" value="yes" class="btn btn-danger">
                                <i class="fa fa-trash me-2"></i>Yes, Delete
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

<?php 
require_once BASE_PATH . '/views/layouts/footer.php'; 
?>