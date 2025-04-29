<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$conn = getDatabaseConnection();
$userId = $_SESSION['id'];
$notificationId = $_POST['notification_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$notificationId || !in_array($action, ['accept', 'reject'])) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
    exit;
}

// Hole die Follow-Anfrage zur Notification
$stmt = $conn->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ? AND type = 'follow_request'");
$stmt->execute([$notificationId, $userId]);
$notification = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$notification) {
    echo json_encode(['success' => false, 'message' => 'Anfrage nicht gefunden']);
    exit;
}

// Extrahiere den Follower aus dem Notification-Text
preg_match('/@([\w-]+)/', $notification['content'], $matches);
if (!isset($matches[1])) {
    echo json_encode(['success' => false, 'message' => 'Benutzername nicht gefunden']);
    exit;
}
$followerUsername = $matches[1];

// Hole die Follower-ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$followerUsername]);
$follower = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$follower) {
    echo json_encode(['success' => false, 'message' => 'Follower nicht gefunden']);
    exit;
}
$followerId = $follower['id'];

if ($action === 'accept') {
    // Setze Status auf 'accepted'
    $stmt = $conn->prepare("UPDATE followers SET status = 'accepted' WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$followerId, $userId]);
    // Optionale Notification an Follower
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, content) VALUES (?, 'follow', ?)");
    $content = "@{$_SESSION['username']} hat deine Anfrage angenommen";
    $stmt->execute([$followerId, $content]);
} else {
    // Anfrage ablehnen: Eintrag löschen
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$followerId, $userId]);
}
// Notification löschen
$stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
$stmt->execute([$notificationId]);

echo json_encode(['success' => true]);
