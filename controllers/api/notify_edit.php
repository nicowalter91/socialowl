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

    // Loggen der Bearbeitung
    $stmt = $conn->prepare("INSERT INTO edit_log (type, item_id, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$type, $id, $_SESSION['id'] ?? 0]);

    // HTML für das bearbeitete Element abrufen
    if ($type === 'post') {
        $stmt = $conn->prepare("
            SELECT p.*, u.username, u.profile_img,
                   (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
                   (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = ?) as liked
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$_SESSION['id'] ?? 0, $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // HTML für den Post generieren
            ob_start();
            include __DIR__ . '/../../views/partials/post.php';
            $html = ob_get_clean();
            
            echo json_encode([
                'success' => true,
                'type' => 'post',
                'id' => $id,
                'html' => $html,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit;
        }
    } elseif ($type === 'comment') {
        $stmt = $conn->prepare("
            SELECT c.*, u.username, u.profile_img,
                   (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id) as like_count,
                   (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id AND user_id = ?) as liked
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$_SESSION['id'] ?? 0, $id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($comment) {
            // HTML für den Kommentar generieren
            ob_start();
            include __DIR__ . '/../../views/partials/comment.php';
            $html = ob_get_clean();
            
            echo json_encode([
                'success' => true,
                'type' => 'comment',
                'id' => $id,
                'html' => $html,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            exit;
        }
    }

    throw new Exception('Element nicht gefunden');

} catch (Exception $e) {
    // Fehler loggen
    error_log("Notify Edit Fehler: " . $e->getMessage());
    
    // Fehlerantwort senden
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ein Fehler ist aufgetreten',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]);
} 