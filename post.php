<?php 
function fetchAllPosts($conn) {
    $stmt = $conn->prepare("
      SELECT posts.*, users.username, users.profile_img 
      FROM posts 
      JOIN users ON posts.user_id = users.id 
      ORDER BY posts.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>