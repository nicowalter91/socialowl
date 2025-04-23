document.addEventListener("DOMContentLoaded", () => {
  // ============================
  // DOM-Elemente sammeln
  // ============================
  const tweetInput = document.querySelector(".tweet-input-box");
  const postBtnWrapper = document.getElementById("post-btn-wrapper");
  const editBtnWrapper = document.getElementById("edit-btn-wrapper");
  const editPostIdInput = document.getElementById("edit-post-id");
  const originalImagePathInput = document.getElementById("original-image-path");
  const cancelEditBtn = document.getElementById("cancel-edit");
  const form = document.querySelector(".tweet-box");
  const feed = document.querySelector(".feed");
  const imageInput = document.getElementById("file-upload-image");
  const videoInput = document.getElementById("file-upload-video");
  const imagePreview = document.getElementById("image-preview");
  const videoPreview = document.getElementById("video-preview");
  const removeBtn = document.getElementById("remove-preview");

  if (!form || !feed) {
    console.error("❌ Formular oder Feed nicht gefunden!");
    return;
  }

  // ============================
  // Bildvorschau
  // ============================
  imageInput.addEventListener("change", () => {
    const file = imageInput.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      imagePreview.src = url;
      imagePreview.classList.remove("d-none");

      videoPreview.classList.add("d-none");
      videoPreview.querySelector("source").src = "";
      videoPreview.load();

      removeBtn.classList.remove("d-none");
    }
  });

  // ============================
  // Videovorschau
  // ============================
  videoInput.addEventListener("change", () => {
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

  // ============================
  // Vorschau entfernen
  // ============================
  removeBtn.addEventListener("click", () => {
    imageInput.value = "";
    videoInput.value = "";

    imagePreview.classList.add("d-none");
    imagePreview.src = "";

    videoPreview.classList.add("d-none");
    videoPreview.querySelector("source").src = "";
    videoPreview.load();

    removeBtn.classList.add("d-none");
  });

  // ============================
  // Beitrag bearbeiten vorbereiten
  // ============================
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

  // ============================
  // Bearbeitung abbrechen
  // ============================
  cancelEditBtn?.addEventListener("click", () => {
    tweetInput.value = "";
    editPostIdInput.value = "";
    originalImagePathInput.value = "";

    postBtnWrapper.classList.remove("d-none");
    editBtnWrapper.classList.add("d-none");

    removeBtn.click(); // Vorschau zurücksetzen
  });

  // ============================
  // Kommentarformular toggeln
  // ============================
  document.querySelectorAll(".toggle-comment-form").forEach((btn) => {
    btn.addEventListener("click", () => {
      const form = document.getElementById(
        `comment-form-${btn.dataset.postId}`
      );
      if (form) {
        form.style.display = form.style.display === "none" ? "block" : "none";
      }
    });
  });

  // ============================
  // Beitrag zum Löschen vormerken
  // ============================
  document
    .querySelectorAll('[data-bs-target="#deleteModal"]')
    .forEach((deleteBtn) => {
      deleteBtn.addEventListener("click", () => {
        const postId = deleteBtn.closest(".tweet-card")?.dataset.postId;
        const deleteInput = document.getElementById("delete-post-id");
        if (deleteInput) {
          deleteInput.value = postId;
        }
      });
    });

  // ============================
  // Beitrag erstellen/bearbeiten via AJAX
  // ============================
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const res = await fetch("/Social_App/ajax/create_post.php", {
        method: "POST",
        body: formData,
      });

      const raw = await res.text();
      console.log("🔍 Serverantwort:", raw);

      let data;
      try {
        data = JSON.parse(raw);
      } catch (jsonErr) {
        console.error("❌ Fehler beim Parsen der JSON-Antwort:", jsonErr);
        return;
      }

      if (data.success) {
        const existing = document.querySelector(
          `.tweet-card[data-post-id="${data.post_id}"]`
        );
        if (existing) {
          existing.outerHTML = data.html;
        } else {
          feed.insertAdjacentHTML("afterbegin", data.html);
        }

        // Formular zurücksetzen
        form.reset();
        editPostIdInput.value = "";
        originalImagePathInput.value = "";
        postBtnWrapper.classList.remove("d-none");
        editBtnWrapper.classList.add("d-none");

        // Vorschau entfernen
        removeBtn.click();
      } else {
        alert("⚠️ Fehler beim Absenden des Beitrags.");
      }
    } catch (err) {
      console.error("❌ Fehler beim Senden des Formulars:", err);
    }
  });

  // ============================
  // Beitrag löschen (AJAX)
  // ============================
  document.addEventListener("click", async (e) => {
    if (e.target.matches(".confirm-delete-btn")) {
      const postId = document.getElementById("delete-post-id").value;
      if (!postId) {
        alert("⚠️ Keine Post-ID gefunden.");
        return;
      }

      const formData = new FormData();
      formData.append("post_id", postId);

      try {
        const res = await fetch("/Social_App/ajax/delete_post.php", {
          method: "POST",
          body: formData,
        });

        const result = await res.json();

        if (result.success) {
          // Beitrag im DOM entfernen
          const card = document.querySelector(
            `.tweet-card[data-post-id="${postId}"]`
          );
          if (card) card.remove();

          // Modal schließen
          const modalEl = document.getElementById("deleteModal");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) {
            modalInstance.hide();
          }

          // Optional: Weiterleitung
          // window.location.href = "welcome.php";
        } else {
          alert("❌ Fehler beim Löschen: " + result.message);
        }
      } catch (err) {
        console.error("❌ Fehler beim Löschen via AJAX:", err);
        alert("❌ AJAX Fehler beim Löschen.");
      }
    }
  });
});

