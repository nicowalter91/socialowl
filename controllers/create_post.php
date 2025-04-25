<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';
require_once MODELS . '/post.php';

$conn = getDatabaseConnection();
ensureLogin($conn);

header('Content-Type: application/json');

$user_id = $_SESSION["id"];
$content = trim($_POST["content"] ?? '');
$edit_id = $_POST["edit_post_id"] ?? null;

$image_path = handleUpload("image", POSTS);
$video_path = handleUpload("video", POSTS);

if (!empty($edit_id)) {
    updatePost($conn, (int)$edit_id, $user_id, $content, $image_path, $video_path);
    $post_id = (int)$edit_id;
} else {
    $post_id = createPost($conn, $user_id, $content, $image_path, $video_path);
}

$post = fetchPostById($conn, $post_id, $user_id);


// HTML mit partial/post_card.php rendern
ob_start();
include PARTIALS . '/post_card.php';
$html = ob_get_clean();

echo json_encode([
    "success" => true,
    "post_id" => $post_id,
    "html" => $html
]);
exit;
