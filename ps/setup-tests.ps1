# Setze Arbeitsverzeichnis
$basePath = "C:\Schulung\www\Social_App"
$testDir = Join-Path $basePath "tests"

# Ordner erstellen, falls nicht vorhanden
if (!(Test-Path $testDir)) {
    New-Item -Path $testDir -ItemType Directory
    Write-Host "✅ Ordner 'tests' erstellt."
}

# Datei: test_create_post.php
@'
<?php
session_start();
$_SESSION["id"] = 1; // Benutzer-ID anpassen
require_once "../includes/config.php";
require_once "../includes/connection.php";
require_once "../models/post.php";

$content = "Test-Post von PowerShell " . time();
$postId = createPost($conn, $_SESSION["id"], $content, null, null);
$post = fetchPostById($conn, $postId, $_SESSION["id"]);

echo "✅ Post erstellt: ID=$postId\n";
echo "Inhalt: " . $post["content"] . "\n";
'@ | Out-File -Encoding UTF8 (Join-Path $testDir "test_create_post.php")

# Datei: test_create_comment.php
@'
<?php
session_start();
$_SESSION["id"] = 1; // Benutzer-ID anpassen
require_once "../includes/config.php";
require_once "../includes/connection.php";
require_once "../models/comment.php";

$postId = 1; // Bestehende Post-ID setzen
$content = "Test-Kommentar von PowerShell " . time();

$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$postId, $_SESSION["id"], $content]);

echo "✅ Kommentar erstellt für Post #$postId\n";
'@ | Out-File -Encoding UTF8 (Join-Path $testDir "test_create_comment.php")

# Datei: test_like_post.php
@'
<?php
session_start();
$_SESSION["id"] = 1;
require_once "../includes/config.php";
require_once "../includes/connection.php";

$postId = 1;

$stmt = $conn->prepare("SELECT * FROM post_likes WHERE user_id = ? AND post_id = ?");
$stmt->execute([$_SESSION["id"], $postId]);

if ($stmt->rowCount() > 0) {
    $conn->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?")->execute([$_SESSION["id"], $postId]);
    echo "❌ Like entfernt für Post #$postId\n";
} else {
    $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)")->execute([$_SESSION["id"], $postId]);
    echo "✅ Like hinzugefügt für Post #$postId\n";
}
'@ | Out-File -Encoding UTF8 (Join-Path $testDir "test_like_post.php")

# Datei: test_login_valid.php
@'
<?php
require_once "../includes/config.php";
require_once "../includes/connection.php";

$username = "nico91"; // Anpassen
$password = "deinPasswort"; // Anpassen

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user["password"])) {
    echo "✅ Login erfolgreich für '$username'\n";
} else {
    echo "❌ Login fehlgeschlagen für '$username'\n";
}
'@ | Out-File -Encoding UTF8 (Join-Path $testDir "test_login_valid.php")

Write-Host "✅ Testskripte erstellt unter: $testDir"
