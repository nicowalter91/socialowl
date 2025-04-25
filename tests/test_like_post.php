<?php
session_start();
$_SESSION["id"] = 1;
require_once "../includes/config.php";
require_once "../includes/connection.php";

$postId = 1;

$stmt = $conn->prepare("SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?");
$stmt->execute([$_SESSION["id"], $postId]);

if ($stmt->rowCount() > 0) {
    $conn->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?")->execute([$_SESSION["id"], $postId]);
    echo "Like entfernt für Post #$postId\n";
} else {
    $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)")->execute([$_SESSION["id"], $postId]);
    echo "Like hinzugefügt für Post #$postId\n";
}
