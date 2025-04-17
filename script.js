document.addEventListener("DOMContentLoaded", () => {
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

  // 🖊️ Bearbeiten
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

  // ❌ Abbrechen
  cancelEditBtn?.addEventListener("click", () => {
    tweetInput.value = "";
    editPostIdInput.value = "";
    originalImagePathInput.value = "";

    postBtnWrapper.classList.remove("d-none");
    editBtnWrapper.classList.add("d-none");
  });

  // 💬 Kommentar-Formular toggeln
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

  // 🗑️ Löschen vorbereiten
  document
    .querySelectorAll('[data-bs-target="#deleteModal"]')
    .forEach((deleteBtn) => {
      deleteBtn.addEventListener("click", () => {
        const postId = deleteBtn.closest(".tweet-card")?.dataset.postId;
        const deleteInput = document.querySelector(
          '#deleteModal input[name="post_id"]'
        );
        if (deleteInput) {
          deleteInput.value = postId;
        }
      });
    });

  // ✅ AJAX Submit
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const res = await fetch("/Social_App/ajax/create_post.php", {
        method: "POST",
        body: formData,
      });

     

      const data = await res.json();
      console.log("Antwort:", data);

      if (data.success) {
        form.reset();
        postBtnWrapper.classList.remove("d-none");
        editBtnWrapper.classList.add("d-none");
        editPostIdInput.value = "";
        originalImagePathInput.value = "";

        feed.insertAdjacentHTML("afterbegin", data.html);
      } else {
        alert("Fehler beim Absenden.");
      }
    } catch (err) {
      console.error("Fehler beim Senden des Formulars:", err);
    }
  });
});
