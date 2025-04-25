<?php
// tests/test_create_post.php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../models/post.php';

echo "Starte Test: Beitrag erstellen...\n";

// Verbindung holen
$conn = getDatabaseConnection();

// Dummy-Nutzer-ID (achte darauf, dass dieser User existiert!)
$user_id = 8;
$content = "Test-Post um " . date("H:i:s");

// Post anlegen
$post_id = createPost($conn, $user_id, $content, null, null);

// Post abrufen
$post = fetchPostById($conn, $post_id, $user_id);

if (!$post) {
    echo "❌ Fehler: Kein Post zurückgegeben.\n";
    exit(1);
}

if ($post['content'] !== $content) {
    echo "❌ Inhalt stimmt nicht:\n";
    echo "   Erwartet: $content\n";
    echo "   Gefunden: " . $post['content'] . "\n";
    exit(1);
}

echo "✅ Test bestanden: Post #$post_id wurde korrekt gespeichert.\n";
exit(0);
