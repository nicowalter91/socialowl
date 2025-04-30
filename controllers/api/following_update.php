<?php
/**
 * API-Controller: Following-Update
 * Gibt aktualisierte Follower-/Following-Zahlen und das HTML für den neu gefolgten Nutzer zurück (JSON).
 */

require_once "../../includes/config.php";
require_once "../../includes/connection.php";
require_once "../../models/follow.php";

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || !isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$conn = getDatabaseConnection();
$currentUserId = $_SESSION['id'];
$targetUserId = $_GET['user_id'];

// Get updated counts
$followerCount = countFollowers($conn, $currentUserId);
$followingCount = countFollowing($conn, $currentUserId);

// Get the newly followed user's data for the sidebar
$stmt = $conn->prepare("
    SELECT u.id, u.username, u.profile_img, u.bio 
    FROM users u 
    WHERE u.id = :uid
");
$stmt->execute([':uid' => $targetUserId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Generate HTML for the new followed user
$newFollowedUserHtml = '';
if ($userData) {
    $newFollowedUserHtml = '
    <div class="d-flex align-items-center justify-content-between mb-3" data-user-id="' . $userData['id'] . '">
        <div class="d-flex align-items-center">
            <img src="' . BASE_URL . '/assets/uploads/' . htmlspecialchars($userData['profile_img']) . '"
                class="rounded-circle me-2 border border-secondary" width="40" height="40" 
                alt="' . htmlspecialchars($userData['username']) . '">
            <div>
                <strong class="text-light">@' . htmlspecialchars($userData['username']) . '</strong>
                <p class="mb-0 text-light small">' . htmlspecialchars($userData['bio']) . '</p>
            </div>
        </div>
        <form method="POST" action="/Social_App/controllers/unfollow_user.php" class="ms-2">
            <input type="hidden" name="user_id" value="' . $userData['id'] . '">
            <button class="btn btn-sm btn-outline-danger d-flex align-items-center">
                <i class="bi bi-person-x-fill"></i>
            </button>
        </form>
    </div>';
}

echo json_encode([
    'success' => true,
    'followingCount' => $followingCount,
    'followerCount' => $followerCount,
    'newFollowedUserHtml' => $newFollowedUserHtml
]);