<?php
require_once "connection.php";
require_once "auth.php";

checkLogin();

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;
$content = trim($_POST["comment"] ?? '');

if (!$postId || $content === '') {
  header("Location: welcome.php");
  exit();
}

$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
$stmt->bindParam(":post_id", $postId);
$stmt->bindParam(":user_id", $userId);
$stmt->bindParam(":content", $content);
$stmt->execute();

header("Location: welcome.php");
exit();
?>
