<?php
require_once "connection.php";
require_once "auth.php";
require_once "user.php";

checkLogin(); // ⬅️ Session und Token-Check

$user = fetchUserInfo($_SESSION["username"]);

$_SESSION["firstname"] = $user["firstname"] ?? 'Unbekannt';
$_SESSION["lastname"]  = $user["lastname"] ?? 'Unbekannt';
$_SESSION["bio"]       = $user["bio"] ?? 'Noch keine Bio verfügbar';
$_SESSION["follower"]  = $user["follower"] ?? 0;
$_SESSION["following"] = $user["following"] ?? 0;
$_SESSION["profile_img"] = $user["profile_img"] ?? "./img/profil.png";
$_SESSION["header_img"] = $user["header_img"];

echo "Session ID: " . ($_SESSION["id"] ?? 'nicht gesetzt');

// === Alle Posts abrufen ===
$stmt = $conn->prepare("
  SELECT posts.*, users.username, users.profile_img 
  FROM posts 
  JOIN users ON posts.user_id = users.id 
  ORDER BY posts.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);





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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body>

  <!-- ============================
       Navigation Bar
  ============================ -->
  <div class="nav fixed-top d-flex justify-content-between align-items-center px-3">
    <!-- Linke Seite: Logo + Titel -->
    <div class="d-flex align-items-center gap-2" style="min-width: 200px;">
      <img class="logo" src="./img/Owl_logo.svg" alt="Owl Logo">
      <h3 class="text-light mb-0">Social Owl</h3>
    </div>

    <!-- Mitte: Suchfeld -->
    <div class="flex-grow-1 mx-4">
      <form class="d-flex justify-content-center" role="search">
        <input class="form-control" style="border-radius: 48px; max-width: 400px;" type="search" placeholder="# Search">
      </form>
    </div>

    <!-- Rechte Seite: Notifications + Dropdown -->
    <div class="d-flex align-items-center gap-3">
      <div class="notification-container position-relative">
        <span class="icon"><i class="fa-solid fa-bell text-white"></i></span>
        <span class="notification-badge">3</span>
      </div>

      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="./uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild" width="32" height="32" class="rounded-circle me-2">
          <strong class="text-light"><?php echo $_SESSION["username"] ?></strong>
        </a>
        <ul class="dropdown-menu bg-dark dropdown-menu-end">
          <li><a class="dropdown-item text-light" data-bs-toggle="modal" data-bs-target="#profilModal">
              <i class="bi bi-person-circle me-2"></i>Profil
            </a></li>
          <li><a class="dropdown-item text-light" href="settings.html"><i class="bi bi-gear me-2"></i>Einstellungen</a></li>
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
      <div class="profile-top" style="background-image: url(./uploads/<?php echo $_SESSION["header_img"] ?>)"></div>
      <div class="profile">
        <img class="profile-image" src="./uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
        <h3><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></h3>
        <p class="username text-light">@<?php echo $_SESSION["username"] ?></p>
        <p class="bio text-light"><?php echo $_SESSION["bio"] ?></p>
      </div>

      <div class="stats">
        <div class="left-stats">
          <p class="text-light">Follower</p>
          <h3 class="text-light"><?php echo $_SESSION["follower"] ?></h3>
        </div>
        <div class="right-stats">
          <p class="text-light">Following</p>
          <h3 class="text-light"><?php echo $_SESSION["following"] ?></h3>
        </div>
      </div>
    </div>

    <!-- Beitrag erstellen Formular -->
    <form action="create_post.php" method="POST" enctype="multipart/form-data" class="tweet-box p-3 d-flex flex-column" style="min-height: 200px;">
      <!-- Beitrag Inhalt -->
      <div class="d-flex align-items-start mb-3">
        <img class="tweet-profile-image me-3" src="./uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
        <div class="flex-grow-1 d-flex flex-column">
          <textarea name="content" class="form-control tweet-input-box text-dark border-0 rounded-4 px-3 py-2 flex-grow-1"
            rows="4" placeholder="Was passiert gerade?" style="min-height: 120px;" required></textarea>
        </div>
      </div>

      <!-- Footer mit Buttons -->
      <div class="mt-auto d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top border-secondary">
        <div class="d-flex gap-2 flex-wrap">
          <!-- Bild Upload -->
          <label for="file-upload-image" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-image me-1"></i> Bild
          </label>
          <input type="file" name="image" id="file-upload-image" style="display: none;" accept="image/*">

          <!-- (Optional) Video Upload -->
          <label for="file-upload-video" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-video me-1"></i> Video
          </label>
          <input type="file" name="video" id="file-upload-video" style="display: none;" accept="video/*">

          <!-- Emoji Picker Button -->
          <button id="emoji-button" type="button" class="btn btn-sm btn-outline-secondary">
            <i class="fa-regular fa-face-smile me-1"></i> Emoji
          </button>
        </div>

        <!-- Senden Button -->
        <button type="submit" class="btn btn-sm btn-primary px-4">Posten</button>
      </div>
    </form>




    <!-- Feedbereich (Beiträge) -->
    <div class="feed">
      <?php foreach ($posts as $post): ?>
        <?php
        // Likes für diesen Post laden
        $likeStmt = $conn->prepare("SELECT COUNT(*) AS like_count, SUM(CASE WHEN user_id = :current_user THEN 1 ELSE 0 END) AS liked_by_me FROM post_likes WHERE post_id = :post_id");
        $likeStmt->execute([
          ":post_id" => $post["id"],
          ":current_user" => $_SESSION["id"]
        ]);
        $likeData = $likeStmt->fetch(PDO::FETCH_ASSOC);

        $liked = $likeData["liked_by_me"] > 0;
        $likeCount = $likeData["like_count"];
        ?>

        <div class="tweet-card mb-4 p-3 rounded">
          <!-- Beitrag Kopf -->
          <div class="d-flex align-items-start mb-3">
            <img class="tweet-profile-image me-3" src="./uploads/<?= htmlspecialchars($post["profile_img"]) ?>" alt="Profilbild">
            <div>
              <h6 class="text-light mb-0">@<?= htmlspecialchars($post["username"]) ?></h6>
              <small class="text-light"><?= htmlspecialchars($post["created_at"]) ?></small>
            </div>
          </div>

          <!-- Beitrag Inhalt -->
          <div class="mb-3">
            <p class="text-light mb-2"><?= nl2br(htmlspecialchars($post["content"])) ?></p>
            <?php if (!empty($post["image_path"])): ?>
              <div class="tweet-image-wrapper text-center">
                <img src="./posts/<?= htmlspecialchars($post["image_path"]) ?>" alt="Beitragsbild" class="tweet-image">
              </div>
            <?php endif; ?>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
            <form action="like_post.php" method="POST" class="me-2 d-inline">
              <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
              <button type="submit" class="btn btn-sm <?= $liked ? 'btn-light text-dark' : 'btn-primary' ?>">
                <i class="bi bi-hand-thumbs-up me-1"></i>
                <?= $liked ? 'Gefällt' : 'Gefällt mir' ?>
                <?php if ($liked): ?>
                  <span class="ms-2 text-dark"><?= $likeCount ?></span>
                <?php endif; ?>
              </button>
            </form>


            <button class="btn btn-outline-light btn-sm toggle-comment-form" data-post-id="<?= $post['id'] ?>">
              <i class="bi bi-chat-left-text me-1"></i>Kommentieren
            </button>

          </div>

          <!-- Kommentarbereich -->
          <div class="mb-3">
            <?php
            $commentStmt = $conn->prepare("
              SELECT comments.*, users.username, users.profile_img 
              FROM comments 
              JOIN users ON comments.user_id = users.id 
              WHERE comments.post_id = :post_id 
              ORDER BY comments.created_at ASC
            ");
            $commentStmt->execute(["post_id" => $post["id"]]);
            $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Kommentarformular -->
            <div class="comment-form mt-3" id="comment-form-<?= $post['id'] ?>" style="display: none;">
              <form action="create_comment.php" method="POST" class="d-flex align-items-start gap-2 mb-2">
                <img class="rounded-circle" src="./uploads/<?= $_SESSION["profile_img"] ?>" alt="Profilbild" style="width: 32px; height: 32px;">
                <div class="flex-grow-1">
                  <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
                  <div class="input-group">
                    <input type="text" name="comment" class="form-control bg-dark text-light border-secondary" placeholder="Schreibe einen Kommentar..." required>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i>Senden</button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Kommentare anzeigen -->
            <?php foreach ($comments as $comment): ?>
              <div class="comment d-flex align-items-start gap-2 mb-2">
                <img class="rounded-circle" src="./uploads/<?= htmlspecialchars($comment["profile_img"]) ?>" alt="Profilbild" style="width: 32px; height: 32px;">
                <div>
                  <strong class="text-light">@<?= htmlspecialchars($comment["username"]) ?></strong><br>
                  <span class="text-light"><?= nl2br(htmlspecialchars($comment["content"])) ?></span>
                </div>
              </div>
            <?php endforeach; ?>


          </div>


        </div>
      <?php endforeach; ?>
    </div>


    <!-- Rechte Seitenleiste -->
    <div class="searchbar p-3 d-flex flex-column gap-4">

      <!-- Wem folgen -->
      <div class="suggestions">
        <h6 class="text-light mb-3">Wem folgen?</h6>

        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <img src="./img/profil.png" class="rounded-circle me-2" width="40" height="40" alt="User 1">
            <div>
              <strong class="text-light">@max_dev</strong>
              <p class="mb-0 text-light small">Webentwickler 🚀</p>
            </div>
          </div>
          <button class="btn btn-sm btn-outline-light">Folgen</button>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <img src="./img/profil.png" class="rounded-circle me-2" width="40" height="40" alt="User 2">
            <div>
              <strong class="text-light">@frontend_queen</strong>
              <p class="mb-0 text-light small">React 💅 CSS 🎨</p>
            </div>
          </div>
          <button class="btn btn-sm btn-outline-light">Folgen</button>
        </div>
      </div>

      <!-- Trends -->
      <div class="trends mt-4">
        <h6 class="text-light mb-3">Trends für dich</h6>
        <div class="mb-2">
          <p class="mb-0 text-light small">Tech · Trending</p>
          <strong class="text-light">#PHP8</strong><br>
          <small class="text-light">12.3K Tweets</small>
        </div>

        <div class="mb-2">
          <p class="mb-0 text-light small">Deutschland · Trending</p>
          <strong class="text-light">#KI</strong><br>
          <small class="text-light">27.8K Tweets</small>
        </div>

        <div class="mb-2">
          <p class="mb-0 text-light small">Entwicklung</p>
          <strong class="text-light">#Bootstrap5</strong><br>
          <small class="text-light">3.2K Tweets</small>
        </div>
      </div>
    </div>


  </div>

  <!-- ============================
      Modal 
  ==============================-->

  <!-- === MODAL: PROFIL BEARBEITEN === -->
  <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark text-light border border-secondary shadow-lg">
        <form action="profil-update.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header border-bottom border-secondary">
            <h5 class="modal-title" id="profilModalLabel">
              <i class="bi bi-person-circle me-2"></i> Profil bearbeiten
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
          </div>

          <div class="modal-body">

            <!-- Hintergrundbild -->
            <div class="mb-4 text-center">
              <label class="form-label fw-bold">Aktuelles Hintergrundbild</label><br>
              <img src="./uploads/<?= $_SESSION['header_img'] ?? 'img/Background.jpg' ?>" class="img-fluid rounded mb-2" style="max-height: 200px; object-fit: cover;">
              <input type="file" name="header_img" class="form-control mt-2">
            </div>

            <!-- Profilbild -->
            <div class="mb-4 text-center">
              <label class="form-label fw-bold">Profilbild</label><br>
              <img src="./uploads/<?= $_SESSION['profile_img'] ?? 'img/profil.png' ?>" class="rounded-circle mb-2 border border-light" width="100" height="100">
              <input type="file" name="profile_img" class="form-control mt-2">
            </div>

            <!-- Bio -->
            <div class="mb-3">
              <label for="bio" class="form-label fw-bold">Bio</label>
              <textarea name="bio" id="bio" class="form-control bg-dark text-light border-secondary" maxlength="160" rows="3"><?= htmlspecialchars($_SESSION["bio"] ?? '') ?></textarea>
              <small class="text-muted">Max. 160 Zeichen</small>
            </div>

          </div>

          <div class="modal-footer border-top border-secondary">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i> Speichern
            </button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Schließen</button>
          </div>
        </form>
      </div>
    </div>
  </div>




  <!-- ============================
       Skripte & Interaktionen
  ============================ -->
  
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


    document.querySelectorAll('.toggle-comment-form').forEach(btn => {
      btn.addEventListener('click', () => {
        const postId = btn.dataset.postId;
        const form = document.getElementById(`comment-form-${postId}`);
        if (form) {
          form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
      });
    });
  </script>
</body>

</html>