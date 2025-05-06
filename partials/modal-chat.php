<!--
  Partial: Chat-Modal
  Stellt das Chat-Fenster als Bootstrap-Modal bereit.
  Userliste und Nachrichten werden per JavaScript geladen.
-->

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content theme-card chat-modal-container shadow-lg">
      <div class="modal-header border-0 px-4 py-3">
        <h5 class="modal-title fw-bold" id="chatModalLabel">
          <i class="bi bi-chat-dots-fill me-2 text-primary"></i>Chat
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex p-0" style="height: 520px;">
        <!-- Userliste mit Suchleiste -->
        <div class="chat-sidebar">
          <div class="chat-search-container py-3 px-3 ">
            <div class="input-group bg-transparent rounded-pill search-wrapper">
              <span class="input-group-text bg-transparent border-0 ps-3 pe-1">
                <i class="bi bi-search text-secondary"></i>
              </span>
              <input type="text" id="chat-user-search" class="form-control bg-transparent border-0 ps-0" 
                placeholder="Nutzer suchen..." autocomplete="off" />
            </div>
          </div>
            <div class="chat-user-list-container px-3">
            <ul class="list-group chat-user-list" id="chat-user-list">
              <!-- User werden per JS geladen -->
              <div class="text-center p-4 text-secondary">
              <div class="spinner-border spinner-border-sm me-2" role="status">
                <span class="visually-hidden">Lädt...</span>
              </div>
              Lade Kontakte...
              </div>
            </ul>
            </div>
        </div>
        
        <!-- Chat Bereich -->
        <div class="chat-content">
          <div class="chat-header py-3 px-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
              <div class="chat-partner">
                <div class="d-flex align-items-center">
                  
                  <div>
                    <span id="chat-partner-name" class="fw-bold fs-5">Wähle einen Kontakt</span>
                    <div class="d-flex align-items-center mt-1">
                     
                      <small id="chat-partner-status" class="text-secondary chat-status d-none">Online</small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="chat-actions">
                <button class="btn btn-sm btn-outline-secondary rounded-circle d-none" id="chat-delete-btn" 
                        title="Chat löschen">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
          
          <div id="chat-messages" class="chat-messages">
            <!-- Chat Platzhalter Anzeige, wenn kein Chat ausgewählt ist -->
            <div class="chat-placeholder d-flex flex-column align-items-center justify-content-center h-100 text-center px-4">
              <div class="chat-placeholder-icon mb-4">
                <i class="bi bi-chat-square-text fs-1 text-secondary opacity-50"></i>
              </div>
              <h5 class="fw-bold mb-3">Deine Nachrichten</h5>
              <p class="text-secondary">Wähle einen Kontakt aus, um eine Konversation zu starten.</p>
            </div>
            <!-- Nachrichten werden per JS geladen -->
          </div>
          
          <form id="chat-send-form" class="chat-input">
            <div class="input-group align-items-center chat-input-group">
              <button type="button" id="emoji-btn" class="btn btn-outline-secondary rounded-circle btn-emoji me-3" 
                      title="Emoji einfügen">
                <i class="bi bi-emoji-smile"></i>
              </button>
              <div class="emoji-picker d-none position-absolute"></div>
              <div class="chat-input-wrapper flex-grow-1">
                <input type="text" id="chat-message-input" class="form-control chat-message-input" 
                  placeholder="Nachricht schreiben..." autocomplete="off" />
              </div>
              <button type="submit" class="btn btn-primary rounded-circle btn-send ms-3">
                <i class="bi bi-send-fill"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Modernes Chat-Modal Design */
.chat-modal-container {
  border-radius: 20px;
  overflow: hidden;
}

/* Chat Layout */
.chat-sidebar {
  width: 300px;
  border-right: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
  background-color: var(--color-bg-secondary);
}

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: var(--color-card);
}

/* Suche Styling */
.chat-search-container {
  background-color: var(--color-bg-secondary);
}

.search-wrapper {
  background-color: var(--color-input-bg);
  border: 1px solid var(--color-border);
  transition: all 0.2s;
  padding: 4px;
}

.search-wrapper:focus-within {
  box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.25);
  border-color: var(--color-primary);
}

#chat-user-search:focus {
  box-shadow: none;
}

/* Nutzer-Liste */
.chat-user-list-container {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
}

.chat-user-list {
  border-radius: 0;
  background-color: transparent;
}

.chat-user-item {
  display: flex;
  align-items: center;
  padding: 14px 18px;
  border-bottom: 1px solid var(--color-border);
  cursor: pointer;
  transition: all 0.2s;
  background-color: transparent;
  position: relative;
}

