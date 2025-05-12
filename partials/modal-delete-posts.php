<!--
  Partial: Modal zum Löschen von Posts
  Zeigt ein Bestätigungs-Modal zum endgültigen Löschen eines Beitrags an.
-->


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-0 shadow-lg rounded-4">

      <!-- Header -->
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
          <h5 class="modal-title mb-0" id="deleteModalLabel">Post wirklich löschen?</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>

      <!-- Body -->
      <div class="modal-body pt-2">
        <p class="mb-0">Diese Aktion kann nicht rückgängig gemacht werden. Willst du den Beitrag wirklich löschen?</p>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 pt-0 d-flex">
        <input type="hidden" id="delete-post-id">

        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-4" data-bs-dismiss="modal">
          Abbrechen
        </button>

        <button type="button" class="btn btn-sm btn-danger rounded-pill confirm-delete-btn px-4">
          <i class="bi bi-trash me-2"></i> Ja, löschen
        </button>
      </div>

    </div>
  </div>
</div>