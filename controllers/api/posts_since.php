<?php
require_once '../../includes/connection.php';
require_once '../../includes/config.php';

session_start();
header('Content-Type: application/json');

$pdo = getDatabaseConnection();

$since = $_GET['since'] ?? '1970-01-01 00:00:00';

try {
    // 1) Daten holen
    $stmt = $pdo->prepare("
        SELECT p.*, u.username, u.profile_img,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) AS like_count,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = ?) AS liked_by_me
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.created_at > ?
        ORDER BY p.created_at ASC
    ");
    $stmt->execute([ $_SESSION['id'], $since ]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2) Für jedes Post das HTML‐Partial rendern
    $htmls = [];
    foreach ($posts as $post) {
        // $post steht jetzt als $post im partial zur Verfügung
        $GLOBALS['post'] = $post;
        ob_start();
        include PARTIALS . '/post_card.php';
        $htmls[] = ob_get_clean();
    }

    echo json_encode([
        'success' => true,
        'posts'   => $posts,
        'html'    => $htmls
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Abrufen der Posts: ' . $e->getMessage(),
    ]);
}
