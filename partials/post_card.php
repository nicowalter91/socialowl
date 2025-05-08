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
          class="tweet-profile-image me-3 rounded-circle border border-2"
          style="border-color: var(--color-border) !important;"
          src="<?= BASE_URL ?>/assets/uploads/<?= htmlspecialchars($post["profile_img"]) ?>"
          alt="Profilbild von @<?= htmlspecialchars($post["username"]) ?>"
          width="48" height="48"
          onerror="this.src='<?= BASE_URL ?>/assets/img/default-avatar.png';"
        >
      </a>
      <div>
        <a href="<?= BASE_URL ?>/views/profile.php?username=<?= htmlspecialchars($post['username']) ?>" 
           class="text-decoration-none">
          <h6 class="mb-0 hover-underline" style="color: var(--color-text) !important;">
            <?php if (!empty($post["display_name"])): ?>
              <span class="fw-bold"><?= htmlspecialchars($post["display_name"]) ?></span>
              <small class="text-muted">@<?= htmlspecialchars($post["username"]) ?></small>
            <?php else: ?>
              @<?= htmlspecialchars($post["username"]) ?>
            <?php endif; ?>
          </h6>
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
          class="btn btn-sm btn-outline-dark btn-rounded"
          type="button"
          data-bs-toggle="dropdown"
          aria-expanded="false"
          aria-label="Post-Optionen"
        >
          <i class="bi bi-three-dots-vertical"></i><span class="d-none d-md-inline ms-1">Optionen</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end post-card-dropdown">
          <li>
            <a
              href="#"
              class="dropdown-item edit-post-btn"
              data-post-id="<?= $post['id'] ?>"
              data-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>"
              data-image="<?= !empty($post['image_path'])
                ? BASE_URL . '/assets/posts/' . htmlspecialchars($post['image_path'])
                : '' ?>"
            >
              <i class="bi bi-pencil-square me-2"></i>Bearbeiten
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
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
    <p class="post-text color-text mb-3 fs-6 <?= mb_strlen($post["content"]) > 280 ? 'long-post collapsed' : '' ?>">
      <?php
        $content = htmlspecialchars($post["content"]);
        // URLs automatisch verlinken
        $urlPattern = '/(https?:\/\/[^\s]+)/';
        $content = preg_replace($urlPattern, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">$1</a>', $content);
        // Hashtags verlinken
        $content = preg_replace('/#(\w+)/', '<a href="'.BASE_URL.'/views/search.view.php?q=%23$1" class="hashtag text-primary text-decoration-none">#$1</a>', $content);
        echo nl2br($content);
      ?>
    </p>
    
    <?php if (mb_strlen($post["content"]) > 280): ?>
      <div class="text-center mb-3">
        <button class="btn btn-sm btn-outline-secondary toggle-post-length" data-post-id="<?= $post['id'] ?>">
          <span class="more-text">Mehr anzeigen</span>
          <span class="less-text d-none">Weniger anzeigen</span>
        </button>
      </div>
    <?php endif; ?>

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
            onerror="this.src='<?= BASE_URL ?>/assets/img/placeholder.png'; this.classList.add('image-error');"
          >
          <div class="position-absolute top-0 end-0 m-2 d-none d-md-block">
            <span class="badge bg-dark bg-opacity-75 p-2 rounded-pill">
              <i class="bi bi-arrows-fullscreen"></i>
              <?php 
                if (file_exists(BASE_URL . "/assets/posts/" . $post["image_path"])) {
                  $filesize = filesize(BASE_URL . "/assets/posts/" . $post["image_path"]);
                  echo human_filesize($filesize);
                }
              ?>
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
            <track kind="captions" src="" label="Deutsch">
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
      class="me-2 d-inline like-form"
      data-post-id="<?= $post["id"] ?>"
    >
      <?php if (function_exists('csrf_token')): ?>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <?php endif; ?>
      <input type="hidden" name="post_id" value="<?= $post["id"] ?>">
      <button
        type="button"
        class="btn btn-sm <?= !empty($post["liked_by_me"])
          ? 'btn-light text-dark'
          : 'btn-outline-primary' ?> btn-rounded transition-all like-btn"
        data-liked="<?= !empty($post["liked_by_me"]) ? '1' : '0' ?>"
        title="<?= !empty($post["liked_by_me"]) ? 'Dir gefällt dieser Beitrag' : 'Gefällt mir markieren' ?>"
      >
        <i class="bi <?= !empty($post["liked_by_me"]) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' ?> me-1"></i>
        <span class="d-none d-md-inline"><?= !empty($post["liked_by_me"]) ? 'Gefällt' : 'Gefällt mir' ?></span>
        <span class="badge bg-<?= !empty($post["liked_by_me"]) ? 'dark' : 'primary' ?> ms-1 like-count">
          <?= !empty($post["like_count"]) ? $post["like_count"] : '0' ?>
        </span>
      </button>
    </form>

    <button
      type="button"
      class="btn btn-sm btn-outline-dark btn-rounded toggle-comment-form"
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
      class="btn btn-sm btn-outline-dark btn-rounded ms-auto share-post"
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
                class="btn btn-sm btn-outline-dark btn-rounded emoji-comment-btn"
                title="Emoji einfügen"
              >
                <i class="bi bi-emoji-smile me-1"></i><span class="d-none d-md-inline">Emoji</span>
              </button>
              <div
                class="emoji-picker d-none position-absolute top-100 end-0 mt-2 rounded-4 p-3 shadow-lg bg-dark text-light"
                style="z-index: 1050; width: 320px;"
              ></div>
            </div>

            <!-- Senden-Button -->
            <button type="submit" class="btn btn-sm btn-primary btn-rounded">
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

/* Darkmode-Kompatibilität */
body.dark-mode {
  --color-text: #f8f9fa;
  --color-border: #2c3e50;
}

body:not(.dark-mode) {
  --color-text: #212529;
  --color-border: #dee2e6;
}

/* Lange Posts */
.long-post.collapsed {
  max-height: 300px;
  overflow: hidden;
  position: relative;
}

.long-post.collapsed::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 60px;
  background: linear-gradient(transparent, var(--bs-body-bg));
  pointer-events: none;
}

