<?php
// Sicheres Default-Profilbild
$profileImg = !empty($_SESSION["profile_img"]) && file_exists("assets/uploads/" . $_SESSION["profile_img"])
    ? "assets/uploads/" . $_SESSION["profile_img"]
    : "assets/img/profil.png";

// Sicheres Default-Headerbild
$headerImg = !empty($_SESSION["header_img"]) && file_exists("assets/uploads/" . $_SESSION["header_img"])
    ? "assets/uploads/" . $_SESSION["header_img"]
    : "assets/img/default_header.png";
?>


<!-- ============================
     MODAL: PROFIL BEARBEITEN
============================ -->
<div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light shadow-lg">
      <form action="profil-update.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header" style="border-bottom: none">
          <h5 class="modal-title" id="profilModalLabel">
            <i class="bi bi-person-circle me-2"></i> Profil bearbeiten
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
        </div>

        <div class="modal-body">

          <!-- Vorschau Header + Profilbild -->
          <div class="position-relative mb-5 text-center">

            <!-- Header-Bild -->
            <div class="rounded-4 overflow-hidden shadow-sm mb-2" style="height: 200px;">
              <img id="headerPreview"
                   src="/Social_App/<?= $headerImg ?>"
                   class="img-fluid w-100 h-100 object-fit-cover"
                   alt="Headerbild">
            </div>

            <!-- Profilbild -->
            <div class="position-absolute top-100 start-50 translate-middle" style="margin-top: -50px;">
              <img id="profilePreview"
                   src="/Social_App/<?= $profileImg ?>"
                   class="rounded-circle border border-3 border-light shadow"
                   width="100" height="100"
                   alt="Profilbild">
            </div>
          </div>

          <!-- Upload Header -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Hintergrundbild</label>
            <input type="file" name="header_img" class="form-control bg-dark text-light border-secondary" accept="image/*" onchange="previewImage(this, 'headerPreview')">
          </div>

          <!-- Upload Profilbild -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Profilbild</label>
            <input type="file" name="profile_img" class="form-control bg-dark text-light border-secondary" accept="image/*" onchange="previewImage(this, 'profilePreview')">
          </div>

          <!-- Bio -->
          <div class="mb-3">
            <label for="bio" class="form-label fw-bold">Bio</label>
            <div class="position-relative">
              <textarea name="bio" id="bio" class="form-control bg-dark text-light border-secondary pe-5" rows="3" maxlength="150"><?= htmlspecialchars($_SESSION["bio"] ?? '') ?></textarea>
            </div>
            <div class="text-end mt-1">
              <small class="text-light"><span id="bioCounter">0</span>/150 Zeichen</small>
            </div>
          </div>

        </div>

        <div class="modal-footer" style="border-top: none">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Speichern
          </button>
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Schließen</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script: Vorschau & Zeichenzähler -->
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
    const bioTextarea = document.getElementById("bio");
    if (bioTextarea) {
      updateBioCounter(); // Initial
      bioTextarea.addEventListener("input", updateBioCounter);
    }
  });
</script>


<script>
  function updateBioCounter() {
    const textarea = document.getElementById("bio");
    const counter = document.getElementById("bioCounter");
    if (textarea && counter) {
      counter.textContent = textarea.value.length;
    }
  }

  // Init sobald Seite geladen ist
  document.addEventListener("DOMContentLoaded", () => {
    const bioTextarea = document.getElementById("bio");
    if (bioTextarea) {
      updateBioCounter(); // Initial
      bioTextarea.addEventListener("input", updateBioCounter);
    }
  });
</script>
