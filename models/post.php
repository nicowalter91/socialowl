<?php
require_once __DIR__ . '/like.php';
require_once __DIR__ . '/comment.php';

/**
 * Speichert hochgeladene Datei (Bild/Video) in Zielordner.
 */
function handleUpload(string $key, string $destinationDir): ?string {
    if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
    $filename = uniqid("media_", true) . '.' . strtolower($ext);
    $target = rtrim($destinationDir, '/') . '/' . $filename;

    if (move_uploaded_file($_FILES[$key]['tmp_name'], $target)) {
        return $filename;
    }

    return null;
}

/**
 * Legt einen neuen Post an.
 */
function createPost(PDO $conn, int $user_id, string $content, ?string $image_path, ?string $video_path): int {
    $stmt = $conn->prepare("
        INSERT INTO posts (user_id, content, image_path, video_path) 
        VALUES (:user_id, :content, :image_path, :video_path)
    ");
    $stmt->execute([
        ":user_id" => $user_id,
        ":content" => $content,
        ":image_path" => $image_path,
        ":video_path" => $video_path
    ]);

    return (int)$conn->lastInsertId();
}

/**
 * Aktualisiert bestehenden Post.
 */
function updatePost(PDO $conn, int $post_id, int $user_id, string $content, ?string $image_path, ?string $video_path): void {
    $sql = "UPDATE posts SET content = :content";

    if ($image_path !== null) $sql .= ", image_path = :image_path";
    if ($video_path !== null) $sql .= ", video_path = :video_path";

    $sql .= ", updated_at = NOW() WHERE id = :id AND user_id = :user_id";

    $stmt = $conn->prepare($sql);
    $params = [
        ":content" => $content,
        ":id" => $post_id,
        ":user_id" => $user_id
    ];
    if ($image_path !== null) $params[":image_path"] = $image_path;
    if ($video_path !== null) $params[":video_path"] = $video_path;

    $stmt->execute($params);
}

/**
 * Holt einen einzelnen Post mit Benutzerinfos + Like-Daten.
 */
function fetchPostById(PDO $conn, int $post_id, int $currentUserId): array {
    $stmt = $conn->prepare("
        SELECT posts.*, users.username, users.profile_img
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = :id
    ");
    $stmt->execute([':id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) return [];

    // Likes laden
    $likeStmt = $conn->prepare("
        SELECT COUNT(*) AS like_count,
               SUM(CASE WHEN user_id = :uid THEN 1 ELSE 0 END) AS liked_by_me
        FROM post_likes
        WHERE post_id = :pid
    ");
    $likeStmt->execute([
        ":uid" => $currentUserId,
        ":pid" => $post_id
    ]);
    $likeData = $likeStmt->fetch(PDO::FETCH_ASSOC);

    $post["like_count"] = (int)($likeData["like_count"] ?? 0);
    $post["liked_by_me"] = (int)($likeData["liked_by_me"] ?? 0) > 0;

    return $post;
}


/**
 * Holt alle Posts inkl. Benutzerinfos + Like-Daten.
 */
function fetchAllPosts(PDO $conn): array {
    $userId = $_SESSION["id"];

    $stmt = $conn->prepare("
        SELECT posts.*, users.username, users.profile_img,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id) AS like_count,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id AND user_id = :uid) AS liked_by_me
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute([":uid" => $userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Holt alle Posts inkl. Kommentare.
 */
function fetchAllPostsWithComments(PDO $conn, int $currentUserId): array {
    $posts = fetchAllPosts($conn);

    foreach ($posts as &$post) {
        $post["comments"] = fetchCommentsForPost($conn, $post["id"], $currentUserId);
    }

    return $posts;
}

function fetchPostsSince(PDO $conn, int $userId, int $sinceTimestamp): array {
    $stmt = $conn->prepare("
        SELECT p.*, u.username, u.profile_img
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE UNIX_TIMESTAMP(p.created_at) > :since
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([":since" => $sinceTimestamp]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Optional: Kommentare direkt mitschicken oder später per JavaScript nachladen
    foreach ($posts as &$post) {
        $post["comments"] = []; // du könntest hier z.B. fetchCommentsForPost($conn, $post["id"]) aufrufen
    }

    return $posts;
}
