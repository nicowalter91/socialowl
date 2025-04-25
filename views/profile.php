<?php
require "connection.php";
require_once "auth.php";
require_once "user.php";

checkLogin();
$user = fetchUserInfo($_SESSION["username"]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Mein Profil – Social Owl</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-light">

<div class="container py-5" style="max-width: 700px;">
  <h2 class="mb-4"><i class="bi bi-person-circle me-2"></i>Profil bearbeiten</h2>

  <form action="profil-update.php" method="POST" enctype="multipart/form-data">

    <!-- Profilbild -->
    <div class="mb-4">
      <label class="form-label">Aktuelles Profilbild</label><br>
      <img src="<?= $user['profile_img'] ?? '/Social_App/assets/img/profil.png' ?>" class="rounded-circle mb-2" width="100" height="100">
      <input type="file" name="profile_img" class="form-control mt-2">
    </div>

    <!-- Bio -->
    <div class="mb-3">
      <label for="bio" class="form-label">Über dich (Bio)</label>
      <textarea name="bio" id="bio" class="form-control bg-dark text-light" maxlength="160" rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
      <small class="text-muted">Max. 160 Zeichen</small>
    </div>

    <!-- Button -->
    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Speichern</button>
    <a href="welcome.php" class="btn btn-outline-light ms-2">Zurück</a>

  </form>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
