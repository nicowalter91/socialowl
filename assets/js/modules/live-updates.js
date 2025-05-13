/**
 * Modul: live-updates.js
 * Steuert Live-Updates für Posts, Kommentare, Follows und Benachrichtigungen per SSE oder Polling.
 */

// Live-Update Funktionalität
export class LiveUpdates {
    constructor() {
        this.lastPostTimestamp = null;
        this.lastCommentTimestamp = null;
        this.lastLikeTimestamp = null;
        this.eventSource = null;
        this.pollingInterval = null;
        this.updateQueue = new Map(); // Queue für Updates
        this.isProcessingQueue = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 3; // Reduziert von 5 auf 3
        this.processedEditIds = new Set(); // Speichert IDs der bereits verarbeiteten Bearbeitungen
        this.init();
    }

    init() {
        this.initLastCommentTimestamp();
        this.initLastPostTimestamp();
        this.initLastLikeTimestamp();
        this.initLiveUpdateListeners();
        this.startPolling();
    }

    // Neu hinzugefügt: Initialisierung des lastLikeTimestamp
    initLastLikeTimestamp() {
        // Aktuelle Zeit als Startpunkt für Like-Updates
        const now = new Date();
        this.lastLikeTimestamp = this.formatTimestamp(now);
    }

    initLastCommentTimestamp() {
        const timestampElements = document.querySelectorAll(".comment-timestamp");
        if (timestampElements.length > 0) {
            const timestamps = Array.from(timestampElements)
                .map((el) => new Date(el.dataset.timestamp))
                .filter((date) => !isNaN(date));

            if (timestamps.length > 0) {
                const latest = timestamps.sort((a, b) => b - a)[0];
                this.lastCommentTimestamp = this.formatTimestamp(latest);
            }
        }
    }

    initLastPostTimestamp() {
        const els = document.querySelectorAll(".post-timestamp");
        if (!els.length) return;
        const dates = Array.from(els)
            .map((el) => new Date(el.dataset.timestamp))
            .filter((d) => !isNaN(d))
            .sort((a, b) => b - a);
        const latest = dates[0];
        this.lastPostTimestamp = this.formatTimestamp(latest);
    }

    formatTimestamp(date) {
        return date.getFullYear() +
            "-" +
            String(date.getMonth() + 1).padStart(2, "0") +
            "-" +
            String(date.getDate()).padStart(2, "0") +
            " " +
            String(date.getHours()).padStart(2, "0") +
            ":" +
            String(date.getMinutes()).padStart(2, "0") +
            ":" +
            String(date.getSeconds()).padStart(2, "0");
    }

