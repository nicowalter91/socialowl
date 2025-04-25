<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/comment.php';
require_once __DIR__ . '/../models/post.php';
require_once __DIR__ . '/../models/user.php';

echo "Starte Test: Kommentar erstellen...\n";

$conn = getDatabaseConnection();

// Testnutzer sicherstellen
$username = 'testuser';
$userStmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->execute([':username' => $username]);
$user = $userStmt->fetch();

if (!$user) {
    echo "❌ Fehler: Testnutzer '$username' nicht gefunden.\n";
    exit;
}
$user_id = $user['id'];

// Dummy-Post erstellen
$post_id = createPost($conn, $user_id, "Kommentar-Test-Post", null, null);

// Kommentar hinzufügen
$content = "Test-Kommentar via Testscript";
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
$stmt->execute([
    ":post_id" => $post_id,
    ":user_id" => $user_id,
    ":content" => $content
]);

$comment_id = $conn->lastInsertId();

// Kommentar abrufen & prüfen
$comments = fetchCommentsForPost($conn, $post_id, $user_id);

$found = false;
foreach ($comments as $comment) {
    if ($comment["id"] == $comment_id && $comment["content"] === $content) {
        $found = true;
        break;
    }
}

if ($found) {
    echo "✅ Test bestanden: Kommentar #$comment_id wurde korrekt gespeichert.\n";
} else {
    echo "❌ Fehler: Kommentar wurde nicht gefunden.\n";
}
