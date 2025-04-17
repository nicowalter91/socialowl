<?php
require_once "../connection.php";
require_once "../auth.php";
checkLogin();

header('Content-Type: application/json');

$user_id = $_SESSION["id"];
$content = $_POST["content"] ?? '';
$edit_id = $_POST["edit_post_id"] ?? null;
$image_path = null;

// === Bild-Upload verarbeiten ===
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
    $uploadDir = realpath(__DIR__ . "../assets/posts");
    $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
    $targetPath = $uploadDir ."/". $filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        $image_path = $filename;
    }
}

// === Bestehenden Post bearbeiten ===
if (!empty($edit_id)) {
    $sql = "UPDATE posts SET content = :content";
    if ($image_path) $sql .= ", image_path = :image_path";
    $sql .= " WHERE id = :id AND user_id = :user_id";

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
    $post_id = $edit_id;

} else {
    // === Neuen Post anlegen ===
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (:user_id, :content, :image_path)");
    $stmt->execute([
        ":user_id" => $user_id,
        ":content" => $content,
        ":image_path" => $image_path
    ]);
    $post_id = $conn->lastInsertId();
}

// === Post zurückholen
$stmt = $conn->prepare("
    SELECT posts.*, users.username, users.profile_img 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE posts.id = :id
");
$stmt->execute([':id' => $post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// === HTML bauen mit Template
ob_start();
include "../partials/post_card.php";
$html = ob_get_clean();

echo json_encode([
    "success" => true,
    "post_id" => $post_id,
    "html" => $html
]);
exit;
