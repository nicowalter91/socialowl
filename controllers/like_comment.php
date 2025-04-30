<?php
/**
 * Controller: Kommentar liken/entliken
 * Schaltet den Like-Status für einen Kommentar um und gibt die neue Like-Anzahl zurück.
 * Antwortet mit JSON (success/liked/like_count).
 */
require_once "../includes/config.php";
require_once "../includes/connection.php";
require_once "../includes/auth.php";

header('Content-Type: application/json');

$conn = getDatabaseConnection();
ensureLogin($conn);

if (!isset($_POST["comment_id"])) {
  echo json_encode(["success" => false, "message" => "Keine comment_id übergeben."]);
  exit;
}

$commentId = $_POST["comment_id"];
$userId = $_SESSION["id"];

// Prüfen ob Like existiert
$stmt = $conn->prepare("SELECT id FROM comment_likes WHERE comment_id = :cid AND user_id = :uid");
$stmt->execute([
  ":cid" => $commentId,
  ":uid" => $userId
]);
$like = $stmt->fetch(PDO::FETCH_ASSOC);

if ($like) {
  // Bereits geliked -> löschen
  $del = $conn->prepare("DELETE FROM comment_likes WHERE id = :id");
  $del->execute([":id" => $like["id"]]);
  $liked = false;
} else {
  // Neu liken
  $insert = $conn->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (:cid, :uid)");
  $insert->execute([
    ":cid" => $commentId,
    ":uid" => $userId
  ]);
  $liked = true;
}

// Neue Like-Anzahl holen
$countStmt = $conn->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = :cid");
$countStmt->execute([":cid" => $commentId]);
$likeCount = $countStmt->fetchColumn();

echo json_encode([
  "success" => true,
  "liked" => $liked,
  "like_count" => $likeCount
]);