    initLiveUpdateListeners() {
        if (this.eventSource) {
            this.eventSource.close();
        }

        this.eventSource = new EventSource("/Social_App/controllers/api/event_stream.php");
        
        this.eventSource.onmessage = (event) => {
            try {
                if (event.data === "heartbeat") return;
                
                const data = JSON.parse(event.data);
                if (data.type === 'heartbeat') return;
                this.addToUpdateQueue(data);
            } catch (err) {
                console.error("Fehler beim Verarbeiten der Nachricht:", err);
            }
        };

        const handleStreamError = (stream) => {
            const baseDelay = 5000; // Erhöht von 1000 auf 5000ms
            let reconnectTimeout = null;
            let lastHeartbeat = Date.now();
            const heartbeatTimeout = 60000; // Erhöht von 30000 auf 60000ms
            let isReconnecting = false;

            // Heartbeat-Überprüfung weniger häufig
            const heartbeatInterval = setInterval(() => {
                if (Date.now() - lastHeartbeat > heartbeatTimeout) {
                    console.log("Heartbeat timeout - Verbindung wird neu aufgebaut");
                    stream.close();
                    clearInterval(heartbeatInterval);
                    handleReconnect();
                }
            }, 30000); // Erhöht von 10000 auf 30000ms

            const handleReconnect = () => {
                if (isReconnecting || this.reconnectAttempts >= this.maxReconnectAttempts) {
                    if (this.reconnectAttempts >= this.maxReconnectAttempts) {
                        console.log("Max. Reconnect-Versuche erreicht - Wechsel zu Polling-Modus");
                        this.startPolling();
                    }
                    return;
                }

                isReconnecting = true;
                if (reconnectTimeout) clearTimeout(reconnectTimeout);

                const delay = baseDelay * Math.pow(2, this.reconnectAttempts);
                console.log(`Neuverbindungsversuch in ${delay/1000} Sekunden...`);
                
                reconnectTimeout = setTimeout(() => {
                    this.reconnectAttempts++;
                    isReconnecting = false;
                    this.initLiveUpdateListeners();
                }, delay);
            };

            return (error) => {
                console.log("Stream-Verbindung unterbrochen, starte Neuverbindung...");
                stream.close();
                clearInterval(heartbeatInterval);
                handleReconnect();
            };
        };

        this.eventSource.onerror = handleStreamError(this.eventSource);
        this.eventSource.onopen = () => {
            this.reconnectAttempts = 0;
            console.log("Event-Stream verbunden");
        };
        
        // Spezieller Event-Listener für Likes
        document.addEventListener('postLikeToggled', (event) => {
            // Bei lokalen Like-Aktionen nicht duplizieren
            if (event.detail.isLocalAction) return;
            
            const { postId, isLiked, likeCount } = event.detail;
            this.updateLikeUI(postId, isLiked, likeCount);
        });
    }    // Queue für Updates
    addToUpdateQueue(data) {
        if (!data || !data.type || !data.action) {
            console.error("Ungültige Daten für Update-Queue:", data);
            return;
        }

        const key = `${data.type}_${data.id}`;
        
        // Bei bearbeiteten Posts: überprüfen und in processedEditIds speichern
        if (data.action === 'edit') {
            this.processedEditIds.add(data.id);
        }
        
        // Bei neuen Posts: überprüfen, ob sie kürzlich bearbeitet wurden
        if (data.action === 'new' && this.processedEditIds.has(data.id)) {
            console.log(`Post ${data.id} wurde bereits bearbeitet, überspringe "neu"-Update`);
            return; // Nicht zur Queue hinzufügen, wenn bereits bearbeitet
        }
        
        this.updateQueue.set(key, data);
        
        if (!this.isProcessingQueue) {
            this.processUpdateQueue();
        }
    }

    async processUpdateQueue() {
        if (this.isProcessingQueue || this.updateQueue.size === 0) return;
        
        this.isProcessingQueue = true;
        
        try {
            // Batch-Update für alle Änderungen
            const updates = Array.from(this.updateQueue.values());
            this.updateQueue.clear();

            // Updates nach Typ gruppieren
            const deletions = updates.filter(u => u.action === 'delete');
            const edits = updates.filter(u => u.action === 'edit');
            const newItems = updates.filter(u => u.action === 'new');
            const likes = updates.filter(u => u.action === 'like' || u.action === 'unlike');

            // Verarbeite Updates in der richtigen Reihenfolge
            await this.processDeletions(deletions);
            await this.processEdits(edits);
            await this.processNewItems(newItems);
            await this.processLikes(likes);
        } finally {
            this.isProcessingQueue = false;
            
            // Prüfe, ob neue Updates hinzugekommen sind
            if (this.updateQueue.size > 0) {
                this.processUpdateQueue();
            }
        }
    }

