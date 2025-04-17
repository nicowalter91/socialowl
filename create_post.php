<?php
require_once "connection.php";
require_once "auth.php";
checkLogin();

$content = $_POST["content"] ?? '';
$user_id = $_SESSION["id"];
$edit_id = $_POST["edit_post_id"] ?? null;

$image_path = null;
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
  $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
  move_uploaded_file($_FILES["image"]["tmp_name"], "assets/posts/" . $filename);
  $image_path = $filename;
}

// UPDATE
if (!empty($edit_id)) {
  $sql = "UPDATE posts SET content = :content" .
         ($image_path ? ", image_path = :image_path" : "") .
         " WHERE id = :id AND user_id = :user_id";

  $stmt = $conn->prepare($sql);
  $params = [
    ":content" => $content,
    ":id" => $edit_id,
    ":user_id" => $user_id
  ];
  if ($image_path) {
    $params[":image_path"] = $image_path;
  }
  $stmt->execute($params);

// NEUER POST
} else {
  $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (:user_id, :content, :image_path)");
  $stmt->execute([
    ":user_id" => $user_id,
    ":content" => $content,
    ":image_path" => $image_path
  ]);
}

header("Location: welcome.php");
exit();

?>
