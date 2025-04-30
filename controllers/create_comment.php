<?php
/**
 * Controller: Kommentar erstellen
 * Verarbeitet das Absenden eines neuen Kommentars zu einem Post.
 * Gibt eine JSON-Antwort mit HTML-Partial und Rohdaten zurück.
 */
require_once "../includes/config.php";
require_once INCLUDES . "/connection.php";
require_once INCLUDES . "/auth.php";
require_once MODELS . "/comment.php";

$conn = getDatabaseConnection();
ensureLogin($conn);

header('Content-Type: application/json');

$userId  = $_SESSION["id"];
$postId  = $_POST["post_id"]  ?? null;
$content = trim($_POST["comment"] ?? '');

// Eingabe validieren
if (!$postId || $content === '') {
  echo json_encode([
    "success" => false,
    "message" => "Ungültige Eingabe."
  ]);
  exit;
}

// Kommentar speichern
$stmt = $conn->prepare("
  INSERT INTO comments (post_id, user_id, content, created_at)
  VALUES (:post_id, :user_id, :content, NOW())
");
$stmt->execute([
  ":post_id" => $postId,
  ":user_id" => $userId,
  ":content" => $content
]);

$commentId = $conn->lastInsertId();

// Post-Besitzer abrufen und Benachrichtigung erstellen
$stmt = $conn->prepare("
    SELECT p.user_id, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.id = :post_id
");
$stmt->execute([":post_id" => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Benachrichtigung erstellen (nur wenn der Kommentar nicht vom Post-Besitzer ist)
if ($post['user_id'] != $userId) {
    // Benutzername des Kommentators abrufen
    $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = :user_id");
    $stmt2->execute([":user_id" => $userId]);
    $commenter = $stmt2->fetch(PDO::FETCH_ASSOC);
    // Benachrichtigung einfügen
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, type, content, post_id) 
        VALUES (:user_id, 'comment', :content, :post_id)
    ");
    $content = "@{$commenter['username']} hat deinen Post kommentiert";
    $stmt->execute([
        ":user_id" => $post['user_id'],
        ":content" => $content,
        ":post_id" => $postId
    ]);
}

// Den neuen Kommentar samt Userinfos & Like-Daten holen
$comment = fetchSingleComment($conn, $commentId, $userId);
if (!$comment) {
  echo json_encode([
    "success" => false,
    "message" => "Kommentar konnte nicht geladen werden."
  ]);
  exit;
}

// HTML-Partial für direkte Einbettung (wird optional)
ob_start();
$GLOBALS["comment"] = $comment;
include PARTIALS . '/comment_item.php';
$html = ob_get_clean();

// JSON-Antwort mit HTML und den Rohdaten
echo json_encode([
  "success" => true,
  "html"    => $html,
  "comment" => $comment
]);
exit;
