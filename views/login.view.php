<?php
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Social Owl</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-dark">
    <div class="login-container p-4 rounded shadow text-light" style="width: 100%; max-width: 400px; background-color: #05141c;">
        <div class="text-center mb-3">
            <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" height="60" alt="Social Owl Logo">
        </div>

        <h2 class="text-center mb-4">Anmelden</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/controllers/login.php">

            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="mt-1">
                    <a href="<?= BASE_URL ?>/controllers/reset_mail_send.php" class="text-light">Passwort vergessen?</a>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" value="1">
                <label class="form-check-label" for="rememberMe">Angemeldet bleiben</label>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="submit">Anmelden</button>

            <div class="mt-3 text-center">
                <p>Noch kein Konto? <a href="<?= BASE_URL ?>/controllers/register.php" class="text-light">Registrieren</a></p>
            </div>
        </form>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
