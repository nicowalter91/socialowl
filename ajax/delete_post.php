<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["success" => false, "message" => "Nur POST erlaubt."]);
  exit;
}

$postId = $_POST["post_id"] ?? null;
$userId = $_SESSION["id"];

if (!$postId) {
  echo json_encode(["success" => false, "message" => "Keine Post-ID übermittelt."]);
  exit;
}

// Beitrag laden (inkl. Mediapfade)
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute([
  ":id" => $postId,
  ":user_id" => $userId
]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  echo json_encode(["success" => false, "message" => "Beitrag nicht gefunden oder keine Berechtigung."]);
  exit;
}

// Mediendateien löschen
$uploadDir = realpath(__DIR__ . "/../assets/posts") . DIRECTORY_SEPARATOR;

if (!empty($post["image_path"])) {
  $img = $uploadDir . $post["image_path"];
  if (file_exists($img)) unlink($img);
}
if (!empty($post["video_path"])) {
  $vid = $uploadDir . $post["video_path"];
  if (file_exists($vid)) unlink($vid);
}

// Beitrag löschen
$deleteStmt = $conn->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
$deleteStmt->execute([
  ":id" => $postId,
  ":user_id" => $userId
]);

echo json_encode(["success" => true]);
exit;
