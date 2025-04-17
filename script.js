document.addEventListener("DOMContentLoaded", () => {
  // ============================
  // 🔧 DOM-Elemente sammeln
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
  // 🖼️ Bildvorschau
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
  // 🎥 Videovorschau
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
  // ❌ Vorschau entfernen
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
  // ✏️ Beitrag bearbeiten vorbereiten
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
  // ❌ Bearbeitung abbrechen
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
  // 💬 Kommentarformular toggeln
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
  // 🗑️ Beitrag zum Löschen vormerken
  // ============================
  document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", () => {
      const postId = deleteBtn.closest(".tweet-card")?.dataset.postId;
      const deleteInput = document.getElementById("delete-post-id");
      if (deleteInput) {
        deleteInput.value = postId;
      }
    });
  });

  // ============================
  // ✅ Beitrag erstellen/bearbeiten via AJAX
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
  // 🗑️ Beitrag löschen (AJAX)
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
