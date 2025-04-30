/**
 * Hauptskript für die Social App
 * Initialisiert alle Module und Event-Listener
 * Verwaltet die grundlegende Funktionalität der App
 */

// ============================
// DOMContentLoaded Start
// ============================
document.addEventListener("DOMContentLoaded", async () => {
  console.log("✅ script.js geladen");

  // Module importieren und initialisieren
  try {
    // Alle Module parallel laden für bessere Performance
    const [liveUpdatesModule, postHandlerModule, commentHandlerModule, searchHandlerModule] = await Promise.all([
      import('./modules/live-updates.js'),
      import('./modules/post-handler.js'),
      import('./modules/comment-handler.js'),
      import('./modules/search-handler.js')
    ]);

    // Module initialisieren und global verfügbar machen
    window.liveUpdates = new liveUpdatesModule.LiveUpdates();
    window.postHandler = new postHandlerModule.PostHandler();
    window.commentHandler = new commentHandlerModule.CommentHandler();
    window.searchHandler = new searchHandlerModule.SearchHandler();

    console.log("✅ Alle Module erfolgreich initialisiert");
  } catch (error) {
    console.error("❌ Fehler beim Laden der Module:", error);
  }

  // Timestamps für Live-Updates
  let lastPostTimestamp = null;
  let lastCommentTimestamp = null;
  initLastCommentTimestamp();

  // Post-Card Events initialisieren
  initPostCardEvents();

  // ============================
  // Globale DOM Elemente
  // ============================
  const tweetInput = document.querySelector(".tweet-input-box");
  const postBtnWrapper = document.getElementById("post-btn-wrapper");
  const editBtnWrapper = document.getElementById("edit-btn-wrapper");
  const editPostIdInput = document.getElementById("edit-post-id");
  const originalImagePathInput = document.getElementById("original-image-path");
  const cancelEditBtn = document.getElementById("cancel-edit");
  const form = document.querySelector(".tweet-box");
  const feed = document.getElementById("feed");
  const imageInput = document.getElementById("file-upload-image");
  const videoInput = document.getElementById("file-upload-video");
  const imagePreview = document.getElementById("image-preview");
  const videoPreview = document.getElementById("video-preview");
  const removeBtn = document.getElementById("remove-preview");
  const CURRENT_USER_ID = parseInt(document.body.dataset.currentUserId, 10) || null;
  let chatPartnerName = '';

  // ============================
  // Last Comment Timestamp automatisch setzen
  // ============================
  /**
   * Initialisiert den lastCommentTimestamp anhand aller vorhandenen Kommentare
   * Wird für Live-Updates von Kommentaren benötigt
   */
  function initLastCommentTimestamp() {
    const timestampElements = document.querySelectorAll(".comment-timestamp");

    if (timestampElements.length > 0) {
      const timestamps = Array.from(timestampElements)
        .map((el) => new Date(el.dataset.timestamp))
        .filter((date) => !isNaN(date)); // Nur gültige Datumswerte nehmen

      if (timestamps.length > 0) {
        const latest = timestamps.sort((a, b) => b - a)[0]; // neuestes Datum finden

        lastCommentTimestamp =
          latest.getFullYear() +
          "-" +
          String(latest.getMonth() + 1).padStart(2, "0") +
          "-" +
          String(latest.getDate()).padStart(2, "0") +
          " " +
          String(latest.getHours()).padStart(2, "0") +
          ":" +
          String(latest.getMinutes()).padStart(2, "0") +
          ":" +
          String(latest.getSeconds()).padStart(2, "0");
      }
    }
  }

  /**
   * Initialisiert lastPostTimestamp anhand aller bereits
   * gerenderten Posts im HTML.
   * Wird für Live-Updates von Posts benötigt
   */
  function initLastPostTimestamp() {
    const els = document.querySelectorAll(".post-timestamp");
    if (!els.length) return;
    const dates = Array.from(els)
      .map((el) => new Date(el.dataset.timestamp))
      .filter((d) => !isNaN(d))
      .sort((a, b) => b - a);
    const latest = dates[0];
    lastPostTimestamp =
      latest.getFullYear() +
      "-" +
      String(latest.getMonth() + 1).padStart(2, "0") +
      "-" +
      String(latest.getDate()).padStart(2, "0") +
      " " +
      String(latest.getHours()).padStart(2, "0") +
      ":" +
      String(latest.getMinutes()).padStart(2, "0") +
      ":" +
      String(latest.getSeconds()).padStart(2, "0");
  }

  // direkt nach Variablen-Initialisierung aufrufen
  initLastPostTimestamp();

  // ============================
  // Initialisierungen
  // ============================
  /**
   * Initialisiert die Event-Listener für Post-Cards
   * Ermöglicht das Bearbeiten von Posts
   */
  function initPostCardEvents() {
    document.querySelectorAll(".edit-post-btn").forEach((button) => {
      button.addEventListener("click", () => {
        tweetInput.value = button.dataset.content;
        editPostIdInput.value = button.dataset.postId;
        originalImagePathInput.value = button.dataset.image;
        postBtnWrapper.classList.add("d-none");
        editBtnWrapper.classList.remove("d-none");
        window.scrollTo({ top: 100, behavior: "smooth" });
      });
    });
  }

  /**
   * Escaped HTML-Sonderzeichen für sichere Ausgabe
   * @param {string} text - Der zu escapende Text
   * @returns {string} - Der escapte Text
   */
  function escapeHTML(text) {
    if (!text) return "";
    return text.replace(/[&<>"']/g, (match) => {
      switch (match) {
        case "&":
          return "&amp;";
        case "<":
          return "&lt;";
        case ">":
          return "&gt;";
        case '"':
          return "&quot;";
        case "'":
          return "&#039;";
      }
    });
  }

  /**
   * Setzt das Post-Formular zurück
   * Wird nach dem Absenden eines Posts oder beim Abbrechen der Bearbeitung aufgerufen
   */
  function resetPostForm() {
    tweetInput.value = "";
    editPostIdInput.value = "";
    originalImagePathInput.value = "";
    postBtnWrapper.classList.remove("d-none");
    editBtnWrapper.classList.add("d-none");
    removeBtn.click();
  }

  // Event-Listener für den Abbrechen-Button
  cancelEditBtn?.addEventListener("click", resetPostForm);

  // ============================
  // Beitrag löschen (Post)
  // ============================

  document.addEventListener("click", (e) => {
    const deleteTrigger = e.target.closest(".dropdown-item.text-danger");
    if (!deleteTrigger) return;

    const postId = deleteTrigger.closest(".tweet-card")?.dataset.postId;
    if (!postId) return;

    const confirmBtn = document.querySelector(".confirm-delete-btn");
    const hiddenInput = document.getElementById("delete-post-id");

    if (confirmBtn && hiddenInput) {
      hiddenInput.value = postId;
      confirmBtn.dataset.postId = postId;
    }
  });

  document
    .querySelector(".confirm-delete-btn")
    ?.addEventListener("click", async () => {
      const postId = document.getElementById("delete-post-id")?.value;
      if (!postId) return;

      const formData = new FormData();
      formData.append("post_id", postId);

      try {
        const res = await fetch("/Social_App/controllers/delete_post.php", {
          method: "POST",
          body: formData,
        });

        const result = await res.json();
        if (result.success) {
          document
            .querySelector(`.tweet-card[data-post-id="${postId}"]`)
            ?.remove();
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("deleteModal")
          );
          modal?.hide();
        } else {
          alert("⚠️ Fehler beim Löschen des Beitrags:\n" + result.message);
        }
      } catch (err) {
        console.error("❌ Fehler beim Löschen des Beitrags:", err);
        alert("❌ Es ist ein Fehler beim Löschen aufgetreten.");
      }
    });

  // ============================
  // Vorschau für Bild & Video
  // ============================

  imageInput?.addEventListener("change", () => {
    const file = imageInput.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      imagePreview.src = url;
      imagePreview.classList.remove("d-none");
      videoPreview.classList.add("d-none");
      videoPreview.querySelector("source").src = "";
      removeBtn.classList.remove("d-none");
    }
  });

  videoInput?.addEventListener("change", () => {
    const file = videoInput.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      videoPreview.querySelector("source").src = url;
      videoPreview.load();
      videoPreview.classList.remove("d-none");
      imagePreview.classList.add("d-none");
      imagePreview.src = "";
      removeBtn.classList.remove("d-none");
    }
  });

  removeBtn?.addEventListener("click", () => {
    imageInput.value = "";
    videoInput.value = "";
    imagePreview.src = "";
    imagePreview.classList.add("d-none");
    videoPreview.querySelector("source").src = "";
    videoPreview.load();
    videoPreview.classList.add("d-none");
    removeBtn.classList.add("d-none");
  });

  // ============================
  // Live-Update-Listener initialisieren
  // initLiveUpdateListeners(); // ENTFERNT, da dies bereits durch LiveUpdates-Instanz erfolgt

  // ============================
  // Chat-Modal & Chat-Funktionen
  // ============================
  let chatCurrentUserId = null;
  let chatCurrentChatId = null;
  let chatPollingInterval = null;
  let chatEmojiHandler = null;

  // EmojiHandler importieren und für Chat initialisieren
  import('./modules/emoji-handler.js').then(module => {
    chatEmojiHandler = new module.EmojiHandler();
    // Chat-spezifischer Picker
    const emojiBtn = document.getElementById('emoji-btn');
    const chatInput = document.getElementById('chat-message-input');
    const pickerContainer = document.getElementById('emoji-picker-container');
    if (emojiBtn && chatInput && pickerContainer) {
      // Picker-Container als .emoji-picker für Handler
      pickerContainer.classList.add('emoji-picker', 'd-none', 'position-absolute');
      emojiBtn.classList.add('position-relative');
      chatEmojiHandler.initCommonEmojiPicker(emojiBtn, chatInput);
    }
  });

  // Chat-Modal öffnen: Userliste laden
  const chatModal = document.getElementById('chatModal');
  if (chatModal) {
    chatModal.addEventListener('show.bs.modal', async () => {
      await loadChatUserList();
      clearChatWindow();
    });
  }

  async function loadChatUserList() {
    const list = document.getElementById('chat-user-list');
    if (!list) return;
    list.innerHTML = '<li class="text-center text-secondary py-2">Lade...</li>';
    try {
      const res = await fetch('/Social_App/controllers/api/chat_followers.php');
      const data = await res.json();
      if (data.success && data.followers.length) {
        list.innerHTML = '';
        data.followers.forEach(user => {
          const isOnline = user.last_active && (new Date(user.last_active).getTime() > Date.now() - 5 * 60 * 1000); // 5 Minuten
          const li = document.createElement('li');
          li.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2 cursor-pointer rounded-4 bg-dark text-light border-0 mb-1';
          li.innerHTML = `
            <span class="me-2 align-self-center">
              <span class="rounded-circle d-inline-block" style="width:12px;height:12px; background:${isOnline ? '#28a745' : '#dc3545'}"></span>
            </span>
            <img src="/Social_App/assets/uploads/${user.profile_img || 'profil.png'}" class="rounded-circle" width="36" height="36" alt="Profil">
            <span>@${user.username}</span>
            <button class="btn btn-sm btn-link text-danger ms-auto delete-chat-user-btn" title="Chat mit @${user.username} löschen" style="opacity:0.7;">
              <i class="bi bi-trash"></i>
            </button>
          `;
          li.style.cursor = 'pointer';
          li.tabIndex = 0;
          // ...Highlighting und Chat öffnen wie gehabt...
          li.addEventListener('click', (e) => {
            // Verhindere, dass Klick auf den Papierkorb den Chat öffnet
            if (e.target.closest('.delete-chat-user-btn')) return;
            document.querySelectorAll('#chat-user-list .list-group-item').forEach(el => {
              el.classList.remove('active', 'bg-secondary', 'text-light');
              el.classList.add('bg-dark', 'text-light');
            });
            li.classList.add('active', 'bg-secondary', 'text-light');
            li.classList.remove('bg-dark');
            chatPartnerName = user.username;
            openChatWithUser(user.id);
          });
          li.addEventListener('keydown', (e) => { if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.delete-chat-user-btn')) {
            document.querySelectorAll('#chat-user-list .list-group-item').forEach(el => {
              el.classList.remove('active', 'bg-secondary', 'text-light');
              el.classList.add('bg-dark', 'text-light');
            });
            li.classList.add('active', 'bg-secondary', 'text-light');
            li.classList.remove('bg-dark');
            chatPartnerName = user.username;
            openChatWithUser(user.id);
          }});
          // Chat löschen Button für diesen User
          li.querySelector('.delete-chat-user-btn').addEventListener('click', async (e) => {
            e.stopPropagation();
            if (!confirm(`Diesen Chat mit @${user.username} wirklich löschen?`)) return;
            await fetch('/Social_App/controllers/api/chat_delete.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ user_id: user.id })
            });
            clearChatWindow();
            await loadChatUserList();
            const partnerNameEl = document.getElementById('chat-partner-name');
            if (partnerNameEl) partnerNameEl.textContent = '';
          });
          list.appendChild(li);
        });
      } else {
        list.innerHTML = '<li class="text-center text-secondary py-2">Keine Follower gefunden.</li>';
      }
    } catch (e) {
      list.innerHTML = '<li class="text-center text-danger py-2">Fehler beim Laden.</li>';
    }
  }

  function clearChatWindow() {
    const msgBox = document.getElementById('chat-messages');
    if (msgBox) msgBox.innerHTML = '<div class="text-center text-secondary mt-5">Wähle einen Nutzer aus der Liste.</div>';
    chatCurrentUserId = null;
    chatCurrentChatId = null;
    if (chatPollingInterval) clearInterval(chatPollingInterval);
    // Eingabefeld und Senden-Button deaktivieren
    const input = document.getElementById('chat-message-input');
    const sendBtn = document.querySelector('#chat-send-form button[type="submit"]');
    if (input) input.disabled = true;
    if (sendBtn) sendBtn.disabled = true;
  }

  async function openChatWithUser(userId) {
    chatCurrentUserId = userId;
    // Eingabefeld und Senden-Button aktivieren
    const input = document.getElementById('chat-message-input');
    const sendBtn = document.querySelector('#chat-send-form button[type="submit"]');
    if (input) input.disabled = false;
    if (sendBtn) sendBtn.disabled = false;
    // Username anzeigen
    const partnerNameEl = document.getElementById('chat-partner-name');
    if (partnerNameEl) partnerNameEl.textContent = '@' + chatPartnerName;
    // Chat löschen Button-Event immer neu binden
    const deleteChatBtn = document.getElementById('delete-chat-btn');
    if (deleteChatBtn) {
      deleteChatBtn.onclick = async () => {
        if (!chatCurrentUserId) return;
        if (!confirm('Diesen Chat wirklich löschen?')) return;
        await fetch('/Social_App/controllers/api/chat_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: chatCurrentUserId })
        });
        clearChatWindow();
        await loadChatUserList();
        if (partnerNameEl) partnerNameEl.textContent = '';
      };
    }
    await loadChatMessages();
    if (chatPollingInterval) clearInterval(chatPollingInterval);
    chatPollingInterval = setInterval(loadChatMessages, 3000);
    setTimeout(() => {
      if (input) input.focus();
    }, 100);
  }

  async function loadChatMessages() {
    if (!chatCurrentUserId) return;
    const msgBox = document.getElementById('chat-messages');
    if (!msgBox) return;
    try {
      const res = await fetch(`/Social_App/controllers/api/chat_messages.php?user_id=${chatCurrentUserId}`);
      const data = await res.json();
      if (data.success) {
        chatCurrentChatId = data.chat_id;
        msgBox.innerHTML = '';
        if (data.messages.length === 0) {
          msgBox.innerHTML = '<div class="text-center text-secondary mt-5">Noch keine Nachrichten. Schreibe die erste Nachricht!</div>';
        } else {
          data.messages.forEach(msg => {
            const isMe = msg.sender_id == CURRENT_USER_ID;
            const msgDiv = document.createElement('div');
            msgDiv.className = `d-flex mb-2 ${isMe ? 'justify-content-end' : 'justify-content-start'}`;
            msgDiv.innerHTML = `<div class="p-2 rounded-4 ${isMe ? 'bg-primary text-white' : 'bg-secondary text-light'}" style="max-width:70%;word-break:break-word;">${escapeHTML(msg.message)}<div class="small text-end text-light mt-1" style="font-size:0.8em;">${formatChatTime(msg.created_at)}</div></div>`;
            msgBox.appendChild(msgDiv);
          });
        }
        msgBox.scrollTop = msgBox.scrollHeight;
      }
    } catch (e) {
      // Fehler ignorieren
    }
  }

  // Nachricht absenden
  const chatSendForm = document.getElementById('chat-send-form');
  if (chatSendForm) {
    chatSendForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const input = document.getElementById('chat-message-input');
      if (!input || !chatCurrentUserId || !input.value.trim()) return;
      const msg = input.value.trim();
      input.value = '';
      await fetch('/Social_App/controllers/api/chat_send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: chatCurrentUserId, message: msg })
      });
      await loadChatMessages();
    });
  }

  // Zeitformat für Chat
  function formatChatTime(ts) {
    const d = new Date(ts.replace(' ', 'T'));
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  // Badge für ungelesene Nachrichten in Navbar
  async function updateChatBadge() {
    try {
      const res = await fetch('/Social_App/controllers/api/chat_unread.php');
      const data = await res.json();
      const badge = document.getElementById('chat-badge');
      if (badge) {
        badge.textContent = data.unread > 0 ? data.unread : '';
        badge.style.display = data.unread > 0 ? 'inline-flex' : 'none';
      }
    } catch (e) {}
  }
  setInterval(updateChatBadge, 5000);
  updateChatBadge();

  // ============================
  // Light/Dark Mode Umschaltung
  // ============================
  const themeToggle = document.getElementById('theme-toggle');
  const themeToggleIcon = document.getElementById('theme-toggle-icon');

  function setTheme(mode) {
    if (mode === 'dark') {
      document.body.classList.add('dark-mode');
      themeToggleIcon.classList.remove('bi-sun-fill');
      themeToggleIcon.classList.add('bi-moon-stars-fill');
    } else {
      document.body.classList.remove('dark-mode');
      themeToggleIcon.classList.remove('bi-moon-stars-fill');
      themeToggleIcon.classList.add('bi-sun-fill');
    }
  }

  function getPreferredTheme() {
    return localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  }

  function toggleDarkMode() {
    const current = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    setTheme(next);
    localStorage.setItem('theme', next);
  }

  if (themeToggle) {
    setTheme(getPreferredTheme());
    themeToggle.addEventListener('click', () => {
      toggleDarkMode();
    });
  }
});

