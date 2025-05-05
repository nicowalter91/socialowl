/**
 * Notification-Stream-Handler Modul
 * Verwaltet den EventSource für Benachrichtigungen und folgt dem Stream-Paradigma
 */

export class NotificationStreamHandler {
  constructor() {
    this.followStream = null;
    this.notificationCheckInterval = null;

    console.log('✅ NotificationStreamHandler initialisiert');
    this.init();
  }

  init() {
    // EventSource für Follower-Anfragen initialisieren
    this.initFollowStream();

    // Benachrichtigungen alle 30 Sekunden aktualisieren
    this.notificationCheckInterval = setInterval(() => this.loadNotifications(), 30000);

    // Initial Benachrichtigungen laden
    this.loadNotifications();
  }

  initFollowStream() {
    if (window.EventSource) {
      this.followStream = new EventSource('/Social_App/controllers/api/follow_stream.php');

      this.followStream.onmessage = (event) => {
        if (!event.data || event.data === 'heartbeat') return;

        try {
          const data = JSON.parse(event.data);
          if (data.event === 'new_follow_request' || data.event === 'follow_request_update') {
            this.loadNotifications();
            if (window.liveUpdates && window.CURRENT_USER_ID) {
              window.liveUpdates.updateFollowingSidebar(window.CURRENT_USER_ID);
            }
          }
        } catch (e) {
          // ignore parse errors for heartbeats etc.
        }
      };

      this.followStream.onerror = () => {
        // Optional: reconnect Logik oder Fehlermeldung
        // Wir könnten hier eine automatische Reconnect-Strategie implementieren
      };
    }
  }

  async loadNotifications() {
    console.log('🔄 Lade Benachrichtigungen...');
    try {
      const response = await fetch('/Social_App/controllers/api/notifications.php');

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      if (data.success) {
        this.updateNotificationBadge(data.unread_count);
        this.updateNotificationsList(data.notifications);
      } else {
        console.error('Fehler beim Laden der Benachrichtigungen:', data.message);
      }
    } catch (error) {
      console.error('Fehler beim Laden der Benachrichtigungen:', error);
      this.updateNotificationBadge(0);
    }
  }

  updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    if (count > 0) {
      badge.textContent = count;
      badge.style.display = 'block';
    } else {
      badge.style.display = 'none';
    }
  }

  updateNotificationsList(notifications) {
    const list = document.getElementById('notifications-list');
    if (!list) return;

    list.innerHTML = '';

    if (!notifications || notifications.length === 0) {
      list.innerHTML = '<div class="p-3 text-light text-center">Keine Benachrichtigungen</div>';
      return;
    }

    // Benachrichtigungen hinzufügen
    notifications.forEach(notification => {
      const item = document.createElement('div');
      item.className = `notification-item p-3 border-bottom border-secondary ${notification.is_read ? '' : 'bg-dark'}`;
      item.dataset.notificationId = notification.id;

      // Icon und Farbe je nach Typ
      let icon, color;
      switch (notification.type) {
        case 'follow':
          icon = 'bi-person-plus-fill';
          color = 'text-primary';
          break;
        case 'follow_request':
          icon = 'bi-person-plus';
          color = 'text-warning';
          break;
        case 'like':
          icon = 'bi-heart-fill';
          color = 'text-danger';
          break;
        case 'comment':
          icon = 'bi-chat-dots-fill';
          color = 'text-success';
          break;
        default:
          icon = 'bi-bell-fill';
          color = 'text-secondary';
      }

      // Wenn post_id vorhanden ist, mache die Notification klickbar
      const wrapperClass = notification.post_id ? 'cursor-pointer' : '';

      // Spezialfall: Follow-Request mit Buttons
      let notificationContent = `<p class=\"mb-1 text-light\">${notification.content}</p>\n<small class=\"text-secondary\">${this.formatDate(notification.created_at)}</small>`;
      if (notification.type === 'follow_request') {
        notificationContent = `
          <p class=\"mb-1 text-light\">${notification.content}</p>
          <div class=\"mt-2 d-flex gap-2\">
            <button type=\"button\" class=\"btn btn-success btn-sm accept-follow-request\" data-request-id=\"${notification.id}\"><i class=\"bi bi-check-lg\"></i></button>
            <button type=\"button\" class=\"btn btn-danger btn-sm reject-follow-request\" data-request-id=\"${notification.id}\"><i class=\"bi bi-x-lg\"></i></button>
          </div>
          <small class=\"text-secondary\">${this.formatDate(notification.created_at)}</small>
        `;
      }

      item.innerHTML = `
        <div class=\"d-flex align-items-start gap-2 ${wrapperClass}\">
          <i class=\"bi ${icon} ${color} fs-4\"></i>
          <div class=\"flex-grow-1\">
            <div class=\"d-flex justify-content-between align-items-start\">
              <div>
                ${notificationContent}
              </div>
              <button type=\"button\" class=\"btn btn-link btn-sm delete-notification ps-4\" title=\"Benachrichtigung löschen\" style=\"color: #b7c8d2; font-size: 1.2rem; padding-left: 1.5rem !important; padding-right: 0; margin-left: 12px;\">
                <i class=\"bi bi-x\"></i>
              </button>
            </div>
          </div>
        </div>
      `;

      list.appendChild(item);

      // Event-Listener für Löschen-Button
      const deleteButton = item.querySelector('.delete-notification');
      if (deleteButton) {
        deleteButton.addEventListener('click', async (e) => {
          e.preventDefault();
          e.stopPropagation();
          await this.deleteNotification(notification.id);
        });
      }

      // Event-Listener für Klick auf Benachrichtigung (nur wenn post_id vorhanden)
      if (notification.post_id) {
        const wrapper = item.querySelector(`.${wrapperClass}`);
        if (wrapper) {
          wrapper.addEventListener('click', (e) => {
            if (!e.target.closest('.delete-notification')) {
              const postElement = document.querySelector(`#post-${notification.post_id}`);
              if (postElement) {
                postElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                postElement.classList.add('highlight-post');
                setTimeout(() => postElement.classList.remove('highlight-post'), 2000);
              }
            }
          });
        }
      }

      // Event-Listener für Follow-Request-Buttons
      if (notification.type === 'follow_request') {
        const acceptBtn = item.querySelector('.accept-follow-request');
        const rejectBtn = item.querySelector('.reject-follow-request');
        if (acceptBtn) {
          acceptBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            await this.handleFollowRequest(notification.id, true);
          });
        }
        if (rejectBtn) {
          rejectBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            await this.handleFollowRequest(notification.id, false);
          });
        }
      }
    });

    // "Alle löschen" Button nach den Benachrichtigungen hinzufügen
    const deleteAllButton = document.createElement('button');
    deleteAllButton.className = 'btn text-danger mx-3 mt-2 d-flex align-items-center gap-1';
    deleteAllButton.style.fontWeight = '300';
    deleteAllButton.innerHTML = 'Alle Benachrichtigungen löschen';
    list.appendChild(deleteAllButton);

    // Event-Listener für "Alle löschen" Button
    deleteAllButton.addEventListener('click', async (e) => {
      e.preventDefault();
      if (confirm('Möchten Sie wirklich alle Benachrichtigungen löschen?')) {
        await this.deleteAllNotifications();
      }
    });
  }

  async deleteNotification(notificationId) {
    console.log(`🗑️ Lösche Benachrichtigung mit ID: ${notificationId}`);
    try {
      const formData = new FormData();
      formData.append('notification_id', notificationId);

      const response = await fetch('/Social_App/controllers/api/delete_notification.php', {
        method: 'POST',
        body: formData
      });

      if (!response.ok) throw new Error('Netzwerkfehler');

      const data = await response.json();
      if (data.success) {
        await this.loadNotifications();
      } else {
        console.error('Fehler beim Löschen:', data.message);
      }
    } catch (error) {
      console.error('Fehler beim Löschen der Benachrichtigung:', error);
    }
  }

  async deleteAllNotifications() {
    console.log('🗑️ Lösche alle Benachrichtigungen...');
    try {
      const formData = new FormData();
      formData.append('delete_all', 'true');

      const response = await fetch('/Social_App/controllers/api/delete_notification.php', {
        method: 'POST',
        body: formData
      });

      if (!response.ok) throw new Error('Netzwerkfehler');

      const data = await response.json();
      if (data.success) {
        await this.loadNotifications();
      } else {
        console.error('Fehler beim Löschen aller Benachrichtigungen:', data.message);
      }
    } catch (error) {
      console.error('Fehler beim Löschen aller Benachrichtigungen:', error);
    }
  }

  formatDate(dateString) {
    const options = {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    };
    return new Date(dateString).toLocaleString('de-DE', options);
  }

  async handleFollowRequest(notificationId, accept) {
    console.log(`🔄 Bearbeite Follow-Anfrage: ID=${notificationId}, akzeptiert=${accept}`);
    try {
      const formData = new FormData();
      formData.append('notification_id', notificationId);
      formData.append('action', accept ? 'accept' : 'reject');
      const response = await fetch('/Social_App/controllers/api/follow_request_action.php', {
        method: 'POST',
        body: formData
      });
      if (!response.ok) throw new Error('Netzwerkfehler');
      const data = await response.json();
      if (data.success) {
        await this.loadNotifications();
      } else {
        alert('Fehler: ' + (data.message || 'Unbekannter Fehler'));
      }
    } catch (error) {
      alert('Fehler beim Bearbeiten der Anfrage');
    }
  }

  // Cleanup Methode für Garbage Collection
  destroy() {
    if (this.followStream) {
      this.followStream.close();
      this.followStream = null;
    }

    if (this.notificationCheckInterval) {
      clearInterval(this.notificationCheckInterval);
      this.notificationCheckInterval = null;
    }
  }
}