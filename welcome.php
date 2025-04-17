<?php
require_once "connection.php";
require_once "auth.php";
require_once "user.php";
require_once "post.php";

checkLogin();
$user = initUserSession($_SESSION["username"]);
$posts = fetchAllPosts($conn);
?>


<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social Owl</title>

  <!-- Styles -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./css/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="/Social_App/assets/img/Owl_logo.svg" type="image/x-icon">
</head>

<body>
  <!-- Navbar -->
  <?php include "./partials/navbar.php" ?>

  <!-- Main -->
  <div class="parent fixed-top">

    <?php include "./partials/sidebar-left.php" ?>
    <?php include "./partials/post-form.php" ?>
    <?php include "./partials/feed.php" ?>
    <?php include "./partials/sidebar-right.php" ?>
  </div>

  <!-- Modale -->
  <?php include "./partials/modal-profil.php" ?>
  <?php include "./partials/modal-delete-posts.php" ?>

  <!-- Java Script -->
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="./script.js"></script>
</body>

</html>