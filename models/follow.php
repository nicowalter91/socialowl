<?php
/**
 * Modellfunktionen für Follows (Folgen/Follower)
 * Stellt Funktionen zum Verwalten und Abfragen von Follower-Beziehungen bereit.
 */

/**
 * Gibt die Nutzer zurück, denen ein User folgt.
 *
 * @param PDO $conn
 * @param int $userId
 * @return array
 */
function getFollowedUsers(PDO $conn, int $userId): array {
  $stmt = $conn->prepare("
    SELECT u.id, u.username, u.profile_img, u.bio
    FROM followers f
    JOIN users u ON u.id = f.followed_id
    WHERE f.follower_id = :uid AND f.status = 'accepted'
    ORDER BY f.followed_id DESC
    LIMIT 10
  ");
  $stmt->execute([':uid' => $userId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Gibt Nutzer-Vorschläge zurück, denen der aktuelle User noch nicht folgt.
 *
 * @param PDO $conn
 * @param int $currentUserId
 * @return array
 */
function getSuggestions(PDO $conn, int $currentUserId): array {
    $stmt = $conn->prepare("
      SELECT u.id, u.username, u.profile_img, u.bio
      FROM users u
      WHERE u.id != :uid
        AND u.id NOT IN (
          SELECT followed_id FROM followers WHERE follower_id = :uid2 AND status = 'accepted'
        )
      ORDER BY RAND()
      LIMIT 10
    ");
    $stmt->execute([
      ":uid" => $currentUserId,
      ":uid2" => $currentUserId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Zählt die Follower eines Nutzers.
 *
 * @param PDO $conn
 * @param int $userId
 * @return int
 */
function countFollowers(PDO $conn, int $userId): int {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE followed_id = :id AND status = 'accepted'");
    $stmt->execute([":id" => $userId]);
    return (int)$stmt->fetchColumn();
}

/**
 * Zählt, wie viele Nutzer ein User folgt.
 *
 * @param PDO $conn
 * @param int $userId
 * @return int
 */
function countFollowing(PDO $conn, int $userId): int {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE follower_id = :id AND status = 'accepted'");
    $stmt->execute([":id" => $userId]);
    return (int)$stmt->fetchColumn();
}

/**
 * Prüft, ob ein Nutzer einem anderen bereits folgt.
 *
 * @param PDO $conn
 * @param int $followerId
 * @param int $followedId
 * @return bool
 */
function isFollowing(PDO $conn, int $followerId, int $followedId): bool {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE follower_id = ? AND followed_id = ? AND status = 'accepted'");
    $stmt->execute([$followerId, $followedId]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Erstellt eine neue Follow-Anfrage (Status: pending).
 *
 * @param PDO $conn
 * @param int $followerId
 * @param int $followedId
 * @return bool Erfolg
 */
function addFollow(PDO $conn, int $followerId, int $followedId): bool {
    $stmt = $conn->prepare("INSERT INTO followers (follower_id, followed_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
    return $stmt->execute([$followerId, $followedId]);
}


