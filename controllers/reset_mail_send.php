<?php
/**
 * Controller: Passwort-Reset ohne Zwischenschritt
 * Vereinfachter Prozess: PIN-Verifikation gefolgt von direkter Passwortänderung
 */

require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
// PHPMailer-Klassen einbinden
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$conn = getDatabaseConnection();
$errorMessage = "";
$successMessage = "";
$showEmailForm = true;
$showPinForm = false;
$showPasswordForm = false;
$userEmail = "";
$username = "";

// Funktion zum Generieren eines 6-stelligen PINs
function generatePin() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Schritt 3: Neues Passwort speichern
if (isset($_POST["reset_password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];
    $userEmail = $email;
    
    // Validierung
    if ($password !== $passwordRepeat) {
        $errorMessage = "Die Passwörter stimmen nicht überein.";
        $showPasswordForm = true;
    } elseif (strlen($password) < 8) {
        $errorMessage = "Das Passwort muss mindestens 8 Zeichen lang sein.";
        $showPasswordForm = true;
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Passwort aktualisieren und reset_pin zurücksetzen
        $stmt = $conn->prepare("UPDATE users SET password = :password, reset_pin = NULL WHERE email = :email");
        $stmt->bindParam(":password", $passwordHash);
        $stmt->bindParam(":email", $email);
        
        if ($stmt->execute()) {
            $successMessage = "Dein Passwort wurde erfolgreich zurückgesetzt! Du wirst in wenigen Sekunden zum Login weitergeleitet.";
            $showEmailForm = false;
            $showPinForm = false;
            $showPasswordForm = false;
        } else {
            $errorMessage = "Fehler beim Zurücksetzen des Passworts. Bitte versuche es später noch einmal.";
            $showPasswordForm = true;
        }
    }
}

// Schritt 2: PIN-Verifikation
if (isset($_POST["verify_pin"])) {
    $email = $_POST["email"];
    $pin = $_POST["pin"];
    $userEmail = $email;
    
    // Prüfen, ob die E-Mail existiert und der PIN korrekt ist
    $checkStmt = $conn->prepare("SELECT reset_pin, username FROM users WHERE email = :email");
    $checkStmt->bindParam(":email", $email);
    $checkStmt->execute();
    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['reset_pin'] === $pin) {
        // PIN ist korrekt, direkt zur Passwort-Änderung weiterleiten
        $username = $user['username'];
        $showEmailForm = false;
        $showPinForm = false;
        $showPasswordForm = true;
        $successMessage = "PIN erfolgreich verifiziert. Du kannst jetzt ein neues Passwort setzen.";
    } else {
        $errorMessage = "Der eingegebene PIN ist nicht korrekt. Bitte versuche es erneut.";
        $showPinForm = true;
    }
}

// Schritt 1: E-Mail-Adresse prüfen und PIN generieren
if (isset($_POST["send_pin"])) {
    $email = trim($_POST["email"]);
    $userEmail = $email;
    
    if (empty($email)) {
        $errorMessage = "Bitte gib eine E-Mail-Adresse ein.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Bitte gib eine gültige E-Mail-Adresse ein.";
    } else {
        // Prüfen, ob die E-Mail existiert
        $checkStmt = $conn->prepare("SELECT username FROM users WHERE email = :email");
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();
        $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            // Aus Sicherheitsgründen immer erfolgreich anzeigen, aber keine E-Mail senden
            $successMessage = "Falls ein Konto mit dieser E-Mail-Adresse existiert, wurde ein PIN zur Verifikation an deine E-Mail-Adresse gesendet. Bitte überprüfe dein Postfach (auch den Spam-Ordner).";
            $showEmailForm = false;
            $showPinForm = true;
        } else {
            // PIN generieren und in Datenbank speichern
            $pin = generatePin();
            $stmt = $conn->prepare("UPDATE users SET reset_pin = :pin WHERE email = :email");
            $stmt->bindParam(":pin", $pin);
            $stmt->bindParam(":email", $email);
            
            if ($stmt->execute()) {
                // E-Mail mit PHPMailer und Mailtrap senden
                $mail = new PHPMailer(true);
                
                try {
                    // Mailtrap-Konfiguration
                    // Looking to send emails in production? Check out our Email API/SMTP product!
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Port = 2525;
                    $mail->Username = 'af44a51e3bcdd5';
                    $mail->Password = '1edfab6a3a2e59';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    
                    // Absender und Empfänger
                    $mail->setFrom('noreply@socialowl.de', 'Social Owl');
                    $mail->addAddress($email, $user['username']);
                    
                    // Das Logo als Anhang hinzufügen
                    $logo_path = __DIR__ . '/../assets/img/Owl_logo.svg';
                    if (file_exists($logo_path)) {
                        $mail->addEmbeddedImage($logo_path, 'logo', 'Owl_logo.svg');
                    }
                    
                    // E-Mail-Inhalt
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Dein Sicherheits-PIN zur Zurücksetzung des Passworts bei Social Owl';
                    $mail->Body = '
                        <html>
                        <head>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    line-height: 1.6;
                                    color: #333;
                                }
                                .container {
                                    max-width: 600px;
                                    margin: 0 auto;
                                    padding: 20px;
                                    border: 1px solid #ddd;
                                    border-radius: 10px;
                                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                                }
                                .header {
                                    background-color: #4F6D7A;
                                    color: white;
                                    padding: 15px;
                                    text-align: center;
                                    border-radius: 8px 8px 0 0;
                                    margin-bottom: 20px;
                                }
                                .logo {
                                    max-width: 150px;
                                    height: auto;
                                    margin-bottom: 10px;
                                }
                                .content {
                                    padding: 0 20px 20px;
                                }
                                .pin-container {
                                    margin: 20px 0;
                                    text-align: center;
                                }
                                .pin {
                                    font-size: 32px;
                                    font-weight: bold;
                                    letter-spacing: 6px;
                                    background-color: #f5f5f5;
                                    padding: 12px 20px;
                                    border-radius: 8px;
                                    display: inline-block;
                                    border: 1px solid #ddd;
                                }
                                .footer {
                                    margin-top: 30px;
                                    text-align: center;
                                    font-size: 0.8em;
                                    color: #777;
                                    border-top: 1px solid #eee;
                                    padding-top: 15px;
                                }
                                .info {
                                    background-color: #e8f4f8;
                                    border-left: 4px solid #4F6D7A;
                                    padding: 10px;
                                    margin: 15px 0;
                                    font-size: 0.9em;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <div class="header">
                                    <h2>Social Owl - Sicherheits-PIN</h2>
                                </div>
                                <div class="content">
                                    <p>Hallo ' . $user['username'] . ',</p>
                                    <p>Du hast eine Anfrage zum Zurücksetzen deines Passworts gestellt. Bitte verwende den folgenden 6-stelligen PIN, um deine Identität zu bestätigen:</p>
                                    
                                    <div class="pin-container">
                                        <div class="pin">' . $pin . '</div>
                                    </div>
                                    
                                    <div class="info">
                                        <p><strong>Wichtig:</strong> Dieser PIN ist aus Sicherheitsgründen nur für 30 Minuten gültig.</p>
                                        <p>Nach erfolgreicher Eingabe des PINs kannst du direkt ein neues Passwort setzen.</p>
                                        <p>Wenn du keine Passwortzurücksetzung angefordert hast, kannst du diese E-Mail ignorieren.</p>
                                    </div>
                                </div>
                                <div class="footer">
                                    <p>Mit freundlichen Grüßen,<br>Das Social Owl Team</p>
                                    <p>&copy; ' . date('Y') . ' Social Owl. Alle Rechte vorbehalten.</p>
                                </div>
                            </div>
                        </body>
                        </html>
                    ';
                    
                    $mail->AltBody = 'Hallo ' . $user['username'] . ',
                    
Du hast eine Anfrage zum Zurücksetzen deines Passworts bei Social Owl gestellt.
Bitte verwende den folgenden 6-stelligen PIN, um deine Identität zu bestätigen:

' . $pin . '

Dieser PIN ist aus Sicherheitsgründen nur für 30 Minuten gültig.
Nach erfolgreicher Eingabe des PINs kannst du direkt ein neues Passwort setzen.

Wenn du keine Passwortzurücksetzung angefordert hast, kannst du diese E-Mail ignorieren.

Mit freundlichen Grüßen,
Das Social Owl Team';
                    
                    $mail->send();
                    $showEmailForm = false;
                    $showPinForm = true;
                    $successMessage = "Ein 6-stelliger PIN wurde an deine E-Mail-Adresse gesendet. Bitte überprüfe dein Postfach (auch den Spam-Ordner) und gib den PIN unten ein.";
                } catch (Exception $e) {
                    $errorMessage = "E-Mail konnte nicht gesendet werden. Bitte versuche es später noch einmal.";
                    if (DEBUG_MODE) {
                        error_log("Mailer Error: " . $mail->ErrorInfo);
                    }
                }
            } else {
                $errorMessage = "Fehler beim Erstellen des PINs. Bitte versuche es später noch einmal.";
            }
        }
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
        .step.active .step-number {
            background: var(--color-primary);
            color: white;
        }
        .step.completed .step-number {
            background: var(--color-primary);
            color: white;
        }
        .step.active .step-label {
            color: var(--color-text);
            font-weight: bold;
        }
        .pin-input-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .pin-input {
            width: 45px;
            height: 55px;
            text-align: center;
            font-size: 24px;
            border: 2px solid var(--color-border);
            border-radius: 8px;
            background: var(--color-input-bg);
            color: var(--color-input-text);
        }
        .pin-input:focus {
            border-color: var(--color-primary);
            outline: none;
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
            <div class="step <?= $showEmailForm ? 'active' : ($showPinForm || $showPasswordForm ? 'completed' : '') ?>">
                <div class="step-number"><?= $showEmailForm ? '1' : '<i class="bi bi-check"></i>' ?></div>
                <div class="step-label">E-Mail eingeben</div>
            </div>
            <div class="step <?= $showPinForm ? 'active' : ($showPasswordForm ? 'completed' : '') ?>">
                <div class="step-number"><?= $showPinForm ? '2' : ($showPasswordForm ? '<i class="bi bi-check"></i>' : '2') ?></div>
                <div class="step-label">PIN verifizieren</div>
            </div>
            <div class="step <?= $showPasswordForm ? 'active' : '' ?>">
                <div class="step-number">3</div>
                <div class="step-label">Neues Passwort</div>
            </div>
        </div>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger border-0 rounded-3 mb-3" role="alert" aria-live="assertive" style="background: var(--color-danger); color: #fff;">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($successMessage && !$showPinForm && !$showPasswordForm): ?>
            <div class="alert alert-success border-0 rounded-3 mb-4" role="alert" aria-live="polite" style="background: var(--color-success); color: #fff;">
                <i class="bi bi-check-circle me-2"></i> <?php echo $successMessage; ?>
            </div>
            
            <div class="text-center">
                <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="60" class="mb-3">
                <p class="mb-4">Dein Passwort wurde erfolgreich zurückgesetzt.</p>
                <div class="d-grid gap-2">
                    <a href="./login.php" class="btn btn-outline-primary rounded-3">Zurück zum Login</a>
                    <div id="redirectNotice" class="mt-2 text-center">
                        <small>Automatische Weiterleitung in <span id="countdown">5</span> Sekunden...</small>
                    </div>
                </div>
            </div>
        <?php elseif ($showPinForm): ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-info border-0 rounded-3 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
                    <i class="bi bi-info-circle me-2"></i> <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="./reset_mail_send.php" id="pinForm">
                <input type="hidden" name="email" value="<?= htmlspecialchars($userEmail) ?>">
                
                <div class="mb-4 text-center">
                    <p>Bitte gib den 6-stelligen PIN ein, der an deine E-Mail gesendet wurde, um fortzufahren.</p>
                </div>
                
                <div class="pin-input-container mb-3">
                    <input type="text" maxlength="1" class="pin-input" data-index="0" inputmode="numeric">
                    <input type="text" maxlength="1" class="pin-input" data-index="1" inputmode="numeric">
                    <input type="text" maxlength="1" class="pin-input" data-index="2" inputmode="numeric">
                    <input type="text" maxlength="1" class="pin-input" data-index="3" inputmode="numeric">
                    <input type="text" maxlength="1" class="pin-input" data-index="4" inputmode="numeric">
                    <input type="text" maxlength="1" class="pin-input" data-index="5" inputmode="numeric">
                    <input type="hidden" name="pin" id="pinComplete">
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-3 border-0" name="verify_pin" id="verifyBtn" disabled>
                        <i class="bi bi-shield-check me-2"></i> PIN verifizieren
                    </button>
                </div>
                
                <div class="mt-3 text-center">
                    <p>PIN nicht erhalten? <a href="./reset_mail_send.php" class="text-primary">Zurück</a></p>
                </div>
            </form>
        <?php elseif ($showPasswordForm): ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-info border-0 rounded-3 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
                    <i class="bi bi-info-circle me-2"></i> <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($username)): ?>
            <div class="alert alert-info rounded-3 border-0 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
                <p class="mb-0"><i class="bi bi-person-circle me-2"></i> Hallo <?= htmlspecialchars($username) ?>,</p>
                <p class="mb-0">bitte wähle ein neues Passwort für dein Konto.</p>
            </div>
            <?php endif; ?>
            
            <form method="post" action="./reset_mail_send.php" id="passwordForm">
                <input type="hidden" name="email" value="<?= htmlspecialchars($userEmail) ?>">
                
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
                        id="passwordRepeat" placeholder="Passwort bestätigen" name="passwordRepeat" 
                        required oninput="checkPasswords();" 
                        style="background: var(--color-input-bg); color: var(--color-input-text);"
                        autocomplete="new-password">
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-3 border-0" name="reset_password" id="resetBtn" disabled>
                        <i class="bi bi-arrow-repeat me-2"></i> Passwort zurücksetzen
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info border-0 rounded-3 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
                <i class="bi bi-info-circle me-2"></i> Gib deine E-Mail-Adresse ein, um einen PIN zur Verifikation zu erhalten.
            </div>
            
            <form method="post" action="./reset_mail_send.php" id="resetForm">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope me-1"></i> E-Mail-Adresse
                    </label>
                    <div class="input-group">
                        <span class="input-group-text border-0 rounded-start" style="background: var(--color-input-bg);">
                            <i class="bi bi-at text-muted"></i>
                        </span>
                        <input type="email" class="form-control border-0 rounded-end" 
                            id="email" placeholder="deine@email.de" name="email" required
                            style="background: var(--color-input-bg); color: var(--color-input-text);" 
                            autocomplete="email" inputmode="email" 
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="invalid-feedback" id="emailFeedback" style="display: none;">
                        Bitte gib eine gültige E-Mail-Adresse ein.
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" id="submitBtn" class="btn btn-primary rounded-3 border-0" name="send_pin">
                        <i class="bi bi-shield-lock me-2"></i> PIN anfordern
                    </button>
                </div>
                
                <div class="mt-3 text-center">
                    <p>Erinnerst du dich an dein Passwort? <a href="./login.php" class="text-primary">Einloggen</a></p>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // E-Mail-Validierung
            const emailInput = document.getElementById('email');
            const emailFeedback = document.getElementById('emailFeedback');
            const submitBtn = document.getElementById('submitBtn');
            const resetForm = document.getElementById('resetForm');
            
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    validateEmail();
                });
                
                // Fokus auf E-Mail-Feld setzen, wenn es existiert
                emailInput.focus();
            }
            
            if (resetForm) {
                resetForm.addEventListener('submit', function(event) {
                    if (!validateEmail()) {
                        event.preventDefault();
                    }
                });
            }
            
            function validateEmail() {
                if (!emailInput) return true;
                
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const isValid = emailRegex.test(email);
                
                if (!isValid && email !== '') {
                    emailInput.classList.add('is-invalid');
                    emailFeedback.style.display = 'block';
                    if (submitBtn) submitBtn.disabled = true;
                    return false;
                } else {
                    emailInput.classList.remove('is-invalid');
                    emailFeedback.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = false;
                    return true;
                }
            }
            
            // PIN-Eingabe Handling
            const pinInputs = document.querySelectorAll('.pin-input');
            const pinComplete = document.getElementById('pinComplete');
            const verifyBtn = document.getElementById('verifyBtn');
            
            if (pinInputs.length > 0) {
                // Fokus auf erstes PIN-Eingabefeld setzen
                pinInputs[0].focus();
                
                pinInputs.forEach(input => {
                    input.addEventListener('keyup', function(e) {
                        // Nur Zahlen erlauben
                        this.value = this.value.replace(/[^0-9]/g, '');
                        
                        // Wenn eine Zahl eingegeben wurde, zum nächsten Feld springen
                        const index = parseInt(this.dataset.index);
                        if (this.value && index < pinInputs.length - 1) {
                            pinInputs[index + 1].focus();
                        }
                        
                        // Vollständigen PIN zusammensetzen
                        updateCompletePin();
                    });
                    
                    input.addEventListener('keydown', function(e) {
                        const index = parseInt(this.dataset.index);
                        
                        // Bei Backspace zum vorherigen Feld springen
                        if (e.key === 'Backspace' && !this.value && index > 0) {
                            pinInputs[index - 1].focus();
                            pinInputs[index - 1].value = '';
                            updateCompletePin();
                        }
                    });
                    
                    // Paste-Handling für den gesamten PIN
                    input.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const paste = (e.clipboardData || window.clipboardData).getData('text');
                        const digits = paste.replace(/[^0-9]/g, '').slice(0, 6).split('');
                        
                        if (digits.length > 0) {
                            pinInputs.forEach((input, i) => {
                                if (i < digits.length) {
                                    input.value = digits[i];
                                }
                            });
                            
                            if (digits.length > 0 && digits.length < 6) {
                                pinInputs[digits.length].focus();
                            }
                            
                            updateCompletePin();
                        }
                    });
                });
                
                function updateCompletePin() {
                    let pin = '';
                    let isComplete = true;
                    
                    pinInputs.forEach(input => {
                        pin += input.value;
                        if (!input.value) {
                            isComplete = false;
                        }
                    });
                    
                    if (pinComplete) pinComplete.value = pin;
                    if (verifyBtn) verifyBtn.disabled = !isComplete;
                }
            }
            
            // Passwort-Funktionalität
            const password = document.getElementById('password');
            const passwordRepeat = document.getElementById('passwordRepeat');
            const passwordMessage = document.getElementById('passwordMessage');
            const resetBtn = document.getElementById('resetBtn');
            const togglePassword = document.getElementById('togglePassword');
            
            if (password && passwordRepeat) {
                // Fokus auf Passwort-Feld setzen
                password.focus();
                
                // Passwort-Überprüfung
                function checkPasswords() {
                    if (password.value === '' || passwordRepeat.value === '') {
                        if (passwordMessage) passwordMessage.style.display = 'none';
                        if (resetBtn) resetBtn.disabled = true;
                        return;
                    }
                    
                    if (passwordMessage) passwordMessage.style.display = 'block';
                    
                    if (password.value !== passwordRepeat.value) {
                        if (passwordMessage) {
                            passwordMessage.textContent = 'Die Passwörter stimmen nicht überein.';
                            passwordMessage.classList.add('text-danger');
                            passwordMessage.classList.remove('text-success');
                        }
                        if (resetBtn) resetBtn.disabled = true;
                    } else if (password.value.length < 8) {
                        if (passwordMessage) {
                            passwordMessage.textContent = 'Das Passwort muss mindestens 8 Zeichen lang sein.';
                            passwordMessage.classList.add('text-danger');
                            passwordMessage.classList.remove('text-success');
                        }
                        if (resetBtn) resetBtn.disabled = true;
                    } else {
                        if (passwordMessage) {
                            passwordMessage.textContent = 'Die Passwörter stimmen überein.';
                            passwordMessage.classList.add('text-success');
                            passwordMessage.classList.remove('text-danger');
                        }
                        if (resetBtn) resetBtn.disabled = false;
                    }
                }
                
                // Passwort-Stärke Funktion
                function checkPasswordStrength() {
                    const strengthBar = document.getElementById('strengthBar');
                    const strengthText = document.getElementById('strengthText');
                    
                    if (!password || !strengthBar || !strengthText) return;
                    
                    // Löscht alle Klassen
                    strengthBar.className = '';
                    
                    if (password.value === '') {
                        strengthBar.style.width = '0%';
                        strengthText.textContent = 'Wähle ein sicheres Passwort';
                        return;
                    }
                    
                    let strength = 0;
                    
                    // Mindestlänge
                    if (password.value.length >= 8) strength += 1;
                    if (password.value.length >= 12) strength += 1;
                    
                    // Komplexität
                    if (password.value.match(/[a-z]+/)) strength += 1;
                    if (password.value.match(/[A-Z]+/)) strength += 1;
                    if (password.value.match(/[0-9]+/)) strength += 1;
                    if (password.value.match(/[^a-zA-Z0-9]+/)) strength += 1;
                    
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
                
                // Event-Listener für Passwort-Felder
                password.addEventListener('input', function() {
                    checkPasswordStrength();
                    checkPasswords();
                });
                
                passwordRepeat.addEventListener('input', checkPasswords);
                
                // Initial auslösen
                checkPasswordStrength();
                
                // Toggle Password Visibility
                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        
                        // Icon ändern
                        const icon = this.querySelector('i');
                        if (type === 'password') {
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        } else {
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        }
                    });
                }
            }
            
            // Countdown für automatische Weiterleitung
            const countdownEl = document.getElementById('countdown');
            if (countdownEl) {
                let seconds = 5;
                const countdown = setInterval(function() {
                    seconds--;
                    countdownEl.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        window.location.href = './login.php';
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>