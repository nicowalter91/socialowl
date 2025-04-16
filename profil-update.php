<?php
require_once "connection.php";
session_start();

$username = $_SESSION["username"];
$bio = $_POST["bio"] ?? '';

// === Dateinamen vorbereiten ===
$profileImageName = null;
$headerImageName = null;

// === PROFILBILD HOCHLADEN ===
if (isset($_FILES["profile_img"]) && $_FILES["profile_img"]["error"] === UPLOAD_ERR_OK) {
  $tmpPath = $_FILES["profile_img"]["tmp_name"];
  $originalName = $_FILES["profile_img"]["name"];
  $ext = pathinfo($originalName, PATHINFO_EXTENSION);
  $newName = $username . "_profile_" . time() . "." . $ext;

  $uploadPath = "uploads/" . $newName;
  $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

  if (in_array(strtolower($ext), $allowedTypes)) {
    move_uploaded_file($tmpPath, $uploadPath);
    $profileImageName = $newName;
  }
}

// === HINTERGRUNDBILD HOCHLADEN ===
if (isset($_FILES["header_img"]) && $_FILES["header_img"]["error"] === UPLOAD_ERR_OK) {
  $tmpPath = $_FILES["header_img"]["tmp_name"];
  $originalName = $_FILES["header_img"]["name"];
  $ext = pathinfo($originalName, PATHINFO_EXTENSION);
  $newName = $username . "_header_" . time() . "." . $ext;

  $uploadPath = "uploads/" . $newName;
  $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

  if (in_array(strtolower($ext), $allowedTypes)) {
    move_uploaded_file($tmpPath, $uploadPath);
    $headerImageName = $newName;
  }
}

// === SQL vorbereiten ===
$sql = "UPDATE users SET bio = :bio";
if ($profileImageName) {
  $sql .= ", profile_img = :profile_img";
}
if ($headerImageName) {
  $sql .= ", header_img = :header_img";
}
$sql .= " WHERE username = :username";

$stmt = $conn->prepare($sql);
$stmt->bindParam(":bio", $bio);
$stmt->bindParam(":username", $username);
if ($profileImageName) {
  $stmt->bindParam(":profile_img", $profileImageName);
}
if ($headerImageName) {
  $stmt->bindParam(":header_img", $headerImageName);
}

$stmt->execute();

// === SESSION AKTUALISIEREN ===
$_SESSION["bio"] = $bio;
if ($profileImageName) {
  $_SESSION["profile_img"] = $profileImageName;
}
if ($headerImageName) {
  $_SESSION["header_img"] = $headerImageName;
}

header("Location: welcome.php");
exit();
?>
