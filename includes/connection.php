<?php
// Stellt die zentrale Datenbankverbindung bereit
require_once __DIR__ . '/../includes/config.php';

/**
 * Erstellt und liefert eine PDO-Datenbankverbindung.
 * Gibt bei Fehlern eine verständliche Fehlermeldung aus (im Debug-Modus als JSON).
 *
 * @return PDO
 */
function getDatabaseConnection(): PDO {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Fehler sauber als JSON ausgeben, wenn nötig
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage()
            ]);
            exit;
        } else {
            die('Verbindungsfehler.');
        }
    }
}
