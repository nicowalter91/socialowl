<?php
/**
 * Controller: Nutzer entfolgen
 * Entfernt eine Follower-Beziehung und leitet zum Index weiter.
 */

require_once "../includes/connection.php";
require_once "../includes/auth.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

if (!isset($_POST["user_id"])) {
  header("Location: /Social_App/views/index.php");
  exit;
}

$currentUserId = $_SESSION["id"];
$followedId = $_POST["user_id"];

$stmt = $conn->prepare("DELETE FROM followers WHERE follower_id = :follower AND followed_id = :followed");
$stmt->execute([
  ":follower" => $currentUserId,
  ":followed" => $followedId
]);

header("Location: /Social_App/views/index.php");
exit;
