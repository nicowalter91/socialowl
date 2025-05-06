<?php
/**
 * Controller: Registrierung
 * Verarbeitet die Registrierung eines neuen Nutzers und prüft Eingaben.
 * Beim erfolgreichen Onboarding werden Profilbild, Header und Bio gespeichert.
 */
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/profile.helper.php';

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
    $bio = isset($_POST["bio"]) ? trim($_POST["bio"]) : '';

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
            // Standardwerte für Profilbilder festlegen
            $profileImage = "profil.png";
            $headerImage = "default_header.png";
            
            // User anlegen
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, firstname, lastname, password, bio, profile_img, header_img) 
                                    VALUES (:username, :email, :firstname, :lastname, :password, :bio, :profile_img, :header_img)");
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":firstname" => $firstname,
                ":lastname" => $lastname,
                ":password" => $hash,
                ":bio" => $bio,
                ":profile_img" => $profileImage,
                ":header_img" => $headerImage
            ]);
            
            // ID des neuen Nutzers abrufen
            $userId = $conn->lastInsertId();
            
            // Profilbild hochladen, wenn vorhanden
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = uploadProfileImage($_FILES['profile_image'], $username, $userId);
                if ($uploadResult['success']) {
                    $profileImage = $uploadResult['filename'];
                    
                    // Update user record with profile image
                    $stmt = $conn->prepare("UPDATE users SET profile_img = :profile_img WHERE id = :id");
                    $stmt->execute([
                        ":profile_img" => $profileImage,
                        ":id" => $userId
                    ]);
                }
            }
            
            // Header-Bild hochladen, wenn vorhanden
            if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = uploadHeaderImage($_FILES['header_image'], $username, $userId);
                if ($uploadResult['success']) {
                    $headerImage = $uploadResult['filename'];
                    
                    // Update user record with header image
                    $stmt = $conn->prepare("UPDATE users SET header_img = :header_img WHERE id = :id");
                    $stmt->execute([
                        ":header_img" => $headerImage,
                        ":id" => $userId
                    ]);
                }
            }
            
            // Weiterleitung zur Login-Seite nach erfolgreicher Registrierung
            header("Location: " . BASE_URL . "/controllers/login.php?registered=1");
            exit;
        }
    }
}

/**
 * Lädt ein Profilbild hoch und gibt Informationen über den Upload zurück
 * 
 * @param array $file Die Datei aus dem $_FILES Array
 * @param string $username Der Benutzername
 * @param int $userId Die ID des Benutzers
 * @return array Ergebnis-Array mit success und filename
 */
function uploadProfileImage($file, $username, $userId) {
    // Zielverzeichnis für Uploads
    $uploadDir = __DIR__ . '/../assets/uploads/';
    
    // Dateityp überprüfen
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Ungültiger Dateityp. Erlaubt sind nur JPEG, PNG, GIF und WEBP.'];
    }
    
    // Dateigröße überprüfen (max. 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Die Datei ist zu groß. Maximale Größe: 5MB.'];
    }
    
    // Dateiname generieren
    $timestamp = time();
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFilename = "{$username}_profile_{$timestamp}.{$extension}";
    $uploadPath = $uploadDir . $newFilename;
    
    // Datei verschieben
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $newFilename];
    } else {
        return ['success' => false, 'message' => 'Beim Hochladen ist ein Fehler aufgetreten.'];
    }
}

/**
 * Lädt ein Header-Bild hoch und gibt Informationen über den Upload zurück
 * 
 * @param array $file Die Datei aus dem $_FILES Array
 * @param string $username Der Benutzername
 * @param int $userId Die ID des Benutzers
 * @return array Ergebnis-Array mit success und filename
 */
function uploadHeaderImage($file, $username, $userId) {
    // Zielverzeichnis für Uploads
    $uploadDir = __DIR__ . '/../assets/uploads/';
    
    // Dateityp überprüfen
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Ungültiger Dateityp. Erlaubt sind nur JPEG, PNG, GIF und WEBP.'];
    }
    
    // Dateigröße überprüfen (max. 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Die Datei ist zu groß. Maximale Größe: 5MB.'];
    }
    
    // Dateiname generieren
    $timestamp = time();
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFilename = "{$username}_header_{$timestamp}.{$extension}";
    $uploadPath = $uploadDir . $newFilename;
    
    // Datei verschieben
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $newFilename];
    } else {
        return ['success' => false, 'message' => 'Beim Hochladen ist ein Fehler aufgetreten.'];
    }
}

// View einbinden
require_once VIEWS . '/register.view.php';
