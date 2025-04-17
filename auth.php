<?php
session_start();
require_once "connection.php";

// Benutzer über Session oder Remember Token authentifizieren
function checkLogin() {
  global $conn;

  if (!isset($_SESSION["username"])) {
    if (isset($_COOKIE["remember_token"])) {
      $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = :remember_token");
      $stmt->bindParam(":remember_token", $_COOKIE["remember_token"]);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($data) {
        $_SESSION["username"] = $data["username"];
        $_SESSION["id"] = $data["id"];
      } else {
        setcookie("remember_token", "", time() - 3600);
      }
    }

    if (!isset($_SESSION["username"])) {
      header("Location: login.php");
      exit();
    }
  }

  // Immer die ID nachziehen, wenn sie noch fehlt
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
