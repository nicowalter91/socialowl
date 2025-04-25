<?php
require_once "../../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

// Session prüfen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$conn = getDatabaseConnection();
ensureLogin($conn);

$userId = $_SESSION["id"];
$since = isset($_GET["since"]) ? strtotime($_GET["since"]) : 0;

$comments = fetchCommentsSince($conn, $userId, $since); // Du brauchst diese Funktion im comment.php Model

$htmlOutput = "";
$latestTime = $since;

foreach ($comments as $comment) {
    $GLOBALS["comment"] = $comment;
    ob_start();
    include PARTIALS . "/comment_item.php";
    $htmlOutput .= ob_get_clean();

    $createdAt = strtotime($comment["created_at"]);
    if ($createdAt > $latestTime) {
        $latestTime = $createdAt;
    }
}

echo json_encode([
    "success" => true,
    "html" => $htmlOutput,
    "latest" => date("Y-m-d H:i:s", $latestTime)
]);
