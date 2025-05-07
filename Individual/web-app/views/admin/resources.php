<?php
ob_start();
?>
<h2>Manage Resources</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resources as $resource): ?>
            <tr>
                <td><?php echo htmlspecialchars($resource['id']); ?></td>
                <td><?php echo htmlspecialchars($resource['title']); ?></td>
                <td><?php echo htmlspecialchars($resource['category']); ?></td>
                <td>
                    <form method="POST" action="/?route=admin/resources" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $resource['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>