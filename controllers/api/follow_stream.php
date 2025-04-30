<?php
/**
 * API-Controller: Follow Stream (SSE)
 * Sendet neue und aktualisierte Follow-Anfragen per Server-Sent Events an den Client.
 * Wird für Live-Updates im Frontend genutzt.
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/connection.php';

session_start();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');
header('Access-Control-Allow-Origin: *');

if (!isset($_SESSION['id'])) {
    echo "event: error\n";
    echo "data: {\"message\": \"Nicht eingeloggt\"}\n\n";
    exit;
}

$conn = getDatabaseConnection();
$userId = $_SESSION['id'];

// Merke den letzten Stand, um nur neue Events zu senden
$lastCheck = date('Y-m-d H:i:s', strtotime('-10 seconds'));

try {
    while (true) {
        if (connection_aborted()) break;

        // Neue Follower-Anfragen (pending)
        $stmt = $conn->prepare("SELECT f.follower_id, u.username, u.profile_img, f.status, f.created_at FROM followers f JOIN users u ON u.id = f.follower_id WHERE f.followed_id = ? AND f.status = 'pending' AND f.created_at > ?");
        $stmt->execute([$userId, $lastCheck]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($requests as $req) {
            $req['event'] = 'new_follow_request';
            echo "data: " . json_encode($req) . "\n\n";
            ob_flush(); flush();
        }

        // Angenommene/abgelehnte Anfragen (Statuswechsel)
        $stmt = $conn->prepare("SELECT f.follower_id, u.username, u.profile_img, f.status, f.updated_at FROM followers f JOIN users u ON u.id = f.follower_id WHERE f.followed_id = ? AND f.status IN ('accepted','rejected') AND f.updated_at > ?");
        $stmt->execute([$userId, $lastCheck]);
        $changes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($changes as $change) {
            $change['event'] = 'follow_request_update';
            echo "data: " . json_encode($change) . "\n\n";
            ob_flush(); flush();
        }

        // Heartbeat
        echo "data: heartbeat\n\n";
        ob_flush(); flush();

        $lastCheck = date('Y-m-d H:i:s');
        sleep(5);
    }
} catch (Exception $e) {
    echo "event: error\n";
    echo "data: {\"message\": \"Fehler: {$e->getMessage()}\"}\n\n";
    ob_flush(); flush();
}