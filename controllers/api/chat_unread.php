<?php
/**
 * API-Controller: Ungelesene Chat-Nachrichten
 * Gibt die Anzahl aller ungelesenen Nachrichten für den eingeloggten Nutzer zurück (JSON).
 */

require_once __DIR__ . '/../../includes/connection.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt.']);
    exit;
}

$userId = $_SESSION['id'];

try {
    $pdo = getDatabaseConnection();
    // Zähle alle ungelesenen Nachrichten, die an den User gingen
    $stmt = $pdo->prepare('
        SELECT COUNT(*) as unread_count
        FROM messages m
        JOIN chats c ON m.chat_id = c.id
        WHERE m.is_read = 0 AND ((c.user1_id = ? OR c.user2_id = ?) AND m.sender_id != ?)
    ');
    $stmt->execute([$userId, $userId, $userId]);
    $row = $stmt->fetch();
    $count = $row ? (int)$row['unread_count'] : 0;
    echo json_encode(['success' => true, 'unread' => $count]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
