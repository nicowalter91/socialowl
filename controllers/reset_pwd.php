<?php
/**
 * Controller: Passwort zurücksetzen
 * Prüft Token, setzt neues Passwort und zeigt Statusmeldungen an.
 */
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
$conn = getDatabaseConnection(); // Datenbankverbindung initialisieren
$errorMessage = "";
$successMessage = "";

// E-Mail und Token aus der URL holen
$tokenValid = false;
if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    $email = $_GET["email"];
    $reset_token = $_GET['reset_token'];
    // Token-Validierung aus DB
    $stmt = $conn->prepare("SELECT reset_token, username FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['reset_token'] === $reset_token && !empty($reset_token)) {
        $tokenValid = true;
        $username = $row['username']; // Username für personalisierte Begrüßung
    }
} else {
    // Wenn kein Token und keine E-Mail in der URL vorhanden sind, Zugriff verweigern
    header("Location: login.php");
    exit();
}

// Wenn der Token ungültig ist, Zugriff verweigern
if (!$tokenValid) {
    header("Location: login.php?error=invalid_token");
    exit();
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
    } else if (strlen($password) < 8) {
        $errorMessage = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $result = updatePassword($passwordHash, $email);
            if ($result) {
                $successMessage = "Dein Passwort wurde erfolgreich zurückgesetzt! Du wirst in wenigen Sekunden zum Login weitergeleitet.";
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
    <style>
        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }
        .progress-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--color-border);
            z-index: 0;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            color: var(--color-text-muted);
            font-weight: bold;
        }
        .step-label {
            font-size: 12px;
            color: var(--color-text-muted);
        }
        .step.completed .step-number,
        .step.active .step-number {
            background: var(--color-primary);
            color: white;
        }
        .step.active .step-label {
            color: var(--color-text);
            font-weight: bold;
        }
        .password-strength-meter {
            height: 5px;
            background-color: #e0e0e0;
            border-radius: 3px;
            margin-top: 10px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .password-strength-meter div {
            height: 100%;
            border-radius: 3px;
            transition: width 0.5s ease;
        }
        .password-strength-text {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .very-weak { width: 20%; background-color: #f25f5c; }
        .weak { width: 40%; background-color: #ffb400; }
        .medium { width: 60%; background-color: #ffd000; }
        .strong { width: 80%; background-color: #90be6d; }
        .very-strong { width: 100%; background-color: #43aa8b; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 400px; margin-top: 80px; background: unset; color: unset;">
        <h2 class="text-center mb-3">Passwort zurücksetzen</h2>
        
        <div class="progress-indicator mb-4">
            <div class="step completed">
                <div class="step-number"><i class="bi bi-check"></i></div>
                <div class="step-label">Link anfordern</div>
            </div>
            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-label">Neues Passwort</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Fertig</div>
            </div>
        </div>
        
        <?php if (isset($username)): ?>
        <div class="alert alert-info rounded-3 border-0 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
            <p class="mb-0"><i class="bi bi-person-circle me-2"></i> Hallo <?= htmlspecialchars($username) ?>,</p>
            <p class="mb-0">bitte wähle ein neues Passwort für dein Konto.</p>
        </div>
        <?php endif; ?>
        
        <div class="alert alert-info rounded-3 border-0 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
            <small><i class="bi bi-clock-history me-2"></i> Dieser Link ist 24 Stunden gültig.</small>
        </div>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger border-0 rounded-3 mb-3" role="alert" aria-live="assertive" style="background: var(--color-danger); color: #fff;">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($successMessage): ?>
            <div id="successAlert" class="alert alert-success border-0 rounded-3 mb-3" role="alert" aria-live="polite" style="background: var(--color-success); color: #fff;">
                <i class="bi bi-check-circle me-2"></i> <?php echo $successMessage; ?>
                <div class="mt-2 text-center">
                    <span>Weiterleitung in <span id="countdown">5</span> Sekunden...</span>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="post" action="./reset_pwd.php<?= isset($_GET['email']) && isset($_GET['reset_token']) ? '?email='.urlencode($_GET['email']).'&reset_token='.urlencode($_GET['reset_token']) : '' ?>" id="passwordResetForm">
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-shield-lock me-1"></i> Neues Passwort
                </label>
                <div class="input-group mb-2">
                    <input type="password" class="form-control rounded-start border-0" 
                        id="password" placeholder="Neues Passwort eingeben" name="password" 
                        required oninput="checkPasswords(); checkPasswordStrength();" 
                        style="background: var(--color-input-bg); color: var(--color-input-text);"
                        aria-describedby="passwordHelp"
                        autocomplete="new-password">
                    <button class="btn border-0 rounded-end" type="button" id="togglePassword" 
                        style="background: var(--color-input-bg);">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
                
                <!-- Passwort-Stärke Anzeige -->
                <div class="password-strength-meter">
                    <div id="strengthBar"></div>
                </div>
                <div class="password-strength-text" id="strengthText">Wähle ein sicheres Passwort</div>
            </div>
            
            <div class="mb-4">
                <label for="passwordRepeat" class="form-label">
                    <i class="bi bi-shield-check me-1"></i> Passwort bestätigen
                </label>
                <input type="password" class="form-control border-0 rounded-3" 
                    id="passwordRepeat" placeholder="Neues Passwort wiederholen" 
                    name="passwordRepeat" required oninput="checkPasswords()" 
                    style="background: var(--color-input-bg); color: var(--color-input-text);"
                    autocomplete="new-password">
                <small id="passwordMessage" style="display:none;"></small>
                <input type="hidden" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>" name="email">
                <input type="hidden" value="<?php echo htmlspecialchars($reset_token ?? '', ENT_QUOTES); ?>" name="reset_token">
            </div>
            
            <?php if ($successMessage): ?>
                <button disabled class="btn btn-success w-100 rounded-3 border-0">
                    <i class="bi bi-check-circle me-2"></i> Passwort geändert
                </button>
            <?php else: ?>
                <button type="submit" id="submitBtn" class="btn btn-primary w-100 rounded-3 border-0" name="reset">
                    Passwort speichern
                </button>
            <?php endif; ?>
            
            <div class="mt-3 text-center">
                <p>Zurück zum <a href="./login.php" class="text-primary">Login</a></p>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        // Funktion zum Prüfen der Passwörter
        function checkPasswords() {
            var password = document.getElementById('password').value;
            var passwordRepeat = document.getElementById('passwordRepeat').value;
            var message = document.getElementById('passwordMessage');
            var submitBtn = document.getElementById('submitBtn');
            
            if (password === '' || passwordRepeat === '') {
                message.style.display = 'none';
                return;
            }
            
            message.style.display = 'block';
            
            if (password !== passwordRepeat) {
                message.textContent = 'Die Passwörter stimmen nicht überein.';
                message.classList.add('text-danger');
                message.classList.remove('text-success');
                if (submitBtn) submitBtn.disabled = true;
            } else {
                message.textContent = 'Die Passwörter stimmen überein.';
                message.classList.add('text-success');
                message.classList.remove('text-danger');
                if (submitBtn) submitBtn.disabled = false;
            }
        }
        
        // Funktion zur Überprüfung der Passwortstärke
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            // Löscht alle Klassen
            strengthBar.className = '';
            
            if (password === '') {
                strengthBar.style.width = '0%';
                strengthText.textContent = 'Wähle ein sicheres Passwort';
                return;
            }
            
            let strength = 0;
            
            // Mindestlänge
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Komplexität
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
            
            // Bewertung und Anzeige
            switch(true) {
                case (strength === 0):
                    strengthBar.classList.add('very-weak');
                    strengthText.textContent = 'Sehr schwach';
                    break;
                case (strength <= 2):
                    strengthBar.classList.add('weak');
                    strengthText.textContent = 'Schwach';
                    break;
                case (strength <= 3):
                    strengthBar.classList.add('medium');
                    strengthText.textContent = 'Mittel';
                    break;
                case (strength <= 5):
                    strengthBar.classList.add('strong');
                    strengthText.textContent = 'Stark';
                    break;
                case (strength > 5):
                    strengthBar.classList.add('very-strong');
                    strengthText.textContent = 'Sehr stark';
                    break;
            }
        }
        
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
        
        <?php if ($successMessage): ?>
        // Countdown für Weiterleitung
        let count = 5;
        const countdown = document.getElementById('countdown');
        const timer = setInterval(function() {
            count--;
            countdown.textContent = count;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = './login.php';
            }
        }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>