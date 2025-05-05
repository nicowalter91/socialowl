/**
 * Kommentar-Verwaltung
 * Verwaltet alle Funktionen rund um Kommentare:
 * - Öffnen/Schließen des Kommentarformulars
 * - Erstellen, Bearbeiten und Löschen von Kommentaren
 * - Liken von Kommentaren
 * - Rendern von Kommentaren
 */
export class CommentHandler {
    /**
     * Initialisiert den CommentHandler
     * Setzt die aktuelle Benutzer-ID und initialisiert die Event-Listener
     */
    constructor() {
        this.CURRENT_USER_ID = parseInt(document.body.dataset.currentUserId, 10) || null;
        console.log("✅ CommentHandler initialisiert");
        this.init();
    }

    /**
     * Initialisiert alle Event-Listener und Funktionen
     */
    init() {
        console.log("🔄 Initialisiere CommentHandler...");
        this.initCommentFormEvents();
        this.initCommentActions();
    }

    /**
     * Initialisiert die Event-Listener für das Kommentarformular
     * - Öffnen/Schließen des Formulars
     * - Absenden von Kommentaren
     */
    initCommentFormEvents() {
        console.log("🔄 Initialisiere Kommentarformular-Events...");
        
        // Event-Listener für das Öffnen/Schließen des Kommentarformulars
        document.addEventListener("click", (e) => {
            const btn = e.target.closest(".toggle-comment-form");
            if (!btn) return;
            
            console.log("🔍 Kommentar-Button geklickt für Post-ID:", btn.dataset.postId);
            
            const form = document.getElementById(`comment-form-${btn.dataset.postId}`);
            if (!form) {
                console.error("❌ Kommentarformular nicht gefunden für Post-ID:", btn.dataset.postId);
                return;
            }
            
            form.classList.toggle("show");
            console.log("✅ Kommentarformular-Toggle:", form.classList.contains("show") ? "geöffnet" : "geschlossen");
            
            // Wenn das Formular geöffnet wird, fokussiere das Textfeld
            if (form.classList.contains("show")) {
                const textarea = form.querySelector("textarea");
                if (textarea) {
                    textarea.focus();
                    console.log("✅ Textfeld fokussiert");
                }
            }
        });

        // Event-Listener für das Absenden von Kommentaren
        document.querySelectorAll(".comment-form-inner").forEach((commentForm) => {
            console.log("🔄 Initialisiere Submit-Event für Kommentarformular:", commentForm.dataset.postId);
            
            commentForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                console.log("📝 Kommentar wird gesendet...");

                const formData = new FormData(commentForm);
                const commentId = commentForm.querySelector(".edit-comment-id")?.value || null;
                const postId = commentForm.dataset.postId;
                const url = commentId
                    ? "/Social_App/controllers/update_comment.php"
                    : "/Social_App/controllers/create_comment.php";

                try {
                    const res = await fetch(url, { method: "POST", body: formData });
                    const result = await res.json();

                    if (result.success) {
                        console.log("✅ Kommentar erfolgreich gesendet");
                        if (commentId) {
                            // Kommentar aktualisieren
                            const commentElement = document.querySelector(`.comment-item[data-comment-id="${commentId}"]`);
                            if (commentElement && result.html) {
                                const tempDiv = document.createElement("div");
                                tempDiv.innerHTML = result.html;
                                const newCommentElement = tempDiv.firstElementChild;
                                commentElement.replaceWith(newCommentElement);
                                
                                // Event-Listener für den aktualisierten Kommentar hinzufügen
                                this.initCommentActionsForElement(newCommentElement);
                            }
                        } else if (result.comment) {
                            // Neuen Kommentar hinzufügen
                            const newCommentElement = this.renderComment(result.comment);
                            if (newCommentElement) {
                                // Event-Listener für den neuen Kommentar hinzufügen
                                this.initCommentActionsForElement(newCommentElement);
                            }
                            window.dispatchEvent(new CustomEvent('commentTimestampUpdated', { 
                                detail: { timestamp: result.comment.created_at }
                            }));
                        }
                        commentForm.reset();
                        const wrapper = commentForm.closest(".comment-form");
                        if (wrapper) wrapper.classList.remove("show");
                    } else {
                        console.error("❌ Fehler beim Senden des Kommentars:", result.message);
                        alert("⚠️ Fehler: " + result.message);
                    }
                } catch (err) {
                    console.error("❌ Fehler beim Senden des Kommentars:", err);
                    alert("⚠️ Ein unerwarteter Fehler ist aufgetreten.");
                }
            });
        });
    }

    /**
     * Initialisiert die Event-Listener für einen einzelnen Kommentar
     * @param {HTMLElement} commentElement - Das Kommentar-Element
     */
    initCommentActionsForElement(commentElement) {
        // Bearbeiten-Button
        const editBtn = commentElement.querySelector(".edit-comment-btn");
        if (editBtn) {
            editBtn.addEventListener("click", () => {
                const form = editBtn.closest(".tweet-card")?.querySelector(".comment-form-inner");
                if (!form) return;
                const textarea = form.querySelector("textarea");
                const idInput = form.querySelector(".edit-comment-id");

                if (!textarea || !idInput) return;

                textarea.value = editBtn.dataset.content;
                idInput.value = editBtn.dataset.commentId;

                form.closest(".comment-form")?.classList.add("show");
            });
        }

        // Löschen-Button
        const deleteBtn = commentElement.querySelector(".delete-comment-btn");
        if (deleteBtn) {
            deleteBtn.addEventListener("click", async () => {
                const formData = new FormData();
                formData.append("comment_id", deleteBtn.dataset.commentId);

                try {
                    const res = await fetch("/Social_App/controllers/delete_comment.php", {
                        method: "POST",
                        body: formData,
                    });

                    const result = await res.json();
                    if (result.success) {
                        deleteBtn.closest(".comment-item")?.remove();
                    } else {
                        alert("⚠️ Kommentar konnte nicht gelöscht werden.");
                    }
                } catch (err) {
                    console.error("❌ Fehler beim Löschen des Kommentars:", err);
                }
            });
        }

        // Like-Button
        const likeBtn = commentElement.querySelector(".like-comment-btn");
        if (likeBtn) {
            likeBtn.addEventListener("click", async () => {
                const formData = new FormData();
                formData.append("comment_id", likeBtn.dataset.commentId);

                try {
                    const res = await fetch("/Social_App/controllers/like_comment.php", {
                        method: "POST",
                        body: formData,
                    });

                    const result = await res.json();
                    if (result.success) {
                        likeBtn.classList.toggle("btn-outline-light", !result.liked);
                        likeBtn.classList.toggle("btn-light", result.liked);
                        likeBtn.classList.toggle("text-dark", result.liked);
                        likeBtn.querySelector(".like-count").textContent = result.like_count;
                    }
                } catch (err) {
                    console.error("❌ Fehler beim Liken des Kommentars:", err);
                }
            });
        }
    }

    /**
     * Initialisiert die Event-Listener für Kommentar-Aktionen
     * - Bearbeiten von Kommentaren
     * - Löschen von Kommentaren
     * - Liken von Kommentaren
     */
    initCommentActions() {
        // Event-Listener für alle existierenden Kommentare
        document.querySelectorAll(".comment-item").forEach(commentElement => {
            this.initCommentActionsForElement(commentElement);
        });
    }

    /**
     * Rendert einen neuen Kommentar im DOM
     * @param {Object} comment - Das Kommentar-Objekt mit allen notwendigen Daten
     */
    renderComment(comment) {
        const commentsContainer = document.getElementById(`comment-list-${comment.post_id}`);
        if (!commentsContainer || document.getElementById(`comment-${comment.id}`)) return;

        const isOwn = comment.user_id === this.CURRENT_USER_ID;

        const commentElement = document.createElement("div");
        commentElement.className = "comment-item px-2 py-2 mb-2 position-relative border-bottom border-secondary";
        commentElement.id = `comment-${comment.id}`;
        commentElement.dataset.commentId = comment.id;
        commentElement.dataset.postId = comment.post_id;

        commentElement.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <img class="rounded-circle border border-2 border-secondary"
                     src="/Social_App/assets/uploads/${comment.profile_img || "profil.png"}"
                     alt="Profilbild"
                     width="32" height="32">

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/Social_App/views/profile.php?username=${this.escapeHTML(comment.username)}" 
                           class="text-decoration-none hover-underline">
                            <span class="fw-semibold text-light mb-0">@${this.escapeHTML(comment.username)}</span>
                        </a>
                    </div>

                    <small class="text-light opacity-75 comment-timestamp"
                           data-timestamp="${this.escapeHTML(comment.created_at)}">
                        ${this.formatGermanDate(comment.created_at)}
                    </small>

                    <div class="mt-1">
                        <span class="text-light comment-content d-block">${this.escapeHTML(comment.content)}</span>
                    </div>
                </div>

                <div class="d-flex gap-2 align-items-center">
                    ${isOwn ? `
                        <button type="button"
                                class="btn btn-sm btn-outline-light rounded-pill transition-all edit-comment-btn"
                                data-comment-id="${comment.id}"
                                data-content="${this.escapeHTML(comment.content)}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger rounded-pill transition-all delete-comment-btn"
                                data-comment-id="${comment.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    ` : ""}
                    <button type="button"
                            class="btn btn-sm rounded-pill transition-all like-comment-btn ${comment.liked ? "btn-light text-dark" : "btn-outline-light"}"
                            data-comment-id="${comment.id}">
                        <i class="bi ${comment.liked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up'}"></i>
                        <span class="like-count ${comment.liked ? 'text-dark' : ''}">${comment.like_count || 0}</span>
                    </button>
                </div>
            </div>
        `;

        commentsContainer.appendChild(commentElement);
        return commentElement;
    }

    /**
     * Escaped HTML-Sonderzeichen für sichere Ausgabe
     * @param {string} text - Der zu escapende Text
     * @returns {string} - Der escapte Text
     */
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

    /**
     * Formatiert ein Datum im deutschen Format
     * @param {string} dateString - Das zu formatierende Datum
     * @returns {string} - Das formatierte Datum
     */
    formatGermanDate(dateString) {
        const d = new Date(dateString);
        return `${String(d.getDate()).padStart(2, "0")}.${String(d.getMonth() + 1).padStart(2, "0")}.${d.getFullYear()} ` +
               `${String(d.getHours()).padStart(2, "0")}:${String(d.getMinutes()).padStart(2, "0")}`;
    }
}