    async processDeletions(deletions) {
        for (const data of deletions) {
            if (data.type === "post") {
                const postElement = document.querySelector(`.tweet-card[data-post-id="${data.id}"]`);
                if (postElement) postElement.remove();
            } else if (data.type === "comment") {
                const commentElement = document.querySelector(`.comment[data-comment-id="${data.id}"]`);
                if (commentElement) commentElement.remove();
            }
        }
    }    async processEdits(edits) {
        // Batch-Anfrage für alle Änderungen
        const editPromises = edits.map(data => 
            fetch(`/Social_App/controllers/api/notify_edit.php?type=${data.type}&id=${data.id}`)
                .then(res => res.json())
        );

        const results = await Promise.all(editPromises);
        
        // DOM-Updates in einem Batch durchführen
        results.forEach(data => {
            if (data.success && data.html) {
                const tempDiv = document.createElement("div");
                tempDiv.innerHTML = data.html;

                if (data.type === 'post') {
                    // Finde alle Instanzen des Posts und ersetze sie
                    const updatedPost = tempDiv.querySelector(`.tweet-card[data-post-id="${data.id}"]`);
                    const existingPosts = document.querySelectorAll(`.tweet-card[data-post-id="${data.id}"]`);
                    
                    if (existingPosts.length > 0 && updatedPost) {
                        // Theme auf den aktualisierten Post anwenden
                        this.applyThemeToElement(updatedPost);
                        
                        // Ersetze das erste Vorkommen und entferne alle weiteren Duplikate
                        existingPosts[0].replaceWith(updatedPost);
                        
                        // Entferne eventuell vorhandene Duplikate
                        for (let i = 1; i < existingPosts.length; i++) {
                            existingPosts[i].remove();
                        }
                        
                        // Speichere die ID als bearbeitet, damit sie nicht als "neu" behandelt wird
                        this.processedEditIds.add(data.id);
                        
                        // Aufräumen - Entfernen Sie veraltete Einträge nach einer Weile
                        setTimeout(() => {
                            this.processedEditIds.delete(data.id);
                        }, 60000); // Nach einer Minute wieder freigeben
                    }
                } else if (data.type === 'comment') {
                    const updatedComment = tempDiv.querySelector(`.comment[data-comment-id="${data.id}"]`);
                    const existingComments = document.querySelectorAll(`.comment[data-comment-id="${data.id}"]`);
                    
                    if (existingComments.length > 0 && updatedComment) {
                        // Theme auf den aktualisierten Kommentar anwenden
                        this.applyThemeToElement(updatedComment);
                        
                        // Ersetze das erste Vorkommen und entferne alle weiteren Duplikate
                        existingComments[0].replaceWith(updatedComment);
                        
                        // Entferne eventuell vorhandene Duplikate
                        for (let i = 1; i < existingComments.length; i++) {
                            existingComments[i].remove();
                        }
                    }
                }
            }
        });
    }    // Implementierung für neue Items vervollständigt
    async processNewItems(newItems) {
        if (newItems.length === 0) return;
        
        // Filtere Posts aus, die kürzlich bearbeitet wurden
        const postItems = newItems.filter(item => 
            item.type === 'post' && !this.processedEditIds.has(item.id)
        );
        const commentItems = newItems.filter(item => item.type === 'comment');
        
        // Verarbeitung neuer Posts
        if (postItems.length > 0) {
            try {
                // URL für den API-Aufruf konstruieren
                // Verwende posts_since.php falls get_new_posts.php nicht vorhanden ist
                const apiEndpoint = '/Social_App/controllers/api/posts_since.php';
                const timestamp = this.lastPostTimestamp || '';
                
                // Bereite eine Liste von Post-IDs vor, die wir abrufen möchten
                const postIds = postItems.map(item => item.id);
                
                // Abruf der neuen Posts 
                const queryParams = new URLSearchParams({
                    since: timestamp
                });
                
                const response = await fetch(`${apiEndpoint}?${queryParams.toString()}`);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                
                const data = await response.json();
                if (data.success && Array.isArray(data.html)) {
                    const feedContainer = document.querySelector('#feed');
                    if (!feedContainer) return;
                    
                    // Filtere Posts, die kürzlich bearbeitet wurden
                    const filteredPostIds = postIds.filter(id => !this.processedEditIds.has(id));
                    
                    let actualNewPosts = 0;
                    
                    // Füge die HTML-Snippets in den DOM ein
                    data.html.forEach((html, index) => {
                        const postData = data.posts[index];
                        
                        // Nur verarbeiten, wenn der Post in unserer gefilterten Liste ist
                        if (filteredPostIds.includes(postData.id)) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = html;
                            const post = tempDiv.firstElementChild;
                            
                            // Prüfe, ob der Post bereits im DOM existiert
                            if (post && !document.querySelector(`.tweet-card[data-post-id="${postData.id}"]`)) {
                                // Füge den Post am Anfang des Feeds ein
                                feedContainer.insertBefore(post, feedContainer.firstChild);
                                
                                // Theme auf den neuen Post anwenden
                                this.applyThemeToElement(post);
                                actualNewPosts++;
                            }
                        }
                    });
                    
                    // Zeige Benachrichtigung NUR für wirklich neue Posts
                    if (actualNewPosts > 0) {
                        this.showNotification(`${actualNewPosts} neue Beiträge wurden geladen`, 'info');
                    }
                }
            } catch (error) {
                console.error('Fehler beim Laden neuer Posts:', error);
            }
        }
        
