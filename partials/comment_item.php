<?php
/**
 * Partial: Kommentar-Item
 * Stellt die Darstellung eines einzelnen Kommentars inkl. Bearbeiten/Löschen/Like-Button dar.
 * Erwartet: $comment (assoziatives Array)
 */
if (!isset($comment)) return; ?>

<div class="comment d-flex align-items-start gap-2 mb-2 pt-3 pb-3 border-bottom border-secondary" 
     id="comment-<?= $comment["id"] ?>" 
     data-comment-id="<?= $comment["id"] ?>" 
     data-post-id="<?= $comment["post_id"] ?>">



  <!-- Profilbild -->
  <img class="rounded-circle"
       src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($comment["profile_img"]) ?>"
       alt="Profilbild"
       style="width: 32px; height: 32px;">

  <!-- Inhalt -->
  <div class="flex-grow-1">
    <strong class="text-light">@<?= htmlspecialchars($comment["username"]) ?></strong><br>
    <small 
    class="comment-timestamp text-light" 
    data-timestamp="<?= htmlspecialchars($comment["created_at"]) ?>">
  <?= date("d.m.Y H:i", strtotime($comment["created_at"])) ?>
</small>

    <div class="mt-2">
      <span class="text-light comment-content"><?= nl2br(htmlspecialchars($comment["content"])) ?></span>
    </div>
  </div>

  <!-- Buttons -->
  <div class="ms-auto mt-2 d-flex gap-2 align-items-center">
    <?php if ($comment["user_id"] == $_SESSION["id"]): ?>
      <!-- Bearbeiten -->
      <button type="button"
              class="btn btn-sm btn-outline-dark rounded-pill edit-comment-btn"
              data-comment-id="<?= $comment["id"] ?>"
              data-content="<?= htmlspecialchars($comment["content"], ENT_QUOTES) ?>">
        <i class="bi bi-pencil"></i>
      </button>
      <!-- Löschen -->
      <button type="button"
              class="btn btn-sm btn-outline-danger rounded-pill delete-comment-btn"
              data-comment-id="<?= $comment["id"] ?>">
        <i class="bi bi-trash"></i>
      </button>
    <?php endif; ?>
    <!-- Like (immer gleich) -->
    <button type="button"
            class="btn btn-sm rounded-pill like-comment-btn <?= $comment["liked"] ? 'btn-light text-dark' : 'btn-outline-primary' ?>"
            data-comment-id="<?= $comment["id"] ?>">
      <i class="bi bi-hand-thumbs-up me-1"></i>
      <span class="like-count <?= $comment["liked"] ? 'text-dark' : '' ?>"><?= $comment["like_count"] ?></span>
    </button>
  </div>

</div>
