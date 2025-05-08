<?php
require_once '../config/config.php';
require_once HELPERS . '/auth.helper.php';
require_once MODELS . '/post.model.php';

// Verifiziere, dass der Benutzer eingeloggt ist
if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'Nicht autorisiert'
    ]);
    exit;
}

// CSRF-Schutz prüfen, falls implementiert
if (function_exists('verify_csrf_token')) {
    $token = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '';
    if (!verify_csrf_token($token)) {
        echo json_encode([
            'success' => false,
            'message' => 'Ungültiger CSRF-Token'
        ]);
        exit;
    }
}

// JSON-Daten einlesen
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['post_id']) || !isset($data['action'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Fehlende Parameter'
    ]);
    exit;
}

$post_id = intval($data['post_id']);
$action = $data['action'];
$user_id = $_SESSION['id'];

$post_model = new PostModel();

if ($action === 'like') {
    // Like hinzufügen
    $result = $post_model->likePost($post_id, $user_id);
} else if ($action === 'unlike') {
    // Like entfernen
    $result = $post_model->unlikePost($post_id, $user_id);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Ungültige Aktion'
    ]);
    exit;
}

// Anzahl der Likes für diesen Post abrufen
$like_count = $post_model->getPostLikesCount($post_id);

echo json_encode([
    'success' => $result,
    'like_count' => $like_count,
    'action' => $action
]);
?>
