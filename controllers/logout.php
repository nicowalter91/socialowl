<?php
require_once __DIR__ . '/../includes/config.php';

session_start();

// Session löschen
$_SESSION = [];
session_destroy();

// Remember Me Cookie löschen
setcookie("remember_token", "", time() - 3600, "/", "", false, true);

// Zur Login-Seite weiterleiten
header("Location: " . BASE_URL . "/views/login.view.php");
exit;
