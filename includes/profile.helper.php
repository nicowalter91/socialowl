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

/**
 * Generiert einen eindeutigen Dateinamen für ein Profilbild
 * 
 * @param string $username Benutzername
 * @param string $extension Dateiendung
 * @return string Eindeutiger Dateiname
 */
function generateProfileImageName($username, $extension) {
    $timestamp = time();
    return strtolower($username) . '_profile_' . $timestamp . '.' . $extension;
}

/**
 * Generiert einen eindeutigen Dateinamen für ein Header-Bild
 * 
 * @param string $username Benutzername
 * @param string $extension Dateiendung
 * @return string Eindeutiger Dateiname
 */
function generateHeaderImageName($username, $extension) {
    $timestamp = time();
    return strtolower($username) . '_header_' . $timestamp . '.' . $extension;
}

/**
 * Überprüft, ob ein Bild gültig ist (Typ und Größe)
 * 
 * @param array $file Die Datei aus dem $_FILES Array
 * @param int $maxSize Maximale Dateigröße in Bytes
 * @return array Validierungsergebnis
 */
function validateImage($file, $maxSize = 5242880) { // 5MB default
    // Überprüfen, ob es sich um ein Bild handelt
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return [
            'valid' => false, 
            'message' => 'Ungültiger Dateityp. Erlaubt sind nur JPEG, PNG, GIF und WEBP.'
        ];
    }
    
    // Dateigröße überprüfen
    if ($file['size'] > $maxSize) {
        return [
            'valid' => false, 
            'message' => 'Die Datei ist zu groß. Maximale Größe: ' . ($maxSize / 1024 / 1024) . 'MB.'
        ];
    }
    
    return ['valid' => true];
}

/**
 * Skaliert und optimiert ein Bild für die Verwendung als Profilbild
 * 
 * @param string $sourcePath Pfad zur Quelldatei
 * @param string $targetPath Pfad zur Zieldatei
 * @param int $maxWidth Maximale Breite
 * @param int $maxHeight Maximale Höhe
 * @return bool Erfolg
 */
function resizeAndOptimizeProfileImage($sourcePath, $targetPath, $maxWidth = 300, $maxHeight = 300) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    $mimeType = $imageInfo['mime'];
    
    // Quellbild erstellen basierend auf dem Typ
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }
    
    // Originale Dimensionen
    $origWidth = imagesx($sourceImage);
    $origHeight = imagesy($sourceImage);
    
    // Seitenverhältnis berechnen
    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
    $newWidth = $origWidth * $ratio;
    $newHeight = $origHeight * $ratio;
    
    // Neues Bild erstellen
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Transparenz für PNG und GIF erhalten
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
    }
    
    // Bild skalieren
    imagecopyresampled(
        $newImage, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight, $origWidth, $origHeight
    );
    
    // Bild speichern
    $extension = pathinfo($targetPath, PATHINFO_EXTENSION);
    $result = false;
    
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
            $result = imagejpeg($newImage, $targetPath, 85); // 85% Qualität
            break;
        case 'png':
            $result = imagepng($newImage, $targetPath, 8); // Kompressionsgrad 8
            break;
        case 'gif':
            $result = imagegif($newImage, $targetPath);
            break;
        case 'webp':
            $result = imagewebp($newImage, $targetPath, 85);
            break;
    }
    
    // Speicher freigeben
    imagedestroy($sourceImage);
    imagedestroy($newImage);
    
    return $result;
}

/**
 * Skaliert und optimiert ein Bild für die Verwendung als Header-Bild
 * 
 * @param string $sourcePath Pfad zur Quelldatei
 * @param string $targetPath Pfad zur Zieldatei
 * @param int $maxWidth Maximale Breite
 * @param int $maxHeight Maximale Höhe
 * @return bool Erfolg
 */
function resizeAndOptimizeHeaderImage($sourcePath, $targetPath, $maxWidth = 1200, $maxHeight = 400) {
    // Die gleiche Funktion wie für Profilbilder, aber mit anderen Dimensionen
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    $mimeType = $imageInfo['mime'];
    
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }
    
    $origWidth = imagesx($sourceImage);
    $origHeight = imagesy($sourceImage);
    
    // Bei Header-Bildern ist oft das Seitenverhältnis wichtig, daher skalieren wir auf die maximale Breite
    // und beschneiden ggf. die Höhe
    $ratio = $maxWidth / $origWidth;
    $newWidth = $maxWidth;
    $newHeight = $origHeight * $ratio;
    
    // Wenn das Bild nach der Skalierung höher als maxHeight ist, beschneiden wir es
    if ($newHeight > $maxHeight) {
        // Berechnen des Y-Offsets für zentriertes Beschneiden
        $yOffset = ($newHeight - $maxHeight) / 2;
        
        // Temporäres Bild in Originalgröße erstellen
        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Transparenz für PNG und GIF erhalten
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($tempImage, false);
            imagesavealpha($tempImage, true);
            $transparent = imagecolorallocatealpha($tempImage, 255, 255, 255, 127);
            imagefilledrectangle($tempImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Bild auf temporäres Bild skalieren
        imagecopyresampled(
            $tempImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight, $origWidth, $origHeight
        );
        
        // Zuschneiden auf maximale Höhe
        $newImage = imagecreatetruecolor($newWidth, $maxHeight);
        
        // Transparenz für PNG und GIF erhalten
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $maxHeight, $transparent);
        }
        
        // Bild aus temporärem Bild auf endgültiges Bild kopieren mit Versatz
        imagecopy($newImage, $tempImage, 0, 0, 0, $yOffset, $newWidth, $maxHeight);
        
        // Temporäres Bild freigeben
        imagedestroy($tempImage);
    } else {
        // Wenn das Bild niedriger als maxHeight ist, skalieren wir es einfach
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Transparenz für PNG und GIF erhalten
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Bild skalieren
        imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight, $origWidth, $origHeight
        );
    }
    
    // Bild speichern
    $extension = pathinfo($targetPath, PATHINFO_EXTENSION);
    $result = false;
    
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
            $result = imagejpeg($newImage, $targetPath, 85);
            break;
        case 'png':
            $result = imagepng($newImage, $targetPath, 8);
            break;
        case 'gif':
            $result = imagegif($newImage, $targetPath);
            break;
        case 'webp':
            $result = imagewebp($newImage, $targetPath, 85);
            break;
    }
    
    // Speicher freigeben
    imagedestroy($sourceImage);
    imagedestroy($newImage);
    
    return $result;
}
