<!-- ============================
     MODAL: PROFIL BEARBEITEN
============================ -->

<div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light border border-secondary shadow-lg">
      <form action="profil-update.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header border-bottom border-secondary">
          <h5 class="modal-title" id="profilModalLabel">
            <i class="bi bi-person-circle me-2"></i> Profil bearbeiten
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
        </div>

        <div class="modal-body">
          
          <!-- Vorschau: Header + Profilbild -->
          <div class="position-relative mb-5 text-center">

            <!-- Header-Bild -->
            <div class="rounded-4 overflow-hidden shadow-sm mb-2" style="height: 200px;">
              <img src="/Social_App/assets/uploads/<?= $_SESSION['header_img'] ?? 'img/Background.jpg' ?>"
                   class="img-fluid w-100 h-100 object-fit-cover" alt="Headerbild">
            </div>

            <!-- Profilbild überlagert -->
            <div class="position-absolute top-100 start-50 translate-middle" style="margin-top: -50px;">
              <img src="/Social_App/assets/uploads/<?= $_SESSION['profile_img'] ?? 'img/profil.png' ?>"
                   class="rounded-circle border border-3 border-light shadow"
                   width="100" height="100" alt="Profilbild">
            </div>
          </div>

          <!-- Upload für Headerbild -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Hintergrundbild</label>
            <input type="file" name="header_img" class="form-control bg-dark text-light border-secondary">
          </div>

          <!-- Upload für Profilbild -->
          <div class="mb-4">
            <label class="form-label fw-bold">Neues Profilbild</label>
            <input type="file" name="profile_img" class="form-control bg-dark text-light border-secondary">
          </div>

          <!-- Bio -->
          <div class="mb-3">
            <label for="bio" class="form-label fw-bold">Bio</label>
            <textarea name="bio" id="bio" class="form-control bg-dark text-light border-secondary"
                      maxlength="160" rows="3"><?= htmlspecialchars($_SESSION["bio"] ?? '') ?></textarea>
            <small class="text-muted">Max. 160 Zeichen</small>
          </div>

        </div>

        <div class="modal-footer border-top border-secondary">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Speichern
          </button>
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Schließen</button>
        </div>
      </form>
    </div>
  </div>
</div>
