<?php
/**
 * View: Registrierung
 * Zeigt das Registrierungsformular für neue Nutzer an.
 */

require_once __DIR__ . '/../includes/config.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
<script>
    (function() {
      const mode = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      const html = document.documentElement;
      html.classList.remove('light', 'dark');
      html.classList.add(mode);
    })();
    </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrieren | Social Owl</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <style>
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 0, transparent 60%),
        radial-gradient(circle at 80% 70%, rgba(238,187,195,0.10) 0, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }
  </style>
</head>
<body style="min-height: 100vh; background: linear-gradient(120deg, #eebbc3 60%, #0d6efd 100%); display: flex; align-items: center; justify-content: center;">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow" style="width: 100%; max-width: 500px; margin-top: 80px; background: #fff; color: #232946; box-shadow: 0 2px 16px rgba(35,41,70,0.08);">
    <h2 class="text-center mb-4" style="color: #232946;">Registrieren</h2>
    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger" style="background: #eebbc3; color: #232946; border-radius: 12px; border: none;"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="firstname" class="form-label" style="color: #232946;">Vorname</label>
          <input type="text" class="form-control border-0 rounded-3" id="firstname" name="firstname" placeholder="Vornamen eingeben" required style="background: #f4f4f4; color: #232946;">
        </div>
        <div class="mb-3 col-md-6">
          <label for="lastname" class="form-label" style="color: #232946;">Nachname</label>
          <input type="text" class="form-control border-0 rounded-3" id="lastname" name="lastname" placeholder="Nachnamen eingeben" required style="background: #f4f4f4; color: #232946;">
        </div>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label" style="color: #232946;">Benutzername</label>
        <input type="text" class="form-control border-0 rounded-3" id="username" name="username" placeholder="Usernamen eingeben" required style="background: #f4f4f4; color: #232946;">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label" style="color: #232946;">E-Mail-Adresse</label>
        <input type="email" class="form-control border-0 rounded-3" id="email" name="email" placeholder="Email Adresse eingeben" required style="background: #f4f4f4; color: #232946;">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label" style="color: #232946;">Passwort</label>
        <input type="password" class="form-control border-0 rounded-3" id="password" name="password" placeholder="Passwort eingeben" required style="background: #f4f4f4; color: #232946;">
      </div>
      <div class="mb-3">
        <label for="passwordRepeat" class="form-label" style="color: #232946;">Passwort wiederholen</label>
        <input type="password" class="form-control border-0 rounded-3" id="passwordRepeat" name="passwordRepeat" placeholder="Passwort bestätigen" required style="background: #f4f4f4; color: #232946;">
      </div>
      <button type="submit" class="btn w-100 rounded-3" name="submit" style="background: #eebbc3; color: #232946; font-weight: bold; border: none;">Registrieren</button>
      <div class="mt-3 text-center">
        <p style="color: #232946;">Schon registriert? <a href="<?= BASE_URL ?>/controllers/login.php" style="color: #0d6efd;">Anmelden</a></p>
      </div>
    </form>
  </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>