// ============================
// Emoji Picker Integration
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
const textarea = document.querySelector(".tweet-input-box");

if (pickerBtn && picker && textarea) {
  // Öffnen & schließen
  pickerBtn.addEventListener("click", (e) => {
    e.stopPropagation(); // Damit Klick nicht vom Document-Listener geschlossen wird
    picker.classList.toggle("d-none");
  });

  // Emoji in Textarea einfügen
  picker.querySelectorAll("button").forEach((btn) => {
    btn.addEventListener("click", () => {
      const emoji = btn.textContent;
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      const text = textarea.value;

      textarea.value = text.slice(0, start) + emoji + text.slice(end);
      textarea.focus();
      textarea.selectionStart = textarea.selectionEnd = start + emoji.length;

      picker.classList.add("d-none");
    });
  });

  const emojiSearch = document.getElementById("emoji-search");
  const emojiButtons = document.querySelectorAll("#emoji-list button");

  emojiSearch.addEventListener("input", () => {
    const query = emojiSearch.value.toLowerCase();

    emojiButtons.forEach((btn) => {
      const emoji = btn.textContent;
      const name = emojiNames[emoji] || ""; // Mapping siehe unten
      btn.style.display = name.includes(query) ? "inline-block" : "none";
    });
  });

  // Klick außerhalb → Picker schließen
  document.addEventListener("click", (e) => {
    if (!picker.contains(e.target) && !pickerBtn.contains(e.target)) {
      picker.classList.add("d-none");
    }
  });
}

