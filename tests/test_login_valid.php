<?php
require_once "../includes/config.php";
require_once "../includes/connection.php";

$username = "nico91";
$password = "admin"; 

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user["password"])) {
    echo "Login erfolgreich fÃ¼r '$username'\n";
} else {
    echo "Login fehlgeschlagen fÃ¼r '$username'\n";
}
