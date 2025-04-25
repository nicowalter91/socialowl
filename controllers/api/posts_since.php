<?php
require_once "../../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/post.php";

// Session prüfen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$conn = getDatabaseConnection();
ensureLogin($conn);

// Timestamp holen und casten
$since = isset($_GET["since"]) ? strtotime($_GET["since"]) : 0;

if (!$since || !is_int($since)) {
    echo json_encode(["success" => false, "message" => "Ungültiger Zeitstempel."]);
    exit;
}

// Neue Posts laden
$posts = fetchPostsSince($conn, $_SESSION["id"], $since);

// HTML erzeugen
$html = "";
$latest = $since;

foreach ($posts as $post) {
    $GLOBALS["post"] = $post;
    ob_start();
    include PARTIALS . "/post_card.php";
    $html .= ob_get_clean();

    $createdAt = strtotime($post["created_at"]);
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
