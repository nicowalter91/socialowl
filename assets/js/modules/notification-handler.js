/**
 * Notification-Handler Modul
 * Verwaltet die Anzeige und Verarbeitung von Benachrichtigungen
 */

export class NotificationHandler {
  constructor() {
    // Keine speziellen DOM-Elemente im Konstruktor nötig
  }
  
  /**
   * Zeigt eine Benachrichtigung an
   * @param {string} message - Die Nachricht, die angezeigt werden soll
   * @param {string} type - Der Typ der Nachricht (info, success, warning, danger)
   */
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification`;
    
    // Trash-Icon für "danger"-Benachrichtigungen rot einfärben
    if (type === 'danger' && message.includes('trash')) {
      notification.innerHTML = `<i class='bi bi-trash-fill' style='color:#dc3545; margin-right:0.5em;'></i>${message.replace('trash', '')}`;
    } else {
      notification.textContent = message;
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }
  
  /**
   * Benachrichtigt über die Löschung eines Posts oder Kommentars
   * @param {string} type - "post" oder "comment"
   * @param {string} id - Die ID des gelöschten Elements
   */
  async notifyDeletion(type, id) {
    try {
      const res = await fetch(`/Social_App/controllers/api/notify_deletion.php?type=${type}&id=${id}`);
      const data = await res.json();
  
      if (data.success) {
        if (type === "post") {
          const postElement = document.querySelector(`.tweet-card[data-post-id="${id}"]`);
          if (postElement) {
            postElement.remove();
            this.showNotification(`Ein Post wurde gelöscht`, 'info');
          }
        } else if (type === "comment") {
          const commentElement = document.querySelector(`.comment[data-comment-id="${id}"]`);
          if (commentElement) {
            commentElement.remove();
            this.showNotification(`Ein Kommentar wurde gelöscht`, 'info');
          }
        }
      }
    } catch (err) {
      console.error("❌ Fehler beim Benachrichtigen der Löschung:", err);
      this.showNotification('Fehler beim Aktualisieren der Löschung', 'danger');
    }
  }
  
  /**
   * Benachrichtigt über die Bearbeitung eines Posts oder Kommentars
   * @param {string} type - "post" oder "comment"
   * @param {string} id - Die ID des bearbeiteten Elements
   */
  async notifyEdit(type, id) {
    try {
      const res = await fetch(`/Social_App/controllers/api/notify_edit.php?type=${type}&id=${id}`);
      const data = await res.json();
  
      if (data.success && data.html) {
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = data.html;
  
        if (type === "post") {
          const updatedPost = tempDiv.querySelector(`.tweet-card[data-post-id="${id}"]`);
          const existingPost = document.querySelector(`.tweet-card[data-post-id="${id}"]`);
          if (existingPost && updatedPost) {
            // Setze den Timestamp für die Aktualisierung
            updatedPost.dataset.lastUpdate = new Date().toISOString();
            existingPost.replaceWith(updatedPost);
            this.showNotification(`Ein Post wurde bearbeitet`, 'info');
          }
        } else if (type === "comment") {
          const updatedComment = tempDiv.querySelector(`.comment[data-comment-id="${id}"]`);
          const existingComment = document.querySelector(`.comment[data-comment-id="${id}"]`);
          if (existingComment && updatedComment) {
            existingComment.replaceWith(updatedComment);
            this.showNotification(`Ein Kommentar wurde bearbeitet`, 'info');
          }
        }
      }
    } catch (err) {
      console.error("❌ Fehler beim Benachrichtigen der Bearbeitung:", err);
      this.showNotification('Fehler beim Aktualisieren der Bearbeitung', 'danger');
    }
  }
}