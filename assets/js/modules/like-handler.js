/**
 * Modul: like-handler.js
 * Steuert die AJAX-basierte Like-Funktionalität der Posts
 * 
 * Dieses Modul implementiert die asynchrone Verarbeitung von Likes und Unlikes
 * für Posts mittels AJAX. Es verbessert die Benutzerfreundlichkeit, indem 
 * Seitenneuladen vermieden wird.
 */

export class LikeHandler {
  constructor() {
    this.BASE_URL = document.body.dataset.baseUrl || '/Social_App';
    this.init();
  }

  init() {
    // Event-Delegation für Like-Buttons (funktioniert auch für dynamisch nachgeladene Elemente)
    document.addEventListener('click', (event) => {
      const likeBtn = event.target.closest('.like-btn');
      if (likeBtn) {
        this.handleLikeClick(likeBtn);
      }
    });

    // Initiale Anpassung der Like-Buttons an das aktuelle Theme
    this.updateLikeButtonsForTheme();

    // Theme-Änderungen beobachten und Buttons entsprechend anpassen
    document.addEventListener('themeChanged', (event) => {
      this.updateLikeButtonsForTheme(event.detail.theme);
    });
  }

  /**
   * Verarbeitet das Klick-Event auf einen Like-Button
   * @param {HTMLElement} btn - Der geklickte Like-Button
   */
  handleLikeClick(btn) {
    const form = btn.closest('.like-form');
    const postId = form.dataset.postId;
    const isLiked = btn.dataset.liked === '1';
    const likeCount = btn.querySelector('.like-count');
    const csrfToken = form.querySelector('[name="csrf_token"]')?.value;
    
    // Sofortiges visuelles Feedback bevor die API-Antwort eintrifft
    this.updateButtonUI(btn, !isLiked);
    
    // AJAX-Anfrage an API
    fetch(`${this.BASE_URL}/api/like_post.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      body: JSON.stringify({
        post_id: postId,
        action: isLiked ? 'unlike' : 'like'
      })
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Netzwerk-Antwort war nicht ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Update like count mit dem Wert vom Server
        const newLikeCount = parseInt(data.like_count);
        likeCount.textContent = newLikeCount;
        
        // Button Status vom Server aktualisieren (falls nötig)
        const shouldBeLiked = data.action === 'like';
        if (shouldBeLiked !== (btn.dataset.liked === '1')) {
          this.updateButtonUI(btn, shouldBeLiked);
        }
        
        // Event auslösen für andere Module, die auf Likes reagieren möchten
        const likeEvent = new CustomEvent('postLikeToggled', {
          detail: {
            postId: postId,
            isLiked: shouldBeLiked,
            likeCount: newLikeCount,
            isLocalAction: true // Markieren als lokale Aktion, damit Live-Updates nicht duplizieren
          }
        });
        document.dispatchEvent(likeEvent);
      } else {
        // Bei Fehler UI zurücksetzen
        this.updateButtonUI(btn, isLiked);
        console.error('Fehler beim Like/Unlike:', data.message);
      }
    })
    .catch(error => {
      // Bei Netzwerk-/Serverfehler UI zurücksetzen
      this.updateButtonUI(btn, isLiked);
      console.error('Fehler bei der Like-Anfrage:', error);
    });
  }

  /**
   * Aktualisiert die UI des Like-Buttons basierend auf dem Like-Status
   * @param {HTMLElement} btn - Der Like-Button
   * @param {boolean} isLiked - Ob der Post geliked ist
   * @param {boolean} skipServerUpdate - Optional: Wenn true, wird keine Event ausgelöst (für Live-Updates)
   */
  updateButtonUI(btn, isLiked, skipServerUpdate = false) {
    const likeCount = btn.querySelector('.like-count');
    const isDarkMode = document.body.classList.contains('dark-mode');
    
    if (isLiked) {
      // Like-Status UI
      btn.classList.remove('btn-outline-primary');
      
      if (isDarkMode) {
        // Dark Theme Like-Button
        btn.classList.add('btn-primary');
        btn.classList.remove('btn-light', 'text-primary');
      } else {
        // Light Theme Like-Button
        btn.classList.add('btn-light', 'text-primary');
        btn.classList.remove('btn-primary');
      }
      
      btn.querySelector('.bi').classList.remove('bi-hand-thumbs-up');
      btn.querySelector('.bi').classList.add('bi-hand-thumbs-up-fill');
      likeCount.classList.remove('bg-primary');
      likeCount.classList.add('bg-dark');
      btn.dataset.liked = '1';
      btn.title = 'Dir gefällt dieser Beitrag';
      
      if (btn.querySelector('.d-md-inline')) {
        btn.querySelector('.d-md-inline').textContent = 'Gefällt';
      }
    } else {
      // Unlike-Status UI
      btn.classList.remove('btn-primary', 'btn-light', 'text-primary');
      btn.classList.add('btn-outline-primary');
      
      btn.querySelector('.bi').classList.remove('bi-hand-thumbs-up-fill');
      btn.querySelector('.bi').classList.add('bi-hand-thumbs-up');
      likeCount.classList.remove('bg-dark');
      likeCount.classList.add('bg-primary');
      btn.dataset.liked = '0';
      btn.title = 'Gefällt mir markieren';
      
      if (btn.querySelector('.d-md-inline')) {
        btn.querySelector('.d-md-inline').textContent = 'Gefällt mir';
      }
    }
  }

  /**
   * Aktualisiert alle Like-Buttons entsprechend dem aktuellen Theme
   * @param {string} theme - Optional: Das aktuelle Theme ('light' oder 'dark')
   */
  updateLikeButtonsForTheme(theme = null) {
    const isDarkMode = theme ? theme === 'dark' : document.body.classList.contains('dark-mode');
    
    document.querySelectorAll('.like-btn').forEach(btn => {
      const isLiked = btn.dataset.liked === '1';
      
      if (isLiked) {
        btn.classList.remove('btn-outline-primary');
        btn.classList.remove(isDarkMode ? 'btn-light' : 'btn-primary');
        btn.classList.add(isDarkMode ? 'btn-primary' : 'btn-light');
        
        if (!isDarkMode) {
          btn.classList.add('text-primary');
        } else {
          btn.classList.remove('text-primary');
        }
      } else {
        btn.classList.remove('btn-primary', 'btn-light', 'text-primary');
        btn.classList.add('btn-outline-primary');
      }
    });
  }
}