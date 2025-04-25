<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';

session_start();
$conn = getDatabaseConnection(); 
$errorMessage = "";

// Falls bereits eingeloggt
if (isset($_SESSION["username"])) {
    header("Location: " . BASE_URL . "/views/welcome.php");
    exit;
}


// Verarbeite das Formular
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["username"] = $user["username"];
        $_SESSION["id"] = $user["id"];

        // Remember me aktivieren
        if (!empty($_POST["remember_me"])) {
            $token = bin2hex(random_bytes(16));
            $stmt = $conn->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
            $stmt->execute([
                ":token" => $token,
                ":id" => $user["id"]
            ]);

            $secure = !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off";
            setcookie("remember_token", $token, [
                "expires" => time() + (86400 * 365),
                "path" => "/",
                "secure" => $secure,
                "httponly" => true,
                "samesite" => "Lax"
            ]);
        }

        header("Location: " . BASE_URL . "/views/welcome.php", true, 303);
        exit;
    }

    $errorMessage = "Falscher Benutzername oder Passwort.";
}

// HTML anzeigen
require_once __DIR__ . '/../views/login.view.php';
