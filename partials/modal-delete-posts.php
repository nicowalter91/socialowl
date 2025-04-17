<!-- ============================
      Modal zum löschen von Posts
  ==============================-->


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-light border border-secondary">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">
            <i class="bi bi-exclamation-triangle me-2 text-warning"></i> Post löschen
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
        </div>
        <div class="modal-body">
          Bist du sicher, dass du diesen Post löschen möchtest?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Abbrechen</button>
          <form action="delete_post.php" method="POST" class="d-inline">
            <input type="hidden" name="post_id">
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash me-1"></i> Ja, löschen
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>