/* Image Error Styling */
.image-error {
  opacity: 0.7;
  filter: grayscale(0.5);
}

/* Dropdown-Menü für post_card - Light/Dark Mode Support */
.post-card-dropdown {
  --bs-dropdown-bg: var(--bs-dark);
  --bs-dropdown-color: var(--bs-light);
  --bs-dropdown-border-color: var(--bs-gray-700);
  --bs-dropdown-link-hover-bg: rgba(255,255,255,0.1);
  background-color: var(--bs-dropdown-bg) !important;
  color: var(--bs-dropdown-color) !important;
  border: 1px solid var(--bs-dropdown-border-color) !important;
  border-radius: 16px !important;
  box-shadow: 0 8px 32px rgba(0,0,0,0.25), 0 1.5px 6px rgba(0,0,0,0.10);
  transform-origin: top right;
  animation: dropdown-fade 0.2s ease;
}

body:not(.dark-mode) .post-card-dropdown {
  background-color: #f8f9fa !important;
  color: #212529 !important;
  border: 1px solid #dee2e6 !important;
  backdrop-filter: blur(8px);
}

body.dark-mode .post-card-dropdown {
  background-color: #1b2730 !important;
  color: #f8f9fa !important;
  border: 1px solid #2c3e50 !important;
  backdrop-filter: blur(8px);
}

.post-card-dropdown .dropdown-item {
  color: inherit !important;
  border-radius: 10px;
  margin: 0 0.5rem;
  padding: 0.6rem 1.2rem;
  transition: all 0.2s ease;
}

.post-card-dropdown .dropdown-divider {
  border-color: var(--bs-dropdown-border-color) !important;
  margin: 0.3rem 0;
}

.post-card-dropdown .dropdown-item:hover {
  background-color: var(--bs-dropdown-link-hover-bg) !important;
  transform: translateX(3px);
}

