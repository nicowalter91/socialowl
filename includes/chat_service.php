<?php
/**
 * Chat-Service: Stellt Funktionen für das Chat-System bereit.
 *
 * Diese Datei enthält Hilfsfunktionen zum Suchen/Erstellen eines Chats
 * und zum Speichern von Nachrichten.
 */
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/config.php';

/**
 * Sendet eine Chat-Nachricht. Erstellt bei Bedarf einen neuen Chat.
 *
 * @param int $fromUserId   Absender-ID
 * @param int $toUserId     Empfänger-ID
 * @param string $message   Die Nachricht
 * @return bool|string      true bei Erfolg, Fehlermeldung als String bei Fehler
 */
function sendChatMessage($fromUserId, $toUserId, $message) {
    // Nachricht verschlüsseln
    $encryptedMessage = openssl_encrypt(
        $message,
        'AES-256-CBC',
        CHAT_ENCRYPT_KEY,
        0,
        CHAT_ENCRYPT_IV
    );
    if ($encryptedMessage === false) {
        return 'Verschlüsselung fehlgeschlagen: Prüfe Schlüssel und IV!';
    }
    try {
        $pdo = getDatabaseConnection();
        $user1 = min($fromUserId, $toUserId);
        $user2 = max($fromUserId, $toUserId);
        // Chat suchen oder anlegen
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
        $stmt->execute([$chatId, $fromUserId, $encryptedMessage]);
        return true;
    } catch (Exception $e) {
        return 'Fehler beim Speichern: ' . $e->getMessage();
    }
}
