<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// SSE-Header setzen
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no'); // Wichtig für Nginx
header('Access-Control-Allow-Origin: *');

// Verbindung zur Datenbank herstellen
$conn = getDatabaseConnection();

// Client-ID für diese Verbindung
$clientId = uniqid();

try {
    // Endlosschleife für SSE
    while (true) {
        // Prüfen ob Client noch verbunden ist
        if (connection_aborted()) {
            break;
        }

        // Prüfen auf neue Löschungen
        $stmt = $conn->prepare("SELECT type, item_id as id FROM deletion_log WHERE timestamp > ?");
        $stmt->execute([date('Y-m-d H:i:s', strtotime('-5 seconds'))]);
        $deletions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($deletions as $deletion) {
            $deletion['action'] = 'delete';
            echo "data: " . json_encode($deletion) . "\n\n";
            ob_flush();
            flush();
        }

        // Heartbeat senden
        echo "data: " . json_encode(['type' => 'heartbeat', 'action' => 'heartbeat']) . "\n\n";
        ob_flush();
        flush();

        // 5 Sekunden warten
        sleep(5);
    }
} catch (Exception $e) {
    // Fehler loggen
    error_log("SSE Fehler: " . $e->getMessage());
    
    // Fehler an Client senden
    echo "event: error\n";
    echo "data: " . json_encode([
        'type' => 'error',
        'action' => 'error',
        'message' => 'Ein Fehler ist aufgetreten',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]) . "\n\n";
    ob_flush();
    flush();
}

// Verbindung schließen
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} 