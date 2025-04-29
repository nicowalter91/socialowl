// Post-Verwaltung
export class PostHandler {
    constructor() {
        this.tweetInput = document.querySelector(".tweet-input-box");
        this.postBtnWrapper = document.getElementById("post-btn-wrapper");
        this.editBtnWrapper = document.getElementById("edit-btn-wrapper");
        this.editPostIdInput = document.getElementById("edit-post-id");
        this.originalImagePathInput = document.getElementById("original-image-path");
        this.cancelEditBtn = document.getElementById("cancel-edit");
        this.form = document.querySelector(".tweet-box");
        this.feed = document.getElementById("feed");
        this.imageInput = document.getElementById("file-upload-image");
        this.videoInput = document.getElementById("file-upload-video");
        this.imagePreview = document.getElementById("image-preview");
        this.videoPreview = document.getElementById("video-preview");
        this.removeBtn = document.getElementById("remove-preview");
        this.CURRENT_USER_ID = parseInt(document.body.dataset.currentUserId, 10) || null;

        this.init();
    }

    init() {
        this.initPostCardEvents();
        this.initFormEvents();
        this.initMediaPreview();
    }

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

    initFormEvents() {
        this.form?.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(this.form);
            try {
                const res = await fetch("/Social_App/controllers/create_post.php", {
                    method: "POST",
                    body: formData,
                });
                const raw = await res.text();
                const data = JSON.parse(raw);
                if (data.success && data.html) {
                    this.feed.insertAdjacentHTML("afterbegin", data.html);
                    const inserted = this.feed.firstElementChild;
                    const tsEl = inserted.querySelector(".post-timestamp");
                    if (tsEl) {
                        window.dispatchEvent(new CustomEvent('postTimestampUpdated', { 
                            detail: { timestamp: tsEl.dataset.timestamp }
                        }));
                    }
                    this.resetPostForm();
                    this.initPostCardEvents();
                }
            } catch (err) {
                console.error("❌ Fehler beim Senden des Beitrags:", err);
            }
        });

        this.cancelEditBtn?.addEventListener("click", () => this.resetPostForm());
    }

    initMediaPreview() {
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

        this.removeBtn?.addEventListener("click", () => {
            this.imageInput.value = "";
            this.videoInput.value = "";
            this.imagePreview.src = "";
            this.imagePreview.classList.add("d-none");
            this.videoPreview.querySelector("source").src = "";
            this.videoPreview.load();
            this.videoPreview.classList.add("d-none");
            this.removeBtn.classList.add("d-none");
        });
    }

    resetPostForm() {
        this.tweetInput.value = "";
        this.editPostIdInput.value = "";
        this.originalImagePathInput.value = "";
        this.postBtnWrapper.classList.remove("d-none");
        this.editBtnWrapper.classList.add("d-none");
        this.removeBtn.click();
    }
} 