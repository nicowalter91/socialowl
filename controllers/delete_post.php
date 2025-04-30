<?php
/**
 * Controller: Post löschen
 * Löscht einen Post, sofern der aktuelle Nutzer der Besitzer ist.
 * Löscht ggf. zugehörige Medien-Dateien.
 * Antwortet mit JSON (success/message).
 */
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";

header('Content-Type: application/json');

$conn = getDatabaseConnection();
ensureLogin($conn);

// Prüfen, ob post_id gesendet wurde
if (!isset($_POST["post_id"])) {
  echo json_encode(["success" => false, "message" => "Keine Post-ID übergeben."]);
  exit;
}

$postId = $_POST["post_id"];
$currentUserId = $_SESSION["id"];

// Sicherstellen, dass der aktuelle Nutzer der Besitzer des Posts ist
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :uid");
$stmt->execute([
  ":id" => $postId,
  ":uid" => $currentUserId
]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  echo json_encode(["success" => false, "message" => "Post nicht gefunden oder keine Berechtigung."]);
  exit;
}

// Falls Medien existieren, löschen
if (!empty($post["image_path"])) {
  $imagePath = __DIR__ . "/../assets/posts/" . $post["image_path"];
  if (file_exists($imagePath)) {
    unlink($imagePath);
  }
}
if (!empty($post["video_path"])) {
  $videoPath = __DIR__ . "/../assets/posts/" . $post["video_path"];
  if (file_exists($videoPath)) {
    unlink($videoPath);
  }
}

// Post löschen
$deleteStmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
$deleteStmt->execute([":id" => $postId]);

echo json_encode(["success" => true]);
exit;
