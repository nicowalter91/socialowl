<?php
require_once __DIR__ . '/../../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';

header('Content-Type: application/json');

$conn = getDatabaseConnection();
ensureLogin($conn);

$query = trim($_GET["q"] ?? "");
$currentUserId = $_SESSION["id"];

if (empty($query)) {
    echo json_encode([
        'success' => false,
        'message' => 'Keine Suchanfrage angegeben'
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT u.id, u.username, u.profile_img, u.bio,
               EXISTS(SELECT 1 FROM followers WHERE follower_id = :current_user AND followed_id = u.id) as is_following
        FROM users u
        WHERE u.username LIKE :query
        ORDER BY 
            CASE 
                WHEN u.username LIKE :exact_query THEN 1
                WHEN u.username LIKE :start_query THEN 2
                ELSE 3
            END,
            u.username ASC
        LIMIT 10
    ");
    
    $stmt->execute([
        ':query' => '%' . $query . '%',
        ':exact_query' => $query,
        ':start_query' => $query . '%',
        ':current_user' => $currentUserId
    ]);
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Datenbankfehler: ' . $e->getMessage()
    ]);
}