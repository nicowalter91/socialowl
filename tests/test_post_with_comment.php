<?php
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/post.php';
require_once __DIR__ . '/../models/comment.php';

echo "🧪 Starte Integrationstest: Post mit Kommentar...\n";

$conn = getDatabaseConnection();

// Testnutzer definieren
$username = 'testuser';
$userStmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->execute([':username' => $username]);
$user = $userStmt->fetch();

if (!$user) {
    echo "❌ Fehler: Testnutzer '$username' nicht gefunden.\n";
    exit;
}
$user_id = $user['id'];

// === 1. Post erstellen ===
$content_post = "Testpost aus Integrationstest";
$post_id = createPost($conn, $user_id, $content_post, null, null);

if (!$post_id) {
    echo "❌ Fehler: Post konnte nicht erstellt werden.\n";
    exit;
}

echo "✅ Post #$post_id erstellt.\n";

// === 2. Kommentar zum Post ===
$content_comment = "Kommentar zu Post $post_id";
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
$stmt->execute([
    ":post_id" => $post_id,
    ":user_id" => $user_id,
    ":content" => $content_comment
]);

$comment_id = $conn->lastInsertId();

echo "✅ Kommentar #$comment_id wurde zu Post #$post_id gespeichert.\n";

// === 3. Überprüfen ===
$comments = fetchCommentsForPost($conn, $post_id, $user_id);
$found = false;

foreach ($comments as $comment) {
    if ($comment['id'] == $comment_id && $comment['content'] === $content_comment) {
        $found = true;
        break;
    }
}

if ($found) {
    echo "✅ Test bestanden: Kommentar korrekt dem Post zugeordnet.\n";
} else {
    echo "❌ Fehler: Kommentar nicht gefunden oder Zuordnung inkorrekt.\n";
}

// === 4. Optional: Cleanup ===
$conn->prepare("DELETE FROM comments WHERE id = :id")->execute([":id" => $comment_id]);
$conn->prepare("DELETE FROM posts WHERE id = :id")->execute([":id" => $post_id]);
echo "🧹 Testdaten gelöscht.\n";