// ============================
// Emoji in Kommentar einfügen
// ============================
document.querySelectorAll(".emoji-comment-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const form = btn.closest("form");
    const picker = form.querySelector(".emoji-picker");
    const textarea = form.querySelector("textarea");

    // Picker anzeigen/ausblenden
    picker.classList.toggle("d-none");

    // Picker nur einmal befüllen
    if (picker.childElementCount === 0) {
      const emojis = [
        "😀",
        "😂",
        "😍",
        "😎",
        "😭",
        "😡",
        "👍",
        "❤️",
        "🔥",
        "🎉",
        "👏",
        "💯",
      ];

      // Emoji-Grid
      const grid = document.createElement("div");
      grid.classList.add("emoji-grid");

      emojis.forEach((emoji) => {
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
// Kommentar liken (AJAX)
// ============================
document.addEventListener("click", async (e) => {
  if (e.target.closest(".like-comment-btn")) {
    const button = e.target.closest(".like-comment-btn");
    const commentId = button.dataset.commentId;

    const formData = new FormData();
    formData.append("comment_id", commentId);

    try {
      const res = await fetch("/Social_App/ajax/like_comment.php", {
        method: "POST",
        body: formData
      });

      const result = await res.json();

      if (result.success) {
        // Optik anpassen
        button.classList.toggle("btn-outline-light", !result.liked);
        button.classList.toggle("btn-light", result.liked);
        button.classList.toggle("text-dark", result.liked);

        // Zahl aktualisieren
        button.querySelector(".like-count").textContent = result.like_count;
      }
    } catch (err) {
      console.error("❌ Fehler beim Liken des Kommentars:", err);
    }
  }
});

// ============================
// Kommentar löschen (ohne Modal)
// ============================
document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".delete-comment-btn");

  if (btn) {
    const commentId = btn.dataset.commentId;
    const formData = new FormData();
    formData.append("comment_id", commentId);

    try {
      const res = await fetch("/Social_App/ajax/delete_comment.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success) {
        const commentEl = btn.closest(".comment");
        if (commentEl) commentEl.remove();
      } else {
        alert("⚠️ Kommentar konnte nicht gelöscht werden.");
      }
    } catch (err) {
      console.error("❌ Fehler beim Löschen des Kommentars:", err);
    }
  }
});

// ============================
// Kommentare 
// ============================

// Kommentar bearbeiten – Formular füllen
document.addEventListener("click", (e) => {
  const btn = e.target.closest(".edit-comment-btn");
  if (!btn) return;

  const commentId = btn.dataset.commentId;
  const content = btn.dataset.content;

  // Kommentarformular suchen (liegt vorher im DOM)
  const form = btn.closest(".tweet-card").querySelector(".comment-form-inner");
  if (!form) return;

  const textarea = form.querySelector("textarea");
  const idInput = form.querySelector(".edit-comment-id");

  textarea.value = content;
  idInput.value = commentId;

  form.closest(".comment-form").style.display = "block";
});

// Kommentar speichern (neu oder update)
document.querySelectorAll(".comment-form-inner").forEach((form) => {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const commentId = form.querySelector(".edit-comment-id").value;
    const content = form.querySelector("textarea").value;
    const formData = new FormData(form);
    const postId = form.dataset.postId;

    const commentList = document.querySelector(`#comment-list-${postId}`); // ✅ FIX: gezielter Container

    const url = commentId
      ? "/Social_App/ajax/update_comment.php"
      : "/Social_App/create_comment.php";

    try {
      const res = await fetch(url, {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success) {
        if (commentId) {
          // Kommentar updaten
          const commentEl = document.querySelector(`.comment button[data-comment-id="${commentId}"]`)?.closest(".comment");
          if (commentEl) {
            commentEl.querySelector(".comment-content").textContent = content;
          }
          form.querySelector(".edit-comment-id").value = "";
        } else {
          // Neuer Kommentar korrekt einfügen
          if (result.html && commentList) {
            commentList.insertAdjacentHTML("beforeend", result.html);
          }
        }

        form.reset();
      } else {
        alert("⚠️ Fehler: " + result.message);
      }
    } catch (err) {
      console.error("❌ Fehler beim Speichern des Kommentars:", err);
    }
  });
});



// // ============================
// // Vorschau Profil Bilder
// // ============================

  // Bildvorschau
  function previewImage(input, targetId) {
    const file = input.files[0];
    const preview = document.getElementById(targetId);
    if (file && preview) {
      const reader = new FileReader();
      reader.onload = e => preview.src = e.target.result;
      reader.readAsDataURL(file);
    }
  }
