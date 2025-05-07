<?php
ob_start();
?>
<h2>Search Resources</h2>
<form method="GET" action="/web-app/public/?route=search">
    <div class="mb-3">
        <label for="query" class="form-label">Search Query</label>
        <input type="text" class="form-control" id="query" name="query" value="<?php echo htmlspecialchars($query ?? ''); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>
<?php if (!empty($resources)): ?>
    <h3>Results</h3>
    <div class="row">
        <?php foreach ($resources as $resource): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($resource['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($resource['description']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>