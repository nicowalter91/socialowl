<?php
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

header("Content-Type: application/json");

$commentId = $_POST["edit_comment_id"] ?? null;
$content = trim($_POST["comment"] ?? "");

if (!$commentId || $content === "") {
    echo json_encode(["success" => false, "message" => "Ungültige Eingabe."]);
    exit;
}

$success = updateComment($conn, (int)$commentId, (int)$_SESSION["id"], $content);

if (!$success) {
    echo json_encode(["success" => false, "message" => "Kein Zugriff oder Fehler beim Speichern."]);
    exit;
}

echo json_encode(["success" => true]);
exit;
