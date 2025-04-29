<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// JSON-Header setzen
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Verbindung zur Datenbank herstellen
$conn = getDatabaseConnection();

try {
    // Timestamp aus Query-Parameter holen
    $since = isset($_GET['since']) ? $_GET['since'] : date('Y-m-d H:i:s', strtotime('-1 minute'));
    
    // Neue Posts seit dem letzten Update
    $stmt = $conn->prepare("
        SELECT p.*, u.username, u.profile_img 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.created_at > ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$since]);
    $newPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Neue Kommentare seit dem letzten Update
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_img 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.created_at > ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$since]);
    $newComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Antwort zusammenstellen
    $response = [
        'success' => true,
        'posts' => $newPosts,
        'comments' => $newComments,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    // Fehler loggen
    error_log("Updates Fehler: " . $e->getMessage());
    
    // Fehlerantwort senden
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ein Fehler ist aufgetreten',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]);
} 