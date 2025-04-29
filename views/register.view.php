<?php require_once __DIR__ . '/../includes/config.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrieren | Social Owl</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-dark">
  <div class="login-container p-4 rounded shadow text-light" style="width: 100%; max-width: 500px; background-color: #05141c;">
    <div class="text-center mb-3">
      <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" height="60" alt="Logo">
    </div>
    <h2 class="text-center mb-4">Registrieren</h2>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="firstname" class="form-label">Vorname</label>
          <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Vornamen eingeben"required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="lastname" class="form-label">Nachname</label>
          <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nachnamen eingeben" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Benutzername</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Usernamen eingeben" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-Mail-Adresse</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email Adresse eingeben" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Passwort</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Passwort eingeben" required>
      </div>

      <div class="mb-3">
        <label for="passwordRepeat" class="form-label">Passwort wiederholen</label>
        <input type="password" class="form-control" id="passwordRepeat" name="passwordRepeat" placeholder="Passwort bestätigen" required>
      </div>

      <button type="submit" class="btn btn-primary w-100" name="submit">Registrieren</button>
      <div class="mt-3 text-center">
        <p>Schon registriert? <a href="<?= BASE_URL ?>/controllers/login.php" class="text-light">Anmelden</a></p>
      </div>
    </form>
  </div>

  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
