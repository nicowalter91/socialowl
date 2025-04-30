<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
$errorMessage = "";
$successMessage = "";

// E-Mail und Token aus der URL holen
$tokenValid = false;
if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    $email = $_GET["email"];
    $reset_token = $_GET['reset_token'];
    // Token-Validierung aus DB
    $stmt = $conn->prepare("SELECT reset_token FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['reset_token'] === $reset_token && !empty($reset_token)) {
        $tokenValid = true;
    }
}

// Wenn das Formular abgesendet wird
if (isset($_POST['reset'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];
    $reset_token = $_POST["reset_token"];
    // Token-Validierung aus DB
    $stmt = $conn->prepare("SELECT reset_token FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || $row['reset_token'] !== $reset_token || empty($reset_token)) {
        $errorMessage = "Ungültiger oder abgelaufener Reset-Link.";
    } else if ($password !== $passwordRepeat) {
        $errorMessage = "Die Passwörter stimmen nicht überein.";
    } else {
        $passwordHash = PASSWORD_HASH($password, PASSWORD_DEFAULT);
        try {
            $result = updatePassword($passwordHash, $email);
            if ($result) {
                $successMessage = "Passwort erfolgreich geändert";
            } else {
                $errorMessage = "Fehler beim Zurücksetzen des Passworts. Möglicherweise existiert der Token nicht oder ist ungültig.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Datenbankfehler: " . $e->getMessage();
        }
    }
}


function updatePassword($password, $email)
{
    global $conn;
    $null = NULL; // Setze auf NULL statt einem leeren String
    $stmt = $conn->prepare("UPDATE users SET password=:password, reset_token=:reset WHERE email=:email");
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":reset", $null, PDO::PARAM_NULL);  // Übergabe von NULL
    return $stmt->execute();
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
    <title>Passwort ändern | Social Owl</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 400px; margin-top: 80px; background: unset; color: unset;">
        <h2 class="text-center mb-4">Passwort zurücksetzen</h2>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" style="background: var(--color-danger); color: #fff; border-radius: 12px;">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="alert alert-success" style="background: var(--color-success); color: #fff; border-radius: 12px;">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="./reset_pwd.php">
            <div class="mb-3">
                <label for="password" class="form-label">Neues Passwort</label>
                <input type="password" class="form-control bg-dark text-light border-0 rounded-3" id="password" placeholder="Neues Passwort eingeben" name="password" required oninput="checkPasswords()" style="background: var(--color-input-bg); color: var(--color-input-text);">
            </div>
            <div class="mb-3">
                <label for="passwordRepeat" class="form-label">Neues Passwort wiederholen</label>
                <input type="password" class="form-control bg-dark text-light border-0 rounded-3" id="passwordRepeat" placeholder="Neues Passwort wiederholen" name="passwordRepeat" required oninput="checkPasswords()" style="background: var(--color-input-bg); color: var(--color-input-text);">
                <small id="passwordMessage" style="display:none;"></small>
                <input type="hidden" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" name="email">
                <input type="hidden" value="<?php echo htmlspecialchars($reset_token ?? '', ENT_QUOTES); ?>" name="reset_token">
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-3" name="reset">Speichern</button>
            <div class="mt-3 text-center">
                <p>Zurück zum <a href="./login.php" class="text-primary">Login</a></p>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        function checkPasswords() {
            var password = document.getElementById('password').value;
            var passwordRepeat = document.getElementById('passwordRepeat').value;
            var message = document.getElementById('passwordMessage');
            if (password !== passwordRepeat) {
                message.style.display = 'block';
                message.textContent = 'Die Passwörter stimmen nicht überein.';
                message.classList.add('text-danger');
                message.classList.remove('text-success');
            } else {
                message.style.display = 'block';
                message.textContent = 'Die Passwörter stimmen überein.';
                message.classList.add('text-success');
                message.classList.remove('text-danger');
            }
        }
    </script>
</body>
</html>