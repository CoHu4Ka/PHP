<?php
ob_start();
?>
<h2>Create Resource</h2>
<form id="resourceForm" method="POST" action="/web-app/public/?route=create">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-control" id="category" name="category" required>
            <option value="Technology">Technology</option>
            <option value="Education">Education</option>
            <option value="Health">Health</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <div>
            <input type="radio" id="active" name="status" value="active" checked>
            <label for="active">Active</label>
            <input type="radio" id="inactive" name="status" value="inactive">
            <label for="inactive">Inactive</label>
        </div>
    </div>
    <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <input type="number" class="form-control" id="priority" name="priority" min="1" max="10" required>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>