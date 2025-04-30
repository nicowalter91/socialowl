<?php
/**
 * Controller: Feed
 * Holt alle Posts inkl. Kommentare für den eingeloggten Nutzer
 * und lädt das Feed-View.
 */
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';
require_once MODELS . '/post.php';
require_once MODELS . '/comment.php';

session_start();
$conn = getDatabaseConnection();
ensureLogin($conn);

// Alle Posts inkl. Kommentare laden
$posts = fetchAllPostsWithComments($conn, $_SESSION["id"]);

// Feed-View laden
require_once VIEWS . '/feed.view.php';
