<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();

header('Content-Type: application/json');

$commentId = $_POST["comment_id"] ?? null;
$userId = $_SESSION["id"];

if (!$commentId) {
  echo json_encode(["success" => false, "message" => "Keine Kommentar-ID übergeben."]);
  exit;
}

// Kommentar nur löschen, wenn er vom eingeloggten User ist
$stmt = $conn->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
$stmt->execute([
  ":id" => $commentId,
  ":user_id" => $userId
]);

if ($stmt->rowCount()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "message" => "Löschen fehlgeschlagen oder keine Berechtigung."]);
}
exit;
