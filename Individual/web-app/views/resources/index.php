<?php
ob_start();
?>
<h2>Public Resources</h2>
<div class="row">
    <?php foreach ($resources as $resource): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($resource['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($resource['description']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($resource['category']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($resource['status']); ?></p>
                    <p><strong>Priority:</strong> <?php echo htmlspecialchars($resource['priority']); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>