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
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="./assets/img/Owl_logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Scripts -->
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/7cf2870798.js" crossorigin="anonymous"></script>
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
  <script src="./script.js"></script>
</body>

</html>