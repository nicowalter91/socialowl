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
    // Prüfen ob bereits gefolgt wird
    if (isFollowing($conn, $followerId, $followedId)) {
        throw new Exception('Du folgst diesem Benutzer bereits');
    }

    // Folgen hinzufügen
    addFollow($conn, $followerId, $followedId);

    // Benachrichtigung erstellen
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, type, content) 
        VALUES (?, 'follow', ?)
    ");
    
    // Benutzername des Follower abrufen
    $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt2->execute([$followerId]);
    $follower = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    $content = "@{$follower['username']} folgt dir jetzt";
    $stmt->execute([$followedId, $content]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
