<?php
$db = new Database();
$id = (int)($_POST['id'] ?? 0);

if ($id > 0) {
    $db->execute('DELETE FROM recipes WHERE id = ?', [$id]);
}

header('Location: /');
exit;