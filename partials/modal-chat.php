<!--
  Partial: Chat-Modal
  Stellt das Chat-Fenster als Bootstrap-Modal bereit.
  Userliste und Nachrichten werden per JavaScript geladen.
-->

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-light shadow-lg p-3">
      <div class="modal-header border-secondary px-4">
        <h5 class="modal-title text-light" id="chatModalLabel"><i class="bi bi-envelope-fill me-2"></i>Chat</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div style="height: 16px;"></div>
      <div class="modal-body d-flex p-0 bg-dark" style="height: 500px;">
        <!-- Userliste mit Suchleiste -->
        <div class="bg-dark" style="width: 250px; overflow-y: auto;">
          <div class="p-2 pb-0 bg-dark">
            <input type="text" id="chat-user-search" class="form-control form-control-sm bg-light text-dark border-secondary mb-2" placeholder="Nutzer suchen..." autocomplete="off" />
          </div>
          <ul class="list-group p-2 gap-2" id="chat-user-list" style="background:transparent;">
            <!-- User werden per JS geladen -->
          </ul>
        </div>
        <!-- Chatfenster -->
        <div class="flex-grow-1 d-flex flex-column ps-4 bg-dark" style="min-width:0;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span id="chat-partner-name"></span>
            <!-- Chat löschen Button entfernt, da jetzt in der Userliste -->
          </div>
          <div id="chat-messages" class="flex-grow-1 mb-2 overflow-auto border rounded-4 p-3 bg-white text-dark" style="height: 350px;">
            <!-- Nachrichten werden per JS geladen -->
          </div>
          <form id="chat-send-form" class="d-flex align-items-center mt-2 gap-2">
            <div class="position-relative">
              <button type="button" id="emoji-btn" class="btn btn-outline-light" title="Emoji einfügen">
                <i class="bi bi-emoji-smile"></i>
              </button>
              <div id="emoji-picker-container" class="emoji-picker d-none position-absolute" style="z-index: 1055;"></div>
            </div>
            <input type="text" id="chat-message-input" class="form-control bg-white text-dark border-secondary" placeholder="Nachricht..." autocomplete="off" />
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-send"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
