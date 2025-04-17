<?php
if (!isset($post)) return;

$liked = false;
$likeCount = 0;

// Likes zählen
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
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div class="d-flex align-items-start">
      <img class="tweet-profile-image me-3" src="/Social_App/assets/uploads/<?= htmlspecialchars($post["profile_img"]) ?>" alt="Profilbild">
      <div>
        <h6 class="text-light mb-0">@<?= htmlspecialchars($post["username"]) ?></h6>
        <small class="text-light"><?= date("d.m.Y H:i", strtotime($post["created_at"])) ?></small>
      </div>
    </div>

    <?php if ($post["user_id"] == $_SESSION["id"]): ?>
      <div class="dropdown">
        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-three-dots-vertical"></i>Optionen
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
          <li><hr class="dropdown-divider border-light"></li>
          <li>
            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
              <i class="bi bi-trash me-2"></i>Löschen
            </button>
          </li>
        </ul>
      </div>
    <?php endif; ?>
  </div>

  <div class="mb-3 pb-3 border-bottom border-secondary">
    <p class="text-light mb-2"><?= nl2br(htmlspecialchars($post["content"])) ?></p>
    <?php if (!empty($post["image_path"])): ?>
      <div class="tweet-image-wrapper text-center">
        <img src="/Social_App/assets/posts/<?= htmlspecialchars($post["image_path"]) ?>" alt="Beitragsbild" class="tweet-image">
      </div>
    <?php endif; ?>
  </div>

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
</div>
