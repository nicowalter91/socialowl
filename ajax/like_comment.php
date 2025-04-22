<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();



$userId = $_SESSION["id"];
$commentId = $_POST["comment_id"] ?? null;

if (!$commentId) {
  echo json_encode(["success" => false, "message" => "Kein Kommentar angegeben."]);
  exit;
}

// Prüfen ob Like bereits existiert
$stmt = $conn->prepare("SELECT * FROM comment_likes WHERE comment_id = :comment_id AND user_id = :user_id");
$stmt->execute([
  ":comment_id" => $commentId,
  ":user_id" => $userId
]);

if ($stmt->rowCount() > 0) {
  // Wenn bereits geliked → entfernen
  $delete = $conn->prepare("DELETE FROM comment_likes WHERE comment_id = :comment_id AND user_id = :user_id");
  $delete->execute([
    ":comment_id" => $commentId,
    ":user_id" => $userId
  ]);
  $liked = false;
} else {
  // Like hinzufügen
  $insert = $conn->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (:comment_id, :user_id)");
  $insert->execute([
    ":comment_id" => $commentId,
    ":user_id" => $userId
  ]);
  $liked = true;
}

// Neue Like-Anzahl holen
$count = $conn->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = :comment_id");
$count->execute([":comment_id" => $commentId]);
$likeCount = $count->fetchColumn();

echo json_encode([
  "success" => true,
  "liked" => $liked,
  "like_count" => $likeCount
]);

exit;

