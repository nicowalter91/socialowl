<?php

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

// ✨ NEU: Kommentar erstellen
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

// ✨ NEU: Benutzerinfo für Kommentar
function fetchUserById(PDO $conn, int $userId): array {
    $stmt = $conn->prepare("SELECT username, profile_img FROM users WHERE id = :id");
    $stmt->execute([":id" => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ✏️ Kommentar aktualisieren
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

// 🗑 Kommentar löschen (nur wenn vom User selbst)
function deleteComment(PDO $conn, int $commentId, int $userId): bool {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ":id" => $commentId,
        ":user_id" => $userId
    ]);

    return $stmt->rowCount() > 0;
}

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

function fetchCommentsSince(PDO $conn, int $userId, int $sinceTimestamp): array {
    $stmt = $conn->prepare("
      SELECT 
        c.*,
        u.username,
        u.profile_img,
        IF(cl.id IS NULL, 0, 1) AS liked,
        (
          SELECT COUNT(*) 
          FROM comment_likes 
          WHERE comment_id = c.id
        ) AS like_count
      FROM comments c
      JOIN users u ON u.id = c.user_id
      LEFT JOIN comment_likes cl ON cl.comment_id = c.id AND cl.user_id = :uid
      WHERE UNIX_TIMESTAMP(c.created_at) > :since
      ORDER BY c.created_at ASC
    ");
  
    $stmt->execute([
      ':uid' => $userId,
      ':since' => $sinceTimestamp
    ]);
  
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  