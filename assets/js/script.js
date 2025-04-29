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
