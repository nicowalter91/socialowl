<!-- ============================
       Feed
  ============================ -->

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

    <div class="tweet-card mb-4 p-3 rounded" data-post-id="<?= $post['id'] ?>">


      <!-- Beitrag Kopf mit Dropdown für eigene Posts -->
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="d-flex align-items-start">
          <img class="tweet-profile-image me-3" src="/Social_App/assets/uploads/<?= htmlspecialchars($post["profile_img"]) ?>" alt="Profilbild">
          <div>
            <h6 class="text-light mb-0">@<?= htmlspecialchars($post["username"]) ?></h6>
            <small class="text-light"><?= date("d.m.Y H:i", strtotime($post["created_at"])) ?></small>

          </div>
        </div>

        <!-- Dropdown nur für eigene Posts -->
        <?php if ($post["user_id"] == $_SESSION["id"]): ?>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-three-dots"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end bg-dark border border-secondary">
              <li>
                <a href="#" class="dropdown-item text-light edit-post-btn"
                  data-post-id="<?= $post['id'] ?>"
                  data-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>"
                  data-image="<?= !empty($post['image_path']) ? '/Social_App/assets/posts/' . htmlspecialchars($post['image_path']) : '' ?>">
                  <i class="bi bi-pencil-square me-2"></i>Bearbeiten
                </a>

              </li>
              <li>
                <hr class="dropdown-divider border-light">
              </li>
              <li>
                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                  <i class="bi bi-trash me-2"></i>Löschen
                </button>
              </li>

            </ul>
          </div>


        <?php endif; ?>

      </div>


      <!-- Beitrag Inhalt -->
      <div class="mb-3 pb-3 border-bottom border-secondary">
        <p class="text-light mb-2"><?= nl2br(htmlspecialchars($post["content"])) ?></p>

        <?php if (!empty($post["image_path"])): ?>
          <div class="tweet-media-wrapper mb-2 w-100">
            <img src="/Social_App/assets/posts/<?= htmlspecialchars($post["image_path"]) ?>?t=<?= time() ?>" alt="Bild" class="tweet-media img-fluid rounded-4 shadow-sm">
          </div>
        <?php endif; ?>

        <?php if (!empty($post["video_path"])): ?>
          <div class="tweet-media-wrapper">
            <video controls class="tweet-media rounded-4 shadow-sm" >
              <source src="/Social_App/assets/posts/<?= htmlspecialchars($post["video_path"]) ?>?t=<?= time() ?>" type="video/mp4">
              Dein Browser unterstützt dieses Video nicht.
            </video>
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
            <img class="rounded-circle" src="/Social_App/assets/uploads/<?= $_SESSION["profile_img"] ?>" alt="Profilbild" style="width: 32px; height: 32px;">
            <div class="flex-grow-1">
              <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
              <div class="input-group">
                <input type="text" name="comment" class="form-control comment-custom comment-input-box bg-dark text-light border-secondary" placeholder="Schreibe einen Kommentar..." required>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i>Senden</button>
              </div>
            </div>
          </form>
        </div>

        <!-- Kommentare anzeigen -->
        <?php foreach ($comments as $comment): ?>
          <div class="comment d-flex align-items-start gap-2 mb-2 pt-3 pb-3 border-bottom border-secondary">
            <img class="rounded-circle" src="/Social_App/assets/uploads/<?= htmlspecialchars($comment["profile_img"]) ?>" alt="Profilbild" style="width: 32px; height: 32px;">
            <div>
              <strong class="text-light">@<?= htmlspecialchars($comment["username"]) ?></strong><br>
              <span class="text-light "><?= nl2br(htmlspecialchars($comment["content"])) ?></span>
            </div>
            <label for="file-upload-image" class="btn btn-sm btn-primary">
              <i class="bi bi-hand-thumbs-up me-1"></i>
            </label>
          </div>
        <?php endforeach; ?>


      </div>


    </div>


  <?php endforeach; ?>


</div>