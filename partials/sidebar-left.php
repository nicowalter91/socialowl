<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/follow.php';
require_once __DIR__ . '/../models/post.php';

$conn = getDatabaseConnection();
$userId = $_SESSION["id"];

$followerCount = countFollowers($conn, $userId);
$followingCount = countFollowing($conn, $userId);
$topHashtags = getTopHashtags($conn, 3);
?>


<!-- ============================
       Sidebar links
  ============================ -->

<div class="left-top-sidebar p-3 rounded-4 position-relative overflow-hidden">
  <div class="profile-top position-relative" style="background-image: url(/Social_App/assets/uploads/<?php echo $_SESSION["header_img"]; ?>); background-position: center; background-size: cover; background-repeat: no-repeat; min-height: 110px; border-radius: 20px 20px 0 0;">
    <div class="profile-image-wrapper position-absolute start-50 translate-middle-x rounded-circle border border-3 shadow" style="border-color: var(--color-border) !important; background-color: var(--color-card) !important; bottom: -45px; left: 50%; transform: translateX(-50%); width: 90px; height: 90px; overflow: hidden; z-index:2;">
      <img class="profile-image w-100 h-100 object-fit-cover" src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
    </div>
  </div>
  <div class="profile pt-5 pb-3 d-flex flex-column align-items-center px-3 shadow-sm" style="padding-top:70px!important; border-radius: 0 0 16px 16px;">
    <h3 class="mt-4 mb-0 fw-semibold" style="font-size:1.3em; letter-spacing:0.5px; color: var(--color-text);"> <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?> </h3>
    <p class="username mb-1 small" style="color: var(--color-text-secondary);">@<?php echo $_SESSION["username"] ?></p>
    <div class="bio-container w-100 mb-2">
      <p class="bio text-center small mb-0" style="color: var(--color-text);"><?php echo $_SESSION["bio"] ?></p>
    </div>
    <div class="stats mt-3 w-100 d-flex justify-content-around gap-2">
      <div class="stat-card flex-fill text-center py-2 px-1 hover-effect" role="button" title="Follower anzeigen">
        <div class="small" style="color: var(--color-text-secondary);">Follower</div>
        <div class="fw-bold" style="color: var(--color-text); font-size:1.2em;"><?= $followerCount ?></div>
      </div>
      <div class="stat-card flex-fill text-center py-2 px-1 hover-effect" role="button" title="Following anzeigen">
        <div class="small" style="color: var(--color-text-secondary);">Following</div>
        <div class="fw-bold" style="color: var(--color-text); font-size:1.2em;"><?= $followingCount ?></div>
      </div>
    </div>
  </div>
  <?php if (!empty($topHashtags)): ?>
    <div class="follow mt-2 mb-3 px-3 pt-3 pb-2 shadow-sm trending-hashtags">
      <h6 class="border-bottom pb-2 mb-3 d-flex align-items-center gap-1" style="font-size: 1em; color: var(--color-text);">
        <i class="bi bi-hash"></i>Top Hashtags
      </h6>
      <ul class="list-unstyled mb-0 ps-2">
        <?php foreach ($topHashtags as $tag => $count): ?>
          <li class="mb-2 d-flex align-items-center">
            <span class="hashtag fw-semibold" style="font-size:1em; color: var(--color-accent);">#<?= htmlspecialchars($tag) ?></span>
            <span class="small ms-2" style="color: var(--color-text);">(<?= $count ?>)</span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>