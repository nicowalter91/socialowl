<?php
// Modellfunktionen für Kommentare

/**
 * Holt alle Kommentare zu einem Post inkl. User- und Like-Infos.
 * @param PDO $conn
 * @param int $postId
 * @param int $currentUserId
 * @return array
 */
function fetchCommentsForPost(PDO $conn, int $postId, int $currentUserId): array {
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_img,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id) AS like_count,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id AND user_id = :uid) AS liked
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = :post_id
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([
        ":post_id" => $postId,
        ":uid" => $currentUserId
    ]);
    return $stmt->fetchAll();
}

/**
 * Erstellt einen neuen Kommentar zu einem Post.
 * @param PDO $conn
 * @param int $postId
 * @param int $userId
 * @param string $content
 * @return int Die neue Kommentar-ID
 */
function createComment(PDO $conn, int $postId, int $userId, string $content): int {
    $stmt = $conn->prepare("
        INSERT INTO comments (post_id, user_id, content, created_at)
        VALUES (:post_id, :user_id, :content, NOW())
    ");
    $stmt->execute([
        ":post_id" => $postId,
        ":user_id" => $userId,
        ":content" => $content
    ]);
    return $conn->lastInsertId();
}

/**
 * Holt User-Infos für einen Kommentar.
 * @param PDO $conn
 * @param int $userId
 * @return array
 */
function fetchUserById(PDO $conn, int $userId): array {
    $stmt = $conn->prepare("SELECT username, profile_img FROM users WHERE id = :id");
    $stmt->execute([":id" => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Aktualisiert einen Kommentar (nur wenn vom User selbst).
 * @param PDO $conn
 * @param int $commentId
 * @param int $userId
 * @param string $content
 * @return bool Erfolg
 */
function updateComment(PDO $conn, int $commentId, int $userId, string $content): bool {
    // Erst prüfen, ob Kommentar dem User gehört
    $stmt = $conn->prepare("SELECT id FROM comments WHERE id = :id AND user_id = :uid");
    $stmt->execute([
        ":id" => $commentId,
        ":uid" => $userId
    ]);
    if ($stmt->rowCount() === 0) {
        return false;
    }
    $update = $conn->prepare("UPDATE comments SET content = :content WHERE id = :id");
    return $update->execute([
        ":content" => $content,
        ":id" => $commentId
    ]);
}

/**
 * Löscht einen Kommentar (nur wenn vom User selbst).
 * @param PDO $conn
 * @param int $commentId
 * @param int $userId
 * @return bool Erfolg
 */
function deleteComment(PDO $conn, int $commentId, int $userId): bool {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ":id" => $commentId,
        ":user_id" => $userId
    ]);
    return $stmt->rowCount() > 0;
}

/**
 * Holt einen einzelnen Kommentar inkl. User- und Like-Infos.
 * @param PDO $conn
 * @param int $commentId
 * @param int $userId
 * @return array|null
 */
function fetchSingleComment(PDO $conn, int $commentId, int $userId): ?array {
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_img,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id) AS like_count,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id AND user_id = :uid) AS liked
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = :cid
    ");
    $stmt->execute([
        ":cid" => $commentId,
        ":uid" => $userId
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Holt alle neuen Kommentare seit einem bestimmten Zeitstempel.
 * @param PDO $conn
 * @param int $userId
 * @param int $sinceTimestamp
 * @return array
 */
function fetchCommentsSince(PDO $conn, int $userId, int $sinceTimestamp): array {
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_img
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE UNIX_TIMESTAMP(c.created_at) > :since
          AND (c.post_id IN (
                SELECT p.id
                FROM posts p
                WHERE p.user_id = :self
                   OR p.user_id IN (
                       SELECT followed_id FROM followers WHERE follower_id = :self
                   )
             ))
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([
        ":since" => $sinceTimestamp,
        ":self" => $userId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

