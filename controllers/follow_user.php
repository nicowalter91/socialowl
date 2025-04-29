<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/follow.php';

session_start();

if (!isset($_SESSION['id']) || !isset($_POST['user_id'])) {
    header('Location: ' . BASE_URL);
    exit;
}

$conn = getDatabaseConnection();
$followerId = $_SESSION['id'];
$followedId = $_POST['user_id'];

try {
    // Prüfen ob bereits eine Anfrage existiert
    $stmt = $conn->prepare("SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$followerId, $followedId]);
    if ($stmt->fetch()) {
        throw new Exception('Du hast bereits eine Anfrage gesendet oder folgst diesem Benutzer.');
    }

    // Neue Follow-Anfrage mit Status 'pending' speichern
    $stmt = $conn->prepare("INSERT INTO followers (follower_id, followed_id, status, followed_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([$followerId, $followedId]);

    // Benachrichtigung als Follow-Request erstellen
    $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt2->execute([$followerId]);
    $follower = $stmt2->fetch(PDO::FETCH_ASSOC);
    $content = "@{$follower['username']} möchte dir folgen";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, content) VALUES (?, 'follow_request', ?)");
    $stmt->execute([$followedId, $content]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
