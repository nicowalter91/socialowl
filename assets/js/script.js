// ============================
// DOMContentLoaded Start
// ============================
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ script.js geladen");
  let lastPostTimestamp = null;
  let lastCommentTimestamp = null;
  initLastCommentTimestamp();

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
  const CURRENT_USER_ID =
    parseInt(document.body.dataset.currentUserId, 10) || null;

  // ============================
  // Last Comment Timestamp automatisch setzen
  // ============================

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

  function resetPostForm() {
    tweetInput.value = "";
    editPostIdInput.value = "";
    originalImagePathInput.value = "";
    postBtnWrapper.classList.remove("d-none");
    editBtnWrapper.classList.add("d-none");
    removeBtn.click();
  }

  cancelEditBtn?.addEventListener("click", resetPostForm);

  // ============================
  // Kommentarformular toggeln
  // ============================
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".toggle-comment-form");
    if (!btn) return;
    const form = document.getElementById(`comment-form-${btn.dataset.postId}`);
    if (form) form.classList.toggle("show");
  });

  // ============================
  // Beitrag absenden
  // ============================
  form?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    try {
      const res = await fetch("/Social_App/controllers/create_post.php", {
        method: "POST",
        body: formData,
      });
      const raw = await res.text();
      const data = JSON.parse(raw);
      if (data.success && data.html) {
        // 1) einfügen
        feed.insertAdjacentHTML("afterbegin", data.html);
  
        // 2) lastPostTimestamp auf das Timestamp-Attribut des neuen Elements setzen:
        const inserted = feed.firstElementChild;
        const tsEl = inserted.querySelector(".post-timestamp");
        if (tsEl) {
          lastPostTimestamp = tsEl.dataset.timestamp;
        }
  
        resetPostForm();
        initPostCardEvents();
      }
    } catch (err) {
      console.error("❌ Fehler beim Senden des Beitrags:", err);
    }
  });
  
  // ============================
  // Emoji Picker für Posts
  // ============================

  const emojiNames = {
    "😀": "grinsen",
    "😂": "lachen",
    "😍": "verliebt",
    "😎": "cool",
    "😭": "weinen",
    "😡": "wütend",
    "👍": "daumen hoch",
    "❤️": "herz",
    "🔥": "feuer",
    "🎉": "feier",
    "👏": "applaus",
    "💯": "hundert",
  };

  const pickerBtn = document.getElementById("emoji-picker-btn");
  const picker = document.getElementById("emoji-picker");
  const tweetArea = document.querySelector(".tweet-input-box");

  if (pickerBtn && picker && tweetArea) {
    pickerBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      picker.classList.toggle("d-none");
    });

    picker.querySelectorAll("button").forEach((btn) => {
      btn.addEventListener("click", () => {
        const emoji = btn.textContent;
        const start = tweetArea.selectionStart;
        const end = tweetArea.selectionEnd;
        const text = tweetArea.value;
        tweetArea.value = text.slice(0, start) + emoji + text.slice(end);
        tweetArea.focus();
        tweetArea.selectionStart = tweetArea.selectionEnd =
          start + emoji.length;
        picker.classList.add("d-none");
      });
    });

    const emojiSearch = document.getElementById("emoji-search");
    emojiSearch?.addEventListener("input", () => {
      const query = emojiSearch.value.toLowerCase();
      picker.querySelectorAll("button").forEach((btn) => {
        const emoji = btn.textContent;
        const name = emojiNames[emoji] || "";
        btn.style.display = name.includes(query) ? "inline-block" : "none";
      });
    });

    document.addEventListener("click", (e) => {
      if (!picker.contains(e.target) && !pickerBtn.contains(e.target)) {
        picker.classList.add("d-none");
      }
    });
  }

  // ============================
  // Emoji Picker für Kommentare
  // ============================

  document.querySelectorAll(".emoji-comment-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const form = btn.closest("form");
      const picker = form.querySelector(".emoji-picker");
      const textarea = form.querySelector("textarea");

      picker.classList.toggle("d-none");

      if (picker.childElementCount === 0) {
        const grid = document.createElement("div");
        grid.classList.add("emoji-grid");
        Object.keys(emojiNames).forEach((emoji) => {
          const button = document.createElement("button");
          button.type = "button";
          button.textContent = emoji;
          button.addEventListener("click", () => {
            textarea.value += emoji;
            picker.classList.add("d-none");
          });
          grid.appendChild(button);
        });
        picker.appendChild(grid);
      }
    });
  });

  // ============================
  // Kommentare  bearbeiten
  // ============================

  document.querySelectorAll(".edit-comment-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const form = btn
        .closest(".tweet-card")
        ?.querySelector(".comment-form-inner");
      if (!form) return;
      const textarea = form.querySelector("textarea");
      const idInput = form.querySelector(".edit-comment-id");

      textarea.value = btn.dataset.content;
      idInput.value = btn.dataset.commentId;

      form.closest(".comment-form")?.classList.add("show");
    });
  });

  // ============================
  // Kommentar löschen
  // ============================

  document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".delete-comment-btn");
    if (!btn) return;

    const formData = new FormData();
    formData.append("comment_id", btn.dataset.commentId);

    try {
      const res = await fetch("/Social_App/controllers/delete_comment.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();
      if (result.success) {
        btn.closest(".comment")?.remove();
      } else {
        alert("⚠️ Kommentar konnte nicht gelöscht werden.");
      }
    } catch (err) {
      console.error("❌ Fehler beim Löschen des Kommentars:", err);
    }
  });

  // ============================
  // Kommentar liken
  // ============================

  document.addEventListener("click", async (e) => {
    const button = e.target.closest(".like-comment-btn");
    if (!button) return;

    const formData = new FormData();
    formData.append("comment_id", button.dataset.commentId);

    try {
      const res = await fetch("/Social_App/controllers/like_comment.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();
      if (result.success) {
        button.classList.toggle("btn-outline-light", !result.liked);
        button.classList.toggle("btn-light", result.liked);
        button.classList.toggle("text-dark", result.liked);
        button.querySelector(".like-count").textContent = result.like_count;
      }
    } catch (err) {
      console.error("❌ Fehler beim Liken des Kommentars:", err);
    }
  });

  // ============================
  // Kommentare speichern / bearbeiten (richtig für Kommentarformulare!)
  // ============================

  document.querySelectorAll(".comment-form-inner").forEach((commentForm) => {
    commentForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(commentForm);
      const commentId =
        commentForm.querySelector(".edit-comment-id")?.value || null;
      const postId = commentForm.dataset.postId;
      const url = commentId
        ? "/Social_App/controllers/update_comment.php"
        : "/Social_App/controllers/create_comment.php";

      try {
        const res = await fetch(url, { method: "POST", body: formData });
        const result = await res.json();

        if (result.success) {
          // wenn ein neues Kommentar erzeugt wurde, von den JSON-Daten rendern
          if (!commentId && result.comment) {
            renderComment(result.comment);
            lastCommentTimestamp = result.comment.created_at;
          }
          // zurücksetzen & Form schließen
          commentForm.reset();
          const wrapper = commentForm.closest(".comment-form");
          if (wrapper) wrapper.classList.remove("show");
        } else {
          alert("⚠️ Fehler: " + result.message);
        }
      } catch (err) {
        console.error("❌ Fehler beim Senden des Kommentars:", err);
      }
    });
  });

  function formatGermanDate(dateString) {
    const d = new Date(dateString);
    return (
      `${String(d.getDate()).padStart(2, "0")}.${String(
        d.getMonth() + 1
      ).padStart(2, "0")}.${d.getFullYear()} ` +
      `${String(d.getHours()).padStart(2, "0")}:${String(
        d.getMinutes()
      ).padStart(2, "0")}`
    );
  }

  // ============================
  // Live Update: Neue Posts abrufen
  // ============================

  async function fetchNewPosts() {
    try {
      const res = await fetch(
        `/Social_App/controllers/api/posts_since.php?since=${
          lastPostTimestamp || "1970-01-01 00:00:00"
        }`
      );
      const data = await res.json();
  
      // API liefert „posts“ (Rohdaten) und parallel dazu „html“ (HTML-Fragmente)
      if (data.success && Array.isArray(data.posts) && Array.isArray(data.html)) {
        data.posts.forEach((post, i) => {
          // nur wirklich neu einfügen
          if (!document.getElementById(`post-${post.id}`)) {
            feed.insertAdjacentHTML("afterbegin", data.html[i]);
            lastPostTimestamp = post.created_at;
            // neu hinzugefügte Buttons/Links initialisieren
            initPostCardEvents();
          }
        });
      }
    } catch (err) {
      console.error("❌ Fehler beim Abrufen neuer Posts:", err);
    }
  }
  
  

  // ============================
  // Live Update: Neue Kommentare abrufen
  // ============================

  function renderComment(comment) {
    const commentsContainer = document.getElementById(
      `comment-list-${comment.post_id}`
    );
    if (!commentsContainer || document.getElementById(`comment-${comment.id}`))
      return;

    const isOwn = comment.user_id === CURRENT_USER_ID;

    const commentElement = document.createElement("div");
    commentElement.className =
      "comment d-flex align-items-start gap-2 mb-2 pt-3 pb-3 border-bottom border-secondary";
    commentElement.id = `comment-${comment.id}`;
    commentElement.dataset.commentId = comment.id;
    commentElement.dataset.postId = comment.post_id;

    commentElement.innerHTML = `
      <img class="rounded-circle"
           src="/Social_App/assets/uploads/${
             comment.profile_img || "profil.png"
           }"
           alt="Profilbild"
           style="width:32px;height:32px;">
  
      <div class="flex-grow-1">
        <strong class="text-light">@${escapeHTML(comment.username)}</strong><br>
        <small class="comment-timestamp text-light" data-timestamp="${escapeHTML(
          comment.created_at
        )}">
          ${formatGermanDate(comment.created_at)}
        </small>
        <div class="mt-2">
          <span class="text-light comment-content">${escapeHTML(
            comment.content
          )}</span>
        </div>
      </div>
  
      <div class="${
        isOwn ? "mt-2 d-flex gap-2 align-items-center" : "ms-auto mt-2"
      }">
        ${
          isOwn
            ? `
          <button type="button"
                  class="btn btn-sm btn-outline-light edit-comment-btn"
                  data-comment-id="${comment.id}"
                  data-content="${escapeHTML(comment.content)}">
            <i class="bi bi-pencil me-1"></i>Bearbeiten
          </button>
          <button type="button"
                  class="btn btn-sm btn-outline-danger delete-comment-btn"
                  data-comment-id="${comment.id}">
            <i class="bi bi-trash me-1"></i>Löschen
          </button>
        `
            : ""
        }
        <button type="button"
                class="btn btn-sm like-comment-btn ${
                  comment.liked ? "btn-light text-dark" : "btn-outline-light"
                }"
                data-comment-id="${comment.id}">
          <i class="bi bi-hand-thumbs-up me-1"></i>
          <span class="like-count">${comment.like_count || 0}</span>
        </button>
      </div>
    `;

    commentsContainer.appendChild(commentElement);
  }

  async function fetchNewComments() {
    try {
      const res = await fetch(
        `/Social_App/controllers/api/comments_since.php?since=${
          lastCommentTimestamp || "1970-01-01 00:00:00"
        }`
      );
      const data = await res.json();

      if (data.success && Array.isArray(data.comments)) {
        data.comments.forEach((comment) => {
          renderComment(comment);
          lastCommentTimestamp = comment.created_at;
        });
      }
    } catch (error) {
      console.error("❌ Fehler beim Abrufen neuer Kommentare:", error);
    }
  }

  // ============================
  // Live Update Intervall starten
  // ============================
  setInterval(() => {
    fetchNewPosts();
    fetchNewComments();
  }, 5000);

  // Initialer Aufruf beim Laden
  fetchNewPosts();
  fetchNewComments();

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
  // Suchleiste Navbar
  // ============================

  function performSearch() {
    const input = document.getElementById("post-search");
    const query = input.value.toLowerCase();
    const posts = document.querySelectorAll(".tweet-card");
    const resultsContainer = document.getElementById("search-results");

    resultsContainer.innerHTML = "";
    let found = 0;

    if (query.length === 0) {
      resultsContainer.classList.add("d-none");
      return;
    }

    posts.forEach((post) => {
      let textElement =
        post.querySelector(".post-text") || post.querySelector(".text-light");
      let text = textElement ? textElement.textContent.toLowerCase() : "";
      let username = post.dataset.username || "@unknown";

      if (text.includes(query)) {
        const postId = post.getAttribute("data-post-id");

        const card = document.createElement("div");
        card.className = "bg-dark rounded p-2 mb-2 search-result-card";

        const link = document.createElement("a");
        link.href = `#post-${postId}`;
        link.className = "text-light text-decoration-none d-block";

        const title = document.createElement("div");
        title.className = "fw-bold";
        title.textContent = username;

        const snippet = document.createElement("small");
        snippet.className = "d-block text-light";
        snippet.textContent =
          text.substring(0, 80) + (text.length > 80 ? "..." : "");

        link.appendChild(title);
        link.appendChild(snippet);
        card.appendChild(link);

        // Smooth Scroll und Highlight Effekt bei Klick
        link.addEventListener("click", (e) => {
          e.preventDefault();
          const target = document.getElementById(`post-${postId}`);
          if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });

            // Highlight-Effekt
            target.classList.add("highlight-post");
            setTimeout(() => {
              target.classList.remove("highlight-post");
            }, 2000);
          }
          resultsContainer.classList.add("d-none"); // Ergebnisse ausblenden
        });

        resultsContainer.appendChild(card);
        found++;
      }
    });

    if (found > 0) {
      const info = document.createElement("div");
      info.className = "text-light mb-2";
      info.textContent = `${found} Treffer gefunden:`;
      resultsContainer.prepend(info);

      resultsContainer.classList.remove("d-none");
    } else {
      resultsContainer.innerHTML =
        "<div class='text-danger'><i class='bi bi-exclamation-circle mb-1'></i> Keine Treffer gefunden.</div>";
      resultsContainer.classList.remove("d-none");
    }

    input.value = ""; // Eingabefeld nach Suche leeren
  }

  // Eventlistener für Button-Klick
  document
    .getElementById("search-button")
    .addEventListener("click", performSearch);

  // Eventlistener für ENTER-Taste im Inputfeld
  document
    .getElementById("post-search")
    .addEventListener("keypress", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        performSearch();
      }
    });

  // Suchergebnisse ausblenden, wenn das Suchfeld geleert wird
  document.getElementById("post-search").addEventListener("input", (event) => {
    const resultsContainer = document.getElementById("search-results");
    if (event.target.value.trim() === "") {
      resultsContainer.classList.add("d-none");
    }
  });

  // Suchergebnisse ausblenden, wenn außerhalb geklickt wird
  document.addEventListener("click", (event) => {
    const searchInput = document.getElementById("post-search");
    const searchButton = document.getElementById("search-button");
    const resultsContainer = document.getElementById("search-results");

    if (
      !searchInput.contains(event.target) &&
      !searchButton.contains(event.target) &&
      !resultsContainer.contains(event.target)
    ) {
      resultsContainer.classList.add("d-none");
    }
  });
});
