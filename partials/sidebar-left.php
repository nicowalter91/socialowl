<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/follow.php';

$conn = getDatabaseConnection();
$userId = $_SESSION["id"];

$followerCount = countFollowers($conn, $userId);
$followingCount = countFollowing($conn, $userId);
?>


<!-- ============================
       Sidebar links
  ============================ -->

<div class="left-top-sidebar">
      <div class="profile-top" style="background-image: url(/Social_App/assets/uploads/<?php echo $_SESSION["header_img"] ?>)"></div>
      <div class="profile">
        <img class="profile-image" src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
        <h3 class="text-light"><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></h3>
        <p class="username text-light">@<?php echo $_SESSION["username"] ?></p>
        <p class="bio text-light"><?php echo $_SESSION["bio"] ?></p>
      </div>

      <div class="stats">
        <div class="left-stats">
          <p class="text-light">Follower</p>
          <h3 class="text-light"><?php echo $followerCount?></h3>
        </div>
        <div class="right-stats">
          <p class="text-light">Following</p>
          <h3 class="text-light"><?php echo $followingCount?></h3>
        </div>
      </div>
    </div>