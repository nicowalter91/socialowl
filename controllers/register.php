<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';

session_start();
$conn = getDatabaseConnection();
$errorMessage = "";

// Weiterleitung, wenn eingeloggt
if (isset($_SESSION["username"])) {
    header("Location: " . BASE_URL . "/views/index.php");
    exit;
}

// Formularverarbeitung
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];

    // Passwort-Check
    if ($password !== $passwordRepeat) {
        $errorMessage = "Die Passwörter stimmen nicht überein.";
    } else {
        // Nutzername/E-Mail prüfen
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([":username" => $username, ":email" => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            if ($existingUser["username"] === $username && $existingUser["email"] === $email) {
                $errorMessage = "Benutzername und E-Mail sind bereits vergeben.";
            } elseif ($existingUser["username"] === $username) {
                $errorMessage = "Der Benutzername ist bereits vergeben.";
            } else {
                $errorMessage = "Die E-Mail-Adresse ist bereits vergeben.";
            }
        } else {
            // Registrierung durchführen
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, firstname, lastname, password) VALUES (:username, :email, :firstname, :lastname, :password)");
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":firstname" => $firstname,
                ":lastname" => $lastname,
                ":password" => $hash
            ]);

            header("Location: " . BASE_URL . "/controllers/login.php");
            exit;
        }
    }
}

// View einbinden
require_once VIEWS . '/register.view.php';
