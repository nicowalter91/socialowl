<?php
// Authentifizierungs- und Session-Helper
// Stellt Funktionen zur Nutzer-Authentifizierung und Sitzungsverwaltung bereit
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/connection.php';

// Session starten, falls noch nicht geschehen
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/**
 * Prüft, ob ein Nutzer eingeloggt ist (Session oder Remember-Me-Cookie).
 * Setzt ggf. Session-Variablen aus Cookie.
 * @param PDO $conn Datenbankverbindung
 * @return bool true, wenn Nutzer eingeloggt ist
 */
function isUserLoggedIn(PDO $conn): bool {
    // Session vorhanden
    if (isset($_SESSION["username"])) {
        return true;
    }
    // Remember-Me-Cookie prüfen
    if (!empty($_COOKIE["remember_token"])) {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE remember_token = :token");
        $stmt->bindParam(":token", $_COOKIE["remember_token"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // Session aus Cookie wiederherstellen
            $_SESSION["username"] = $user["username"];
            $_SESSION["id"] = $user["id"];
            return true;
        } else {
            // Ungültiges Cookie löschen
            setcookie("remember_token", "", time() - 3600, "/");
        }
    }
    return false;
}

/**
 * Erzwingt Login: Leitet auf Login-Seite um, falls nicht eingeloggt.
 * Setzt ggf. fehlende Session-ID nach.
 * @param PDO $conn Datenbankverbindung
 */
function ensureLogin(PDO $conn): void {
    if (!isUserLoggedIn($conn)) {
        header("Location: " . BASE_URL . "/views/login.view.php");
        exit();
    }
    // Falls ID noch fehlt, aus DB nachladen
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

// Online-Status aktualisieren (z.B. für Chat-Anzeige)
if (isset($_SESSION['id'])) {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare("UPDATE users SET last_active = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
}

