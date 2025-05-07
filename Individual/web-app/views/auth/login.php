<?php
ob_start();
?>
<h2>Login</h2>
<form id="loginForm" method="POST" action="/web-app/public/?route=login">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <a href="/web-app/public/?route=reset">Forgot Password?</a>
</form>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>