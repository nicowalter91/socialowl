/**
 * Post-Edit-Handler Modul
 * Verwaltet die Bearbeitung und Löschung von Posts
 */

export class PostEditHandler {
  constructor() {
    // DOM Elemente für Post-Bearbeitung
    this.tweetInput = document.querySelector(".tweet-input-box");
    this.postBtnWrapper = document.getElementById("post-btn-wrapper");
    this.editBtnWrapper = document.getElementById("edit-btn-wrapper");
    this.editPostIdInput = document.getElementById("edit-post-id");
    this.originalImagePathInput = document.getElementById("original-image-path");
    this.cancelEditBtn = document.getElementById("cancel-edit");
    this.form = document.querySelector(".tweet-box");
    
    this.init();
  }
  
  init() {
    // Event-Listener für Edit-Buttons initialisieren
    this.initPostCardEvents();
    
    // Event-Listener für Abbrechen-Button
    this.cancelEditBtn?.addEventListener("click", () => this.resetPostForm());
    
    // Event-Listener für Löschen-Button in Post-Dropdown
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
    
    // Event-Listener für Löschen-Bestätigung
    document.querySelector(".confirm-delete-btn")?.addEventListener("click", async () => {
      const postId = document.getElementById("delete-post-id")?.value;
      if (!postId) return;
      
      await this.deletePost(postId);
    });
  }
  
  /**
   * Initialisiert die Event-Listener für Post-Cards
   * Ermöglicht das Bearbeiten von Posts
   */
  initPostCardEvents() {
    document.querySelectorAll(".edit-post-btn").forEach((button) => {
      button.addEventListener("click", () => {
        this.tweetInput.value = button.dataset.content;
        this.editPostIdInput.value = button.dataset.postId;
        this.originalImagePathInput.value = button.dataset.image;
        this.postBtnWrapper.classList.add("d-none");
        this.editBtnWrapper.classList.remove("d-none");
        window.scrollTo({ top: 100, behavior: "smooth" });
      });
    });
  }
  
  /**
   * Setzt das Post-Formular zurück
   * Wird nach dem Absenden eines Posts oder beim Abbrechen der Bearbeitung aufgerufen
   */
  resetPostForm() {
    if (this.tweetInput) this.tweetInput.value = "";
    if (this.editPostIdInput) this.editPostIdInput.value = "";
    if (this.originalImagePathInput) this.originalImagePathInput.value = "";
    if (this.postBtnWrapper) this.postBtnWrapper.classList.remove("d-none");
    if (this.editBtnWrapper) this.editBtnWrapper.classList.add("d-none");
    
    // Vorschau zurücksetzen indem wir den Remove-Button simulieren
    const removeBtn = document.getElementById("remove-preview");
    if (removeBtn) removeBtn.click();
  }
  
  /**
   * Löscht einen Post per AJAX
   * @param {string} postId - Die ID des zu löschenden Posts
   */
  async deletePost(postId) {
    const formData = new FormData();
    formData.append("post_id", postId);
    
    try {
      const res = await fetch("/Social_App/controllers/delete_post.php", {
        method: "POST",
        body: formData,
      });
      
      const result = await res.json();
      if (result.success) {
        document.querySelector(`.tweet-card[data-post-id="${postId}"]`)?.remove();
        const modal = bootstrap.Modal.getInstance(document.getElementById("deleteModal"));
        modal?.hide();
      } else {
        alert("⚠️ Fehler beim Löschen des Beitrags:\n" + result.message);
      }
    } catch (err) {
      console.error("❌ Fehler beim Löschen des Beitrags:", err);
      alert("❌ Es ist ein Fehler beim Löschen aufgetreten.");
    }
  }
}