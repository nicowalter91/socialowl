<?php
require_once "../../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/post.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$conn = getDatabaseConnection();
ensureLogin($conn);

$since = isset($_GET["since"]) ? strtotime($_GET["since"]) : 0;
if (!$since) $since = 0;

// NEU: Auch Posts von denen, denen man folgt!
$posts = fetchPostsSince($conn, $_SESSION["id"], $since);

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
