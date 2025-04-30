<?php
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
$data = json_decode(file_get_contents('php://input'), true);
$otherUserId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
if (!$otherUserId) {
    echo json_encode(['success' => false, 'message' => 'Kein Chat-Partner angegeben.']);
    exit;
}

try {
    $pdo = getDatabaseConnection();
    $user1 = min($userId, $otherUserId);
    $user2 = max($userId, $otherUserId);
    // Chat-ID suchen
    $stmt = $pdo->prepare('SELECT id FROM chats WHERE user1_id = ? AND user2_id = ?');
    $stmt->execute([$user1, $user2]);
    $chat = $stmt->fetch();
    if ($chat) {
        $chatId = $chat['id'];
        // Nachrichten löschen
        $pdo->prepare('DELETE FROM messages WHERE chat_id = ?')->execute([$chatId]);
        // Chat löschen
        $pdo->prepare('DELETE FROM chats WHERE id = ?')->execute([$chatId]);
    }
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
