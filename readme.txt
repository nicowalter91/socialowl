==================================================
 SOCIAL OWL - README 
==================================================

📦 Projektname: Social Owl  
📁 Version:     1.1 (April 2025)  
👤 Entwickler:  Nico Walter (FISI, 2. Lehrjahr)  
==================================================

📌 Projektübersicht
--------------------------------------------------
Social Owl ist eine moderne Social-Media-Webanwendung mit Fokus auf
Usability, Erweiterbarkeit und sauberer Struktur nach dem MVC-Prinzip.
Benutzer können Inhalte teilen, kommentieren, liken und sich in einer 
übersichtlichen, responsiven Oberfläche bewegen.

✅ Features (Stand: April 2025)
--------------------------------------------------
✔️ Registrierung & Login inkl. "Angemeldet bleiben" (Token)
✔️ Passwort-Reset via (E-Mail) (Token-basiert)
✔️ Post-Erstellung mit Text, Bildern und Videos  
✔️ Kommentar-Funktion mit:
   → Bearbeiten / Löschen
   → Likes
   → Emoji-Picker
✔️ Live-Updates (Posts & Kommentare via Polling ohne Reload)
✔️ Dark Theme + UI mit Bootstrap 5
✔️ Profilseiten mit Profilbild, Headerbild & Bio
✔️ Live-Zeichenzähler & visuelles Feedback
✔️ Moderner Upload mit Vorschau (Video/Bild)
✔️ Responsive für Desktop & Tablet
✔️ Datenbank in 3. Normalform

🧰 Technologien
--------------------------------------------------
- PHP (procedural + strukturierter MVC-Ansatz)
- JavaScript (ES6, DOM-basierend)
- HTML5, CSS3 (Bootstrap 5.3, Icons)
- MySQL / MariaDB
- UniServerZ als lokale Entwicklungsumgebung

📁 Projektstruktur (nach MVC)
--------------------------------------------------
├── controllers/         → Logik & Routing (z. B. create_post.php)
│   └── api/             → Endpunkte für Polling (z. B. posts_since.php)
├── models/              → Datenbankabfragen (z. B. post.php, comment.php)
├── views/               → HTML-Templates (login.view.php, welcome.php etc.)
├── partials/            → Wiederverwendbare UI-Komponenten
├── includes/            → Config, DB-Verbindung, Authentifizierung
├── assets/
│   ├── css/             → style.css, bootstrap.css
│   ├── js/              → script.js (alle Funktionen in einer Datei)
│   ├── img/             → Logos & Standardbilder
│   ├── posts/           → Medien aus Beiträgen
│   └── uploads/         → Profil- & Headerbilder
├── modal/               → Bootstrap-Modals (Post löschen etc.)
├── trash/               → Archiv, Debug-Dateien, Altversionen

🛠 Einrichtung / Setup
--------------------------------------------------
1. Lokale Umgebung starten (z. B. UniServerZ)
2. Datenbank `social_owl` erstellen
3. SQL-Migration importieren (`social_owl_migration.sql`)
4. Zugangsdaten in `includes/config.php` anpassen
5. Aufruf im Browser: http://localhost/Social_App/

🧾 Konfigurationsdatei (`config.php`)
--------------------------------------------------
define("DB_HOST", "localhost");  
define("DB_NAME", "social_owl");  
define("DB_USER", "owl_user");  
define("DB_PASS", "password");  
define("BASE_URL", "/Social_App");  
define("DEBUG_MODE", true);  

🗃 Datenbankstruktur (Kurzüberblick)
--------------------------------------------------
- `users`            → Nutzerinfo, Tokens, Bilder
- `posts`            → Beiträge mit Medien
- `comments`         → Kommentare mit Like-Zähler
- `post_likes`       → Like-Zuordnung für Posts
- `comment_likes`    → Like-Zuordnung für Kommentare
- `followers`        → (Zukünftig: Follower-Funktion)

📊 Getestete Funktionen (Automatisiert)
--------------------------------------------------
✅ User-Registrierung & Login  
✅ 5 Posts erstellt mit Bild/Video  
✅ Pro Post 5 Kommentare erzeugt  
✅ Kommentare bearbeitet und gelöscht  
✅ Likes für Kommentare getestet  
✅ Alle Inhalte erscheinen live (Polling)  
✅ Vorschau von Bildern & Videos ohne Seitenreload  
✅ Responsives Design überprüft  

🧭 Roadmap / ToDo
--------------------------------------------------
🚧 Folgen/Friend-System (Follow/Unfollow)  
🚧 Nutzerprofile öffentlich anzeigen  
🚧 Admin-Modus mit Moderation  
🚧 Medien-Komprimierung beim Upload (Thumbnails etc.)  
🚧 REST-API für Mobile/Desktop-Client vorbereiten  
🚧 SEO & Ladezeit-Optimierung  
🚧 JavaScript modularisieren (geplant für v1.2)  

📅 Letzter Stand: 24. April 2025  
==================================================
