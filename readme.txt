==================================================
 SOCIAL OWL - README 
==================================================

📦 Projektname:   Social Owl  
📁 Version:       1.3.1 (May 15, 2025)  
👤 Entwickler:    Nico Walter
                  
==================================================

📌 Projektübersicht
--------------------------------------------------
Social Owl ist eine moderne, schlanke Social-Media-Plattform mit Fokus auf:
Benutzerfreundlichkeit, saubere Struktur (MVC) und vielen Social Features.  
Nutzer können Inhalte posten, liken, kommentieren, folgen, chatten und ihr Profil pflegen.

✅ Features
--------------------------------------------------

🔸 Benutzerverwaltung
✔️ Registrierung & Login inkl. "Angemeldet bleiben"-Token  
✔️ Passwort-Reset per "E-Mail" (Token-gesichert)  
✔️ Benutzerprofil mit Profil- & Headerbild  
✔️ Folgen/Entfolgen von anderen Usern inkl. Follow-Requests  

🔸 Inhalte & Interaktion
✔️ Beiträge mit Text, Bildern & Videos (Upload inkl. Vorschau)  
✔️ Kommentare mit Bearbeiten, Löschen & Likes  
✔️ Like-System für Beiträge und Kommentare  
✔️ Emoji-Picker für Posts, Kommentare & Chat  

🔸 Social Experience
✔️ Live-Feed für neue Posts und Kommentare (SSE & Polling)  
✔️ Kommentare & Beiträge nur sichtbar von gefolgten Usern  
✔️ Sidebar mit Follow-Vorschlägen & Trends  
✔️ Notification-System für Likes, Kommentare, Follows &       Follow-Requests (inkl. Live-Update)
✔️ Integriertes 1:1-Chat-System 

🔸 Benutzeroberfläche
✔️ Responsives Dark-Theme (Bootstrap 5) 
✔️ Scrollbarer Feed mit unsichtbarem Scrollbalken  
✔️ Modal-Interface für Beitrag löschen & Profil bearbeiten  
✔️ Saubere UI-Komponenten & Layout mit Bootstrap  
✔️ Suchleiste für Posts, Hashtags und Usern

🔸 Technische Features
✔️ Datenbank vollständig in 3. Normalform
✔️ API-Endpunkte für Live-Updates, Chat, Notifications, Follows
✔️ Automatisierte Tests für Kernfunktionen (PHP)
✔️ Erweiterte Suche

🧰 Technologien
--------------------------------------------------
- PHP 8.2+ (strukturierter MVC-Ansatz)
- HTML5, CSS3, JavaScript (ES6+)
- Bootstrap 5.3 + Bootstrap Icons (lokal Eingebunden)
- MySQL 8.0 / MariaDB 10.6+
- UniServerZ oder XAMPP (lokale Entwicklungsumgebung)
- PHPMailer für E-Mail-Funktionalität
- Server-Sent Events (SSE) für Live-Updates

📁 Projektstruktur (nach MVC)
--------------------------------------------------
├── controllers/      → Business-Logik & API
├── models/           → Datenbankabfragen
├── views/            → Hauptseiten
├── partials/         → Wiederverwendbare UI-Elemente
├── includes/         → Konfiguration, Auth, DB-Verbindung, Chat-Logik
├── assets/           → CSS, JavaScript, Bilder, Uploads
├── tests/            → Automatisierte Test-Skripte
├── sql/              → SQL-Dumps & Migrationen
├── src/              → Zusätzliche Quellcode-Dateien
├── vendor/           → Abhängigkeiten (Composer)

🛠 Einrichtung / Setup
--------------------------------------------------
1. UniServerZ oder XAMPP starten
2. Datenbank `social_owl` anlegen
3. SQL-Dump importieren (`sql/social_owl.sql`)
4. Zugangsdaten in `includes/config.php` eintragen
5. Composer-Abhängigkeiten installieren: `composer install`
6. Projekt starten: http://localhost/Social_App/

🧾 Konfigurationsdatei (`config.php`)
--------------------------------------------------
- DB_HOST     = 'localhost'  
- DB_NAME     = 'social_owl'  
- DB_USER     = 'owl_user'  
- DB_PASS     = 'password'  
- BASE_URL    = '/Social_App'  
- DEBUG_MODE  = true (nur für DEV!)

🗃 Datenbank-Struktur
--------------------------------------------------
- `users`          → Benutzerkonto, Token, Medien
- `posts`          → Beiträge mit Content, Bild, Video
- `comments`       → Kommentare zu Beiträgen
- `post_likes`     → Likes für Beiträge
- `comment_likes`  → Likes für Kommentare
- `followers`      → Wer folgt wem
- `notifications`  → System-Benachrichtigungen
- `chats` & `messages` → Chat-System

📚 Zukünftige Entwicklung
---------------------------------------------------
- Profilansicht aller Usern
- Toastmessages für alle Events
- Mobile App mit passender UX und UI
- Erweiterung der Kommentarfunktion mit Comment of comments


📅 Letzter Stand: 15. Mai 2025
==================================================
