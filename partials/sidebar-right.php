<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/follow.php';

$conn = getDatabaseConnection();
$currentUserId = $_SESSION["id"];

$followedUsers = getFollowedUsers($conn, $currentUserId);
$suggestions = getSuggestions($conn, $currentUserId);
?>

<div class="right-top-sidebar p-3 d-flex flex-column gap-4">

  <!-- Du folgst -->
  <div class="following p-3 follow rounded shadow-sm">
    <h6 class="text-light border-bottom pb-2 mb-3">Du folgst (<?= $followingCount ?>)</h6>

    <?php if (empty($followedUsers)): ?>
      <p class="text-light small">Noch keine Nutzer gefolgt.</p>
    <?php else: ?>
      <?php foreach ($followedUsers as $user): ?>
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($user["profile_img"]) ?>"
                 class="rounded-circle me-2 border border-secondary" width="40" height="40" alt="<?= $user["username"] ?>">
            <div>
              <strong class="text-light">@<?= htmlspecialchars($user["username"]) ?></strong>
              <p class="mb-0 text-light small"><?= htmlspecialchars($user["bio"]) ?></p>
            </div>
          </div>
          <form method="POST" action="/Social_App/controllers/unfollow_user.php" class="ms-2">
            <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
            <button class="btn btn-sm btn-outline-danger d-flex align-items-center">
            <i class="bi bi-person-x-fill"></i>
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Wem folgen -->
  <div class="suggestions p-3 follow rounded shadow-sm">
    <h6 class="text-light border-bottom pb-2 mb-3">Vorschläge</h6>

    <?php if (empty($suggestions)): ?>
      <p class="text-light small">Keine Vorschläge verfügbar.</p>
    <?php else: ?>
      <?php foreach ($suggestions as $user): ?>
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <img src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($user["profile_img"]) ?>"
                 class="rounded-circle me-2 border border-secondary" width="40" height="40" alt="<?= $user["username"] ?>">
            <div>
              <strong class="text-light">@<?= htmlspecialchars($user["username"]) ?></strong>
              <p class="mb-0 text-light small"><?= htmlspecialchars($user["bio"]) ?></p>
            </div>
          </div>
          <form method="POST" action="/Social_App/controllers/follow_user.php" class="ms-2">
            <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
            <button class="btn btn-sm btn-outline-light d-flex align-items-center">
              <i class="bi bi-person-plus-fill me-1"></i>
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>