.chat-user-item:hover {
  background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.chat-user-item.active {
  background-color: rgba(var(--bs-primary-rgb), 0.1);
  border-left: 3px solid var(--color-primary);
}

.chat-user-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  margin-right: 14px;
  border: 2px solid transparent;
  object-fit: cover;
}

.chat-user-online .chat-user-avatar {
  border-color: var(--bs-success);
}

.chat-user-info {
  flex: 1;
  overflow: hidden;
}

.chat-user-name {
  font-weight: 600;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.chat-last-message {
  font-size: 0.85rem;
  color: var(--color-text-secondary);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 180px;
}

.chat-user-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  min-width: 40px;
  margin-left: 8px;
}

.chat-time {
  font-size: 0.75rem;
  color: var(--color-text-secondary);
}

.chat-unread-count {
  background-color: var(--color-primary);
  color: #fff;
  border-radius: 50%;
  min-width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
  margin-top: 6px;
}

/* Chat Header */
.chat-header {
  background-color: var(--color-card);
  padding: 12px 20px;
  border-bottom: 1px solid var(--color-border);
}

.chat-status-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #adb5bd;
  display: inline-block;
  margin-right: 4px;
}

.chat-status-indicator.online {
  background-color: var(--bs-success);
}

/* Chat Messages */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
  background-color: var(--color-bg);
  display: flex;
  flex-direction: column;
}

.chat-placeholder {
  color: var(--color-text-secondary);
}

.chat-placeholder-icon {
  font-size: 3.5rem;
  opacity: 0.5;
  margin-bottom: 16px;
}

.message-container {
  margin-bottom: 20px;
  max-width: 75%;
  align-self: flex-start;
}

.message-container.outgoing {
  align-self: flex-end;
}

.message-bubble {
  padding: 14px 18px;
  border-radius: 18px;
  position: relative;
  background-color: var(--color-bg-secondary);
  color: var(--color-text);
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message-container.outgoing .message-bubble {
  background-color: var(--color-primary);
  color: white;
  border-bottom-right-radius: 4px;
}

.message-container:not(.outgoing) .message-bubble {
  border-bottom-left-radius: 4px;
}

.message-text {
  word-wrap: break-word;
  line-height: 1.4;
  font-size: 0.95rem;
}

.message-time {
  font-size: 0.75rem;
  color: var(--color-text-secondary);
  margin-top: 6px;
  text-align: right;
  padding-right: 6px;
}

.message-container.outgoing .message-time {
  color: rgba(255,255,255,0.8);
}

/* Chat Input */
.chat-input {
  padding: 18px;
  background-color: var(--color-card);
  border-top: 1px solid var(--color-border);
}

.chat-input-group {
  background-color: var(--color-bg);
  border-radius: 24px;
  padding: 10px 12px;
}

.chat-input-wrapper {
  position: relative;
}

.chat-message-input {
  border: none;
  background-color: transparent;
  resize: none;
  height: 42px;
  border-radius: 20px;
  padding: 10px 18px;
  max-height: 120px;
  font-size: 0.95rem;
}

.chat-message-input:focus {
  outline: none;
  box-shadow: none;
}

.btn-emoji, .btn-send {
  width: 42px;
  height: 42px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-emoji i, .btn-send i {
  font-size: 1.3rem;
}

.btn-send {
  background-color: var(--color-primary);
  border-color: var(--color-primary);
}

/* Emoji Picker */
.emoji-picker {
  bottom: 76px;
  left: 20px;
  width: 320px;
  z-index: 1050;
  border-radius: 16px;
  box-shadow: 0 5px 30px rgba(0,0,0,0.2);
  border: 1px solid var(--color-border);
}

/* Responsive Design für Mobile */
@media (max-width: 767px) {
  .chat-sidebar {
    position: absolute;
    height: 100%;
    left: 0;
    z-index: 1040;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    width: 100%;
  }
  
  .chat-sidebar.show {
    transform: translateX(0);
  }

  .chat-header {
    position: relative;
  }
  
  .back-to-users {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--color-bg-secondary);
    border: none;
    cursor: pointer;
    margin-right: 12px;
  }
  
  .chat-user-item {
    padding: 16px;
  }

  .message-container {
    max-width: 85%;
  }
}

/* Hover-Effekte für Buttons verbessern */
.btn-emoji:hover, .btn-send:hover {
  transform: scale(1.05);
  transition: transform 0.2s ease;
}

.btn-emoji:active, .btn-send:active {
  transform: scale(0.95);
}
</style>