<?php
ob_start();
?>
<h2>Reset Password</h2>
<form method="POST" action="/web-app/public/?route=reset">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary">Send Reset Link</button>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>