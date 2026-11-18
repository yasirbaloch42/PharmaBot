<?php
require_once 'includes/db_connect.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare('SELECT id, password, role, name FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header('Location: /');
            exit;
        } else {
            $message = 'Invalid credentials';
        }
    } else {
        $message = 'Invalid credentials';
    }
}
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - PharmaBot</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if ($message): ?><p class="error"><?= escape($message) ?></p><?php endif; ?>
    <form method="POST" action="">
        <label>Email</label><br>
        <input type="email" name="email" required><br>
        <label>Password</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Demo users can be created via <code>setup_demo.php</code></p>
</div>
</body>
</html>