<?php
function getFollowedUsers(PDO $conn, int $userId): array {
  $stmt = $conn->prepare("
    SELECT u.id, u.username, u.profile_img, u.bio
    FROM followers f
    JOIN users u ON u.id = f.followed_id
    WHERE f.follower_id = :uid
    ORDER BY f.followed_at DESC
    LIMIT 5
  ");
  $stmt->execute([':uid' => $userId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSuggestions(PDO $conn, int $currentUserId): array {
    $stmt = $conn->prepare("
      SELECT u.id, u.username, u.profile_img, u.bio
      FROM users u
      WHERE u.id != :uid
        AND u.id NOT IN (
          SELECT followed_id FROM followers WHERE follower_id = :uid2
        )
      ORDER BY RAND()
      LIMIT 5
    ");
  
    $stmt->execute([
      ":uid" => $currentUserId,
      ":uid2" => $currentUserId
    ]);
  
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  
