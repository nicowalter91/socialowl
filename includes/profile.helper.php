<?php
require_once __DIR__ . '/config.php';

/**
 * Lädt ein Bild hoch, prüft Typ & speichert es im Upload-Ordner.
 */
function handleImageUpload(array $file, string $username, string $type): ?string {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;

    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) return null;

    $filename = "{$username}_{$type}_" . time() . ".{$ext}";
    $targetPath = UPLOADS . "/" . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        return $filename;
    }

    return null;
}

/**
 * Aktualisiert Benutzerdaten (Bio + Bilder) in der DB.
 */
function updateUserProfile(PDO $conn, string $username, string $bio, ?string $profileImg, ?string $headerImg): void {
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
}
