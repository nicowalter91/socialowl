<?php
require_once "connection.php";
session_start();

if (!isset($_SESSION["id"])) {
  die("Nicht eingeloggt!");
}

$userId = $_SESSION["id"];
$content = $_POST["content"] ?? '';
$imageName = null;

// Bildverarbeitung
if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
  $tmpPath = $_FILES["image"]["tmp_name"];
  $originalName = $_FILES["image"]["name"];
  $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

  $allowed = ['jpg', 'jpeg', 'png', 'gif'];
  if (in_array($ext, $allowed)) {
    $imageName = $userId . "_post_" . time() . "." . $ext;
    move_uploaded_file($tmpPath, "posts/" . $imageName);
  }
}

// Post speichern
$stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path, created_at) VALUES (:uid, :content, :img, NOW())");
$stmt->bindParam(":uid", $userId);
$stmt->bindParam(":content", $content);
$stmt->bindParam(":img", $imageName);

if (!$stmt->execute()) {
  echo "Fehler beim Speichern: ";
  print_r($stmt->errorInfo());
  exit();
}

header("Location: welcome.php");
exit();
?>
