// ============================
// DOMContentLoaded Start
// ============================
document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ script.js geladen");

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
  const feed = document.querySelector(".feed");
  const imageInput = document.getElementById("file-upload-image");
  const videoInput = document.getElementById("file-upload-video");
  const imagePreview = document.getElementById("image-preview");
  const videoPreview = document.getElementById("video-preview");
  const removeBtn = document.getElementById("remove-preview");

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
        feed.insertAdjacentHTML("afterbegin", data.html);
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
  // Kommentare speichern / bearbeiten
  // ============================

  document.querySelectorAll(".comment-form-inner").forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const commentId = form.querySelector(".edit-comment-id").value;
      const content = form.querySelector("textarea").value;
      const postId = form.dataset.postId;
      const formData = new FormData(form);
      const commentList = document.querySelector(`#comment-list-${postId}`);

      const url = commentId
        ? "/Social_App/controllers/update_comment.php"
        : "/Social_App/controllers/create_comment.php";

      try {
        const res = await fetch(url, { method: "POST", body: formData });
        const raw = await res.text();
        console.log("📦 Kommentar-Serverantwort:", raw);

        const result = JSON.parse(raw);

        if (result.success) {
          if (commentId) {
            const commentEl = document
              .querySelector(`.comment button[data-comment-id="${commentId}"]`)
              ?.closest(".comment");
            if (commentEl)
              commentEl.querySelector(".comment-content").textContent = content;
            form.querySelector(".edit-comment-id").value = "";
          } else {
            if (result.html && commentList)
              commentList.insertAdjacentHTML("beforeend", result.html);
          }

          // 🧹 Formular leeren & schließen
          form.reset();
          console.log("🧹 Formular zurückgesetzt:", form);

          form.querySelector("textarea").value = "";
          const wrapper = form.closest(".comment-form");
          if (wrapper) wrapper.classList.remove("show");
        } else {
          alert("⚠️ Fehler: " + result.message);
        }
      } catch (err) {
        console.error("❌ Fehler beim Speichern des Kommentars:", err);
      }
    });
  });

  // Kommentar bearbeiten
  document.querySelectorAll(".edit-comment-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const form = btn.closest(".tweet-card")?.querySelector(".comment-form-inner");
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
  // Live Update Polling für neue Posts
  // ============================
  let lastPostTimestamp = null;
  const latestPost = document.querySelector(".tweet-card small.text-light")?.textContent;
  if (latestPost) {
    const [d, m, y, time] = latestPost.split(/\.| |:/);
    lastPostTimestamp = `${y}-${m}-${d} ${time}`;
  }

  async function fetchNewPosts() {
    try {
      const res = await fetch(`/Social_App/controllers/api/posts_since.php?since=${encodeURIComponent(lastPostTimestamp ?? "")}`);
      const result = await res.json();

      if (result.success && result.html) {
        feed.insertAdjacentHTML("afterbegin", result.html);
        lastPostTimestamp = result.latest;
        initPostCardEvents();
      }
    } catch (err) {
      console.error("❌ Fehler beim Abrufen neuer Posts:", err);
    }
  }

  setInterval(fetchNewPosts, 10000);

  // ============================
  // Live Update Polling für neue Kommentare
  // ============================
  let latestCommentTimestamp = null;

  async function fetchNewComments() {
    try {
      const res = await fetch(`/Social_App/controllers/api/comments_since.php?since=${latestCommentTimestamp || 0}`);
      const data = await res.json();

      if (data.success && data.html) {
        const temp = document.createElement("div");
        temp.innerHTML = data.html;

        temp.querySelectorAll(".comment").forEach((comment) => {
         let latestCommentTimestamp = null;

  async function fetchNewComments() {
    try {
      const res = await fetch(`/Social_App/controllers/api/comments_since.php?since=${latestCommentTimestamp || 0}`);
      const data = await res.json();

      if (data.success && data.html) {
        const temp = document.createElement("div");
        temp.innerHTML = data.html;

        temp.querySelectorAll(".comment").forEach((comment) => {
          let latestCommentTimestamp = null;

  async function fetchNewComments() {
    try {
      const res = await fetch(`/Social_App/controllers/api/comments_since.php?since=${latestCommentTimestamp || 0}`);
      const data = await res.json();

      if (data.success && data.html) {
        const temp = document.createElement("div");
        temp.innerHTML = data.html;

        temp.querySelectorAll(".comment").forEach((comment) => {
          const tweetCard = [...document.querySelectorAll(".tweet-card")].find(card =>
            card.contains(comment)
          );
          const postId = tweetCard?.dataset.postId;
          const commentList = document.querySelector(`#comment-list-${postId}`);
          if (commentList) commentList.appendChild(comment);
        });

        latestCommentTimestamp = data.latest;
      }
    } catch (err) {
      console.error("❌ Fehler beim Abrufen neuer Kommentare:", err);
    }
  }

  setInterval(fetchNewComments, 10000);

  // Initialisieren von Events nach Page Load
  initPostCardEvents();


          const commentList = document.querySelector(`#comment-list-${postId}`);
          if (commentList) commentList.appendChild(comment);
        });

        latestCommentTimestamp = data.latest;
      }
    } catch (err) {
      console.error("❌ Fehler beim Abrufen neuer Kommentare:", err);
    }
  }

  setInterval(fetchNewComments, 10000);

  // Initialisieren von Events nach Page Load
  initPostCardEvents();


          const commentList = document.querySelector(`#comment-list-${postId}`);
          if (commentList) commentList.appendChild(comment);
        });

        latestCommentTimestamp = data.latest;
      }
    } catch (err) {
      console.error("❌ Fehler beim Abrufen neuer Kommentare:", err);
    }
  }

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

document.querySelector(".confirm-delete-btn")?.addEventListener("click", async () => {
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

document.getElementById("search-button").addEventListener("click", () => {
  const query = document.getElementById("post-search").value.toLowerCase();
  const posts = document.querySelectorAll(".tweet-card");
  const resultsContainer = document.getElementById("search-results");

  resultsContainer.innerHTML = "";
  let found = 0;

  posts.forEach(post => {
    let textElement = post.querySelector(".post-text") || post.querySelector(".text-light");
    let text = textElement ? textElement.textContent.toLowerCase() : "";
    let username = post.dataset.username || "@unknown";

    if (text.includes(query) && query.length > 0) {
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
      snippet.textContent = text.substring(0, 80) + (text.length > 80 ? "..." : "");

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
          }, 2000); // nach 2 Sekunden wieder entfernen
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
    resultsContainer.innerHTML = "<div class='text-danger'><i class='bi bi-exclamation-circle mb-1'></i> Keine Treffer gefunden.</div>";
    resultsContainer.classList.remove("d-none");
  }
});


});