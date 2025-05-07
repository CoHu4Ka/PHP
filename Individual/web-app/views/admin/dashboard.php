<?php
ob_start();
?>
<h2>Admin Dashboard</h2>
<ul>
    <li><a href="/web-app/public/?route=admin/users">Manage Users</a></li>
    <li><a href="/web-app/public/?route=admin/resources">Manage Resources</a></li>
</ul>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>