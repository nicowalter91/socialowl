<?php
// API-Endpunkt zum Senden einer Chat-Nachricht
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/chat_service.php';

header('Content-Type: application/json');

// Session prüfen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt.']);
    exit;
}

$userId = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);
$otherUserId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
$message = isset($data['message']) ? trim($data['message']) : '';
if (!$otherUserId || $message === '') {
    echo json_encode(['success' => false, 'message' => 'Empfänger oder Nachricht fehlt.']);
    exit;
}

// Chat-Nachricht senden (Logik ausgelagert)
$result = sendChatMessage($userId, $otherUserId, $message);
if ($result === true) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $result]);
}
