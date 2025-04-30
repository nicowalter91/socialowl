<?php require_once __DIR__ . '/../includes/config.php'; ?>

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
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 500px; margin-top: 80px;">

    <h2 class="text-center mb-4">Registrieren</h2>
    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger" style="background: var(--color-danger); color: #fff; border-radius: 12px;"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="firstname" class="form-label">Vorname</label>
          <input type="text" class="form-control bg-dark text-light border-0 rounded-3" id="firstname" name="firstname" placeholder="Vornamen eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
        </div>
        <div class="mb-3 col-md-6">
          <label for="lastname" class="form-label">Nachname</label>
          <input type="text" class="form-control bg-dark text-light border-0 rounded-3" id="lastname" name="lastname" placeholder="Nachnamen eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
        </div>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Benutzername</label>
        <input type="text" class="form-control bg-dark text-light border-0 rounded-3" id="username" name="username" placeholder="Usernamen eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-Mail-Adresse</label>
        <input type="email" class="form-control bg-dark text-light border-0 rounded-3" id="email" name="email" placeholder="Email Adresse eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Passwort</label>
        <input type="password" class="form-control bg-dark text-light border-0 rounded-3" id="password" name="password" placeholder="Passwort eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
      </div>
      <div class="mb-3">
        <label for="passwordRepeat" class="form-label">Passwort wiederholen</label>
        <input type="password" class="form-control bg-dark text-light border-0 rounded-3" id="passwordRepeat" name="passwordRepeat" placeholder="Passwort bestätigen" required style="background: var(--color-input-bg); color: var(--color-input-text);">
      </div>
      <button type="submit" class="btn btn-primary w-100 rounded-3" name="submit">Registrieren</button>
      <div class="mt-3 text-center">
        <p>Schon registriert? <a href="<?= BASE_URL ?>/controllers/login.php" class="text-primary">Anmelden</a></p>
      </div>
    </form>
  </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>
