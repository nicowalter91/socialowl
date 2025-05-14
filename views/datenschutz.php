<?php
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Datenschutz - Social Owl</title>
  <meta name="description" content="Datenschutzerklärung von Social Owl - Informationen zum Umgang mit Ihren Daten.">
  <meta name="theme-color" content="#232946" />

  <!-- CSS -->  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile-image-fix.css">
  <link rel="icon" href="<?= BASE_URL ?>/assets/img/Owl_logo.svg" type="image/x-icon">
  <style>
    /* Zusätzliche Website-Styles */
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .header-section {
      background-color: #232946;
      color: white;
      padding: 50px 0;
      position: relative;
    }
    
    .header-section::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(120deg, rgba(35, 41, 70, 0.92) 60%, rgba(13, 110, 253, 0.85) 100%);
      z-index: 0;
    }
    
    .header-content {
      position: relative;
      z-index: 1;
    }
    
    .content-section {
      padding: 50px 0;
      background-color: #f9f9f9;
    }
    
    .content-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      padding: 30px;
      margin-bottom: 30px;
    }
    
    .content-card h2 {
      color: #232946;
      margin-bottom: 20px;
      font-weight: bold;
    }
    
    .content-card h3 {
      color: #0d6efd;
      margin-top: 25px;
      margin-bottom: 15px;
    }
    
    .privacy-warning {
      background-color: #fff3cd;
      border-left: 5px solid #ffc107;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
    
    .info-box {
      background-color: #e7f5ff;
      border-left: 5px solid #0d6efd;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
    
    .table-of-contents {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 30px;
    }
    
    .table-of-contents ul {
      margin-bottom: 0;
    }
    
    .table-of-contents a {
      text-decoration: none;
      color: #0d6efd;
    }
    
    .table-of-contents a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: #232946;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/views/website.php">
        <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="36" class="me-2 align-text-top">Social Owl
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#preview">App-Vorschau</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#gallery">Galerie</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#team">Team</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#faq">FAQ</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/views/website.php#kontakt">Kontakt</a></li>
        </ul>
        <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <a href="<?= BASE_URL ?>/views/login.view.php" class="btn btn-outline-light me-2" style="border-color: #eebbc3; color: #eebbc3;">Login</a>
          <a href="<?= BASE_URL ?>/views/register.view.php" class="btn" style="background: #eebbc3; color: #232946; font-weight: bold;">Registrieren</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <section class="header-section">
    <div class="container header-content">
      <div class="row">
        <div class="col-md-12 text-center">
          <h1 class="display-4 fw-bold" style="color: #eebbc3;">Datenschutz</h1>
          <p class="lead">Informationen zum Umgang mit Ihren Daten bei Social Owl</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Content -->
  <section class="content-section">
    <div class="container">
      <div class="content-card">
        <div class="row">
          <div class="col-md-12">
            <div class="bg-danger text-white p-4 rounded mb-4">
              <h3 class="text-white mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Wichtiger Hinweis für alle Benutzer</h3>
              <p class="mb-0 fs-5">Social Owl ist ein <strong>Ausbildungsprojekt für Fachinformatiker</strong> und dient ausschließlich zu Lern- und Übungszwecken. Bitte verwenden Sie <strong>nur Testdaten</strong> und keine realen personenbezogenen Daten. In unserer Datenbank werden ausschließlich Testdaten gespeichert und keine Echtdaten. Diese Plattform ist nicht für den produktiven Einsatz vorgesehen.</p>
            </div>
            
            <h2>Datenschutzerklärung</h2>
            
            <div class="table-of-contents">
              <p class="fw-bold mb-2">Inhaltsverzeichnis:</p>
              <ul>
                <li><a href="#ausbildungsprojekt">Ausbildungsprojekt & Testdaten</a></li>
                <li><a href="#allgemein">Allgemeine Informationen</a></li>
                <li><a href="#daten">Welche Daten wir erheben</a></li>
                <li><a href="#cookies">Cookies und Tracking</a></li>
                <li><a href="#rechte">Ihre Rechte</a></li>
                <li><a href="#sicherheit">Datensicherheit</a></li>
                <li><a href="#kontakt">Kontakt zum Datenschutz</a></li>
              </ul>
            </div>
            
            <h3 id="ausbildungsprojekt">Ausbildungsprojekt & Testdaten</h3>
            <div class="info-box">
              <p><strong>Social Owl ist ein Ausbildungsprojekt für angehende Fachinformatiker und dient ausschließlich zu Bildungszwecken.</strong></p>
              <p>Wir möchten Sie ausdrücklich darauf hinweisen, dass:</p>
              <ul>
                <li>alle von Ihnen eingegebenen Daten nur für Testzwecke verwendet werden sollten</li>
                <li>Sie keine realen personenbezogenen Daten eingeben sollten</li>
                <li>unsere Datenbank ausschließlich für Testdaten konzipiert ist</li>
                <li>keine Garantie für die Sicherheit oder Vertraulichkeit der Daten gegeben werden kann</li>
                <li>alle Daten regelmäßig und ohne Vorankündigung gelöscht werden können</li>
              </ul>
              <p class="mb-0">Für einen verantwortungsvollen Umgang mit der Plattform empfehlen wir Ihnen, fiktive Namen, E-Mail-Adressen und sonstige Angaben zu verwenden.</p>
            </div>
            
            <h3 id="allgemein">Allgemeine Informationen</h3>
            <p>In dieser Datenschutzerklärung informieren wir Sie über die Verarbeitung personenbezogener Daten im Rahmen unseres Ausbildungsprojekts "Social Owl". Diese Erklärung dient rein zu Bildungszwecken und stellt die Umsetzung einer fiktiven Datenschutzerklärung für ein soziales Netzwerk dar.</p>
            
            <p>Verantwortlich für die Datenverarbeitung im Sinne des fiktiven Projekts ist:</p>
            <p>
              Social Owl GmbH (fiktiv)<br>
              Veilhofstraße 34-36<br>
              90489 Nürnberg<br>
              Deutschland<br>
              E-Mail: datenschutz@social-owl.com
            </p>
            
            <h3 id="daten">Welche Daten wir erheben</h3>
            <p>Im Rahmen des Ausbildungsprojekts werden folgende Arten von Daten erhoben und gespeichert:</p>
            
            <h4>Bei der Registrierung:</h4>
            <ul>
              <li>Benutzername (fiktiv)</li>
              <li>E-Mail-Adresse (fiktiv)</li>
              <li>Passwort (verschlüsselt)</li>
            </ul>
            
            <h4>Bei der Profilgestaltung (optional):</h4>
            <ul>
              <li>Profilbild</li>
              <li>Headerbild</li>
              <li>Biografische Informationen</li>
              <li>Interessen und Vorlieben</li>
            </ul>
            
            <h4>Bei der Nutzung der Plattform:</h4>
            <ul>
              <li>Beiträge und Kommentare</li>
              <li>Likes und andere Reaktionen</li>
              <li>Verbindungen mit anderen Benutzern</li>
              <li>Nachrichten zwischen Benutzern</li>
              <li>IP-Adresse (zu Testzwecken)</li>
              <li>Geräteinformationen (zu Testzwecken)</li>
            </ul>
            
            <h3 id="cookies">Cookies und Tracking</h3>
            <p>Unsere Plattform verwendet zu Lernzwecken verschiedene Arten von Cookies und ähnlichen Technologien:</p>
            
            <h4>Technisch notwendige Cookies:</h4>
            <ul>
              <li>Session-Cookies zur Benutzerauthentifizierung</li>
              <li>Cookies für Sicherheitsfunktionen</li>
            </ul>
            
            <h4>Funktionale Cookies (optional):</h4>
            <ul>
              <li>Cookies zur Speicherung von Benutzereinstellungen</li>
              <li>Cookies für personalisierte Inhalte</li>
            </ul>
            
            <h4>Analyse-Cookies (zu Lernzwecken):</h4>
            <ul>
              <li>Cookies zur Analyse des Nutzerverhaltens</li>
              <li>Cookies zur Optimierung der Plattform</li>
            </ul>
            
            <p>Sie können die Verwendung von Cookies in Ihrem Browser jederzeit deaktivieren oder einschränken. Bitte beachten Sie, dass dadurch einige Funktionen der Plattform möglicherweise nicht mehr verfügbar sind.</p>
            
            <h3 id="rechte">Ihre Rechte</h3>
            <p>Im Rahmen des fiktiven Projekts implementieren wir folgende Datenschutzrechte:</p>
            
            <ul>
              <li><strong>Recht auf Auskunft:</strong> Sie können eine Bestätigung darüber verlangen, ob und welche Daten über Sie gespeichert sind.</li>
              <li><strong>Recht auf Berichtigung:</strong> Sie können die Berichtigung unrichtiger oder die Vervollständigung unvollständiger Daten verlangen.</li>
              <li><strong>Recht auf Löschung:</strong> Sie können die Löschung Ihrer Daten verlangen.</li>
              <li><strong>Recht auf Einschränkung der Verarbeitung:</strong> Sie können verlangen, dass die Verarbeitung Ihrer Daten eingeschränkt wird.</li>
              <li><strong>Recht auf Datenübertragbarkeit:</strong> Sie können verlangen, Ihre Daten in einem strukturierten, gängigen und maschinenlesbaren Format zu erhalten.</li>
              <li><strong>Widerspruchsrecht:</strong> Sie können der Verarbeitung Ihrer Daten widersprechen.</li>
            </ul>
            
            <p>Zur Ausübung Ihrer Rechte kontaktieren Sie uns bitte unter der oben angegebenen E-Mail-Adresse.</p>
            
            <h3 id="sicherheit">Datensicherheit</h3>
            <p>Im Rahmen des Ausbildungsprojekts erproben wir verschiedene Sicherheitsmaßnahmen zum Schutz von Daten, darunter:</p>
            
            <ul>
              <li>Verschlüsselung von Passwörtern (Hash-Funktionen)</li>
              <li>SSL/TLS-Verschlüsselung für die Datenübertragung</li>
              <li>Zugriffsbeschränkungen für Datenbankzugriffe</li>
              <li>Implementierung von Best Practices für die Webentwicklung</li>
            </ul>
            
            <p>Da es sich jedoch um ein Ausbildungsprojekt handelt, bitten wir Sie, keine sensiblen Echtdaten zu verwenden, da die Sicherheitsmaßnahmen zu Lernzwecken implementiert sind und keinen produktiven Sicherheitsstandards entsprechen.</p>
            
            <h3 id="kontakt">Kontakt zum Datenschutz</h3>
            <p>Bei Fragen zum Datenschutz im Rahmen des Ausbildungsprojekts wenden Sie sich bitte an:</p>
            
            <p>
              Datenschutzteam Social Owl<br>
              E-Mail: datenschutz@social-owl.com
            </p>
            
            <div class="privacy-warning mt-4">
              <p class="fw-bold mb-2">Nochmaliger Hinweis:</p>
              <p class="mb-0">Diese Datenschutzerklärung ist Teil eines Ausbildungsprojekts und dient ausschließlich zu Bildungszwecken. Bitte verwenden Sie keine echten personenbezogenen Daten auf dieser Plattform. Alle auf der Plattform gespeicherten Daten können jederzeit und ohne Vorankündigung gelöscht werden.</p>
            </div>
            
            <p class="mt-4 text-muted">Stand: Mai 2025</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-white py-4" style="background: #232946;">
    <div class="container">
      <div class="row gy-4">
        <div class="col-md-4">
          <div class="mb-3 d-flex align-items-center">
            <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="36" class="me-2 bg-white rounded-circle p-1">
            <span class="fw-bold fs-5">Social Owl</span>
          </div>
          <p class="mb-3">Dein soziales Netzwerk für kreative Köpfe, Austausch & Inspiration.</p>
          <div class="d-flex gap-3">
            <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white fs-5"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="text-white fs-5"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
        <div class="col-md-2">
          <h5 class="fw-bold mb-3" style="color: #eebbc3;">Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#hero" class="text-white text-decoration-none">Home</a></li>
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#features" class="text-white text-decoration-none">Features</a></li>
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#gallery" class="text-white text-decoration-none">Galerie</a></li>
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#team" class="text-white text-decoration-none">Team</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h5 class="fw-bold mb-3" style="color: #eebbc3;">Support</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#faq" class="text-white text-decoration-none">FAQ</a></li>
            <li class="mb-2"><a href="<?= BASE_URL ?>/views/website.php#kontakt" class="text-white text-decoration-none">Kontakt</a></li>
            <li class="mb-2"><a href="#" class="text-white text-decoration-none">Hilfe</a></li>
            <li class="mb-2"><a href="#" class="text-white text-decoration-none">Community</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5 class="fw-bold mb-3" style="color: #eebbc3;">Newsletter</h5>
          <p class="mb-3">Melde dich für unseren Newsletter an und erhalte Updates zu neuen Features und Events.</p>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Deine E-Mail-Adresse" aria-label="Email" aria-describedby="button-addon2">
            <button class="btn" type="button" id="button-addon2" style="background: #eebbc3; color: #232946;">Anmelden</button>
          </div>
        </div>
      </div>
      <hr class="mt-4 mb-3" style="border-color: rgba(255,255,255,0.1);">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <small>© 2025 Social Owl. Alle Rechte vorbehalten.</small>
        </div>
        <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
          <a href="<?= BASE_URL ?>/views/datenschutz.php" class="text-white text-decoration-none me-3"><small>Datenschutz</small></a>
          <a href="<?= BASE_URL ?>/views/impressum.php" class="text-white text-decoration-none me-3"><small>Impressum</small></a>
          <a href="#" class="text-white text-decoration-none"><small>AGB</small></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS -->
  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/script.js"></script>

  <!-- Cookie Banner -->
  <div id="cookie-banner" class="cookie-banner">
    <!-- Der Inhalt wird dynamisch durch JavaScript geladen -->
  </div>

  <!-- Cookie Banner CSS und JS einbinden -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cookie-banner.css">
  <script src="<?= BASE_URL ?>/assets/js/cookie-banner.js"></script>
</body>

</html>