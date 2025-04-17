<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();

header('Content-Type: application/json');

$postId = $_POST["post_id"] ?? null;
$userId = $_SESSION["id"];

if (!$postId) {
  echo json_encode(["success" => false, "message" => "Keine Post-ID übermittelt."]);
  exit;
}

$stmt = $conn->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
$stmt->execute([
  ":id" => $postId,
  ":user_id" => $userId
]);

echo json_encode(["success" => true]);