// ============================
// Live Deletion of Posts and Comments (Fixed)
// ============================
async function notifyDeletion(type, id) {
  try {
    const res = await fetch(`/Social_App/controllers/api/notify_deletion.php?type=${type}&id=${id}`);
    const data = await res.json();

    if (data.success) {
      if (type === "post") {
        const postElement = document.querySelector(`.tweet-card[data-post-id="${id}"]`);
        if (postElement) {
          postElement.remove();
          showNotification(`Ein Post wurde gelöscht`, 'info');
        }
      } else if (type === "comment") {
        const commentElement = document.querySelector(`.comment[data-comment-id="${id}"]`);
        if (commentElement) {
          commentElement.remove();
          showNotification(`Ein Kommentar wurde gelöscht`, 'info');
        }
      }
    }
  } catch (err) {
    console.error("❌ Fehler beim Benachrichtigen der Löschung:", err);
    showNotification('Fehler beim Aktualisieren der Löschung', 'danger');
  }
}

// ============================
// Live Update After Editing Posts or Comments (Fixed)
// ============================
async function notifyEdit(type, id) {
  try {
    const res = await fetch(`/Social_App/controllers/api/notify_edit.php?type=${type}&id=${id}`);
    const data = await res.json();

    if (data.success && data.html) {
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = data.html;

      if (type === "post") {
        const updatedPost = tempDiv.querySelector(`.tweet-card[data-post-id="${id}"]`);
        const existingPost = document.querySelector(`.tweet-card[data-post-id="${id}"]`);
        if (existingPost && updatedPost) {
          // Setze den Timestamp für die Aktualisierung
          updatedPost.dataset.lastUpdate = new Date().toISOString();
          existingPost.replaceWith(updatedPost);
          showNotification(`Ein Post wurde bearbeitet`, 'info');
        }
      } else if (type === "comment") {
        const updatedComment = tempDiv.querySelector(`.comment[data-comment-id="${id}"]`);
        const existingComment = document.querySelector(`.comment[data-comment-id="${id}"]`);
        if (existingComment && updatedComment) {
          existingComment.replaceWith(updatedComment);
          showNotification(`Ein Kommentar wurde bearbeitet`, 'info');
        }
      }
    }
  } catch (err) {
    console.error("❌ Fehler beim Benachrichtigen der Bearbeitung:", err);
    showNotification('Fehler beim Aktualisieren der Bearbeitung', 'danger');
  }
}

function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `alert alert-${type} notification`;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.remove();
  }, 3000);
}
