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
  <div class="profile-top" style="background-image: url(/Social_App/assets/uploads/<?php echo $_SESSION["header_img"]; ?>); background-position: center; background-size: cover; 
              background-repeat: no-repeat;"></div>
  <div class="profile">
    <img class="profile-image" src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
    <h3 class="text-light mt-3"><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></h3>
    <p class="username text-light">@<?php echo $_SESSION["username"] ?></p>
    <p class="bio text-light"><?php echo $_SESSION["bio"] ?></p>
  </div>

  <!-- 📊 Statistiken -->
  <div class="stats mt-3 pt-3 d-flex justify-content-around text-center">

    <!-- Follower (hoverbar) -->
    <div class="left-stats hover-effect" role="button" title="Follower anzeigen">
      <p class="text-light mb-1">Follower</p>
      <h3 class="text-light mb-0"><?= $followerCount ?></h3>
    </div>

    <!-- Following (hoverbar) -->
    <div class="right-stats hover-effect" role="button" title="Following anzeigen">
      <p class="text-light mb-1">Following</p>
      <h3 class="text-light mb-0"><?= $followingCount ?></h3>
    </div>
  </div>








</div>