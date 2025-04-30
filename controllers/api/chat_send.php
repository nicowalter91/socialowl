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
$message = isset($data['message']) ? trim($data['message']) : '';
if (!$otherUserId || $message === '') {
    echo json_encode(['success' => false, 'message' => 'Empfänger oder Nachricht fehlt.']);
    exit;
}

try {
    $pdo = getDatabaseConnection();
    // Chat-ID suchen oder anlegen
    $user1 = min($userId, $otherUserId);
    $user2 = max($userId, $otherUserId);
    $stmt = $pdo->prepare('SELECT id FROM chats WHERE user1_id = ? AND user2_id = ?');
    $stmt->execute([$user1, $user2]);
    $chat = $stmt->fetch();
    if (!$chat) {
        $stmt = $pdo->prepare('INSERT INTO chats (user1_id, user2_id) VALUES (?, ?)');
        $stmt->execute([$user1, $user2]);
        $chatId = $pdo->lastInsertId();
    } else {
        $chatId = $chat['id'];
    }
    // Nachricht speichern
    $stmt = $pdo->prepare('INSERT INTO messages (chat_id, sender_id, message) VALUES (?, ?, ?)');
    $stmt->execute([$chatId, $userId, $message]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
