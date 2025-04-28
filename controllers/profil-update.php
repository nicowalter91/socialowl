<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/profile.helper.php';

session_start();
$conn = getDatabaseConnection();

$username = $_SESSION["username"] ?? null;
if (!$username) {
    header("Location: " . BASE_URL . "/controllers/login.php");
    exit;
}

$bio = trim($_POST["bio"] ?? '');

$profileImg = handleImageUpload($_FILES["profile_img"], $username, "profile");
$headerImg  = handleImageUpload($_FILES["header_img"], $username, "header");

updateUserProfile($conn, $username, $bio, $profileImg, $headerImg);

// Session aktualisieren
$_SESSION["bio"] = $bio;
if ($profileImg)  $_SESSION["profile_img"] = $profileImg;
if ($headerImg)   $_SESSION["header_img"] = $headerImg;

header("Location: " . BASE_URL . "/views/index.php");
exit;
