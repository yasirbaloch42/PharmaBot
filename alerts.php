<?php
require_once 'includes/auth.php';
require_login();

// Low stock
$low = $conn->query('SELECT m.name, i.quantity, i.min_threshold FROM inventory i JOIN medicines m ON m.id = i.medicine_id WHERE i.quantity <= i.min_threshold');

// Expiring within 30 days
$exp = $conn->query("SELECT m.name, i.expiry_date FROM inventory i JOIN medicines m ON m.id = i.medicine_id WHERE i.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND i.expiry_date >= CURDATE()"); 
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Alerts</title><link rel="stylesheet" href="/assets/css/style.css"></head>
<body>
<div class="container">
<h1>Alerts</h1>
<h2>Low Stock</h2>
<ul>
<?php while ($r = $low->fetch_assoc()): ?>
<li><?= escape($r['name']) ?> — <?= escape($r['quantity']) ?> (min <?= escape($r['min_threshold']) ?>)</li>
<?php endwhile; ?>
</ul>
<h2>Expiring Soon</h2>
<ul>
<?php while ($r = $exp->fetch_assoc()): ?>
<li><?= escape($r['name']) ?> — <?= escape($r['expiry_date']) ?></li>
<?php endwhile; ?>
</ul>
</div>
</body>
</html>