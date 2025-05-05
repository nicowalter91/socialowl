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
  class="tweet-card mb-4 p-3 rounded shadow-sm"
  id="post-<?= $post['id'] ?>"
  data-post-id="<?= $post['id'] ?>"
  data-username="@<?= htmlspecialchars($post['username']) ?>"
>
  <!-- Kopfbereich -->
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div class="d-flex align-items-start">
      <a href="<?= BASE_URL ?>/views/profile.php?username=<?= htmlspecialchars($post['username']) ?>" 
         class="text-decoration-none">
        <img
          class="tweet-profile-image me-3 rounded-circle border border-2 border-secondary"
          src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($post["profile_img"]) ?>"
          alt="Profilbild von @<?= htmlspecialchars($post["username"]) ?>"
          width="48" height="48"
        >
      </a>
      <div>
        <a href="<?= BASE_URL ?>/views/profile.php?username=<?= htmlspecialchars($post['username']) ?>" 
           class="text-decoration-none">
          <h6 class="text-light mb-0 hover-underline">@<?= htmlspecialchars($post["username"]) ?></h6>
        </a>
        <small
          class="post-timestamp text-light opacity-75"
          data-timestamp="<?= htmlspecialchars($post['created_at'], ENT_QUOTES) ?>"
          title="<?= date("d.m.Y H:i", strtotime($post["created_at"])) ?>"
        >
          <?= date("d.m.Y H:i", strtotime($post["created_at"])) ?>
        </small>
      </div>
    </div>

    <?php if ($post["user_id"] == $_SESSION["id"]): ?>
      <div class="dropdown">
        <button
          class="btn btn-sm btn-outline-light rounded-pill"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          aria-label="Post-Optionen"
        >
          <i class="bi bi-three-dots-vertical"></i><span class="d-none d-md-inline ms-1">Optionen</span>
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
    <p class="post-text text-light mb-3 fs-6">
      <?php
        $content = htmlspecialchars($post["content"]);
        $content = preg_replace('/#(\w+)/', '<a href="'.BASE_URL.'/views/search.view.php?q=%23$1" class="hashtag text-primary text-decoration-none">#$1</a>', $content);
        echo nl2br($content);
      ?>
    </p>

    <?php if (!empty($post["image_path"])): ?>
      <div class="tweet-media-wrapper mb-2 w-100">
        <a href="<?= BASE_URL ?>/assets/posts/<?= htmlspecialchars($post["image_path"]) ?>?t=<?= time() ?>" 
           data-fancybox="gallery-<?= $post['id'] ?>" 
           class="d-block position-relative">
          <img
            src="<?= BASE_URL ?>/assets/posts/<?= htmlspecialchars($post["image_path"]) ?>?t=<?= time() ?>"
            alt="Bild zum Post von @<?= htmlspecialchars($post["username"]) ?>"
            class="tweet-media img-fluid rounded-4 shadow-sm"
            loading="lazy"
          >
          <div class="position-absolute top-0 end-0 m-2 d-none d-md-block">
            <span class="badge bg-dark bg-opacity-75 p-2 rounded-pill">
              <i class="bi bi-arrows-fullscreen"></i>
            </span>
          </div>
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($post["video_path"])): ?>
      <div class="tweet-media-wrapper">
        <div class="position-relative">
          <video
            controls
            preload="metadata"
            class="tweet-media rounded-4 shadow-sm"
            style="max-width: 100%;"
            poster="<?= BASE_URL ?>/assets/img/video-thumbnail.png"
          >
            <source
              src="<?= BASE_URL ?>/assets/posts/<?= htmlspecialchars($post["video_path"]) ?>?t=<?= time() ?>"
              type="video/mp4"
            >
            Dein Browser unterstützt dieses Video nicht.
          </video>
        </div>
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
          : 'btn-outline-primary' ?> rounded-pill transition-all"
        title="<?= !empty($post["liked_by_me"]) ? 'Dir gefällt dieser Beitrag' : 'Gefällt mir markieren' ?>"
      >
        <i class="bi <?= !empty($post["liked_by_me"]) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' ?> me-1"></i>
        <span class="d-none d-md-inline"><?= !empty($post["liked_by_me"]) ? 'Gefällt' : 'Gefällt mir' ?></span>
        <?php if (!empty($post["like_count"])): ?>
          <span class="badge bg-<?= !empty($post["liked_by_me"]) ? 'dark' : 'primary' ?> ms-1"><?= $post["like_count"] ?></span>
        <?php endif; ?>
      </button>
    </form>

    <button
      type="button"
      class="btn btn-sm btn-outline-light rounded-pill toggle-comment-form"
      data-post-id="<?= $post['id'] ?>"
      title="Kommentar schreiben"
    >
      <i class="bi bi-chat-left-text me-1"></i>
      <span class="d-none d-md-inline">Kommentieren</span>
      <?php if (!empty($post["comments"]) && count($post["comments"]) > 0): ?>
        <span class="badge bg-secondary ms-1"><?= count($post["comments"]) ?></span>
      <?php endif; ?>
    </button>
    
    <button 
      type="button" 
      class="btn btn-sm btn-outline-light rounded-pill ms-auto share-post"
      data-post-url="<?= BASE_URL ?>/views/feed.view.php?post=<?= $post['id'] ?>"
      title="Diesen Post teilen"
    >
      <i class="bi bi-share me-1"></i>
      <span class="d-none d-md-inline">Teilen</span>
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
        class="rounded-circle mt-1 border border-secondary"
        src="<?= BASE_URL ?>/assets/uploads/<?= $_SESSION["profile_img"] ?>"
        alt="Profilbild"
        width="32" height="32"
      />

      <div class="flex-grow-1 w-100">
        <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
        <input type="hidden" name="edit_comment_id" class="edit-comment-id" value="">

        <textarea
          name="comment"
          class="tweet-comment-box form-control text-light border-0 rounded-4 px-3 py-2 w-100"
          rows="2"
          placeholder="Schreibe einen Kommentar..."
          required
        ></textarea>
        
        <div class="d-flex justify-content-between align-items-center mt-2">
          <small class="text-muted char-counter">0/200 Zeichen</small>
          
          <div class="d-flex gap-2 align-items-center">
            <!-- Emoji-Container -->
            <div class="position-relative">
              <button
                type="button"
                class="btn btn-sm btn-outline-light rounded-pill emoji-comment-btn"
                title="Emoji einfügen"
              >
                <i class="bi bi-emoji-smile me-1"></i><span class="d-none d-md-inline">Emoji</span>
              </button>
              <div
                class="emoji-picker d-none position-absolute top-100 end-0 mt-2 rounded-4 p-3 shadow-lg bg-dark text-light"
                style="z-index: 1050; width: 250px;"
              ></div>
            </div>

            <!-- Senden-Button -->
            <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill">
              <i class="bi bi-send me-1"></i><span class="d-none d-md-inline">Senden</span>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- Kommentar-Liste -->
  <div id="comment-list-<?= $post["id"] ?>" class="ps-4 mt-2">
    <?php if (!empty($post["comments"])): ?>
      <div class="comments-header mb-2">
        <small class="text-light fw-bold">
          <i class="bi bi-chat-square-text me-1"></i>
          <?= count($post["comments"]) ?> Kommentar<?= count($post["comments"]) !== 1 ? 'e' : '' ?>
        </small>
      </div>
      <?php foreach ($post["comments"] as $comment): ?>
        <?php include PARTIALS . "/comment_item.php"; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<style>
