<?php
// ============================
// Session- und Authentifizierung
// ============================
require "connection.php";
session_start();

if (!isset($_SESSION["username"])) {
  if (isset($_COOKIE["remember_token"])) {
    $remember_token = $_COOKIE["remember_token"];

    // Token aus Datenbank prüfen
    $stmt = $conn->prepare("SELECT username FROM users WHERE remember_token = :remember_token");
    $stmt->bindParam(":remember_token", $remember_token);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
      $_SESSION["username"] = $data["username"];
    } else {
      setcookie("remember_token", "", time() - 3600);
    }
  }

  if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
  }
}

// Vor- und Nachname abrufen
$stmt2 = $conn->prepare("SELECT firstname, lastname FROM users WHERE username = :username");
$stmt2->bindParam(":username", $_SESSION["username"]);
$stmt2->execute();
$data2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($data2) {
  $_SESSION["firstname"] = $data2["firstname"];
  $_SESSION["lastname"] = $data2["lastname"];
} else {
  $_SESSION["firstname"] = 'Unbekannt';
  $_SESSION["lastname"] = 'Unbekannt';
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social Owl</title>

  <!-- Styles & Icons -->
  <script src="https://kit.fontawesome.com/7cf2870798.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js"></script>
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="./img/Owl_logo.svg" type="image/x-icon">
</head>

<body>

  <!-- ============================
       Navigation Bar
  ============================ -->
  <div class="nav fixed-top">
    <div class="nav-left">
      <img class="logo" src="./img/Owl_logo.svg" alt="Owl Logo">
      <div class="container-fluid">
        <form class="d-flex" role="search">
          <input class="form-control me-2" style="border-radius: 48px" type="search" placeholder="# Search">
        </form>
      </div>
    </div>

    <div class="nav-right">
      <div class="notification-container">
        <span class="icon"><i class="fa-solid fa-bell"></i></span>
        <span class="notification-badge">3</span>
      </div>

      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="./img/profil.png" alt="" width="32" height="32" class="rounded-circle me-2">
          <strong class="text-light"><?php echo $_SESSION["username"] ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>Profil</a></li>
          <li><a class="dropdown-item" href="settings.html"><i class="bi bi-gear me-2"></i>Einstellungen</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item text-danger" href="./logout.php"><i class="bi bi-box-arrow-right me-2"></i>Abmelden</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ============================
       Hauptbereich (Grid Layout)
  ============================ -->
  <div class="parent fixed-top">

    <!-- Sidebar: Profilbereich -->
    <div class="left-top-sidebar">
      <div class="profile-top"></div>
      <div class="profile">
        <img class="profile-image" src="./img/profil.png" alt="Profilbild">
        <h3><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></h3>
        <p class="username text-light">@<?php echo $_SESSION["username"] ?></p>
        <p class="bio text-light">🌍 Explorer of ideas</p>
      </div>

      <div class="stats">
        <div class="left-stats">
          <p class="text-light">Follower</p>
          <h3 class="text-light">7</h3>
        </div>
        <div class="right-stats">
          <p class="text-light">Following</p>
          <h3 class="text-light">35</h3>
        </div>
      </div>
    </div>

    <!-- Beitrag erstellen -->
    <div class="tweet-box p-3">
      <div class="d-flex align-items-start mb-3">
        <img class="tweet-profile-image me-3" src="./img/profil.png" alt="">
        <div class="flex-grow-1">
          <textarea class="form-control tweet-input-box bg-dark text-light border-0 rounded-4 px-3 py-2" rows="3" placeholder="Was passiert gerade?"></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
        <div class="d-flex gap-2 flex-wrap">
          <label for="file-upload-image" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-image me-1"></i> Bild
          </label>
          <input type="file" id="file-upload-image" style="display: none;">

          <label for="file-upload-video" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-video me-1"></i> Video
          </label>
          <input type="file" id="file-upload-video" style="display: none;">

          <button id="emoji-button" class="btn btn-sm btn-outline-secondary">
            <i class="fa-regular fa-face-smile me-1"></i> Emoji
          </button>
        </div>

        <button type="button" class="btn btn-sm btn-primary px-4">Posten</button>
      </div>
    </div>

    <!-- Rechte Seitenleiste -->
    <div class="searchbar"></div>

    <!-- Feedbereich (Beiträge) -->
    <div class="feed">
      <div class="tweet-card mb-4 p-3 rounded">

        <!-- Beitrag Kopf -->
        <div class="d-flex align-items-start mb-3">
          <img class="tweet-profile-image me-3" src="./img/profil.png" alt="">
          <div>
            <h6 class="text-light mb-0">@<?php echo $_SESSION["username"] ?></h6>
            <small class="text-light">vor 3 Minuten</small>
          </div>
        </div>

        <!-- Beitrag Inhalt -->
        <div class="mb-3">
          <p class="text-light mb-2">
            "Wenn Code Kunst ist, dann ist jede Zeile eine Entscheidung, jede Schleife ein Rhythmus und jedes Pixel ein Ausdruck deiner Vision. ✨"
          </p>
          <div class="tweet-image-wrapper text-center">
            <img src="./img/Background.jpg" alt="Inspirierender Beitrag" class="tweet-image">
          </div>
        </div>


        <!-- Buttons -->
        <div class="d-flex justify-content-start gap-2 mb-3">
          <button class="btn btn-outline-light btn-sm">
            <i class="bi bi-hand-thumbs-up me-1"></i>Gefällt mir
          </button>
          <button class="btn btn-outline-light btn-sm">
            <i class="bi bi-chat-left-text me-1"></i>Kommentieren
          </button>
        </div>

        <!-- Kommentare (Beispielkommentare) -->
        <div class="mb-3">
          <?php foreach ($comments as $comment): ?>
            <div class="comment mb-2">
              <div class="d-flex align-items-center">
                <img class="tweet-profile-image" src="<?= $comment['profile_img'] ?>" alt="" style="width: 30px; height: 30px;">
                <strong class="text-light ms-2"><?= $comment['username'] ?></strong>
              </div>
              <p class="text-light mt-1 mb-0"><?= $comment['comment'] ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Kommentar Eingabe -->
        <div>
          <form class="d-flex align-items-center">
            <img class="tweet-profile-image" src="./img/profil.png" alt="" style="width: 30px; height: 30px;">
            <input type="text" class="form-control bg-dark text-light border-0 ms-2" placeholder="Deinen Kommentar eingeben...">
            <button class="btn btn-light ms-2" type="submit">
              <i class="bi bi-send"></i>
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- ============================
       Skripte & Interaktionen
  ============================ -->
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const emojiBtn = document.querySelector('#emoji-button');
      const tweetInput = document.querySelector('.tweet-input-box');

      if (!emojiBtn || !tweetInput) return;

      const picker = new EmojiButton({
        theme: 'dark'
      });

      picker.on('emoji', emoji => {
        const start = tweetInput.selectionStart;
        const end = tweetInput.selectionEnd;
        const text = tweetInput.value;
        tweetInput.value = text.slice(0, start) + emoji + text.slice(end);
        tweetInput.focus();
        tweetInput.selectionStart = tweetInput.selectionEnd = start + emoji.length;
      });

      emojiBtn.addEventListener('click', () => {
        picker.togglePicker(emojiBtn);
      });
    });
  </script>
</body>

</html>