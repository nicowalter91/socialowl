<?php
require_once __DIR__ . '/../includes/config.php';

// Pfade auflösen
$profileImg = isset($_SESSION["profile_img"]) && file_exists(UPLOADS . '/' . $_SESSION["profile_img"])
    ? BASE_URL . "/assets/uploads/" . $_SESSION["profile_img"]
    : BASE_URL . "/assets/img/" . DEFAULT_PROFILE_IMG;

$headerImg = isset($_SESSION["header_img"]) && file_exists(UPLOADS . '/' . $_SESSION["header_img"])
    ? BASE_URL . "/assets/uploads/" . $_SESSION["header_img"]
    : BASE_URL . "/assets/img/" . DEFAULT_HEADER_IMG;
?>

<div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light shadow-lg">
      <form action="<?= BASE_URL ?>/controllers/profil-update.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header border-0">
          <h5 class="modal-title"><i class="bi bi-person-circle me-2"></i>Profil bearbeiten</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Bildvorschau -->
          <div class="position-relative mb-5 text-center">
            <div class="rounded-4 overflow-hidden shadow-sm mb-2" style="height: 200px;">
              <img id="headerPreview" src="<?= $headerImg ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="Headerbild">
            </div>
            <div class="position-absolute top-100 start-50 translate-middle" style="margin-top: -50px;">
              <img id="profilePreview" src="<?= $profileImg ?>" class="rounded-circle border border-3 border-light shadow" width="100" height="100" alt="Profilbild">
            </div>
          </div>

          <!-- Headerbild -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Hintergrundbild</label>
            <input type="file" name="header_img" class="form-control bg-dark text-light border-secondary" accept="image/*" onchange="previewImage(this, 'headerPreview')">
          </div>

          <!-- Profilbild -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Profilbild</label>
            <input type="file" name="profile_img" class="form-control bg-dark text-light border-secondary" accept="image/*" onchange="previewImage(this, 'profilePreview')">
          </div>

          <!-- Bio -->
          <div class="mb-3">
            <label for="bio" class="form-label fw-bold">Bio</label>
            <textarea name="bio" id="bio" class="form-control bg-dark text-light border-secondary pe-5" style="white-space: pre-wrap;" maxlength="150" rows="3"><?= htmlspecialchars($_SESSION["bio"] ?? '') ?></textarea>
            <div class="text-end mt-1">
              <small class="text-light"><span id="bioCounter">0</span>/150 Zeichen</small>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Speichern</button>
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Schließen</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Vorschau + Zeichenzähler -->
<script>
function previewImage(input, targetId) {
  const file = input.files[0];
  const preview = document.getElementById(targetId);
  if (file && preview) {
    const reader = new FileReader();
    reader.onload = e => preview.src = e.target.result;
    reader.readAsDataURL(file);
  }
}

function updateBioCounter() {
  const textarea = document.getElementById("bio");
  const counter = document.getElementById("bioCounter");
  if (textarea && counter) {
    counter.textContent = textarea.value.length;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  updateBioCounter();
  document.getElementById("bio")?.addEventListener("input", updateBioCounter);
});
</script>