        // Verarbeitung neuer Kommentare
        if (commentItems.length > 0) {
            try {
                const commentIds = commentItems.map(item => item.id).join(',');
                const response = await fetch(`/Social_App/controllers/api/get_new_comments.php?ids=${commentIds}`);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                
                const data = await response.json();
                if (data.success && data.comments) {
                    data.comments.forEach(comment => {
                        const commentContainer = document.querySelector(`.comments-container[data-post-id="${comment.post_id}"]`);
                        if (commentContainer) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = comment.html;
                            
                            // Prüfe ob der Kommentar bereits existiert
                            const commentId = tempDiv.querySelector('.comment').dataset.commentId;
                            if (!document.querySelector(`.comment[data-comment-id="${commentId}"]`)) {
                                const commentElement = tempDiv.firstChild;
                                commentContainer.appendChild(commentElement);
                                
                                // Wende Theme auf neue Elemente im Kommentar an
                                this.applyThemeToElement(commentElement);
                                
                                // Aktualisiere den Kommentarzähler
                                const countElement = document.querySelector(`.comment-count[data-post-id="${comment.post_id}"]`);
                                if (countElement) {
                                    const currentCount = parseInt(countElement.textContent);
                                    countElement.textContent = currentCount + 1;
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Fehler beim Laden neuer Kommentare:', error);
            }
        }
    }
    
    // Neu hinzugefügt: Hilfsfunktion zur Anwendung des aktuellen Themes auf neue Elemente
    applyThemeToElement(element) {
        const isDarkMode = document.body.classList.contains('dark-mode');
        
        // Like-Buttons im Element finden und entsprechend formatieren
        const likeButtons = element.querySelectorAll('.like-btn');
        likeButtons.forEach(btn => {
            const isLiked = btn.dataset.liked === '1';
            
            if (isLiked) {
                btn.classList.remove('btn-outline-primary');
                
                if (isDarkMode) {
                    btn.classList.remove('btn-light', 'text-primary');
                    btn.classList.add('btn-primary');
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-light', 'text-primary');
                }
                
                // Auch den Counter anpassen
                const counter = btn.querySelector('.like-count');
                if (counter) {
                    counter.classList.remove('bg-primary');
                    counter.classList.add('bg-dark');
                }
            } else {
                btn.classList.remove('btn-primary', 'btn-light', 'text-primary');
                btn.classList.add('btn-outline-primary');
                
                // Auch den Counter anpassen
                const counter = btn.querySelector('.like-count');
                if (counter) {
                    counter.classList.remove('bg-dark');
                    counter.classList.add('bg-primary');
                }
            }
        });
        
        // Kommentar-Buttons stylen (Neu hinzugefügt)
        const editCommentButtons = element.querySelectorAll('.edit-comment-btn');
        const deleteCommentButtons = element.querySelectorAll('.delete-comment-btn');
        const likeCommentButtons = element.querySelectorAll('.like-comment-btn');
        
        // Edit-Buttons für Kommentare
        editCommentButtons.forEach(btn => {
            if (isDarkMode) {
                btn.classList.remove('btn-outline-dark');
                btn.classList.add('btn-outline-light');
            } else {
                btn.classList.remove('btn-outline-light');
                btn.classList.add('btn-outline-dark');
            }
        });
        
        // Delete-Buttons für Kommentare (bleiben immer btn-outline-danger)
        
        // Like-Buttons für Kommentare
        likeCommentButtons.forEach(btn => {
            const isLiked = btn.classList.contains('btn-light');
            
            if (isLiked) {
                // Like-Status bleibt gleich (btn-light text-dark)
            } else {
                // Unlike-Status anpassen
                if (isDarkMode) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-outline-light');
                } else {
                    btn.classList.remove('btn-outline-light');
                    btn.classList.add('btn-outline-primary');
                }
            }
        });
        
        // Weitere theme-spezifische Anpassungen hier...
    }
    
    // Neu hinzugefügt: Verarbeitung von Like-Updates
    async processLikes(likes) {
        for (const data of likes) {
            if (data.type === 'post') {
                this.updateLikeUI(data.id, data.action === 'like', data.count);
            }
        }
    }
    
    // Neu hinzugefügt: UI-Aktualisierung für Likes
    updateLikeUI(postId, isLiked, likeCount) {
        const likeBtn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
        if (!likeBtn) return;
        
        // Nur UI-Update durchführen ohne Server-Anfrage
        if (window.likeHandler) {
            const isDarkMode = document.body.classList.contains('dark-mode');
            // Theme-Information mit übergeben
            window.likeHandler.updateButtonUI(likeBtn, isLiked, true);
            
            // Zusätzliche theme-spezifische Anpassungen
            if (isLiked) {
                if (isDarkMode) {
                    likeBtn.classList.remove('btn-light', 'text-primary');
                    likeBtn.classList.add('btn-primary');
                } else {
                    likeBtn.classList.remove('btn-primary');
                    likeBtn.classList.add('btn-light', 'text-primary');
                }
            }
            
            // Aktualisiere den Like-Zähler
            const countElement = likeBtn.querySelector('.like-count');
            if (countElement && likeCount !== undefined) {
                countElement.textContent = likeCount;
            }
        }
    }

    startPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }

        this.pollingInterval = setInterval(() => {
            this.fetchUpdates();
        }, 5000);
    }

    async fetchUpdates() {
        try {
            const params = new URLSearchParams({
                sincePosts: this.lastPostTimestamp,
                sinceComments: this.lastCommentTimestamp,
                sinceLikes: this.lastLikeTimestamp
            });
            
            const response = await fetch(`/Social_App/controllers/api/updates_since.php?${params}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            
            if (data.success) {
                // Verarbeite neue Posts
                if (data.posts && data.posts.length > 0) {
                    data.posts.forEach(post => {
                        this.addToUpdateQueue({
                            type: 'post',
                            action: 'new',
                            id: post.id,
                            data: post
                        });
                    });
                    
                    if (data.postTimestamp) {
                        this.lastPostTimestamp = data.postTimestamp;
                    }
                }

                // Verarbeite neue Kommentare
                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        this.addToUpdateQueue({
                            type: 'comment',
                            action: 'new',
                            id: comment.id,
                            data: comment
                        });
                    });
                    
                    if (data.commentTimestamp) {
                        this.lastCommentTimestamp = data.commentTimestamp;
                    }
                }
                
                // Verarbeite neue Likes
                if (data.likes && data.likes.length > 0) {
                    data.likes.forEach(like => {
                        this.addToUpdateQueue({
                            type: 'post',
                            action: like.action, // 'like' oder 'unlike'
                            id: like.post_id,
                            count: like.like_count
                        });
                    });
                    
                    if (data.likeTimestamp) {
                        this.lastLikeTimestamp = data.likeTimestamp;
                    }
                }
            }
        } catch (error) {
            console.error("Fehler beim Abrufen von Updates:", error);
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// ============================
// Live Update: Following Count and Sidebar Updates
// ============================
async function updateFollowingSidebar(userId) {
  try {
    const res = await fetch(`/Social_App/controllers/api/following_update.php?user_id=${userId}`);
    const data = await res.json();

    if (data.success) {
      // Update Following Count in Sidebar-Left
      const followingCountElement = document.querySelector(".right-stats h3");
      if (followingCountElement) {
        followingCountElement.textContent = data.followingCount;
      }

      // Update Follower Count in Sidebar-Left
      const followerCountElement = document.querySelector(".left-stats h3");
      if (followerCountElement) {
        followerCountElement.textContent = data.followerCount;
      }

      // Update Sidebar-Right with New Followed User
      const sidebarRight = document.querySelector(".following");
      if (sidebarRight && data.newFollowedUserHtml) {
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = data.newFollowedUserHtml;
        const newUserElement = tempDiv.firstElementChild;

        // Check for duplicates before adding
        const existingUser = sidebarRight.querySelector(`[data-user-id="${userId}"]`);
        if (!existingUser) {
          // Find the container after the heading
          const container = sidebarRight.querySelector(".following p") || 
                          sidebarRight.querySelector("h6").nextElementSibling;
          
          // If "Noch keine Nutzer gefolgt" message exists, remove it
          const noUsersMessage = sidebarRight.querySelector("p.text-light.small");
          if (noUsersMessage && noUsersMessage.textContent.includes("Noch keine Nutzer gefolgt")) {
            noUsersMessage.remove();
          }

          container.parentNode.insertBefore(newUserElement, container.nextSibling);
        }
      }
    }
  } catch (err) {
    console.error("❌ Fehler beim Aktualisieren der Sidebar:", err);
  }
}

// Listen for follow events
const followEventSource = new EventSource("/Social_App/controllers/api/follow_stream.php");
followEventSource.onmessage = (event) => {
  if (event.data === "heartbeat") return; // Heartbeat ignorieren
  const { userId } = JSON.parse(event.data);
  updateFollowingSidebar(userId);
};