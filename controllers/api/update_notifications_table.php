<?php
require_once __DIR__ . '/../../includes/config.php';
require_once INCLUDES . '/connection.php';

$conn = getDatabaseConnection();

try {
    // Read and execute the SQL file
    $sql = file_get_contents(__DIR__ . '/../../sql/notifications.sql');
    $conn->exec($sql);
    echo "Notifications table updated successfully!";
} catch (PDOException $e) {
    echo "Error updating notifications table: " . $e->getMessage();
}