<?php
require_once 'includes/auth.php';
require_login();

// Simple list
$result = $conn->query('SELECT s.*, u.name as user_name, m.name as med_name FROM scans s LEFT JOIN users u ON u.id = s.user_id LEFT JOIN medicines m ON m.id = s.medicine_id ORDER BY s.created_at DESC LIMIT 200');
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Scan History</title><link rel="stylesheet" href="/assets/css/style.css"></head>
<body>
<div class="container">
<h1>Scan History</h1>
<table>
<tr><th>ID</th><th>User</th><th>Extracted</th><th>Matched</th><th>Success</th><th>Date</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= escape($row['id']) ?></td>
<td><?= escape($row['user_name']) ?></td>
<td><?= escape($row['extracted_name']) ?></td>
<td><?= escape($row['med_name'] ?? '—') ?></td>
<td><?= $row['success'] ? 'Yes' : 'No' ?></td>
<td><?= escape($row['created_at']) ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
</body>
</html>