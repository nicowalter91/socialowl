<!-- ============================
       Post Formular Posts
  ============================ -->

<form action="<?= BASE_URL ?>/controllers/create_post.php" method="POST" enctype="multipart/form-data" class="tweet-box p-3 d-flex flex-column">
  <!-- Beitrag Inhalt -->
  <div class="d-flex align-items-start mb-3">
    <img class="tweet-profile-image me-3"
      src="<?= BASE_URL ?>/assets/uploads/<?= $_SESSION["profile_img"] ?? DEFAULT_PROFILE_IMG ?>"
      alt="Profilbild">

    <div class="flex-grow-1 d-flex flex-column">
      <textarea name="content" class="form-control tweet-input-box text-light border-0 rounded-4 px-3 py-2 flex-grow-1"
        rows="4" placeholder="Was passiert gerade?" style="min-height: 80px;" required></textarea>
    </div>
  </div>

  <!-- Vorschau -->
  <div id="media-preview" class="position-relative mt-3 rounded overflow-hidden shadow-sm d-inline-block bg-dark-subtle">

    <!-- Entfernen-Icon -->
    <button type="button"
      id="remove-preview"
      class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 d-none"
      aria-label="Vorschau entfernen"
      style="z-index: 10;">
      <i class="bi bi-trash"></i>
    </button>

    <!-- Bild Vorschau -->
    <img id="image-preview"
      src="#"
      alt="Bildvorschau"
      class="d-none w-100"
      style="max-height: 240px; object-fit: cover;">

    <!-- Video Vorschau -->
    <video id="video-preview"
      controls
      class="d-none w-100"
      style="max-height: 240px; object-fit: cover;">
      <source src="#" type="video/mp4">
      Dein Browser unterstützt keine Videoanzeige.
    </video>
  </div>



  <!-- Footer mit Buttons -->
  <!-- Versteckte Felder -->
  <input type="hidden" id="edit-post-id" name="edit_post_id">
  <input type="hidden" id="original-image-path" name="original_image_path">

  <!-- Footer mit Buttons -->
  <div class="mt-auto d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3">
    <div class="d-flex gap-2 flex-wrap">
      <!-- Bild Upload -->
      <label for="file-upload-image" class="btn btn-sm btn-outline-light">
        <i class="bi bi-image-fill me-1"></i> Bild
      </label>
      <input type="file" name="image" id="file-upload-image" style="display: none;" accept="image/*">

      <!-- Video Upload -->
      <label for="file-upload-video" class="btn btn-sm btn-outline-light">
        <i class="bi bi-camera-reels me-1"></i> Video
      </label>
      <input type="file" name="video" id="file-upload-video" style="display: none;" accept="video/*">

      <!-- Emoji Button + Picker -->
      <div class="position-relative">
        <button type="button" class="btn btn-sm btn-outline-light" id="emoji-picker-btn"><i class="bi bi-emoji-smile me-1"></i>Emoji
        </button>

        <div id="emoji-picker" class="emoji-picker d-none position-absolute p-3 shadow-lg rounded-4 bg-dark text-light">
          <input type="text" id="emoji-search" class="form-control bg-light form-control-sm mb-2 text-dark" placeholder="Suchen...">

          <div id="emoji-list" class="emoji-grid">
            <button type="button">😀</button>
            <button type="button">😂</button>
            <button type="button">😍</button>
            <button type="button">😎</button>
            <button type="button">😭</button>
            <button type="button">😡</button>
            <button type="button">👍</button>
            <button type="button">❤️</button>
            <button type="button">🔥</button>
            <button type="button">🎉</button>
            <button type="button">👏</button>
            <button type="button">💯</button>
          </div>
        </div>

      </div>





    </div>



    <!-- Posten -->
    <div id="post-btn-wrapper" class="d-flex gap-2">
      <button type="submit" id="create-post-btn" class="btn btn-sm btn-primary px-4"><i class="bi bi-send me-2"></i>Posten</button>
    </div>

    <!-- Update & Abbrechen -->
    <div id="edit-btn-wrapper" class="d-none d-flex gap-2">
      <button type="submit" class="btn btn-success btn-sm px-4">Update</button>
      <button type="button" id="cancel-edit" class="btn btn-outline-light btn-sm">Abbrechen</button>
    </div>




  </div>

</form>