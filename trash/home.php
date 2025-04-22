<?php
require "connection.php";
session_start();

if (!isset($_SESSION["username"])) {
    if (isset($_COOKIE["remember_token"])) {
        $remember_token = $_COOKIE["remember_token"];
        $stmt = $conn->prepare("SELECT username FROM users WHERE remember_token = :remember_token");
        $stmt->bindParam(":remember_token", $remember_token);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $_SESSION["username"] = $data["username"];
        } else {
            setcookie("remember_token", "", time() - 3600);
        }
    }
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
}

$content = 'home_content.php';
include 'template/layout.php';
?>