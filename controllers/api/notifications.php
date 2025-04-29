<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

// Session starten
session_start();

// JSON-Header setzen
header('Content-Type: application/json');

try {
    // Prüfen ob Benutzer eingeloggt ist
    if (!isset($_SESSION['id'])) {
        throw new Exception('Nicht eingeloggt');
    }

    $conn = getDatabaseConnection();
    $userId = $_SESSION['id'];

    // Benachrichtigungen abrufen
    $stmt = $conn->prepare("
        SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ungelesene Benachrichtigungen zählen
    $stmt = $conn->prepare("
        SELECT COUNT(*) as unread_count 
        FROM notifications 
        WHERE user_id = ? AND is_read = FALSE
    ");
    $stmt->execute([$userId]);
    $unreadCount = $stmt->fetch(PDO::FETCH_ASSOC)['unread_count'];

    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unreadCount
    ]);

} catch (Exception $e) {
    error_log("Notifications API Fehler: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 