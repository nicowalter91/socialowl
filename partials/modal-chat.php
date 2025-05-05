<!--
  Partial: Chat-Modal
  Stellt das Chat-Fenster als Bootstrap-Modal bereit.
  Userliste und Nachrichten werden per JavaScript geladen.
-->

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content theme-card chat-modal-container shadow-lg p-3">
      <div class="modal-header border-0 px-4">
        <h5 class="modal-title" id="chatModalLabel"><i class="bi bi-chat-dots-fill me-2 text-primary"></i>Chat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex p-0" style="height: 520px;">
        <!-- Userliste mit Suchleiste -->
        <div class="chat-sidebar">
          <div class="chat-search-container">
            <div class="input-group">
              <span class="input-group-text bg-transparent border-0 ps-3 pe-1">
                <i class="bi bi-search text-secondary"></i>
              </span>
              <input type="text" id="chat-user-search" class="form-control bg-transparent border-0 ps-0" 
                placeholder="Nutzer suchen..." autocomplete="off" />
            </div>
          </div>
          <div class="chat-user-list-container">
            <ul class="list-group chat-user-list" id="chat-user-list">
              <!-- User werden per JS geladen -->
            </ul>
          </div>
        </div>
        
        <!-- Chat Bereich -->
        <div class="chat-content">
          <div class="chat-header">
            <div class="d-flex justify-content-between align-items-center">
              <div class="chat-partner">
                <span id="chat-partner-name" class="fw-bold fs-5"></span>
                <small class="text-secondary chat-status d-none">Online</small>
              </div>
            </div>
          </div>
          
          <div id="chat-messages" class="chat-messages">
            <!-- Nachrichten werden per JS geladen -->
          </div>
          
          <form id="chat-send-form" class="chat-input">
            <div class="input-group align-items-end chat-input-group">
              <button type="button" id="emoji-btn" class="btn btn-emoji" title="Emoji einfügen">
                <i class="bi bi-emoji-smile"></i>
              </button>
              <div class="emoji-picker d-none position-absolute"></div>
              <input type="text" id="chat-message-input" class="form-control chat-message-input" 
                placeholder="Nachricht..." autocomplete="off" />
              <button type="submit" class="btn btn-send">
                <i class="bi bi-send-fill"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
