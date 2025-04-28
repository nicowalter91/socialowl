<?php
require_once '../../includes/connection.php';

header('Content-Type: application/json');

$pdo = getDatabaseConnection(); // Verbindungsobjekt holen!

$since = isset($_GET['since']) ? $_GET['since'] : '1970-01-01 00:00:00';

try {
    $stmt = $pdo->prepare("
        SELECT comments.id, comments.post_id, comments.user_id, comments.content, comments.created_at,
               users.username, users.profile_img
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE comments.created_at > ?
        ORDER BY comments.created_at ASC
    ");
    $stmt->execute([$since]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'comments' => $comments,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Abrufen der Kommentare: ' . $e->getMessage(),
    ]);
}
?>
