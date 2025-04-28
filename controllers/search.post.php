<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';
require_once MODELS . '/post.php';
include VIEWS . "/search.view.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

$query = trim($_GET["q"] ?? "");

$results = [];

if (!empty($query)) {
  $stmt = $conn->prepare("
    SELECT p.*, u.username, u.profile_img,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) AS like_count,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = :uid) AS liked_by_me
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.content LIKE :q OR u.username LIKE :q
    ORDER BY p.created_at DESC
  ");
  $stmt->execute([
    ":q" => '%' . $query . '%',
    ":uid" => $_SESSION["id"]
  ]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

