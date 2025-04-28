<?php
require_once "../../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$conn = getDatabaseConnection();
ensureLogin($conn);

$userId = $_SESSION["id"];
$since = isset($_GET["since"]) ? strtotime($_GET["since"]) : 0;
if (!$since) $since = 0;

// Holt ALLE Kommentare seit $since
$comments = fetchCommentsSince($conn, $userId, $since); 

$html = "";
$latest = $since;

foreach ($comments as $comment) {
    $GLOBALS["comment"] = $comment;
    ob_start();
    include PARTIALS . "/comment_item.php";
    $html .= ob_get_clean();

    $createdAt = strtotime($comment["created_at"]);
    if ($createdAt > $latest) {
        $latest = $createdAt;
    }
}

echo json_encode([
    "success" => true,
    "html" => $html,
    "latest" => date("Y-m-d H:i:s", $latest),
]);
exit;
