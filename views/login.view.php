<?php
/**
 * View: Login
 * Zeigt das Login-Formular für die Anmeldung an.
 */
require_once __DIR__ . '/../includes/config.php';
?>

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Social Owl</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css" />
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 400px; margin-top: 80px; background: unset; color: unset;">
        <h2 class="text-center mb-4">Anmelden</h2>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" style="background: var(--color-danger); color: #fff; border-radius: 12px;"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>/controllers/login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control bg-dark text-light border-0 rounded-3" id="username" name="username" placeholder="Benutzername eingeben" required autofocus style="background: var(--color-input-bg); color: var(--color-input-text);">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" class="form-control bg-dark text-light border-0 rounded-3" id="password" name="password" placeholder="Passwort eingeben" required style="background: var(--color-input-bg); color: var(--color-input-text);">
                <div class="mt-1">
                    <a href="<?= BASE_URL ?>/controllers/reset_mail_send.php" class="text-primary">Passwort vergessen?</a>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" value="1">
                <label class="form-check-label" for="rememberMe">Angemeldet bleiben</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3" name="submit">Anmelden</button>
            <div class="mt-3 text-center">
                <p>Noch kein Konto? <a href="<?= BASE_URL ?>/controllers/register.php" class="text-primary">Registrieren</a></p>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>

</html>