.post-card-dropdown .dropdown-item i {
  width: 20px;
  text-align: center;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.post-card-dropdown .dropdown-item:hover i {
  opacity: 1;
}

/* Spezielles Styling für den Löschen-Button im Dropdown */
.post-card-dropdown .dropdown-item.text-danger {
  color: var(--color-danger) !important;
}

.post-card-dropdown .dropdown-item.text-danger:hover {
  background: rgba(220,53,69,0.12) !important;
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
  // Verbesserte Zugänglichkeit für interaktive Elemente
  document.querySelectorAll('.share-post, .toggle-comment-form, .edit-post-btn').forEach(btn => {
    if (!btn.getAttribute('aria-label')) {
      const iconText = btn.querySelector('.bi')?.nextSibling?.textContent?.trim() || 
                      btn.textContent.trim();
      btn.setAttribute('aria-label', iconText);
    }
  });

  // Mehr/Weniger für lange Posts
  document.querySelectorAll('.toggle-post-length').forEach(btn => {
    btn.addEventListener('click', function() {
      const postId = this.dataset.postId;
      const postElement = document.querySelector(`#post-${postId} .long-post`);
      const moreText = this.querySelector('.more-text');
      const lessText = this.querySelector('.less-text');
      
      postElement.classList.toggle('collapsed');
      moreText.classList.toggle('d-none');
      lessText.classList.toggle('d-none');
    });
  });

  // AJAX Like Funktion
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const form = this.closest('.like-form');
      const postId = form.dataset.postId;
      const isLiked = this.dataset.liked === '1';
      const likeCount = this.querySelector('.like-count');
      const csrfToken = form.querySelector('[name="csrf_token"]')?.value;
      
      fetch(`${BASE_URL}/api/like_post.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || ''
        },
        body: JSON.stringify({
          post_id: postId,
          action: isLiked ? 'unlike' : 'like'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update like status
          const newLikeCount = parseInt(data.like_count);
          likeCount.textContent = newLikeCount;
          
          if (isLiked) {
            // Unlike
            this.classList.remove('btn-light', 'text-dark');
            this.classList.add('btn-outline-primary');
            this.querySelector('.bi').classList.remove('bi-hand-thumbs-up-fill');
            this.querySelector('.bi').classList.add('bi-hand-thumbs-up');
            likeCount.classList.remove('bg-dark');
            likeCount.classList.add('bg-primary');
            this.dataset.liked = '0';
            this.title = 'Gefällt mir markieren';
            
            if (this.querySelector('.d-md-inline')) {
              this.querySelector('.d-md-inline').textContent = 'Gefällt mir';
            }
          } else {
            // Like
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-light', 'text-dark');
            this.querySelector('.bi').classList.remove('bi-hand-thumbs-up');
            this.querySelector('.bi').classList.add('bi-hand-thumbs-up-fill');
            likeCount.classList.remove('bg-primary');
            likeCount.classList.add('bg-dark');
            this.dataset.liked = '1';
            this.title = 'Dir gefällt dieser Beitrag';
            
            if (this.querySelector('.d-md-inline')) {
              this.querySelector('.d-md-inline').textContent = 'Gefällt';
            }
          }
        }
      })
      .catch(error => {
        console.error('Fehler beim Like/Unlike:', error);
      });
    });
  });

  // Zeitstempel formatieren
  document.querySelectorAll('.post-timestamp').forEach(timestamp => {
    const dateStr = timestamp.dataset.timestamp;
    if (dateStr) {
      const postDate = new Date(dateStr);
      const now = new Date();
      const diffMs = now - postDate;
      const diffMins = Math.floor(diffMs / (1000 * 60));
      const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
      const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
      
      // Relative Zeitangaben für bessere Nutzerfreundlichkeit
      if (diffMins < 60) {
        timestamp.textContent = `vor ${diffMins} ${diffMins === 1 ? 'Minute' : 'Minuten'}`;
      } else if (diffHours < 24) {
        timestamp.textContent = `vor ${diffHours} ${diffHours === 1 ? 'Stunde' : 'Stunden'}`;
      } else if (diffDays < 7) {
        timestamp.textContent = `vor ${diffDays} ${diffDays === 1 ? 'Tag' : 'Tagen'}`;
      }
      // Bei älteren Posts das vorhandene Format beibehalten
    }
  });

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
        button.classList.add('btn-outline-dark');
      }, 2000);
    }).catch(err => {
      console.error('Fehler beim Kopieren:', err);
    });
  }

  // Error-Handling für Bilder verbessern
  document.querySelectorAll('.tweet-media').forEach(img => {
    img.addEventListener('error', function() {
      this.src = BASE_URL + '/assets/img/placeholder.png';
      this.classList.add('image-error');
    });
  });
});

// Hilfsfunktion für menschenlesbare Dateigrößen
function human_filesize(bytes, decimals = 1) {
  if (bytes === 0) return '0 B';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
</script>

<?php
// Hilfsfunktion für menschenlesbare Dateigrößen (PHP-Variante)
if (!function_exists('human_filesize')) {
  function human_filesize($bytes, $decimals = 1) {
    if ($bytes === 0) return '0 B';
    $k = 1024;
    $dm = $decimals < 0 ? 0 : $decimals;
    $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
  }
}
?>
