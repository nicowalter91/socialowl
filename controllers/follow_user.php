<?php
require_once "../includes/connection.php";
require_once "../includes/auth.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

if (!isset($_POST["user_id"])) {
  header("Location: /Social_App/index.php");
  exit;
}

$currentUserId = $_SESSION["id"];
$followedId = $_POST["user_id"];

$stmt = $conn->prepare("INSERT IGNORE INTO followers (follower_id, followed_id) VALUES (:follower, :followed)");
$stmt->execute([
  ":follower" => $currentUserId,
  ":followed" => $followedId
]);

header("Location: /Social_App/views/index.php");
exit;
