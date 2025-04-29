<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/comment.php';

// Fehlerbehandlung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// JSON-Header setzen
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $conn = getDatabaseConnection();
    ensureLogin($conn);

    $commentId = $_POST["edit_comment_id"] ?? null;
    $content = trim($_POST["comment"] ?? "");

    if (!$commentId || $content === "") {
        throw new Exception("Ungültige Eingabe.");
    }

    // Kommentar aktualisieren
    $success = updateComment($conn, (int)$commentId, (int)$_SESSION["id"], $content);

    if (!$success) {
        throw new Exception("Kein Zugriff oder Fehler beim Speichern.");
    }

    // Aktualisierte Kommentardaten abrufen
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_img,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id) as like_count,
               (SELECT COUNT(*) FROM comment_likes WHERE comment_id = c.id AND user_id = ?) as liked
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = ?
    ");
    $stmt->execute([$_SESSION["id"], $commentId]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment) {
        throw new Exception("Kommentar nicht gefunden.");
    }

    // HTML für den aktualisierten Kommentar generieren
    ob_start();
    include __DIR__ . '/../views/partials/comment.php';
    $html = ob_get_clean();

    echo json_encode([
        'success' => true,
        'comment' => $comment,
        'html' => $html
    ]);

} catch (Exception $e) {
    // Fehler loggen
    error_log("Update Comment Fehler: " . $e->getMessage());
    
    // Fehlerantwort senden
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
