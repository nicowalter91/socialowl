<?php
/**
 * Partial: Modal Post-Formular
 * Stellt das Post-Formular als Modal-Dialog bereit
 */
?>

<!-- Post-Modal -->
<div class="modal fade" id="postFormModal" tabindex="-1" aria-labelledby="postFormModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-0">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="postFormModalLabel">Neuen Beitrag erstellen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <form action="<?= BASE_URL ?>/controllers/create_post.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column">
          <!-- Edit-Modus Hinweis -->
          <div id="edit-mode-indicator-modal" class="alert alert-info py-2 mb-4 d-none">
            <i class="bi bi-pencil-square me-2"></i>Du bearbeitest einen bestehenden Beitrag
          </div>

          <!-- Beitrag Inhalt -->
          <div class="d-flex align-items-start mb-3">
            <img class="tweet-profile-image me-3 rounded-circle border border-2 border-secondary"
              src="<?= BASE_URL ?>/assets/uploads/<?= $_SESSION["profile_img"] ?? DEFAULT_PROFILE_IMG ?>"
              width="50" height="50"
              alt="Profilbild">

            <div class="flex-grow-1 d-flex flex-column">              <textarea name="content" class="form-control tweet-input-box text-light border-0 rounded-4 px-3 py-2 flex-grow-1"
                rows="4" placeholder="Was machst du gerade?" style="height: 120px; max-height: 120px; resize: none;" required></textarea>
              <div class="mt-1 text-end">
                <small id="char-counter-modal" class="text-light">0/280</small>
              </div>
            </div>
          </div>

          <!-- Media Upload & Submit -->
          <div class="d-flex align-items-center justify-content-between mt-2">
            <div class="d-flex align-items-center">
              <!-- Medien-Upload -->
              <div class="position-relative me-2">
                <label for="image-upload-modal" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Bild oder Video hinzufügen">
                  <i class="bi bi-image"></i>
                </label>
                <input type="file" name="image" id="image-upload-modal" class="d-none" accept="image/*,video/*">
              </div>
              
              <!-- Emoji-Picker -->
              <div class="position-relative me-2">
                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle p-2 emoji-btn" title="Emoji einfügen">
                  <i class="bi bi-emoji-smile"></i>
                </button>
                <div class="emoji-picker d-none position-absolute end-0 mt-2 rounded-4 p-3 shadow-lg bg-dark text-light"
                  style="z-index: 1050; width: 320px;">
                  <!-- Emoji-Picker Content wird per JavaScript geladen -->
                </div>
              </div>

              <!-- Vorschau -->
              <div id="media-preview-modal" class="d-none position-relative ms-3">
                <img id="media-preview-img-modal" src="#" alt="Medienvorschau" class="rounded-3" style="max-height: 80px; max-width: 80px;">
                <button type="button" id="remove-media-modal" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0" 
                        style="transform: translate(25%, -25%);">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary rounded-pill px-4">
              <i class="bi bi-send me-1"></i>Posten
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// JavaScript für das Modal-Post-Formular
document.addEventListener('DOMContentLoaded', function() {
  // Character counter
  const textareaModal = document.querySelector('#postFormModal textarea');
  const counterModal = document.querySelector('#char-counter-modal');
  
  if (textareaModal && counterModal) {
    textareaModal.addEventListener('input', function() {
      const count = this.value.length;
      counterModal.textContent = `${count}/280`;
      
      if (count > 280) {
        counterModal.classList.add('text-danger');
      } else {
        counterModal.classList.remove('text-danger');
      }
    });
  }
  
  // Media preview
  const fileInputModal = document.getElementById('image-upload-modal');
  const mediaPreviewModal = document.getElementById('media-preview-modal');
  const previewImgModal = document.getElementById('media-preview-img-modal');
  const removeMediaBtnModal = document.getElementById('remove-media-modal');
  
  if (fileInputModal && mediaPreviewModal && previewImgModal && removeMediaBtnModal) {
    fileInputModal.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
          previewImgModal.src = e.target.result;
          mediaPreviewModal.classList.remove('d-none');
        };
        
        reader.readAsDataURL(file);
      }
    });
    
    removeMediaBtnModal.addEventListener('click', function() {
      fileInputModal.value = '';
      mediaPreviewModal.classList.add('d-none');
    });
  }
});
</script>
