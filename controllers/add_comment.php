<?php
/**
 * Controller: Kommentar hinzufügen
 * Fügt einen neuen Kommentar zu einem Post hinzu und erstellt ggf. eine Benachrichtigung.
 * Leitet zurück auf die vorherige Seite.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/comment.php';

session_start();

if (!isset($_SESSION['id']) || !isset($_POST['post_id']) || !isset($_POST['content'])) {
    header('Location: ' . BASE_URL);
    exit;
}

$conn = getDatabaseConnection();
$userId = $_SESSION['id'];
$postId = $_POST['post_id'];
$content = $_POST['content'];

try {
    // Kommentar hinzufügen
    $stmt = $conn->prepare("
        INSERT INTO comments (user_id, post_id, content) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$userId, $postId, $content]);
    $commentId = $conn->lastInsertId();

    // Post-Besitzer abrufen
    $stmt = $conn->prepare("
        SELECT p.user_id, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Benachrichtigung erstellen (nur wenn der Kommentar nicht vom Post-Besitzer ist)
    if ($post['user_id'] != $userId) {
        $stmt = $conn->prepare("
            INSERT INTO notifications (user_id, type, content) 
            VALUES (?, 'comment', ?)
        ");
        
        // Benutzername des Kommentators abrufen
        $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $stmt2->execute([$userId]);
        $commenter = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        $content = "@{$commenter['username']} hat deinen Post kommentiert";
        $stmt->execute([$post['user_id'], $content]);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}