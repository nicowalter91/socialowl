<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();

header("Content-Type: application/json");

$commentId = $_POST["edit_comment_id"] ?? null;
$content = trim($_POST["comment"] ?? "");

if (!$commentId || $content === "") {
  echo json_encode(["success" => false, "message" => "Ungültige Eingabe."]);
  exit;
}

// Prüfen, ob der Kommentar dem aktuellen User gehört
$stmt = $conn->prepare("SELECT * FROM comments WHERE id = :id AND user_id = :uid");
$stmt->execute([
  ":id" => $commentId,
  ":uid" => $_SESSION["id"]
]);

if ($stmt->rowCount() === 0) {
  echo json_encode(["success" => false, "message" => "Kein Zugriff auf diesen Kommentar."]);
  exit;
}

// Kommentar aktualisieren
$update = $conn->prepare("UPDATE comments SET content = :content WHERE id = :id");
$update->execute([
  ":content" => $content,
  ":id" => $commentId
]);

echo json_encode(["success" => true]);
exit;
