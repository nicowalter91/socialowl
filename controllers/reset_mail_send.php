<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
$errorMessage = "";

if (isset($_POST["reset"])) {
    $email = $_POST["email"];
    $reset_token = md5(rand());
    $stmt = $conn->prepare("UPDATE users SET reset_token=:reset_token WHERE email=:email");
    $stmt->bindParam(":reset_token", $reset_token);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    if ($stmt->execute()) {
        $reset_link = "http://localhost:8080/Social_App/reset_pwd.php";
        $reset_link .= "?email=" . $email;
        $reset_link .= "&reset_token=" . $reset_token;
        $errorMessage = "Mail wurde verschickt! ";
    }
}
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen | Social Owl</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 400px; margin-top: 80px; background: unset; color: unset;">
        <h2 class="text-center mb-4">Passwort zurücksetzen</h2>
        <?php if ($errorMessage): ?>
            <div class="alert alert-success" style="background: var(--color-success); color: #fff; border-radius: 12px;">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="./reset_mail_send.php">
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control bg-dark text-light border-0 rounded-3" id="email" placeholder="E-Mail eingeben" name="email" required style="background: var(--color-input-bg); color: var(--color-input-text);">
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3" name="reset">Senden</button>
            <?php if (isset($reset_link)): ?>
                <div class="alert alert-light mt-4" role="alert">
                    <a href="<?php echo $reset_link; ?>">Passwort zurücksetzen</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>