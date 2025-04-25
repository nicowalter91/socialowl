<?php
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";


$conn = getDatabaseConnection();
ensureLogin($conn);

header('Content-Type: application/json');

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;
$content = trim($_POST["comment"] ?? '');

if (!$postId || $content === '') {
  echo json_encode(["success" => false, "message" => "Ungültige Eingabe."]);
  exit;
}

// Kommentar speichern
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
$stmt->execute([
  ":post_id" => $postId,
  ":user_id" => $userId,
  ":content" => $content
]);

$commentId = $conn->lastInsertId();

// Kommentar erneut holen mit Userinfos & Like-Daten
$comment = fetchSingleComment($conn, $commentId, $userId);

if (!$comment) {
  echo json_encode(["success" => false, "message" => "Kommentar konnte nicht geladen werden."]);
  exit;
}

// HTML generieren mit Partial
ob_start();
$GLOBALS["comment"] = $comment;
include PARTIALS . '/comment_item.php';
$html = ob_get_clean();

echo json_encode(["success" => true, "html" => $html]);
exit;
