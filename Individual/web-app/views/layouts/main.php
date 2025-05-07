<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>/?route=home">Web App</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=search">Search</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=create">Create Resource</a></li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=admin/dashboard">Admin Dashboard</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/?route=register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php echo $content; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/scripts.js"></script>
</body>
</html>