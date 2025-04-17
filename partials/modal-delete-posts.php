<!-- ============================
      Modal zum löschen von Posts
  ==============================-->


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
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
          Abbrechen
        </button>
        <form action="delete_post.php" method="POST" class="d-inline">
          <input type="hidden" name="post_id">
          <button type="submit" class="btn btn-danger px-4">
            <i class="bi bi-trash me-2"></i> Ja, löschen
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
