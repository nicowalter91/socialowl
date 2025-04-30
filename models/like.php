<?php
// Modellfunktionen für Likes (Gefällt mir)

/**
 * Holt Like-Daten für einen Post (Anzahl Likes, ob vom aktuellen User geliked).
 * @param PDO $conn
 * @param int $postId
 * @param int $currentUserId
 * @return array
 */
function getPostLikeData(PDO $conn, int $postId, int $currentUserId): array {
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) AS like_count,
            SUM(CASE WHEN user_id = :currentUser THEN 1 ELSE 0 END) AS liked_by_me
        FROM post_likes
        WHERE post_id = :postId
    ");
    $stmt->execute([
        ":postId" => $postId,
        ":currentUser" => $currentUserId
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Schaltet den Like-Status für einen Post um (Like/Unlike).
 * @param PDO $conn
 * @param int $userId
 * @param int $postId
 * @return bool true, wenn jetzt geliked, false wenn entliked
 */
function togglePostLike(PDO $conn, int $userId, int $postId): bool {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM post_likes WHERE user_id = :uid AND post_id = :pid");
    $stmt->execute([':uid' => $userId, ':pid' => $postId]);
    if ($stmt->fetchColumn() > 0) {
        // Bereits geliked – Like entfernen
        $conn->prepare("DELETE FROM post_likes WHERE user_id = :uid AND post_id = :pid")
             ->execute([':uid' => $userId, ':pid' => $postId]);
        return false;
    } else {
        // Noch nicht geliked – Like setzen
        $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (:uid, :pid)")
             ->execute([':uid' => $userId, ':pid' => $postId]);
        return true;
    }
}