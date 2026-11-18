<?php
require_once 'includes/db_connect.php';

$sql = file_get_contents(__DIR__ . '/sql/setup.sql');
if ($conn->multi_query($sql)) {
    echo "Database & tables created.\n";
    // consume all results
    do { $conn->store_result(); } while ($conn->more_results() && $conn->next_result());
    echo "Demo data inserted.\n";
} else {
    echo "Error: " . $conn->error;
}
?>