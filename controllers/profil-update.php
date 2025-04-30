<?php
/**
 * Controller: Profil aktualisieren
 * Aktualisiert Bio und Profil-/Headerbild eines Nutzers.
 * Leitet nach erfolgreicher Änderung zum Index weiter.
 */
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

// Alte Dateinamen aus der Session holen
$oldProfileImg = $_SESSION["profile_img"] ?? null;
$oldHeaderImg = $_SESSION["header_img"] ?? null;

// Neue Bilder hochladen
$profileImg = handleImageUpload($_FILES["profile_img"], $username, "profile", $oldProfileImg);
$headerImg  = handleImageUpload($_FILES["header_img"], $username, "header", $oldHeaderImg);

// Profil aktualisieren
updateUserProfile($conn, $username, $bio, $profileImg, $headerImg, $oldProfileImg, $oldHeaderImg);

// Session aktualisieren
$_SESSION["bio"] = $bio;
if ($profileImg)  $_SESSION["profile_img"] = $profileImg;
if ($headerImg)   $_SESSION["header_img"] = $headerImg;

header("Location: " . BASE_URL . "/views/index.php");
exit;
