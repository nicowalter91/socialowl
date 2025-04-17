document.addEventListener("DOMContentLoaded", () => {
  // === DOM-Referenzen
  const tweetInput = document.querySelector(".tweet-input-box");
  const postBtnWrapper = document.getElementById("post-btn-wrapper");
  const editBtnWrapper = document.getElementById("edit-btn-wrapper");
  const editPostIdInput = document.getElementById("edit-post-id");
  const originalImagePathInput = document.getElementById("original-image-path");
  const cancelEditBtn = document.getElementById("cancel-edit");
  const form = document.querySelector(".tweet-box");
  const feed = document.querySelector(".feed");

  if (!form || !feed) {
    console.error("Formular oder Feed nicht gefunden!");
    return;
  }

  // === Hilfsfunktion: Event-Handler für "Bearbeiten"-Buttons
  
  function bindEditButtons() {
    document.querySelectorAll(".edit-post-btn").forEach((button) => {
      button.addEventListener("click", () => {
        tweetInput.value = button.dataset.content || "";
        editPostIdInput.value = button.dataset.postId;
        originalImagePathInput.value = button.dataset.image || "";

        postBtnWrapper.classList.add("d-none");
        editBtnWrapper.classList.remove("d-none");

        // Optional: Du kannst ein Bildvorschau-Element einbauen und hier aktualisieren

        window.scrollTo({ top: 100, behavior: "smooth" });
      });
    });
  }

  // === Event-Handler: "Abbrechen"-Button
  cancelEditBtn?.addEventListener("click", () => {
    form.reset();
    tweetInput.value = "";
    editPostIdInput.value = "";
    originalImagePathInput.value = "";

    postBtnWrapper.classList.remove("d-none");
    editBtnWrapper.classList.add("d-none");
  });

  // === Kommentar-Formular toggeln
  document.querySelectorAll(".toggle-comment-form").forEach((btn) => {
    btn.addEventListener("click", () => {
      const form = document.getElementById(`comment-form-${btn.dataset.postId}`);
      if (form) {
        form.style.display = form.style.display === "none" ? "block" : "none";
      }
    });
  });

  // === Löschen vorbereiten: Übergibt ID ans Modal
  document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach((deleteBtn) => {
    deleteBtn.addEventListener("click", () => {
      const postId = deleteBtn.closest(".tweet-card")?.dataset.postId;
      const deleteInput = document.querySelector('#deleteModal input[name="post_id"]');
      if (deleteInput) {
        deleteInput.value = postId;
      }
    });
  });

  // === Formular absenden per AJAX
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const postId = editPostIdInput.value;

    try {
      const res = await fetch("/Social_App/ajax/create_post.php", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();
      if (data.success) {
        // Alte Karte ersetzen oder neue einfügen
        const existingCard = document.querySelector(`.tweet-card[data-post-id="${data.post_id}"]`);
        const newCard = document.createElement("div");
        newCard.innerHTML = data.html.trim();
        const newCardElement = newCard.firstElementChild;

        if (existingCard) {
          existingCard.replaceWith(newCardElement); // ⬅️ korrekt ersetzen
        } else {
          feed.insertAdjacentElement("afterbegin", newCardElement);
        }

        // Formular zurücksetzen
        form.reset();
        tweetInput.value = "";
        editPostIdInput.value = "";
        originalImagePathInput.value = "";
        postBtnWrapper.classList.remove("d-none");
        editBtnWrapper.classList.add("d-none");

        // Event-Binding neu auf neue Elemente anwenden
        bindEditButtons();
      } else {
        alert("Fehler beim Absenden.");
      }
    } catch (err) {
      console.error("❌ Fehler beim Senden des Formulars:", err);
    }
  });

  // === Direkt nach DOM-Ready initial Edit-Buttons binden
  bindEditButtons();
});

// === AJAX: Post löschen
document.addEventListener("click", async (e) => {
  if (e.target.matches(".confirm-delete-btn")) {
    const postId = e.target.dataset.postId;
    const formData = new FormData();
    formData.append("post_id", postId);

    try {
      const res = await fetch("/Social_App/ajax/delete_post.php", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();
      if (data.success) {
        const card = document.querySelector(`.tweet-card[data-post-id="${postId}"]`);
        if (card) card.remove();
      } else {
        alert("Fehler beim Löschen.");
      }
    } catch (err) {
      console.error("❌ AJAX-Fehler beim Löschen:", err);
    }
  }
});
