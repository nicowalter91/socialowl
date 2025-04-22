<?php
require_once "connection.php";
require_once "auth.php";
checkLogin();

header('Content-Type: application/json');

$userId = $_SESSION["id"];
$postId = $_POST["post_id"] ?? null;
$content = trim($_POST["comment"] ?? '');

if (!$postId || $content === '') {
  echo json_encode(["success" => false, "message" => "Ungültige Eingabe."]);
  exit;
}

// Kommentar speichern
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())");
$stmt->execute([
  ":post_id" => $postId,
  ":user_id" => $userId,
  ":content" => $content
]);

$commentId = $conn->lastInsertId();

// User-Infos für das Kommentar laden
$userStmt = $conn->prepare("SELECT username, profile_img FROM users WHERE id = :id");
$userStmt->execute([":id" => $userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// HTML für Kommentar
ob_start(); ?>
<div class="comment d-flex align-items-start gap-2 mb-2 pt-3 pb-3 border-bottom border-secondary">
  <img class="rounded-circle" src="/Social_App/assets/uploads/<?= htmlspecialchars($user["profile_img"]) ?>" alt="Profilbild" style="width: 32px; height: 32px;">
  <div class="flex-grow-1">
    <strong class="text-light">@<?= htmlspecialchars($user["username"]) ?></strong><br>
    <span class="text-light comment-content"><?= nl2br(htmlspecialchars($content)) ?></span>
  </div>
  <div class="mt-2 d-flex gap-2 align-items-center">
    <!-- Edit -->
    <button class="btn btn-sm btn-outline-light edit-comment-btn" data-comment-id="<?= $commentId ?>" data-content="<?= htmlspecialchars($content, ENT_QUOTES) ?>">
      <i class="bi bi-pencil"></i> Bearbeiten
    </button>
    <!-- Delete -->
    <button type="button" class="btn btn-sm btn-outline-danger delete-comment-btn" data-comment-id="<?= $commentId ?>">
      <i class="bi bi-trash"></i>
    </button>
    <!-- Like -->
    <button type="button" class="btn btn-sm like-comment-btn btn-outline-light" data-comment-id="<?= $commentId ?>">
      <i class="bi bi-hand-thumbs-up me-1"></i>
      <span class="like-count">0</span>
    </button>
  </div>
</div>
<?php
$html = ob_get_clean();

echo json_encode(["success" => true, "html" => $html]);
exit;
?>
