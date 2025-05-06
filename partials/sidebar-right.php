<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/follow.php';

$conn = getDatabaseConnection();
$currentUserId = $_SESSION["id"];

$followedUsers = getFollowedUsers($conn, $currentUserId);
$suggestions = getSuggestions($conn, $currentUserId);
?>

<!-- Du folgst -->
<style>
.following-container {
  max-height: 300px;
  overflow-y: auto;
  scrollbar-width: none; /* Firefox */
}
.following-container.expanded {
  max-height: none;
}
.following-container::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Edge */
}
.following .user-item:not(.visible) {
  display: none;
}
.following .user-item.visible {
  display: flex !important;
}
</style>

<div class="right-top-sidebar p-3 d-flex flex-column gap-4">
  <div class="following p-3 follow rounded shadow-sm">
    <h6 class="text-light border-bottom pb-2 mb-3">Du folgst (<?= $followingCount ?>)</h6>
    
    <?php if (empty($followedUsers)): ?>
      <p class="text-light small">Noch keine Nutzer gefolgt.</p>
    <?php else: ?>
      <div class="following-container">
        <?php 
        $counter = 0;
        foreach ($followedUsers as $user): 
        $visibleClass = $counter < 5 ? 'visible' : '';
        ?>
          <div class="user-item <?= $visibleClass ?> align-items-center justify-content-between mb-3">
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
                <button class="btn btn-sm btn-outline-danger rounded-pill d-flex align-items-center">
                <i class="bi bi-person-x-fill"></i>
                </button>
            </form>
          </div>
        <?php 
        $counter++;
        endforeach; 
        ?>
      </div>
      <?php if (count($followedUsers) > 5): ?>
        <button class="btn btn-sm btn-outline-light rounded-pill w-100 mt-2 toggle-following">
          <span class="more-text">Mehr anzeigen</span>
          <span class="less-text" style="display: none;">Weniger anzeigen</span>
        </button>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <!-- Vorschläge -->
  <div class="suggestions p-3 follow rounded shadow-sm">
    <h6 class="text-light border-bottom pb-2 mb-3">Vorschläge</h6>

    <?php if (empty($suggestions)): ?>
      <p class="text-light small">Keine Vorschläge verfügbar.</p>
    <?php else: ?>
      <?php 
      $counter = 0;
      foreach ($suggestions as $user): 
        if ($counter >= 3) break;
      ?>
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
            <button class="btn btn-sm btn-outline-success rounded-pill d-flex align-items-center">
              <i class="bi bi-person-plus-fill"></i>
            </button>
          </form>
        </div>
      <?php 
      $counter++;
      endforeach; 
      ?>
    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('.toggle-following');
    const container = document.querySelector('.following-container');
    const moreText = document.querySelector('.more-text');
    const lessText = document.querySelector('.less-text');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isExpanded = container.classList.toggle('expanded');
            const userItems = container.querySelectorAll('.user-item');
            
            userItems.forEach(item => {
                if (isExpanded) {
                    item.classList.add('visible');
                    moreText.style.display = 'none';
                    lessText.style.display = 'inline';
                } else {
                    if (!item.classList.contains('visible')) {
                        item.classList.remove('visible');
                    }
                    moreText.style.display = 'inline';
                    lessText.style.display = 'none';
                    
                    // Nur die ersten 5 Einträge anzeigen
                    const visibleItems = Array.from(userItems).slice(0, 5);
                    visibleItems.forEach(item => item.classList.add('visible'));
                    Array.from(userItems).slice(5).forEach(item => item.classList.remove('visible'));
                }
            });
        });
    }
});
</script>
