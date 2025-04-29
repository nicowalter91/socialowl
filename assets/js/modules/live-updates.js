// Live-Update Funktionalität
export class LiveUpdates {
    constructor() {
        this.lastPostTimestamp = null;
        this.lastCommentTimestamp = null;
        this.eventSource = null;
        this.pollingInterval = null;
        this.updateQueue = new Map(); // Queue für Updates
        this.isProcessingQueue = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 3; // Reduziert von 5 auf 3
        this.init();
    }

    init() {
        this.initLastCommentTimestamp();
        this.initLastPostTimestamp();
        this.initLiveUpdateListeners();
        this.startPolling();
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
    }

    // Queue für Updates
    addToUpdateQueue(data) {
        if (!data || !data.type || !data.action) {
            console.error("Ungültige Daten für Update-Queue:", data);
            return;
        }

        const key = `${data.type}_${data.id}`;
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

            // Verarbeite Updates in der richtigen Reihenfolge
            await this.processDeletions(deletions);
            await this.processEdits(edits);
            await this.processNewItems(newItems);
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
    }

    async processEdits(edits) {
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
                    const updatedPost = tempDiv.querySelector(`.tweet-card[data-post-id="${data.id}"]`);
                    const existingPost = document.querySelector(`.tweet-card[data-post-id="${data.id}"]`);
                    if (existingPost && updatedPost) {
                        existingPost.replaceWith(updatedPost);
                    }
                } else if (data.type === 'comment') {
                    const updatedComment = tempDiv.querySelector(`.comment[data-comment-id="${data.id}"]`);
                    const existingComment = document.querySelector(`.comment[data-comment-id="${data.id}"]`);
                    if (existingComment && updatedComment) {
                        existingComment.replaceWith(updatedComment);
                    }
                }
            }
        });
    }

    async processNewItems(newItems) {
        // Implementierung für neue Items
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
            const response = await fetch(`/Social_App/controllers/api/updates_since.php?since=${this.lastPostTimestamp}`);
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
                }

                // Aktualisiere Timestamp
                if (data.timestamp) {
                    this.lastPostTimestamp = data.timestamp;
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
  const { userId } = JSON.parse(event.data);
  updateFollowingSidebar(userId);
};