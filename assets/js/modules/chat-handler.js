/**
 * Chat-Handler Modul
 * Verwaltet die Chat-Funktionalität
 */

export class ChatHandler {
  constructor() {
    this.currentUserId = parseInt(document.body.dataset.currentUserId, 10) || null;
    this.chatCurrentUserId = null;
    this.chatCurrentChatId = null;
    this.chatPollingInterval = null;
    this.chatEmojiHandler = null;
    this.chatUserListCache = [];
    this.chatUserListFiltered = [];
    this.chatPartnerName = '';
    
    // DOM Elemente
    this.chatModal = document.getElementById('chatModal');
    this.chatUserSearch = document.getElementById('chat-user-search');
    this.chatSendForm = document.getElementById('chat-send-form');
    
    this.init();
  }
  
  init() {
    // EmojiHandler importieren und für Chat initialisieren
    import('./emoji-handler.js').then(module => {
      this.chatEmojiHandler = new module.EmojiHandler();
      if (this.chatModal) {
        this.chatModal.addEventListener('show.bs.modal', async () => {
          await this.loadChatUserList();
          this.clearChatWindow();
          this.initChatEmojiPicker();
        });
        // Falls das Modal schon offen ist, Emoji Picker sofort initialisieren
        if (this.chatModal.classList.contains('show')) {
          this.initChatEmojiPicker();
        }
      }
    });
    
    // Suchfunktion für Chat-Userliste
    if (this.chatUserSearch) {
      this.chatUserSearch.addEventListener('input', () => {
        const val = this.chatUserSearch.value.trim().toLowerCase();
        this.chatUserListFiltered = this.chatUserListCache.filter(user => 
          user.username.toLowerCase().includes(val)
        );
        this.renderChatUserList(this.chatUserListFiltered);
      });
    }
    
    // Chat-Nachricht senden
    if (this.chatSendForm) {
      this.chatSendForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('chat-message-input');
        if (!input || !this.chatCurrentUserId || !input.value.trim()) return;
        const msg = input.value.trim();
        input.value = '';
        try {
          const response = await fetch('/Social_App/controllers/api/chat_send.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: this.chatCurrentUserId, message: msg })
          });
          const data = await response.json();
          if (!data.success) {
            alert('Fehler beim Senden: ' + (data.message || 'Unbekannter Fehler'));
            return;
          }
          await this.loadChatMessages();
        } catch (err) {
          alert('Netzwerkfehler beim Senden der Nachricht!');
        }
      });
    }
    
    // Ungelesene Nachrichten Badge aktualisieren
    this.updateChatBadge();
    setInterval(() => this.updateChatBadge(), 5000);
  }
  
  initChatEmojiPicker() {
    const emojiBtn = document.getElementById('emoji-btn');
    const chatInput = document.getElementById('chat-message-input');
    // Picker-Div wie im Handler suchen (ohne ID)
    const pickerContainer = emojiBtn?.closest('.position-relative')?.querySelector('.emoji-picker');
    
    if (emojiBtn && chatInput && pickerContainer && this.chatEmojiHandler) {
      pickerContainer.classList.add('emoji-picker', 'd-none', 'position-absolute');
      emojiBtn.classList.add('position-relative');
      pickerContainer.innerHTML = '';
      this.chatEmojiHandler.initCommonEmojiPicker(emojiBtn, chatInput);
      
      // Theme-Klassen direkt setzen
      const mode = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
      if (mode === 'dark') {
        pickerContainer.classList.add('emoji-picker-dark');
        pickerContainer.classList.remove('emoji-picker-light');
      } else {
        pickerContainer.classList.add('emoji-picker-light');
        pickerContainer.classList.remove('emoji-picker-dark');
      }
    }
  }
  
  async loadChatUserList() {
    const list = document.getElementById('chat-user-list');
    if (!list) return;
    
    list.innerHTML = '<li class="text-center text-secondary py-2">Lade...</li>';
    
    try {
      const res = await fetch('/Social_App/controllers/api/chat_followers.php');
      const data = await res.json();
      
      if (data.success && data.followers.length) {
        this.chatUserListCache = data.followers;
        this.chatUserListFiltered = data.followers;
        this.renderChatUserList(this.chatUserListFiltered);
      } else {
        list.innerHTML = '<li class="text-center text-secondary py-2">Keine Follower gefunden.</li>';
      }
    } catch (e) {
      list.innerHTML = '<li class="text-center text-danger py-2">Fehler beim Laden.</li>';
    }
  }
  
  renderChatUserList(users) {
    const list = document.getElementById('chat-user-list');
    if (!list) return;
    
    list.innerHTML = '';
    
    if (!users.length) {
      list.innerHTML = '<li class="text-center text-secondary py-2">Keine Nutzer gefunden.</li>';
      return;
    }
    
    users.forEach(user => {
      const isOnline = user.last_active && (new Date(user.last_active).getTime() > Date.now() - 5 * 60 * 1000);
      const li = document.createElement('li');
      li.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2 cursor-pointer rounded-4 bg-dark text-light border-0 mb-1';
      li.innerHTML = `
        <img src="/Social_App/assets/uploads/${user.profile_img || 'profil.png'}" class="rounded-circle" width="36" height="36" alt="Profil">
        <span class="position-relative">@${user.username}
          ${user.unread_count && user.unread_count > 0 ? `<span class=\"notification-badge bg-danger position-absolute top-0 start-100 translate-middle\" style=\"right:-10px;top:-6px;min-width:18px;height:18px;font-size:12px;display:inline-flex;align-items:center;justify-content:center;z-index:2;\">${user.unread_count}</span>` : ''}
        </span>
        <span class="ms-2 align-self-center">
          <span class="rounded-circle d-inline-block" style="width:12px;height:12px; background:${isOnline ? '#28a745' : '#dc3545'}"></span>
        </span>
        <button class="btn btn-sm btn-link text-danger ms-auto delete-chat-user-btn" title="Chat mit @${user.username} löschen" style="opacity:0.7;">
          <i class="bi bi-trash"></i>
        </button>
      `;
      
      li.style.cursor = 'pointer';
      li.tabIndex = 0;
      
      li.addEventListener('click', (e) => {
        if (e.target.closest('.delete-chat-user-btn')) return;
        document.querySelectorAll('#chat-user-list .list-group-item').forEach(el => {
          el.classList.remove('active', 'bg-secondary', 'text-light');
          el.classList.add('bg-dark', 'text-light');
        });
        li.classList.add('active', 'bg-secondary', 'text-light');
        li.classList.remove('bg-dark');
        this.chatPartnerName = user.username;
        this.openChatWithUser(user.id);
      });
      
      li.addEventListener('keydown', (e) => { 
        if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.delete-chat-user-btn')) {
          document.querySelectorAll('#chat-user-list .list-group-item').forEach(el => {
            el.classList.remove('active', 'bg-secondary', 'text-light');
            el.classList.add('bg-dark', 'text-light');
          });
          li.classList.add('active', 'bg-secondary', 'text-light');
          li.classList.remove('bg-dark');
          this.chatPartnerName = user.username;
          this.openChatWithUser(user.id);
        }
      });
      
      li.querySelector('.delete-chat-user-btn').addEventListener('click', async (e) => {
        e.stopPropagation();
        if (!confirm(`Diesen Chat mit @${user.username} wirklich löschen?`)) return;
        
        await fetch('/Social_App/controllers/api/chat_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: user.id })
        });
        
        this.clearChatWindow();
        await this.loadChatUserList();
        
        const partnerNameEl = document.getElementById('chat-partner-name');
        if (partnerNameEl) partnerNameEl.textContent = '';
      });
      
      list.appendChild(li);
    });
  }
  
  clearChatWindow() {
    const msgBox = document.getElementById('chat-messages');
    if (msgBox) msgBox.innerHTML = '<div class="text-center text-secondary mt-5">Wähle einen Nutzer aus der Liste.</div>';
    
    this.chatCurrentUserId = null;
    this.chatCurrentChatId = null;
    
    if (this.chatPollingInterval) clearInterval(this.chatPollingInterval);
    
    // Eingabefeld und Senden-Button deaktivieren
    const input = document.getElementById('chat-message-input');
    const sendBtn = document.querySelector('#chat-send-form button[type="submit"]');
    
    if (input) input.disabled = true;
    if (sendBtn) sendBtn.disabled = true;
  }
  
  async openChatWithUser(userId) {
    this.chatCurrentUserId = userId;
    
    // Eingabefeld und Senden-Button aktivieren
    const input = document.getElementById('chat-message-input');
    const sendBtn = document.querySelector('#chat-send-form button[type="submit"]');
    
    if (input) input.disabled = false;
    if (sendBtn) sendBtn.disabled = false;
    
    // Username anzeigen
    const partnerNameEl = document.getElementById('chat-partner-name');
    if (partnerNameEl) partnerNameEl.textContent = '@' + this.chatPartnerName;
    
    // Chat löschen Button-Event immer neu binden
    const deleteChatBtn = document.getElementById('delete-chat-btn');
    if (deleteChatBtn) {
      deleteChatBtn.onclick = async () => {
        if (!this.chatCurrentUserId) return;
        if (!confirm('Diesen Chat wirklich löschen?')) return;
        
        await fetch('/Social_App/controllers/api/chat_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: this.chatCurrentUserId })
        });
        
        this.clearChatWindow();
        await this.loadChatUserList();
        
        if (partnerNameEl) partnerNameEl.textContent = '';
      };
    }
    
    await this.loadChatMessages();
    
    if (this.chatPollingInterval) clearInterval(this.chatPollingInterval);
    this.chatPollingInterval = setInterval(() => this.loadChatMessages(), 3000);
    
    setTimeout(() => {
      if (input) input.focus();
    }, 100);
  }
  
  async loadChatMessages() {
    if (!this.chatCurrentUserId) return;
    
    const msgBox = document.getElementById('chat-messages');
    if (!msgBox) return;
    
    try {
      const res = await fetch(`/Social_App/controllers/api/chat_messages.php?user_id=${this.chatCurrentUserId}`);
      const data = await res.json();
      
      if (data.success) {
        this.chatCurrentChatId = data.chat_id;
        msgBox.innerHTML = '';
        
        if (data.messages.length === 0) {
          msgBox.innerHTML = '<div class="text-center text-secondary mt-5">Noch keine Nachrichten. Schreibe die erste Nachricht!</div>';
        } else {
          data.messages.forEach(msg => {
            const isMe = msg.sender_id == this.currentUserId;
            const msgDiv = document.createElement('div');
            msgDiv.className = `d-flex mb-2 ${isMe ? 'justify-content-end' : 'justify-content-start'}`;
            msgDiv.innerHTML = `<div class="p-2 rounded-4 ${isMe ? 'bg-primary text-white' : 'bg-secondary text-light'}" style="max-width:70%;word-break:break-word;">${this.escapeHTML(msg.message)}<div class="small text-end text-light mt-1" style="font-size:0.8em;">${this.formatChatTime(msg.created_at)}</div></div>`;
            msgBox.appendChild(msgDiv);
          });
        }
        
        msgBox.scrollTop = msgBox.scrollHeight;
      }
    } catch (e) {
      // Fehler ignorieren
    }
  }
  
  async updateChatBadge() {
    try {
      const res = await fetch('/Social_App/controllers/api/chat_unread.php');
      const data = await res.json();
      const badge = document.getElementById('chat-badge');
      
      if (badge) {
        badge.textContent = data.unread > 0 ? data.unread : '';
        if (data.unread > 0) {
          badge.classList.remove('d-none');
          badge.style.display = 'inline-flex';
        } else {
          badge.classList.add('d-none');
          badge.style.display = 'none';
        }
      }
    } catch (e) {
      // Fehler ignorieren
    }
  }
  
  formatChatTime(ts) {
    const d = new Date(ts.replace(' ', 'T'));
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }
  
  escapeHTML(text) {
    if (!text) return "";
    return text.replace(/[&<>"']/g, (match) => {
      switch (match) {
        case "&": return "&amp;";
        case "<": return "&lt;";
        case ">": return "&gt;";
        case '"': return "&quot;";
        case "'": return "&#039;";
      }
    });
  }
}