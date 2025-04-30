<?php
/**
 * API-Controller: Benachrichtigung(en) löschen
 * Löscht eine oder alle Benachrichtigungen des eingeloggten Nutzers (JSON).
 */

session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['id'])) {
        throw new Exception('Nicht eingeloggt');
    }

    $conn = getDatabaseConnection();
    $userId = $_SESSION['id'];

    // Alle Benachrichtigungen löschen
    if (isset($_POST['delete_all']) && $_POST['delete_all'] === 'true') {
        $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
        $stmt->execute([$userId]);
        echo json_encode(['success' => true, 'message' => 'Alle Benachrichtigungen wurden gelöscht']);
        exit;
    }

    // Einzelne Benachrichtigung löschen
    if (!isset($_POST['notification_id'])) {
        throw new Exception('Keine Benachrichtigungs-ID angegeben');
    }

    $notificationId = (int)$_POST['notification_id'];

    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
    $stmt->execute([$notificationId, $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Benachrichtigung wurde gelöscht']);
    } else {
        throw new Exception('Benachrichtigung konnte nicht gelöscht werden');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ein Fehler ist aufgetreten',
        'error' => DEBUG_MODE ? $e->getMessage() : null
    ]);
}