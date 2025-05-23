<?php
// Sicherheitskonfiguration und Konstanten für DB
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');  // Standard MySQL Port
define('DB_NAME', 'social_owl');
define('DB_USER', 'root');
define('DB_PASS', '');

// Optional: Zeitzone und weitere globale Settings
date_default_timezone_set('Europe/Berlin');

// Projekt-Basis-URL (für lokale Nutzung anpassbar)
define("BASE_URL", "/Social_App");

// Absolute Pfade für Projektstruktur (für Includes etc.)
define("ROOT", dirname(__DIR__)); // z. B. C:/Schulung/www/Social_App

// Strukturierte Pfade (für includes, views etc.)
define("INCLUDES", ROOT . "/includes");
define("MODELS", ROOT . "/models");
define("VIEWS", ROOT . "/views");
define("PARTIALS", ROOT . "/partials");
define("CONTROLLERS", ROOT . "/controllers");
define("ASSETS", ROOT . "/assets");
define("UPLOADS", ROOT . "/assets/uploads");
define("POSTS", ROOT . "/assets/posts");

// Optional: Standardbilder
define("DEFAULT_PROFILE_IMG", "profil.png");
define("DEFAULT_HEADER_IMG", "default_header.png");

// Debug-Modus
define("DEBUG_MODE", true);

// Fehleranzeige in DEV (abschalten in PROD)
if (DEBUG_MODE) {
  ini_set("display_errors", 1);
  error_reporting(E_ALL);
} else {
  ini_set("display_errors", 0);
  error_reporting(0);
}

// Verschlüsselungsschlüssel für Chat-Nachrichten (32 Zeichen für AES-256)
define('CHAT_ENCRYPT_KEY', '12345678901234567890123456789012'); // 32 Zeichen!
define('CHAT_ENCRYPT_IV', '1234567890abcdef'); // 16 Zeichen für AES-256-CBC
?>