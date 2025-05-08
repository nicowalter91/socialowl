<?php
require_once '../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once MODELS . '/like.php';

// Session starten und Verbindung herstellen
session_start();
$conn = getDatabaseConnection();

// Verifiziere, dass der Benutzer eingeloggt ist
if (!isset($_SESSION['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Nicht autorisiert'
    ]);
    exit;
}

// CSRF-Schutz prüfen, falls implementiert
if (function_exists('verify_csrf_token')) {
    $token = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '';
    if (!verify_csrf_token($token)) {
        echo json_encode([
            'success' => false,
            'message' => 'Ungültiger CSRF-Token'
        ]);
        exit;
    }
}

// JSON-Daten einlesen
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['post_id']) || !isset($data['action'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Fehlende Parameter'
    ]);
    exit;
}

$post_id = intval($data['post_id']);
$action = $data['action'];
$user_id = $_SESSION['id'];

// Like-Status umschalten mit der vorhandenen togglePostLike-Funktion
$isNowLiked = togglePostLike($conn, $user_id, $post_id);

// Aktuelle Like-Daten abrufen
$likeData = getPostLikeData($conn, $post_id, $user_id);
$likeCount = isset($likeData['like_count']) ? (int)$likeData['like_count'] : 0;

// Rückgabe je nach Aktion
if (($action === 'like' && $isNowLiked) || ($action === 'unlike' && !$isNowLiked)) {
    echo json_encode([
        'success' => true,
        'like_count' => $likeCount,
        'action' => $isNowLiked ? 'like' : 'unlike'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Aktion konnte nicht durchgeführt werden',
        'like_count' => $likeCount,
        'action' => $isNowLiked ? 'like' : 'unlike'
    ]);
}
?>
