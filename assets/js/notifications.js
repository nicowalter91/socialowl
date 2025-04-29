// Benachrichtigungen alle 30 Sekunden aktualisieren
setInterval(loadNotifications, 30000);

// Initial Benachrichtigungen laden
document.addEventListener('DOMContentLoaded', loadNotifications);

async function loadNotifications() {
    try {
        const response = await fetch('/Social_App/controllers/api/notifications.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();

        if (data.success) {
            updateNotificationBadge(data.unread_count);
            updateNotificationsList(data.notifications);
        } else {
            console.error('Fehler beim Laden der Benachrichtigungen:', data.message);
        }
    } catch (error) {
        console.error('Fehler beim Laden der Benachrichtigungen:', error);
        // Bei Fehler Badge ausblenden
        updateNotificationBadge(0);
    }
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (!badge) return;
    
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

function updateNotificationsList(notifications) {
    const list = document.getElementById('notifications-list');
    if (!list) return;
    
    list.innerHTML = '';

    if (!notifications || notifications.length === 0) {
        list.innerHTML = '<div class="p-3 text-light text-center">Keine Benachrichtigungen</div>';
        return;
    }

    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = `notification-item p-3 border-bottom border-secondary ${notification.is_read ? '' : 'bg-dark'}`;
        
        const icon = notification.type === 'follow' ? 'bi-person-plus-fill' : 'bi-chat-dots-fill';
        const color = notification.type === 'follow' ? 'text-primary' : 'text-success';
        
        item.innerHTML = `
            <div class="d-flex align-items-start gap-2">
                <i class="bi ${icon} ${color} fs-4"></i>
                <div class="flex-grow-1">
                    <p class="mb-1 text-light">${notification.content}</p>
                    <small class="text-secondary">${formatDate(notification.created_at)}</small>
                </div>
            </div>
        `;
        
        list.appendChild(item);
    });
}

function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    // Weniger als 24 Stunden
    if (diff < 24 * 60 * 60 * 1000) {
        const hours = Math.floor(diff / (60 * 60 * 1000));
        if (hours === 0) {
            const minutes = Math.floor(diff / (60 * 1000));
            return `vor ${minutes} Minuten`;
        }
        return `vor ${hours} Stunden`;
    }
    
    // Weniger als 7 Tage
    if (diff < 7 * 24 * 60 * 60 * 1000) {
        const days = Math.floor(diff / (24 * 60 * 60 * 1000));
        return `vor ${days} Tagen`;
    }
    
    // Älter als 7 Tage
    return date.toLocaleDateString('de-DE');
} 