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

  try {
    // Alle Module parallel laden für bessere Performance
    const [
      liveUpdatesModule,
      postHandlerModule, 
      commentHandlerModule, 
      searchHandlerModule,
      themeHandlerModule,
      chatHandlerModule,
      postEditHandlerModule,
      mediaPreviewHandlerModule,
      notificationHandlerModule
    ] = await Promise.all([
      import('./modules/live-updates.js'),
      import('./modules/post-handler.js'),
      import('./modules/comment-handler.js'),
      import('./modules/search-handler.js'),
      import('./modules/theme-handler.js'),
      import('./modules/chat-handler.js'),
      import('./modules/post-edit-handler.js'),
      import('./modules/media-preview-handler.js'),
      import('./modules/notification-handler.js')
    ]);

    // Module initialisieren und global verfügbar machen
    window.liveUpdates = new liveUpdatesModule.LiveUpdates();
    window.postHandler = new postHandlerModule.PostHandler();
    window.commentHandler = new commentHandlerModule.CommentHandler();
    window.searchHandler = new searchHandlerModule.SearchHandler();
    window.themeHandler = new themeHandlerModule.ThemeHandler();
    window.chatHandler = new chatHandlerModule.ChatHandler();
    window.postEditHandler = new postEditHandlerModule.PostEditHandler();
    window.mediaPreviewHandler = new mediaPreviewHandlerModule.MediaPreviewHandler();
    window.notificationHandler = new notificationHandlerModule.NotificationHandler();

    // Timestamps für Live-Updates
    let lastPostTimestamp = null;
    let lastCommentTimestamp = null;
    initLastCommentTimestamp();
    initLastPostTimestamp();

    console.log("✅ Alle Module erfolgreich initialisiert");

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
    
  } catch (error) {
    console.error("❌ Fehler beim Laden der Module:", error);
  }
});

// ============================
// Globale Hilfsfunktionen für Live-Updates
// ============================

/**
 * Benachrichtigt über die Löschung eines Posts oder Kommentars
 * @param {string} type - "post" oder "comment"
 * @param {string} id - Die ID des gelöschten Elements
 */
async function notifyDeletion(type, id) {
  if (window.notificationHandler) {
    await window.notificationHandler.notifyDeletion(type, id);
  }
}

/**
 * Benachrichtigt über die Bearbeitung eines Posts oder Kommentars
 * @param {string} type - "post" oder "comment"
 * @param {string} id - Die ID des bearbeiteten Elements
 */
async function notifyEdit(type, id) {
  if (window.notificationHandler) {
    await window.notificationHandler.notifyEdit(type, id);
  }
}

/**
 * Zeigt eine Benachrichtigung an
 * @param {string} message - Die anzuzeigende Nachricht
 * @param {string} type - Der Typ der Nachricht (info, success, warning, danger)
 */
function showNotification(message, type = 'info') {
  if (window.notificationHandler) {
    window.notificationHandler.showNotification(message, type);
  }
}
