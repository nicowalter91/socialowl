/**
 * Notifications.js
 * 
 * Diese Datei ist ein einfacher Wrapper für das notification-stream-handler.js-Modul.
 * Sie importiert das Modul und stellt die Funktionalität global zur Verfügung.
 */

// Import des Moduls aus dem modules-Verzeichnis
import { NotificationStreamHandler } from './modules/notification-stream-handler.js';

// Notification-Handler initialisieren, wenn das DOM geladen ist
document.addEventListener('DOMContentLoaded', () => {
  // Globale Instanz erstellen
  window.notificationStreamHandler = new NotificationStreamHandler();
  
  // Globale Funktionen für die Abwärtskompatibilität bereitstellen
  window.loadNotifications = function() {
    if (window.notificationStreamHandler) {
      window.notificationStreamHandler.loadNotifications();
    }
  };
  
  window.deleteNotification = async function(notificationId) {
    if (window.notificationStreamHandler) {
      await window.notificationStreamHandler.deleteNotification(notificationId);
    }
  };
  
  window.deleteAllNotifications = async function() {
    if (window.notificationStreamHandler) {
      await window.notificationStreamHandler.deleteAllNotifications();
    }
  };
  
  window.handleFollowRequest = async function(notificationId, accept) {
    if (window.notificationStreamHandler) {
      await window.notificationStreamHandler.handleFollowRequest(notificationId, accept);
    }
  };
  
  window.formatDate = function(dateString) {
    if (window.notificationStreamHandler) {
      return window.notificationStreamHandler.formatDate(dateString);
    }
    
    // Fallback
    const options = { 
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    };
    return new Date(dateString).toLocaleString('de-DE', options);
  };
});