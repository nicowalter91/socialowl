/*-- ============================
       Skripte & Interaktionen
  ============================ */

  document.addEventListener('DOMContentLoaded', () => {
    const tweetInput = document.querySelector('.tweet-input-box');
    const postBtnWrapper = document.getElementById('post-btn-wrapper');
    const editBtnWrapper = document.getElementById('edit-btn-wrapper');
    const editPostIdInput = document.getElementById('edit-post-id');
    const originalImagePathInput = document.getElementById('original-image-path');
    const cancelEditBtn = document.getElementById('cancel-edit');

    // 👇 Nur "Posten"-Button sichtbar beim Laden
    postBtnWrapper.classList.remove('d-none');
    editBtnWrapper.classList.add('d-none');

    // ✏️ Bearbeiten geklickt
    document.querySelectorAll('.edit-post-btn').forEach(button => {
      button.addEventListener('click', () => {
        tweetInput.value = button.dataset.content;
        editPostIdInput.value = button.dataset.postId;
        originalImagePathInput.value = button.dataset.image;

        postBtnWrapper.classList.add('d-none');
        editBtnWrapper.classList.remove('d-none');

        window.scrollTo({
          top: 100,
          behavior: 'smooth'
        });
      });
    });

    // ❌ Abbrechen gedrückt
    if (cancelEditBtn) {
      cancelEditBtn.addEventListener('click', () => {
        tweetInput.value = '';
        editPostIdInput.value = '';
        originalImagePathInput.value = '';

        postBtnWrapper.classList.remove('d-none');
        editBtnWrapper.classList.add('d-none');
      });
    }

    // 🗨️ Kommentarformular Umschalten
    document.querySelectorAll('.toggle-comment-form').forEach(btn => {
      btn.addEventListener('click', () => {
        const form = document.getElementById(`comment-form-${btn.dataset.postId}`);
        if (form) {
          form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
      });
    });

    // 🗑️ Löschen vorbereiten
    document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(deleteBtn => {
      deleteBtn.addEventListener('click', () => {
        const postId = deleteBtn.closest('.tweet-card')?.dataset.postId;
        const deleteInput = document.querySelector('#deleteModal input[name="post_id"]');
        if (deleteInput) {
          deleteInput.value = postId;
        }
      });
    });
  });

