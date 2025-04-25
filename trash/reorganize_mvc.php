<?php
/**
 * Reorganize Social_App project into MVC structure.
 */

function moveFile($filename, $targetDir) {
    if (!file_exists($filename)) return;
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $basename = basename($filename);
    rename($filename, "$targetDir/$basename");
}

// === Zielpfade definieren ===
$map = [
    // Controller
    "login.php"             => "controllers",
    "register.php"          => "controllers",
    "welcome.php"           => "controllers",
    "profil-update.php"     => "controllers",
    "create_post.php"       => "controllers",
    "create_comment.php"    => "controllers",
    "update_comment.php"    => "controllers",
    "like_post.php"         => "controllers",
    "like_comment.php"      => "controllers",
    "delete_post.php"       => "controllers",
    "delete_comment.php"    => "controllers",
    "reset_mail_send.php"   => "controllers",
    "reset_pwd.php"         => "controllers",
    "logout.php"            => "controllers",

    // Models
    "user.php"              => "models",
    "post.php"              => "models",

    // Views (kann später angepasst werden)
    "feed.php"              => "views",
    "profile.php"           => "views",

    // Partials
    "navbar.php"            => "partials",
    "sidebar-left.php"      => "partials",
    "sidebar-right.php"     => "partials",
    "post-form.php"         => "partials",
    "modal-profil.php"      => "partials",
    "modal-delete-posts.php"=> "partials",
    "post_card.php"         => "partials",

    // Includes
    "auth.php"              => "includes",
    "connection.php"        => "includes",
    "config.php"            => "includes",

    // Trash (alte Dateien)
    "debug.txt"             => "trash",
    "readme.txt"            => "trash",
];

// === Dateien verschieben ===
foreach ($map as $file => $targetDir) {
    moveFile($file, $targetDir);
}

echo "✅ Projektstruktur erfolgreich reorganisiert nach MVC.\n";
