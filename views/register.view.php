<?php
/**
 * View: Registrierung
 * Zeigt das Registrierungsformular für neue Nutzer an.
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
    <title>Registrieren | Social Owl</title>    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile-image-fix.css" />
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
            
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--accent) 0%, #d4a5af 50%, var(--primary) 100%);
            overflow: hidden;
        }
        
        .register-container {
            width: 100%;
            max-width: 900px; /* Breite erhöht für 2-Spalten-Layout */
            margin: 2rem auto; /* Abstand oben und unten hinzugefügt */
            background: white;
            color: var(--text);
            border-radius: 20px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            padding: 2.5rem;
            transition: all 0.3s ease;
            animation: fadeIn 0.6s ease-out;
            z-index: 10;
            /* Vertikale Zentrierung unterstützen */
            display: flex;
            flex-direction: column;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h2 {
            color: var(--text);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .register-header p {
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
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }
        
        .btn-register:active {
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
        
        /* Step Progress Bar Styling */
        .step-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .step-progress::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            background-color: var(--input-bg);
            z-index: 0;
        }
        
        .step-progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 4px;
            background: var(--primary);
            transition: width 0.3s ease;
            z-index: 1;
        }
        
        .step-item {
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--text);
            position: relative;
        }
        
        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: white;
            border: 3px solid var(--input-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }
        
        .step-title {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.2rem;
            text-align: center;
            color: #666;
            transition: all 0.3s ease;
        }
        
        .step-item.active .step-icon {
            background: var(--primary);
            border-color: transparent;
            color: white;
            transform: scale(1.2);
        }
        
        .step-item.active .step-title {
            color: var(--primary);
            font-weight: 700;
        }
        
        .step-item.completed .step-icon {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .form-step {
            display: none;
            animation: fadeIn 0.6s ease-out;
        }
        
        .form-step.active {
            display: block;
        }
        
        .step-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        
        .btn-prev {
            background-color: #f8f9fa;
            color: var(--text);
            border: 2px solid #e9ecef;
            font-weight: 600;
        }
        
        /* Profil-Bild Upload Styling */
        .profile-upload {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .profile-img-container {
            width: 150px;
            height: 150px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        
        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: white;
            font-size: 0.9rem;
        }
        
        .profile-img-container:hover .profile-img-overlay {
            opacity: 1;
        }
        
        .header-upload {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .header-img-container {
            width: 100%;
            height: 200px;
            margin: 0 auto 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        
        .header-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .header-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: white;
            font-size: 0.9rem;
        }
        
        .header-img-container:hover .header-img-overlay {
            opacity: 1;
        }
        
        .bio-container {
            margin-bottom: 2rem;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }
        
        .password-strength i {
            margin-right: 0.5rem;
        }
        
        /* Styling für die Checkbox anpassen */
        .form-check {
            padding-left: 2rem;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-left: -2rem;
            cursor: pointer;
            border: 2px solid var(--primary);
            position: absolute;
            background-color: white;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            vertical-align: middle;
            outline: none;
            border-radius: 3px;
        }
        
        /* Verbessertes Styling für den Haken in der Checkbox */
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='black' d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 14px;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.95rem;
            margin-left: 0.25rem;
        }

        /* Hervorhebung der Checkbox bei hover */
        .form-check:hover .form-check-input {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }
        
        /* Zurück zum Login Link */
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
        
        /* Zusätzliche Klasse für die Umhüllung des Formulars */
        .page-wrapper {
            width: 100%;
            /* Berücksichtigt die Navbar-Höhe für korrekten Abstand */
            padding: 60px 0 20px 0; /* 60px top padding für die fixed Navbar */
            min-height: calc(100vh - 56px); /* Höhe abzüglich Navbar */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1; /* Füllt den verfügbaren Platz */
        }
        
        @media (max-width: 576px) {
            .register-container {
                border-radius: 12px;
                margin: 0 15px;
                padding: 2rem 1.5rem;
            }
            
            .step-title {
                display: none;
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
        <div class="register-container">
            <div class="d-flex align-items-center position-absolute" style="top: 1rem; left: 1rem;">
                <p class="mb-0 me-1">Bereits ein Konto?</p>
                <a href="login.view.php" class="btn btn-link text-primary p-0"> Zurück zum Login
                </a>
            </div>
            <div class="register-header">
                <h2>Dein Konto erstellen</h2>
                <p>Tritt der Social Owl Community bei und bleibe mit deinen Freunden verbunden</p>
            </div>
            
            <!-- Step Indikator -->
            <div class="step-progress">
                <div class="step-progress-bar" id="stepProgressBar"></div>
                <div class="step-item active" data-step="1">
                    <div class="step-icon">1</div>
                    
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-icon">2</div>
                    
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-icon">3</div>
                    
                </div>
                <div class="step-item" data-step="4">
                    <div class="step-icon">4</div>
                    
                </div>
            </div>
            
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-custom mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?= BASE_URL ?>/controllers/register.php" class="needs-validation" id="registrationForm" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="current_step" id="current_step" value="1">
                
                <!-- Schritt 1: Grundlegende Benutzerdaten -->
                <div class="form-step active" id="step-1">
                    <div class="row">
                        <!-- Linke Spalte -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="firstname" class="form-label">
                                        <i class="bi bi-person me-1"></i>Vorname
                                    </label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" 
                                        placeholder="Dein Vorname" required>
                                    <div class="invalid-feedback">
                                        Bitte gib deinen Vornamen ein.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <label for="lastname" class="form-label">
                                        <i class="bi bi-person me-1"></i>Nachname
                                    </label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" 
                                        placeholder="Dein Nachname" required>
                                    <div class="invalid-feedback">
                                        Bitte gib deinen Nachnamen ein.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="username" class="form-label">
                                    <i class="bi bi-at me-1"></i>Benutzername
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                    placeholder="Wähle einen einzigartigen Benutzernamen" required>
                                <div class="invalid-feedback">
                                    Bitte wähle einen Benutzernamen.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>E-Mail-Adresse
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    placeholder="Deine E-Mail-Adresse" required>
                                <div class="invalid-feedback">
                                    Bitte gib eine gültige E-Mail-Adresse ein.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rechte Spalte -->
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-shield-lock me-1"></i>Passwort
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                    placeholder="Erstelle ein sicheres Passwort" required>
                                <div class="password-strength">
                                    <i class="bi bi-shield-check"></i>
                                    <span id="passwordStrength">Wähle ein starkes Passwort</span>
                                </div>
                                <div class="invalid-feedback">
                                    Bitte erstelle ein Passwort.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="passwordRepeat" class="form-label">
                                    <i class="bi bi-shield-lock me-1"></i>Passwort wiederholen
                                </label>
                                <input type="password" class="form-control" id="passwordRepeat" name="passwordRepeat" 
                                    placeholder="Passwort bestätigen" required>
                                <div class="invalid-feedback">
                                    Die Passwörter stimmen nicht überein.
                                </div>
                            </div>
                            
                            <!-- Progress-Bar für Passwortstärke -->
                            <div class="progress-container mb-4">
                                <div class="progress-bar" id="passwordStrengthBar"></div>
                            </div>
                            
                            <!-- Checkbox für Zustimmung -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    Ich stimme den <a href="#" class="text-decoration-none">Nutzungsbedingungen</a> und <a href="#" class="text-decoration-none">Datenschutzrichtlinien</a> zu
                                </label>
                                <div class="invalid-feedback">
                                    Du musst den Bedingungen zustimmen, um fortzufahren.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Button für nächsten Schritt -->
                    <div class="step-buttons">
                        <div></div> <!-- Platzhalter für die Ausrichtung -->
                        <button type="button" class="btn btn-register next-step" data-step="2">
                            Weiter <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Schritt 2: Profilbild -->
                <div class="form-step" id="step-2">
                    <div class="profile-upload">
                        <h4>Wähle dein Profilbild</h4>
                        <p class="text-muted">Lade ein Bild hoch, das dich repräsentiert (max. 5 MB)</p>
                        
                        <div class="profile-img-container" id="profileImgContainer">
                            <img src="<?= BASE_URL ?>/assets/uploads/profil.png" alt="Profilbild" id="profilePreview">
                            <div class="profile-img-overlay">
                                <i class="bi bi-camera fs-4 mb-2"></i>
                                <span>Bild auswählen</span>
                            </div>
                        </div>
                        
                        <input type="file" name="profile_image" id="profileImageInput" class="d-none" accept="image/*">
                        <small class="text-muted d-block mb-4">Klicke auf das Bild um ein neues hochzuladen</small>
                        <button type="button" id="skipProfileImage" class="btn btn-sm btn-light">Diesen Schritt überspringen</button>
                    </div>
                    
                    <div class="step-buttons">
                        <button type="button" class="btn btn-prev prev-step" data-step="1">
                            <i class="bi bi-arrow-left me-2"></i> Zurück
                        </button>
                        <button type="button" class="btn btn-register next-step" data-step="3">
                            Weiter <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Schritt 3: Header-Bild -->
                <div class="form-step" id="step-3">
                    <div class="header-upload">
                        <h4>Wähle dein Header-Bild</h4>
                        <p class="text-muted">Lade ein Titelbild für dein Profil hoch (max. 5 MB)</p>
                        
                        <div class="header-img-container" id="headerImgContainer">
                            <img src="<?= BASE_URL ?>/assets/uploads/default_header.png" alt="Header-Bild" id="headerPreview">
                            <div class="header-img-overlay">
                                <i class="bi bi-image fs-4 mb-2"></i>
                                <span>Bild auswählen</span>
                            </div>
                        </div>
                        
                        <input type="file" name="header_image" id="headerImageInput" class="d-none" accept="image/*">
                        <small class="text-muted d-block mb-4">Klicke auf das Bild um ein neues hochzuladen</small>
                        <button type="button" id="skipHeaderImage" class="btn btn-sm btn-light">Diesen Schritt überspringen</button>
                    </div>
                    
                    <div class="step-buttons">
                        <button type="button" class="btn btn-prev prev-step" data-step="2">
                            <i class="bi bi-arrow-left me-2"></i> Zurück
                        </button>
                        <button type="button" class="btn btn-register next-step" data-step="4">
                            Weiter <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Schritt 4: Bio -->
                <div class="form-step" id="step-4">
                    <div class="bio-container">
                        <h4>Über dich</h4>
                        <p class="text-muted">Erzähle etwas über dich (max. 160 Zeichen)</p>
                        
                        <div class="mb-4">
                            <label for="bio" class="form-label">
                                <i class="bi bi-file-text me-1"></i>Bio
                            </label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                placeholder="Beschreibe dich kurz..." maxlength="160"></textarea>
                            <div class="d-flex justify-content-end">
                                <small class="text-muted mt-2"><span id="bioCharCount">0</span>/160</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-buttons">
                        <button type="button" class="btn btn-prev prev-step" data-step="3">
                            <i class="bi bi-arrow-left me-2"></i> Zurück
                        </button>
                        <button type="submit" class="btn btn-register" name="register_submit">
                            <i class="bi bi-person-plus me-2"></i>Registrieren
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="<?= BASE_URL ?>/assets/js/script.js"></script>
    <script>
        // Step-by-Step Formular Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const steps = document.querySelectorAll('.form-step');
            const stepItems = document.querySelectorAll('.step-item');
            const nextButtons = document.querySelectorAll('.next-step');
            const prevButtons = document.querySelectorAll('.prev-step');
            const progressBar = document.getElementById('stepProgressBar');
            const currentStepInput = document.getElementById('current_step');
            let currentStep = 1;
            
            // Fortschrittsbalken aktualisieren
            function updateProgressBar() {
                const percent = ((currentStep - 1) / (stepItems.length - 1)) * 100;
                progressBar.style.width = `${percent}%`;
            }
            
            // Steps anzeigen/verstecken und Klassen für aktive Steps setzen
            function showStep(stepNumber) {
                steps.forEach(step => {
                    step.classList.remove('active');
                });
                stepItems.forEach(item => {
                    item.classList.remove('active');
                    const itemStep = parseInt(item.dataset.step);
                    if (itemStep < currentStep) {
                        item.classList.add('completed');
                    } else if (itemStep === currentStep) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('completed');
                    }
                });
                
                document.getElementById(`step-${stepNumber}`).classList.add('active');
                currentStepInput.value = stepNumber;
                updateProgressBar();
            }
            
            // Nächster Schritt
            nextButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = parseInt(this.dataset.step);
                    
                    // Validierung für Schritt 1
                    if (currentStep === 1) {
                        const requiredFields = document.querySelectorAll('#step-1 [required]');
                        let valid = true;
                        
                        requiredFields.forEach(field => {
                            if (!field.checkValidity()) {
                                field.reportValidity();
                                valid = false;
                            }
                        });
                        
                        if (!valid) return;
                        
                        // Überprüfung der Passwörter
                        const password = document.getElementById('password').value;
                        const passwordRepeat = document.getElementById('passwordRepeat').value;
                        if (password !== passwordRepeat) {
                            document.getElementById('passwordRepeat').setCustomValidity('Passwörter stimmen nicht überein');
                            document.getElementById('passwordRepeat').reportValidity();
                            return;
                        }
                    }
                    
                    currentStep = nextStep;
                    showStep(currentStep);
                });
            });
            
            // Vorheriger Schritt
            prevButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentStep = parseInt(this.dataset.step);
                    showStep(currentStep);
                });
            });
            
            // Profilbild Funktionalität
            const profileImgContainer = document.getElementById('profileImgContainer');
            const profileImageInput = document.getElementById('profileImageInput');
            const profilePreview = document.getElementById('profilePreview');
            
            profileImgContainer.addEventListener('click', function() {
                profileImageInput.click();
            });
            
            profileImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Skip Profilbild
            document.getElementById('skipProfileImage').addEventListener('click', function() {
                currentStep = 3;
                showStep(currentStep);
            });
            
            // Header-Bild Funktionalität
            const headerImgContainer = document.getElementById('headerImgContainer');
            const headerImageInput = document.getElementById('headerImageInput');
            const headerPreview = document.getElementById('headerPreview');
            
            headerImgContainer.addEventListener('click', function() {
                headerImageInput.click();
            });
            
            headerImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        headerPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Skip Header-Bild
            document.getElementById('skipHeaderImage').addEventListener('click', function() {
                currentStep = 4;
                showStep(currentStep);
            });
            
            // Bio Zeichenzähler
            const bioTextarea = document.getElementById('bio');
            const bioCharCount = document.getElementById('bioCharCount');
            
            bioTextarea.addEventListener('input', function() {
                const count = this.value.length;
                bioCharCount.textContent = count;
                
                if (count >= 160) {
                    bioCharCount.style.color = '#dc3545';
                } else {
                    bioCharCount.style.color = '';
                }
            });
            
            // Formularvalidierung
            form.addEventListener('submit', function(event) {
                const requiredFields = document.querySelectorAll(`#step-${currentStep} [required]`);
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        valid = false;
                    }
                });
                
                if (!valid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            });
            
            // Passwort-Stärke Anzeige
            const passwordInput = document.getElementById('password');
            const strengthText = document.getElementById('passwordStrength');
            const strengthBar = document.getElementById('passwordStrengthBar');
            
            if (passwordInput && strengthText && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    
                    // Basis-Regeln für Passwortstärke
                    if (password.length >= 8) strength += 1;
                    if (/[A-Z]/.test(password)) strength += 1;
                    if (/[0-9]/.test(password)) strength += 1;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                    
                    // Fortschrittsbalken aktualisieren
                    strengthBar.style.transform = `scaleX(${strength / 4})`;
                    
                    // Text und Farbe aktualisieren
                    switch (strength) {
                        case 0:
                            strengthText.textContent = 'Sehr schwach';
                            strengthText.style.color = '#dc3545';
                            break;
                        case 1:
                            strengthText.textContent = 'Schwach';
                            strengthText.style.color = '#dc3545';
                            break;
                        case 2:
                            strengthText.textContent = 'Mittel';
                            strengthText.style.color = '#fd7e14';
                            break;
                        case 3:
                            strengthText.textContent = 'Stark';
                            strengthText.style.color = '#198754';
                            break;
                        case 4:
                            strengthText.textContent = 'Sehr stark';
                            strengthText.style.color = '#198754';
                            break;
                    }
                });
            }
            
            // Initialisierung des Fortschrittbalkens
            updateProgressBar();
        });
    </script>
</body>

</html>
