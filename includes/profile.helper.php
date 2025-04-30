<?php
/**
 * Profile Helper
 * Stellt Hilfsfunktionen für das Hochladen und Bearbeiten von Nutzerprofilen bereit.
 */
require_once __DIR__ . '/config.php';

/**
 * Löscht eine Datei, wenn sie existiert.
 *
 * @param string $filepath Vollständiger Pfad zur Datei
 * @return bool true, wenn die Datei gelöscht wurde oder nicht existierte, false bei Fehler
 */
function deleteFileIfExists(string $filepath): bool {
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return true;
}

/**
 * Lädt ein Bild hoch, prüft Typ & speichert es im Upload-Ordner.
 * Löscht alte Dateien, wenn sie überschrieben werden.
 *
 * @param array $file Die hochgeladene Datei (aus $_FILES)
 * @param string $username Benutzername
 * @param string $type Typ des Bildes (profile/header)
 * @param string|null $oldFilename Alter Dateiname, der gelöscht werden soll
 * @return string|null Neuer Dateiname oder null bei Fehler
 */
function handleImageUpload(array $file, string $username, string $type, ?string $oldFilename = null): ?string {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) return null;
    // Alte Datei löschen, wenn vorhanden
    if ($oldFilename) {
        $oldPath = UPLOADS . "/" . $oldFilename;
        deleteFileIfExists($oldPath);
    }
    $filename = "{$username}_{$type}_" . time() . ".{$ext}";
    $targetPath = UPLOADS . "/" . $filename;
    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        return $filename;
    }
    return null;
}

/**
 * Aktualisiert Benutzerdaten (Bio + Bilder) in der Datenbank.
 * Löscht alte Dateien, wenn neue hochgeladen wurden.
 *
 * @param PDO $conn Datenbankverbindung
 * @param string $username Benutzername
 * @param string $bio Neue Bio
 * @param string|null $profileImg Neuer Profilbild-Dateiname
 * @param string|null $headerImg Neuer Headerbild-Dateiname
 * @param string|null $oldProfileImg Alter Profilbild-Dateiname
 * @param string|null $oldHeaderImg Alter Headerbild-Dateiname
 */
function updateUserProfile(PDO $conn, string $username, string $bio, ?string $profileImg, ?string $headerImg, ?string $oldProfileImg = null, ?string $oldHeaderImg = null): void {
    $sql = "UPDATE users SET bio = :bio";
    if ($profileImg) $sql .= ", profile_img = :profile_img";
    if ($headerImg) $sql .= ", header_img = :header_img";
    $sql .= " WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":username", $username);
    if ($profileImg) $stmt->bindParam(":profile_img", $profileImg);
    if ($headerImg) $stmt->bindParam(":header_img", $headerImg);
    $stmt->execute();
    // Alte Dateien löschen, wenn neue hochgeladen wurden
    if ($profileImg && $oldProfileImg) {
        $oldPath = UPLOADS . "/" . $oldProfileImg;
        deleteFileIfExists($oldPath);
    }
    if ($headerImg && $oldHeaderImg) {
        $oldPath = UPLOADS . "/" . $oldHeaderImg;
        deleteFileIfExists($oldPath);
    }
}
