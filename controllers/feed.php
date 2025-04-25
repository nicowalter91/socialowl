<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';
require_once MODELS . '/post.php';
require_once MODELS . '/comment.php';

session_start();
$conn = getDatabaseConnection();
ensureLogin($conn);

$posts = fetchAllPostsWithComments($conn, $_SESSION["id"]);

require_once VIEWS . '/feed.view.php';
