<?php
require_once "connection.php";
require_once "auth.php";
checkLogin();

// Prüfen, ob post_id gesendet wurde
if (!isset($_POST["post_id"])) {
  die("Ungültiger Aufruf.");
}

$postId = $_POST["post_id"];
$currentUserId = $_SESSION["id"];

// Sicherstellen, dass der aktuelle Nutzer der Besitzer des Posts ist
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id AND user_id = :uid");
$stmt->execute([
  ":id" => $postId,
  ":uid" => $currentUserId
]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  die("Post nicht gefunden oder keine Berechtigung.");
}

// Falls ein Bild existiert, löschen wir es auch vom Server
if (!empty($post["image_path"]) && file_exists("posts/" . $post["image_path"])) {
  unlink("posts/" . $post["image_path"]);
}

// Post löschen
$deleteStmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
$deleteStmt->execute([":id" => $postId]);

header("Location: welcome.php");
exit();
