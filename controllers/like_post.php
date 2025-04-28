<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once MODELS . '/like.php';

session_start();
$conn = getDatabaseConnection();

if (!isset($_SESSION["id"])) {
    header("Location: " . BASE_URL . "/controllers/login.php");
    exit;
}

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;

if (!$postId || !is_numeric($postId)) {
    die("Ungültiger Post.");
}

togglePostLike($conn, $userId, (int)$postId);

// Dynamische Rückleitung (z. B. nach AJAX oder zurück zu index.php)
$redirect = $_SERVER["HTTP_REFERER"] ?? BASE_URL . "/views/index.php";
header("Location: " . $redirect);
exit;
