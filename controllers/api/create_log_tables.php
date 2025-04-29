<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// JSON-Header setzen
header('Content-Type: application/json');

try {
    // Verbindung zur Datenbank herstellen
    $conn = getDatabaseConnection();
    
    // SQL-Datei einlesen
    $sql = file_get_contents(__DIR__ . '/../../sql/create_log_tables.sql');
    
    // SQL ausführen
    $conn->exec($sql);
    
    echo json_encode([
        'success' => true,
        'message' => 'Log-Tabellen erfolgreich erstellt'
    ]);
    
} catch (Exception $e) {
    // Fehler loggen
    error_log("Fehler beim Erstellen der Log-Tabellen: " . $e->getMessage());
    
    // Fehlerantwort senden
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Erstellen der Log-Tabellen',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]);
} 