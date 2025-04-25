<?php
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function isUserLoggedIn(PDO $conn): bool {
    if (isset($_SESSION["username"])) {
        return true;
    }

    if (!empty($_COOKIE["remember_token"])) {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = :token");
        $stmt->bindParam(":token", $_COOKIE["remember_token"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["id"] = $user["id"];
            return true;
        } else {
            setcookie("remember_token", "", time() - 3600, "/");
        }
    }

    return false;
}

function ensureLogin(PDO $conn): void {
    if (!isUserLoggedIn($conn)) {
        header("Location: " . BASE_URL . "/views/login.view.php");

        exit();
    }

    // Falls ID noch fehlt
    if (!isset($_SESSION["id"])) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $_SESSION["username"]);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $_SESSION["id"] = $data["id"];
        }
    }
}

