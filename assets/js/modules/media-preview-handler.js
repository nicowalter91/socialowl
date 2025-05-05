/**
 * Media-Preview-Handler Modul
 * Verwaltet die Vorschau von hochgeladenen Bildern und Videos
 */

export class MediaPreviewHandler {
  constructor() {
    // DOM Elemente für Media-Vorschau
    this.imageInput = document.getElementById("file-upload-image");
    this.videoInput = document.getElementById("file-upload-video");
    this.imagePreview = document.getElementById("image-preview");
    this.videoPreview = document.getElementById("video-preview");
    this.removeBtn = document.getElementById("remove-preview");
    
    this.init();
  }
  
  init() {
    // Event-Listener für Bild-Upload
    this.imageInput?.addEventListener("change", () => {
      const file = this.imageInput.files[0];
      if (file) {
        const url = URL.createObjectURL(file);
        this.imagePreview.src = url;
        this.imagePreview.classList.remove("d-none");
        this.videoPreview.classList.add("d-none");
        this.videoPreview.querySelector("source").src = "";
        this.removeBtn.classList.remove("d-none");
      }
    });
    
    // Event-Listener für Video-Upload
    this.videoInput?.addEventListener("change", () => {
      const file = this.videoInput.files[0];
      if (file) {
        const url = URL.createObjectURL(file);
        this.videoPreview.querySelector("source").src = url;
        this.videoPreview.load();
        this.videoPreview.classList.remove("d-none");
        this.imagePreview.classList.add("d-none");
        this.imagePreview.src = "";
        this.removeBtn.classList.remove("d-none");
      }
    });
    
    // Event-Listener für Entfernen-Button
    this.removeBtn?.addEventListener("click", () => {
      this.clearPreviews();
    });
  }
  
  /**
   * Setzt alle Vorschauen zurück
   */
  clearPreviews() {
    if (this.imageInput) this.imageInput.value = "";
    if (this.videoInput) this.videoInput.value = "";
    
    if (this.imagePreview) {
      this.imagePreview.src = "";
      this.imagePreview.classList.add("d-none");
    }
    
    if (this.videoPreview) {
      const source = this.videoPreview.querySelector("source");
      if (source) source.src = "";
      this.videoPreview.load();
      this.videoPreview.classList.add("d-none");
    }
    
    if (this.removeBtn) this.removeBtn.classList.add("d-none");
  }
}