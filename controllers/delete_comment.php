<?php
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

header('Content-Type: application/json');

$commentId = $_POST["comment_id"] ?? null;
$userId = $_SESSION["id"];

if (!$commentId) {
    echo json_encode(["success" => false, "message" => "Keine Kommentar-ID übergeben."]);
    exit;
}

$success = deleteComment($conn, (int)$commentId, (int)$userId);

echo json_encode([
    "success" => $success,
    "message" => $success ? null : "Löschen fehlgeschlagen oder keine Berechtigung."
]);
exit;
