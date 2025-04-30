<?php
/**
 * API-Controller: Chat-Follower-Liste
 * Gibt alle akzeptierten Follower des eingeloggten Nutzers für den Chat zurück (JSON).
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
    // Hole alle User, die dem aktuellen User folgen und Status 'accepted' haben
    $stmt = $pdo->prepare('
        SELECT u.id, u.username, u.profile_img, u.last_active
        FROM followers f
        JOIN users u ON f.follower_id = u.id
        WHERE f.followed_id = ? AND f.status = "accepted"
        ORDER BY u.username ASC
    ');
    $stmt->execute([$userId]);
    $followers = $stmt->fetchAll();
    // Für jeden Follower die Anzahl ungelesener Nachrichten berechnen
    foreach ($followers as &$follower) {
        // Chat-ID bestimmen (unabhängig von Reihenfolge)
        $user1 = min($userId, $follower['id']);
        $user2 = max($userId, $follower['id']);
        $stmtChat = $pdo->prepare('SELECT id FROM chats WHERE user1_id = ? AND user2_id = ?');
        $stmtChat->execute([$user1, $user2]);
        $chat = $stmtChat->fetch();
        $unread = 0;
        if ($chat) {
            $chatId = $chat['id'];
            // Ungelesene Nachrichten zählen, die von diesem Follower an den eingeloggten User gingen
            $stmtUnread = $pdo->prepare('SELECT COUNT(*) FROM messages WHERE chat_id = ? AND sender_id = ? AND is_read = 0');
            $stmtUnread->execute([$chatId, $follower['id']]);
            $unread = (int)$stmtUnread->fetchColumn();
        }
        $follower['unread_count'] = $unread;
    }
    echo json_encode(['success' => true, 'followers' => $followers]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
}
