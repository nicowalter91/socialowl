<?php
/**
 * API-Controller: Chat-Nachrichten
 * Gibt alle Nachrichten eines Chats zwischen zwei Nutzern zurück (JSON).
 * Legt den Chat an, falls er noch nicht existiert.
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
$otherUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if (!$otherUserId) {
    echo json_encode(['success' => false, 'message' => 'Kein Chat-Partner angegeben.']);
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
        // Chat anlegen, falls nicht vorhanden
        $stmt = $pdo->prepare('INSERT INTO chats (user1_id, user2_id) VALUES (?, ?)');
        $stmt->execute([$user1, $user2]);
        $chatId = $pdo->lastInsertId();
    } else {
        $chatId = $chat['id'];
    }
    // Nachrichten abrufen
    $stmt = $pdo->prepare('SELECT m.id, m.sender_id, m.message, m.created_at, u.username, u.profile_img
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.chat_id = ?
        ORDER BY m.created_at ASC');
    $stmt->execute([$chatId]);
    $messages = $stmt->fetchAll();
    // Nachrichten entschlüsseln
    foreach ($messages as &$msg) {
        $decrypted = openssl_decrypt(
            $msg['message'],
            'AES-256-CBC',
            CHAT_ENCRYPT_KEY,
            0,
            CHAT_ENCRYPT_IV
        );
        $msg['message'] = $decrypted !== false ? $decrypted : '[Entschlüsselung fehlgeschlagen]';
    }
    // Alle Nachrichten als gelesen markieren, die an den aktuellen User gingen
    $stmt = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE chat_id = ? AND sender_id != ?');
    $stmt->execute([$chatId, $userId]);
    echo json_encode(['success' => true, 'messages' => $messages, 'chat_id' => $chatId]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
