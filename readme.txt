==================================================
 SOCIAL OWL - README 
==================================================

📦 Projektname:   Social Owl  
📁 Version:       1.2 (April 2025)  
👤 Entwickler:    Nico Walter,
                  Georg Diesendorf,
                  Andreas Wiegand,
                  Florian Prottengeier
==================================================

📌 Projektübersicht
--------------------------------------------------
Social Owl ist eine moderne, schlanke Social-Media-Plattform mit Fokus auf 
Benutzerfreundlichkeit, saubere Struktur (MVC) und vielen Social Features.  
Nutzer können Inhalte posten, liken, kommentieren, folgen, chatten und ihr Profil pflegen.

✅ Features
--------------------------------------------------
✔️ Registrierung & Login inkl. "Angemeldet bleiben"-Token  
✔️ Passwort-Reset per "E-Mail" (Token-gesichert)  
✔️ Beiträge mit Text, Bildern & Videos (Upload inkl. Vorschau)  
✔️ Kommentare mit Bearbeiten, Löschen & Likes  
✔️ Like-System für Beiträge und Kommentare  
✔️ Benutzerprofil mit Profil- & Headerbild  
✔️ Folgen/Entfolgen von anderen Usern inkl. Follow-Requests  
✔️ Live-Feed für neue Posts und Kommentare (SSE & Polling)  
✔️ Responsives Dark-Theme (Bootstrap 5)  
✔️ Emoji-Picker für Posts, Kommentare & Chat  
✔️ Sidebar mit Follow-Vorschlägen & Trends  
✔️ Scrollbarer Feed mit unsichtbarem Scrollbalken  
✔️ Kommentare & Beiträge nur sichtbar von gefolgten Usern  
✔️ Sidebar zeigt aktuelle Follower- & Following-Anzahl  
✔️ Modal-Interface für Beitrag löschen & Profil bearbeiten  
✔️ Live-Kommentar- und Postanzeige (nur bei dir)  
✔️ Saubere UI-Komponenten & Layout mit Bootstrap  
✔️ Datenbank vollständig in 3. Normalform
✔️ Suchleiste für Posts, Hashtags und Usern
✔️ Notification-System für Likes, Kommentare, Follows & Follow-Requests (inkl. Live-Update)
✔️ Integriertes 1:1-Chat-System (verschlüsselt, Modal, Live-Badge, Chat-Löschung)
✔️ API-Endpunkte für Live-Updates, Chat, Notifications, Follows
✔️ Automatisierte Tests für Kernfunktionen (PHP)

🧰 Technologien
--------------------------------------------------
- PHP (strukturierter MVC-Ansatz)
- HTML5, CSS3, JavaScript (ES6)
- Bootstrap 5.3 + Bootstrap Icons (lokal Eingebunden)
- MySQL / MariaDB
- UniServerZ oder XAMPP (lokale Entwicklungsumgebung)

📁 Projektstruktur (nach MVC)
--------------------------------------------------
├── controllers/      → Business-Logik & API (z. B. create_post.php, api/)
├── models/           → Datenbankabfragen (z. B. user.php, follow.php)
├── views/            → Hauptseiten wie index.php, login.view.php
├── partials/         → Wiederverwendbare UI-Elemente (Sidebar, Modals)
├── includes/         → Konfiguration, Auth, DB-Verbindung, Chat-Logik
├── assets/
│   ├── css/          → style.css, bootstrap.min.css
│   ├── fonts/        → Bootstrap Icons (woff-Dateien)
│   ├── js/           → script.js (App-Logik), notifications.js 
│   │   └── modules/  → JS-Handler für comments, emoji, live, post, search
│   ├── img/          → Logos & Default-Bilder
│   ├── posts/        → Medien aus Beiträgen
│   └── uploads/      → Profil- und Headerbilder
├── trash/            → Alte/temporäre Dateien (nicht produktiv)
├── tests/            → PHP Skripte für automatisierte Tests
├── sql/              → SQL-Dumps & Migrationen

🛠 Einrichtung / Setup
--------------------------------------------------
1. UniServerZ oder XAMPP starten
2. Datenbank `social_owl` anlegen
3. SQL-Dump importieren (`social_owl_migration.sql`)
4. Zugangsdaten in `includes/config.php` eintragen
5. Projekt starten: http://localhost/Social_App/

🧾 Konfigurationsdatei (`config.php`)
--------------------------------------------------
- DB_HOST     = 'localhost'  
- DB_NAME     = 'social_owl'  
- DB_USER     = 'owl_user'  
- DB_PASS     = 'password'  
- BASE_URL    = '/Social_App'  
- DEBUG_MODE  = true (nur für DEV!)

🗃 Datenbank-Tabellen (Kurzüberblick)
--------------------------------------------------
- `users`          → Benutzerkonto, Token, Medien
- `posts`          → Beiträge mit Content, Bild, Video
- `comments`       → Kommentare zu Beiträgen
- `post_likes`     → Likes für Beiträge
- `comment_likes`  → Likes für Kommentare
- `followers`      → Wer folgt wem
- `notifications`  → System-Benachrichtigungen
- `chats` & `messages` → Chat-System

📚 Features Backlog
---------------------------------------------------
- Erweiterte Suche (z.B. nach Medien)
- Gruppenchat
- Mobile App


📅 Letzter Stand: 30. April 2025
==================================================
