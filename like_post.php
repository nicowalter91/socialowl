<?php
require_once "connection.php";
session_start();

if (!isset($_SESSION["id"])) {
  die("Nicht eingeloggt");
}

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;

if (!$postId) {
  die("Ungültiger Post");
}

// Prüfen, ob bereits geliked
$stmt = $conn->prepare("SELECT * FROM post_likes WHERE user_id = :uid AND post_id = :pid");
$stmt->execute([':uid' => $userId, ':pid' => $postId]);

if ($stmt->rowCount() > 0) {
  // Wenn bereits geliked: Like entfernen
  $conn->prepare("DELETE FROM post_likes WHERE user_id = :uid AND post_id = :pid")
       ->execute([':uid' => $userId, ':pid' => $postId]);
} else {
  // Wenn noch nicht geliked: Like hinzufügen
  $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (:uid, :pid)")
       ->execute([':uid' => $userId, ':pid' => $postId]);
}

header("Location: welcome.php");
exit();
