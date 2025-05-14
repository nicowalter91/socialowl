<?php
/**
 * View: Startseite
 * Zeigt den Einstiegspunkt der App (Feed, Sidebar, Navigation etc.).
 */
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
require_once INCLUDES . '/auth.php';
require_once MODELS . '/user.php';
require_once MODELS . '/post.php';
require_once MODELS . '/comment.php'; 



$conn = getDatabaseConnection();
ensureLogin($conn);

// Benutzer-Session aufbauen & Posts laden
$user = initUserSession($_SESSION["username"]);
$posts = fetchAllPostsWithComments($conn, $_SESSION["id"]);

?>


<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Social Owl</title>
  <meta name="description" content="Willkommen bei Social Owl - deinem sozialen Netzwerk für kreative Köpfe.">
  <meta name="theme-color" content="#0d6efd" />

  <!-- CSS -->  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cookie-banner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile-image-fix.css">
  <link rel="icon" href="<?= BASE_URL ?>/assets/img/Owl_logo.svg" type="image/x-icon">
</head>

<body class="app-body" data-current-user-id="<?= (int)$_SESSION['id'] ?>">

  <!-- Navigation -->
  <?php include PARTIALS . '/navbar.php'; ?>

  <!-- Main Layout -->
  <div class="parent fixed-top">
    <?php include PARTIALS . '/sidebar-left.php'; ?>
    <?php include PARTIALS . '/post-form.php'; ?>
    <?php include VIEWS . '/feed.view.php'; ?>
    <?php include PARTIALS . '/sidebar-right.php'; ?>
  </div>

  <!-- Modals -->
  <?php include PARTIALS . '/modal-profil.php'; ?>
  <?php include PARTIALS . '/modal-delete-posts.php'; ?>
  <?php include PARTIALS . '/modal-chat.php'; ?>

  <!-- JS -->
  <script>
    // Globale Variablen für JavaScript
    const BASE_URL = "<?= BASE_URL ?>";
  </script>
  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/notifications.js" type="module"></script>
  <script src="<?= BASE_URL ?>/assets/js/cookie-banner.js"></script>

  <!-- Cookie Banner -->
  <div id="cookie-banner" class="cookie-banner">
    <div class="cookie-banner-header">
      <i class="bi bi-cookie cookie-icon"></i>
      <h3 class="cookie-banner-title">Cookies & Datenschutz</h3>
    </div>
    <p class="cookie-banner-text">
      Wir verwenden Cookies, um Ihre Erfahrung auf unserer Website zu verbessern und Ihnen personalisierte Inhalte anzubieten.
    </p>
    <div class="cookie-banner-buttons">
      <button id="cookie-accept" class="cookie-accept-btn">Akzeptieren</button>
      <button id="cookie-close" class="cookie-close-btn">Schließen</button>
    </div>
  </div>

</body>

</html>
