<?php
/**
 * Controller: Passwort-Reset-Link anfordern
 * Erstellt einen Reset-Token und sendet ihn per E-Mail via Mailtrap.
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

if (isset($_POST["reset"])) {
    $email = trim($_POST["email"]);
    
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
            // Aus Sicherheitsgründen keine Information darüber geben, ob die E-Mail existiert
            $successMessage = "Falls ein Konto mit dieser E-Mail-Adresse existiert, wurde eine E-Mail mit Anweisungen zum Zurücksetzen des Passworts versendet. Bitte überprüfe dein Postfach (auch den Spam-Ordner).";
        } else {
            $reset_token = md5(rand() . time());
            $stmt = $conn->prepare("UPDATE users SET reset_token=:reset_token WHERE email=:email");
            $stmt->bindParam(":reset_token", $reset_token);
            $stmt->bindParam(":email", $email);
            
            if ($stmt->execute()) {
                // Vollständigen Reset-Link mit localhost erstellen
                $reset_link = "http://localhost/Social_App/controllers/reset_pwd.php";
                $reset_link .= "?email=" . urlencode($email);
                $reset_link .= "&reset_token=" . urlencode($reset_token);
                
                // E-Mail mit PHPMailer und Mailtrap senden
                $mail = new PHPMailer(true);
                
                try {
                    // Mailtrap-Konfiguration mit Ihren Zugangsdaten
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'af44a51e3bcdd5';
                    $mail->Password = '1edfab6a3a2e59';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 2525;
                    
                    // Absender und Empfänger
                    $mail->setFrom('noreply@socialowl.de', 'Social Owl');
                    $mail->addAddress($email, $user['username']);
                    
                    // Absolute URL für den Server erstellen
                    $server_url = "http://localhost/Social_App";
                    
                    // Das Logo als Anhang hinzufügen und mit CID im HTML referenzieren
                    $logo_path = __DIR__ . '/../assets/img/Owl_logo.svg';
                    if (file_exists($logo_path)) {
                        $mail->addEmbeddedImage($logo_path, 'logo', 'Owl_logo.svg');
                    }
                    
                    // E-Mail-Inhalt
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Dein Link zum Zurücksetzen des Passworts bei Social Owl';
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
                                .button {
                                    display: inline-block;
                                    padding: 12px 25px;
                                    margin: 20px 0;
                                    background-color: #4F6D7A;
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 8px;
                                    font-weight: bold;
                                    text-align: center;
                                }
                                .link-container {
                                    margin: 15px 0;
                                    padding: 10px;
                                    background-color: #f5f5f5;
                                    border-radius: 5px;
                                    word-break: break-all;
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
                                    <h2>Social Owl - Passwort zurücksetzen</h2>
                                </div>
                                <div class="content">
                                    <p>Hallo ' . $user['username'] . ',</p>
                                    <p>Du hast eine Anfrage zum Zurücksetzen deines Passworts gestellt. Klicke auf den folgenden Button, um ein neues Passwort zu erstellen:</p>
                                    
                                    <div style="text-align: center;">
                                        <a href="' . $reset_link . '" class="button">Passwort zurücksetzen</a>
                                    </div>
                                    
                                    <p>Oder kopiere diese URL in deinen Browser:</p>
                                    <div class="link-container">
                                        <a href="' . $reset_link . '">' . $reset_link . '</a>
                                    </div>
                                    
                                    <div class="info">
                                        <p><strong>Wichtig:</strong> Dieser Link ist 24 Stunden gültig.</p>
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
Bitte klicke auf den folgenden Link oder kopiere ihn in deinen Browser, um ein neues Passwort zu erstellen:
' . $reset_link . '

Dieser Link ist 24 Stunden gültig.

Wenn du keine Passwortzurücksetzung angefordert hast, kannst du diese E-Mail ignorieren.

Mit freundlichen Grüßen,
Das Social Owl Team';
                    
                    $mail->send();
                    $successMessage = "Eine E-Mail mit dem Link zum Zurücksetzen des Passworts wurde an deine E-Mail-Adresse gesendet. Bitte überprüfe dein Postfach (auch den Spam-Ordner).";
                } catch (Exception $e) {
                    $errorMessage = "E-Mail konnte nicht gesendet werden. Bitte versuche es später noch einmal.";
                    if (DEBUG_MODE) {
                        error_log("Mailer Error: " . $mail->ErrorInfo);
                    }
                }
            } else {
                $errorMessage = "Fehler beim Erstellen des Reset-Links. Bitte versuche es später noch einmal.";
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
        .step.active .step-label {
            color: var(--color-text);
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>
    <div class="login-container p-4 rounded-4 shadow theme-card" style="width: 100%; max-width: 400px; margin-top: 80px; background: unset; color: unset;">
        <h2 class="text-center mb-3">Passwort zurücksetzen</h2>
        
        <div class="progress-indicator mb-4">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Link anfordern</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">Neues Passwort</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Fertig</div>
            </div>
        </div>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger border-0 rounded-3 mb-3" role="alert" aria-live="assertive" style="background: var(--color-danger); color: #fff;">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($successMessage): ?>
            <div class="alert alert-success border-0 rounded-3 mb-4" role="alert" aria-live="polite" style="background: var(--color-success); color: #fff;">
                <i class="bi bi-check-circle me-2"></i> <?php echo $successMessage; ?>
            </div>
            
            <div class="text-center">
                <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="60" class="mb-3">
                <p class="mb-4">Wir haben dir eine E-Mail mit weiteren Anweisungen gesendet.</p>
                <div class="d-grid gap-2">
                    <a href="./login.php" class="btn btn-outline-primary rounded-3">Zurück zum Login</a>
                    <button type="button" id="resendBtn" class="btn btn-outline-secondary rounded-3 mt-2" disabled>
                        <span id="resendText">Erneut senden in <span id="countdown">60</span>s</span>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 rounded-3 mb-3" style="background: var(--color-hover-bg); color: var(--color-text);">
                <i class="bi bi-info-circle me-2"></i> Gib deine E-Mail-Adresse ein, um einen Link zum Zurücksetzen deines Passworts zu erhalten.
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
                    <button type="submit" id="submitBtn" class="btn btn-primary rounded-3 border-0" name="reset">
                        <i class="bi bi-envelope me-2"></i> Link zum Zurücksetzen senden
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
            
            // Countdown für "Erneut senden" Button
            const resendBtn = document.getElementById('resendBtn');
            const countdownEl = document.getElementById('countdown');
            
            if (resendBtn && countdownEl) {
                let seconds = 60;
                const countdown = setInterval(function() {
                    seconds--;
                    countdownEl.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        resendBtn.disabled = false;
                        document.getElementById('resendText').textContent = "Erneut senden";
                        
                        // Event-Listener hinzufügen, um das Formular erneut anzuzeigen
                        resendBtn.addEventListener('click', function() {
                            window.location.href = './reset_mail_send.php';
                        });
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>