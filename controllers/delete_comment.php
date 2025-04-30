<?php
/**
 * Controller: Kommentar löschen
 * Löscht einen Kommentar, sofern der aktuelle Nutzer der Besitzer ist.
 * Antwortet mit JSON (success/message).
 */
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

header('Content-Type: application/json');

$commentId = $_POST["comment_id"] ?? null;
$userId = $_SESSION["id"];

// Prüfen, ob eine Kommentar-ID übergeben wurde
if (!$commentId) {
    echo json_encode(["success" => false, "message" => "Keine Kommentar-ID übergeben."]);
    exit;
}

// Kommentar löschen (nur wenn vom User selbst)
$success = deleteComment($conn, (int)$commentId, (int)$userId);

echo json_encode([
    "success" => $success,
    "message" => $success ? null : "Löschen fehlgeschlagen oder keine Berechtigung."
]);
exit;
