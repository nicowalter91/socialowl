<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// JSON-Header setzen
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $type = $_GET['type'] ?? '';
    $id = $_GET['id'] ?? 0;

    // Heartbeat-Nachrichten ignorieren
    if ($type === 'heartbeat') {
        echo json_encode(['success' => true, 'type' => 'heartbeat']);
        exit;
    }

    if (!$type || !$id) {
        throw new Exception('Fehlende Parameter');
    }

    // Verbindung zur Datenbank herstellen
    $conn = getDatabaseConnection();

    // Loggen der Löschung
    $stmt = $conn->prepare("INSERT INTO deletion_log (type, item_id, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$type, $id, $_SESSION['id'] ?? 0]);

    // Prüfen ob das Element existiert
    if ($type === 'post') {
        $stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $exists = $stmt->fetch();
    } elseif ($type === 'comment') {
        $stmt = $conn->prepare("SELECT id FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $exists = $stmt->fetch();
    } else {
        throw new Exception('Ungültiger Typ');
    }

    if ($exists) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Element nicht gefunden');
    }

} catch (Exception $e) {
    // Fehler loggen
    error_log("Notify Deletion Fehler: " . $e->getMessage());
    
    // Fehlerantwort senden
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ein Fehler ist aufgetreten',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]);
} 