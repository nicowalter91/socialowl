<!-- ============================
       Post Formular Posts
  ============================ -->

<form method="POST" enctype="multipart/form-data" class="tweet-box p-3 d-flex flex-column">
      <!-- Beitrag Inhalt -->
      <div class="d-flex align-items-start mb-3">
        <img class="tweet-profile-image me-3" src="/Social_App/assets/uploads/<?php echo $_SESSION["profile_img"] ?>" alt="Profilbild">
        <div class="flex-grow-1 d-flex flex-column">
          <textarea name="content" class="form-control tweet-input-box text-light border-0 rounded-4 px-3 py-2 flex-grow-1"
            rows="4" placeholder="Was passiert gerade?" style="min-height: 80px;" required></textarea>
        </div>
      </div>

      <!-- Footer mit Buttons -->
      <!-- Versteckte Felder -->
      <input type="hidden" id="edit-post-id" name="edit_post_id">
      <input type="hidden" id="original-image-path" name="original_image_path">

      <!-- Footer mit Buttons -->
      <div class="mt-auto d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top border-secondary">
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
        </div>

        <!-- Posten -->
        <div id="post-btn-wrapper" class="d-flex gap-2">
          <button type="submit" id="create-post-btn" class="btn btn-sm btn-primary px-4"><i class="bi bi-send-fill me-1"></i>Posten</button>
        </div>

        <!-- Update & Abbrechen -->
        <div id="edit-btn-wrapper" class="d-none d-flex gap-2">
          <button type="submit" class="btn btn-success btn-sm px-4">Update</button>
          <button type="button" id="cancel-edit" class="btn btn-outline-light btn-sm">Abbrechen</button>
        </div>




      </div>

    </form>