/* Ergänzende Stile für post_card.php */
.hover-underline:hover {
  text-decoration: underline !important;
}

.transition-all {
  transition: all 0.2s ease;
}

.tweet-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.15) !important;
}

.share-post:focus + .share-options {
  display: block !important;
}

@media (max-width: 576px) {
  .tweet-profile-image {
    width: 40px;
    height: 40px;
  }
}
</style>

<!-- JavaScript für die Share-Funktionalität -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Zeichenzähler für Kommentare
  document.querySelectorAll('.tweet-comment-box').forEach(textarea => {
    textarea.addEventListener('input', function() {
      const maxLength = 200;
      const currentLength = this.value.length;
      const counter = this.closest('.flex-grow-1').querySelector('.char-counter');
      
      if (counter) {
        counter.textContent = `${currentLength}/${maxLength} Zeichen`;
        
        if (currentLength > maxLength * 0.8) {
          counter.classList.add('text-warning');
        } else {
          counter.classList.remove('text-warning');
        }
      }
    });
  });

  // Share Button Funktionalität
  document.querySelectorAll('.share-post').forEach(button => {
    button.addEventListener('click', function() {
      const postUrl = this.getAttribute('data-post-url');
      
      if (navigator.share) {
        // Mobile Web Share API
        navigator.share({
          title: 'Schau dir diesen Post an',
          url: postUrl
        }).catch(err => {
          console.error('Fehler beim Teilen:', err);
          fallbackShare(postUrl);
        });
      } else {
        // Fallback: URL in die Zwischenablage kopieren
        fallbackShare(postUrl);
      }
    });
  });
  
  function fallbackShare(url) {
    navigator.clipboard.writeText(url).then(() => {
      // Temporäre Erfolgsmeldung anzeigen
      const button = document.querySelector(`.share-post[data-post-url="${url}"]`);
      const originalHtml = button.innerHTML;
      
      button.innerHTML = '<i class="bi bi-check-lg me-1"></i><span class="d-none d-md-inline">Kopiert</span>';
      button.classList.remove('btn-outline-light');
      button.classList.add('btn-success');
      
      setTimeout(() => {
        button.innerHTML = originalHtml;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-light');
      }, 2000);
    }).catch(err => {
      console.error('Fehler beim Kopieren:', err);
    });
  }
});
</script>
