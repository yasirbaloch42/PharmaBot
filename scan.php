<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

require_login();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['medicine_image'])) {
    $uploadDir = UPLOAD_DIR;
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
    $filename = time() . '_' . basename($_FILES['medicine_image']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['medicine_image']['tmp_name'], $targetFile)) {
        // Call Python OCR
        $python = PYTHON_BIN;
        $script = OCR_SCRIPT;
        $escaped = escapeshellarg($targetFile);
        $cmd = "$python " . escapeshellarg($script) . " " . $escaped;
        $output = shell_exec($cmd);
        $extracted = trim($output);

        // Attempt to match with medicines (case-insensitive)
        $stmt = $conn->prepare('SELECT m.*, i.quantity, i.expiry_date FROM medicines m LEFT JOIN inventory i ON i.medicine_id = m.id WHERE LOWER(m.name) = LOWER(?) LIMIT 1');
        $stmt->bind_param('s', $extracted);
        $stmt->execute();
        $res = $stmt->get_result();
        $found = $res->fetch_assoc();

        $medicineId = $found ? $found['id'] : null;
        // Record scan
        $stmt2 = $conn->prepare('INSERT INTO scans (user_id, medicine_id, image_url, ocr_text, extracted_name, success) VALUES (?, ?, ?, ?, ?, ?)');
        $success = $found ? 1 : 0;
        $stmt2->bind_param('iisssi', $user['id'], $medicineId, $targetFile, $extracted, $extracted, $success);
        $stmt2->execute();

        // Render response
        header('Content-Type: text/html; charset=utf-8');
        echo '<h2>Scan Result</h2>';
        echo '<p><strong>Extracted Name:</strong> ' . escape($extracted) . '</p>';
        if ($found) {
            echo '<p><strong>Medicine:</strong> ' . escape($found['name']) . '</p>';
            echo '<p><strong>Stock:</strong> ' . escape($found['quantity']) . '</p>';
            echo '<p><strong>Expiry Date:</strong> ' . escape($found['expiry_date']) . '</p>';
            echo '<p><strong>Usage:</strong> ' . escape($found['usage']) . '</p>';
        } else {
            echo '<p>No matching medicine found in database.</p>';
        }
        echo '<p><a href="/scan.php">Back</a></p>';
        exit;
    } else {
        $error = 'Upload failed.';
    }
}

// Show upload form
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scan Medicine - PharmaBot</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Scan Medicine</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="medicine_image" accept="image/*" required>
        <button type="submit">Upload & Scan</button>
    </form>
    <?php if (!empty($error)) echo '<p class="error">'.escape($error).'</p>'; ?>
    <p><a href="/scans.php">View Scan History</a></p>
</div>
</body>
</html>