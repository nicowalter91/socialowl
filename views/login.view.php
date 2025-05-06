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
    <style>
        :root {
            --primary: #0d6efd;
            --accent: #eebbc3;
            --text: #232946;
            --bg-light: #f8f9fa;
            --input-bg: #f4f4f6;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--accent) 0%, #d4a5af 50%, var(--primary) 100%);
            overflow: hidden;
        }

        .page-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            width: 100%;
            padding-top: 80px; /* Ensures the container is below the navbar */
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background: white;
            color: var(--text);
            border-radius: 20px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            transition: all 0.3s ease;
            animation: fadeIn 0.6s ease-out;
            z-index: 10;
            margin-top: 0; /* Remove any additional margin */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--text);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-control {
            padding: 0.8rem 1rem;
            background: var(--input-bg);
            border: 2px solid transparent;
            border-radius: 12px;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .btn-login {
            background: var(--primary);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            background-color: rgba(238, 187, 195, 0.3);
            border-left: 4px solid #d9534f;
            color: var(--text);
            border-radius: 8px;
            padding: 0.8rem 1rem;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            filter: blur(60px);
            z-index: -1;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--primary);
            top: -100px;
            right: -150px;
        }

        .shape-2 {
            width: 250px;
            height: 250px;
            background: var(--accent);
            bottom: -100px;
            left: -100px;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: var(--primary);
            bottom: 100px;
            right: -100px;
            opacity: 0.2;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-container {
                border-radius: 12px;
                margin: 0 15px;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../partials/navbar_minimal.php'; ?>

    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>

    <div class="page-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h2>Willkommen zurück</h2>
                <p>Melde dich an, um mit deinen Freunden in Kontakt zu bleiben</p>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-custom mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/controllers/login.php" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="username" class="form-label">
                        <i class="bi bi-person me-1"></i>Benutzername
                    </label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Dein Benutzername" required>
                    <div class="invalid-feedback">
                        Bitte gib deinen Benutzernamen ein.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-shield-lock me-1"></i>Passwort
                    </label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Dein Passwort" required>
                    <div class="invalid-feedback">
                        Bitte gib dein Passwort ein.
                    </div>
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/controllers/reset_mail_send.php" class="text-decoration-none">Passwort vergessen?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Anmelden
                </button>

                <div class="text-center my-4 position-relative">
                    <hr class="border-top border-dark w-100">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-dark">oder</span>
                </div>

                <div class="login-link">
                    <p>Noch kein Konto? <a href="<?= BASE_URL ?>/views/register.view.php">Jetzt registrieren</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>

</html>
