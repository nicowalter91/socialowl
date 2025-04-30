<?php
/**
 * Partial: Post-Card
 * Stellt die Darstellung eines einzelnen Posts inkl. Medien, Like, Kommentar und Optionen dar.
 * Erwartet: $post (assoziatives Array)
 */
if (!isset($post) || empty($post["id"])) return;
?>

<!-- RENDERE post_card.php für Post-ID <?= $post['id'] ?> -->
<div
  class="tweet-card mb-4 p-3 rounded"
  id="post-<?= $post['id'] ?>"
  data-post-id="<?= $post['id'] ?>"
  data-username="@<?= htmlspecialchars($post['username']) ?>"
>
  <!-- Kopfbereich -->
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div class="d-flex align-items-start">
      <img
        class="tweet-profile-image me-3"
        src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($post["profile_img"]) ?>"
        alt="Profilbild"
      >
      <div>
        <h6 class="text-light mb-0">@<?= htmlspecialchars($post["username"]) ?></h6>
        <small
          class="post-timestamp text-light"
          data-timestamp="<?= htmlspecialchars($post['created_at'], ENT_QUOTES) ?>"
        >
          <?= date("d.m.Y H:i", strtotime($post["created_at"])) ?>
        </small>
      </div>
    </div>

    <?php if ($post["user_id"] == $_SESSION["id"]): ?>
      <div class="dropdown">
        <button
          class="btn btn-sm btn-outline-light dropdown-toggle"
          type="button"
          data-bs-toggle="dropdown"
        >
          <i class="bi bi-three-dots-vertical"></i> Optionen
        </button>
        <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary">
          <li>
            <a
              href="#"
              class="dropdown-item text-light edit-post-btn"
              data-post-id="<?= $post['id'] ?>"
              data-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>"
              data-image="<?= !empty($post['image_path'])
                ? BASE_URL . '/assets/posts/' . htmlspecialchars($post['image_path'])
                : '' ?>"
            >
              <i class="bi bi-pencil-square me-2"></i>Bearbeiten
            </a>
          </li>
          <li><hr class="dropdown-divider border-light"></li>
          <li>
            <button
              class="dropdown-item text-danger"
              data-bs-toggle="modal"
              data-bs-target="#deleteModal"
            >
              <i class="bi bi-trash me-2"></i>Löschen
            </button>
          </li>
        </ul>
      </div>
    <?php endif; ?>
  </div>

  <!-- Inhalt -->
  <div class="mb-3 pb-3 border-bottom border-secondary">
    <p class="post-text text-light mb-2">
      <?php
        $content = htmlspecialchars($post["content"]);
        $content = preg_replace('/#(\w+)/', '<span class="hashtag">#$1</span>', $content);
        echo nl2br($content);
      ?>
    </p>

    <?php if (!empty($post["image_path"])): ?>
      <div class="tweet-media-wrapper mb-2 w-100">
        <img
          src="<?= BASE_URL ?>/assets/posts/<?= htmlspecialchars($post["image_path"]) ?>?t=<?= time() ?>"
          alt="Bild"
          class="tweet-media img-fluid rounded-4 shadow-sm"
        >
      </div>
    <?php endif; ?>

    <?php if (!empty($post["video_path"])): ?>
      <div class="tweet-media-wrapper">
        <video
          controls
          class="tweet-media rounded-4 shadow-sm"
          style="max-width: 100%;"
        >
          <source
            src="<?= BASE_URL ?>/assets/posts/<?= htmlspecialchars($post["video_path"]) ?>?t=<?= time() ?>"
            type="video/mp4"
          >
          Dein Browser unterstützt dieses Video nicht.
        </video>
      </div>
    <?php endif; ?>
  </div>

  <!-- Footer Buttons -->
  <div class="d-flex justify-content-start align-items-center gap-3 mb-3">
    <form
      action="<?= BASE_URL ?>/controllers/like_post.php"
      method="POST"
      class="me-2 d-inline"
    >
      <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
      <button
        type="submit"
        class="btn btn-sm <?= !empty($post["liked_by_me"])
          ? 'btn-light text-dark'
          : 'btn-primary' ?>"
      >
        <i class="bi bi-hand-thumbs-up me-1"></i>
        <?= !empty($post["liked_by_me"]) ? 'Gefällt' : 'Gefällt mir' ?>
        <?php if (!empty($post["like_count"])): ?>
          <span class="ms-2 text-light"><?= $post["like_count"] ?></span>
        <?php endif; ?>
      </button>
    </form>

    <button
      type="button"
      class="btn btn-outline-light btn-sm toggle-comment-form"
      data-post-id="<?= $post['id'] ?>"
    >
      <i class="bi bi-chat-left-text me-1"></i>Kommentieren
    </button>
  </div>

   <!-- Kommentarformular -->
   <div class="comment-form d-none mt-3 px-2" id="comment-form-<?= $post['id'] ?>">
    <form
      method="POST"
      class="comment-form-inner d-flex align-items-start gap-2 mb-3"
      data-post-id="<?= $post["id"] ?>"
    >
      <img
        class="rounded-circle mt-1"
        src="<?= BASE_URL ?>/assets/uploads/<?= $_SESSION["profile_img"] ?>"
        alt="Profilbild"
        style="width: 32px; height: 32px;"
      />

      <div class="flex-grow-1 w-100">
        <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
        <input type="hidden" name="edit_comment_id" class="edit-comment-id" value="">

        <!-- nur noch die Textarea hier -->
        <textarea
          name="comment"
          class="tweet-comment-box form-control text-light border-0 rounded-4 px-3 py-2 w-100"
          rows="2"
          placeholder="Schreibe einen Kommentar..."
          required
        ></textarea>

        <!-- Button-Gruppe: Emoji neben Senden -->
        <div class="d-flex justify-content-end gap-2 align-items-center mt-2">
          <!-- ✦ Emoji-Container -->
          <div class="position-relative">
            <button
              type="button"
              class="btn btn-sm btn-outline-light emoji-comment-btn"
              title="Emoji einfügen"
            >
              <i class="bi bi-emoji-smile me-1"></i>Emoji
            </button>
            <div
              class="emoji-picker d-none position-absolute top-100 end-0 mt-2 rounded-4 p-3 shadow-lg bg-dark text-light"
            ></div>
          </div>

          <!-- ✦ Senden-Button -->
          <button type="submit" class="btn btn-sm btn-primary px-3">
            <i class="bi bi-send me-1"></i>Senden
          </button>
        </div>
      </div>
    </form>
  </div>


  <!-- Kommentar-Liste -->
  <div id="comment-list-<?= $post["id"] ?>">
    <?php if (!empty($post["comments"])): ?>
      <?php foreach ($post["comments"] as $comment): ?>
        <?php include PARTIALS . "/comment_item.php"; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
