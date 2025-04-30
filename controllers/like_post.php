<?php
/**
 * Controller: Post liken/entliken
 * Schaltet den Like-Status für einen Post um und erstellt ggf. eine Benachrichtigung.
 * Leitet zurück auf die vorherige Seite.
 */
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once MODELS . '/like.php';

session_start();
$conn = getDatabaseConnection();

if (!isset($_SESSION["id"])) {
    header("Location: " . BASE_URL . "/controllers/login.php");
    exit;
}

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;

if (!$postId || !is_numeric($postId)) {
    die("Ungültiger Post.");
}

// Like toggle durchführen
$isLiked = togglePostLike($conn, $userId, (int)$postId);

if ($isLiked) {
    // Post-Besitzer und Like-Ersteller abrufen
    $stmt = $conn->prepare("
        SELECT p.user_id, u.username 
        FROM posts p 
        JOIN users u ON u.id = :user_id 
        WHERE p.id = :post_id
    ");
    $stmt->execute([
        ":user_id" => $userId,
        ":post_id" => $postId
    ]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // Benachrichtigung nur erstellen, wenn man nicht seinen eigenen Post liked
    if ($data && $data['user_id'] != $userId) {
        $stmt = $conn->prepare("
            INSERT INTO notifications (user_id, type, content, post_id) 
            VALUES (:user_id, 'like', :content, :post_id)
        ");
        $content = "@{$data['username']} gefällt dein Post";
        $stmt->execute([
            ":user_id" => $data['user_id'],
            ":content" => $content,
            ":post_id" => $postId
        ]);
    }
}

// Dynamische Rückleitung
$redirect = $_SERVER["HTTP_REFERER"] ?? BASE_URL . "/views/index.php";
header("Location: " . $redirect);
exit;
