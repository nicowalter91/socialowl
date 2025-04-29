<?php
require_once __DIR__ . '/like.php';
require_once __DIR__ . '/comment.php';

/**
 * Löscht eine Datei, wenn sie existiert
 * @param string $filepath - Der vollständige Pfad zur Datei
 * @return bool - true wenn die Datei gelöscht wurde oder nicht existierte, false bei Fehler
 */
function deleteFileIfExists(string $filepath): bool {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return true;
}

/**
 * Speichert hochgeladene Datei (Bild/Video) in Zielordner.
 * Löscht alte Dateien, wenn sie überschrieben werden.
 * @param string $key - Der Schlüssel im $_FILES Array
 * @param string $destinationDir - Der Zielordner
 * @param string|null $oldFilename - Der alte Dateiname, der gelöscht werden soll
 * @return string|null - Der neue Dateiname oder null bei Fehler
 */
function handleUpload(string $key, string $destinationDir, ?string $oldFilename = null): ?string {
    if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Alte Datei löschen, wenn vorhanden
    if ($oldFilename) {
        $oldPath = $destinationDir . '/' . $oldFilename;
        deleteFileIfExists($oldPath);
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
 * @param PDO $conn - Die Datenbankverbindung
 * @param int $user_id - Die Benutzer-ID
 * @param string $content - Der Inhalt des Posts
 * @param string|null $image_path - Der Pfad zum Bild
 * @param string|null $video_path - Der Pfad zum Video
 * @return int - Die ID des neuen Posts
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
 * Löscht alte Medien-Dateien, wenn sie überschrieben werden.
 * @param PDO $conn - Die Datenbankverbindung
 * @param int $post_id - Die Post-ID
 * @param int $user_id - Die Benutzer-ID
 * @param string $content - Der neue Inhalt
 * @param string|null $image_path - Der neue Bildpfad
 * @param string|null $video_path - Der neue Videopfad
 * @param string|null $old_image_path - Der alte Bildpfad
 * @param string|null $old_video_path - Der alte Videopfad
 */
function updatePost(PDO $conn, int $post_id, int $user_id, string $content, ?string $image_path, ?string $video_path, ?string $old_image_path = null, ?string $old_video_path = null): void {
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

    // Alte Dateien löschen, wenn neue hochgeladen wurden
    if ($image_path && $old_image_path) {
        $oldPath = POSTS . '/' . $old_image_path;
        deleteFileIfExists($oldPath);
    }
    if ($video_path && $old_video_path) {
        $oldPath = POSTS . '/' . $old_video_path;
        deleteFileIfExists($oldPath);
    }
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

/**
 * Holt nur Posts von Nutzern, denen man folgt (inkl. eigene).
 */
function fetchFollowedPostsWithComments(PDO $conn, int $currentUserId): array {
    $stmt = $conn->prepare("
    SELECT posts.*, users.username, users.profile_img,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id) AS like_count,
           (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id AND user_id = :uid) AS liked_by_me
    FROM posts
    JOIN users ON posts.user_id = users.id
    WHERE posts.user_id = :uid
       OR posts.user_id IN (
           SELECT followed_id FROM follower WHERE follower_id = :uid AND status = 'accepted'
       )
    ORDER BY posts.created_at DESC
");
    $stmt->execute([
        ":uid" => $currentUserId
    ]);

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as &$post) {
        $post["comments"] = fetchCommentsForPost($conn, $post["id"], $currentUserId);
    }

    return $posts;
}

/**
 * Gibt die Top N Hashtags aus allen Posts im Feed zurück (meistgenutzt zuerst).
 * @param PDO $conn
 * @param int $limit
 * @return array Array mit Hashtag als key und Anzahl als value
 */
function getTopHashtags(PDO $conn, int $limit = 3): array {
    $stmt = $conn->query("SELECT content FROM posts");
    $allPosts = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $hashtagCounts = [];
    foreach ($allPosts as $content) {
        // Hashtags mit Unicode-Buchstaben, Zahlen, Unterstrich, Bindestrich, !, ?, . erlauben
        if (preg_match_all('/#([\p{L}\p{N}_\-!?.]{2,50})/u', $content, $matches)) {
            foreach ($matches[1] as $tag) {
                $tag = mb_strtolower($tag); // Case-insensitive
                if (!isset($hashtagCounts[$tag])) $hashtagCounts[$tag] = 0;
                $hashtagCounts[$tag]++;
            }
        }
    }
    arsort($hashtagCounts);
    return array_slice($hashtagCounts, 0, $limit, true);
}
