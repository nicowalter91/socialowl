<?php
/**
 * Partial: Post-Formular
 * Stellt das Formular zum Erstellen oder Bearbeiten eines Posts bereit.
 * Erwartet: ggf. $post (assoziatives Array) für Bearbeitung.
 */
?>
<!-- ============================
     Post Formular Posts
  ============================ -->

<form action="<?= BASE_URL ?>/controllers/create_post.php" method="POST" enctype="multipart/form-data" class="tweet-box p-4 d-flex flex-column">
  <!-- Edit-Modus Hinweis -->
  <div id="edit-mode-indicator" class="alert alert-info py-2 mb-4 d-none">
    <i class="bi bi-pencil-square me-2"></i>Du bearbeitest einen bestehenden Beitrag
  </div>

  <!-- Beitrag Inhalt -->
  <div class="d-flex align-items-start mb-3">
    <img class="tweet-profile-image me-3 rounded-circle border border-2 border-secondary"
      src="<?= BASE_URL ?>/assets/uploads/<?= $_SESSION["profile_img"] ?? DEFAULT_PROFILE_IMG ?>"
      width="50" height="50"
      alt="Profilbild">

    <div class="flex-grow-1 d-flex flex-column">
      <textarea name="content" class="form-control tweet-input-box text-light border-0 rounded-4 px-3 py-2 flex-grow-1"
        rows="2" placeholder="Was machst du gerade?" style="min-height: 70px;" required></textarea>
      <div class="mt-1 text-end">
        <small id="char-counter" class="text-light">0/280</small>
      </div>
    </div>
  </div>

  <!-- Vorschau -->
  <div id="media-preview" class="position-relative mt-2 mb-2 rounded-3 overflow-hidden shadow-sm d-inline-block bg-dark-subtle w-100">
    <!-- Entfernen-Icon -->
    <button type="button"
      id="remove-preview"
      class="btn btn-sm btn-outline-danger rounded-circle position-absolute top-0 end-0 m-3 d-none"
      aria-label="Vorschau entfernen"
      title="Vorschau entfernen"
      style="z-index: 10; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
      <i class="bi bi-trash"></i>
    </button>

    <!-- Bild Vorschau -->
    <img id="image-preview"
      src="#"
      alt="Bildvorschau"
      class="d-none w-100"
      style="max-height: 320px; object-fit: contain;">

    <!-- Video Vorschau -->
    <video id="video-preview"
      controls
      class="d-none w-100"
      style="max-height: 320px; object-fit: contain;">
      <source src="#" type="video/mp4">
      Dein Browser unterstützt keine Videoanzeige.
    </video>
    
    <!-- Upload Progress -->
    <div id="upload-progress-container" class="position-absolute bottom-0 start-0 end-0 d-none">
      <div class="progress" style="height: 8px; border-radius: 0;">
        <div id="upload-progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
      </div>
    </div>
  </div>

  <!-- Versteckte Felder -->
  <input type="hidden" id="edit-post-id" name="edit_post_id">
  <input type="hidden" id="original-image-path" name="original_image_path">

  <!-- Footer mit Buttons -->
  <div class="mt-1 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-1 border-top border-secondary-subtle">
    <div class="d-flex gap-3 flex-wrap py-2">
      <!-- Bild Upload -->
      <label for="file-upload-image" class="btn btn-sm btn-outline-dark rounded-pill transition-all" tabindex="0" role="button" title="Bild hochladen">
        <i class="bi bi-image-fill me-2"></i><span class="d-none d-md-inline">Bild</span>
        <span class="visually-hidden">Maximal 5MB, JPG/PNG/WEBP</span>
      </label>
      <input type="file" name="image" id="file-upload-image" style="display: none;" accept="image/jpeg,image/png,image/webp">

      <!-- Video Upload -->
      <label for="file-upload-video" class="btn btn-sm btn-outline-dark rounded-pill transition-all" tabindex="0" role="button" title="Video hochladen">
        <i class="bi bi-camera-reels me-2"></i><span class="d-none d-md-inline">Video</span>
        <span class="visually-hidden">Maximal 50MB, MP4</span>
      </label>
      <input type="file" name="video" id="file-upload-video" style="display: none;" accept="video/mp4">      <!-- Emoji Button + Picker -->
      <div class="position-relative">
        <button
          type="button"
          id="emoji-picker-btn"
          class="btn btn-sm btn-outline-dark rounded-pill transition-all"
          title="Emoji einfügen">
          <i class="bi bi-emoji-smile me-2"></i><span class="d-none d-md-inline">Emoji</span>
        </button>
        <div class="emoji-picker d-none position-absolute p-3 shadow-lg rounded-4 bg-dark text-light" style="z-index: 100; width: 320px;">
          <!-- Hier wird der Emoji-Picker via JavaScript geladen -->
        </div>
      </div>
      
      <!-- File Info -->
      <small class="file-info d-none d-md-inline align-self-center">
        <i class="bi bi-info-circle me-1"></i>Max: Bild 5MB, Video 50MB
      </small>
    </div>

    <!-- Posten -->
    <div id="post-btn-wrapper" class="d-flex gap-2 py-2">
      <button type="submit" id="create-post-btn" class="btn btn-sm btn-primary rounded-pill">
        <i class="bi bi-send me-2"></i><span class="d-none d-md-inline">Posten</span>
      </button>
    </div>

    <!-- Update & Abbrechen -->
    <div id="edit-btn-wrapper" class="d-none d-flex gap-3 py-2">
      <button type="submit" class="btn btn-sm btn-primary rounded-pill">
        <i class="bi bi-check-lg me-2"></i><span class="d-none d-md-inline">Update</span>
      </button>
      <button type="button" id="cancel-edit" class="btn btn-sm btn-outline-dark rounded-pill">
        <i class="bi bi-x-lg me-2"></i><span class="d-none d-md-inline">Abbrechen</span>
      </button>
    </div>
  </div>
</form>

<style>
.transition-all {
  transition: all 0.2s ease;
}

.transition-all:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.tweet-input-box:focus {
  box-shadow: none;
  border: none;
}

.tweet-input-box {
  min-height: 70px !important;
}

/* File info styles for dark/light mode compatibility */
.file-info {
  color: #6c757d; /* Default color (same as text-muted) */
}

body.dark-mode .file-info {
  color: rgba(255, 255, 255, 0.6); /* Lighter color for dark mode */
}

@media (max-width: 576px) {
  .tweet-profile-image {
    width: 40px !important;
    height: 40px !important;
  }
  
  .tweet-box {
    padding: 0.5rem !important;
  }
  
  .btn {
    padding: 0.25rem 0.5rem !important;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const textarea = document.querySelector('textarea[name="content"]');
  const charCounter = document.getElementById('char-counter');
  const maxLength = 280;
  
  if (textarea && charCounter) {
    textarea.addEventListener('input', function() {
      const currentLength = this.value.length;
      charCounter.textContent = `${currentLength}/${maxLength}`;
      
      if (currentLength >= maxLength * 0.9) {
        charCounter.classList.add('text-danger');
      } else {
        charCounter.classList.remove('text-danger');
      }
    });
  }
});
</script>