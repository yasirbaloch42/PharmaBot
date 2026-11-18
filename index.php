<?php
require_once 'includes/auth.php';
if (is_logged_in()) {
    $user = current_user();
    if ($user['role'] === 'admin') {
        header('Location: /admin/index.php');
    } else {
        header('Location: /scan.php');
    }
    exit;
}
header('Location: /login.php');
exit;
?>