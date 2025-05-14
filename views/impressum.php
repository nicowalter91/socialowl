<?php
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Impressum - Social Owl</title>
  <meta name="description" content="Impressum von Social Owl - Informationen zum Betreiber der Website.">
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
          <h1 class="display-4 fw-bold" style="color: #eebbc3;">Impressum</h1>
          <p class="lead">Informationen zum Betreiber von Social Owl</p>
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
            <h2>Angaben gemäß § 5 TMG</h2>
            
            <div class="bg-light p-4 rounded mb-4 border-start border-5 border-info">
              <p class="mb-0"><strong>Wichtiger Hinweis:</strong> Social Owl ist ein Ausbildungsprojekt für Fachinformatiker und rein fiktiv. 
              Diese Plattform dient ausschließlich Bildungszwecken. Alle Informationen und Daten sind fiktiv und stellen kein reales Unternehmen oder Angebot dar.</p>
            </div>
            
            <h3>Betreiber</h3>
            <p>
              Social Owl GmbH (fiktiv)<br>
              Veilhofstraße 34-36<br>
              90489 Nürnberg<br>
              Deutschland
            </p>
            
            <h3>Kontakt</h3>
            <p>
              Telefon: +49 (0) 911 12345678<br>
              E-Mail: info@social-owl.com
            </p>
            
            <h3>Gesellschafter (fiktiv)</h3>
            <p>
              Nico Walter<br>
              Georg Diesendorf<br>
              Andreas Wiegand<br>
              Florian Prottengeier<br>
              Alexander Rahn
            </p>

            <h3>Handelsregister (fiktiv)</h3>
            <p>
              Amtsgericht Nürnberg<br>
              Registernummer: HRB 12345
            </p>

            <h3>Umsatzsteuer-Identifikationsnummer (fiktiv)</h3>
            <p>
              DE123456789
            </p>

            <h3>Verantwortlich für den Inhalt</h3>
            <p>
              Social Owl Ausbildungsprojekt<br>
              Vertreten durch: Das Ausbildungsteam<br>
              Veilhofstraße 34-36<br>
              90489 Nürnberg
            </p>

            <h3>Ausbildungsprojekt</h3>
            <p>
              Social Owl ist ein Ausbildungsprojekt für angehende Fachinformatiker. Diese Plattform wurde im Rahmen der beruflichen Ausbildung entwickelt und dient dem Erlernen von Webentwicklungstechnologien, Datenbanksystemen und Benutzeroberflächen-Design.
            </p>
            <p>
              Das Projekt ist nicht für kommerzielle Zwecke bestimmt und stellt keine reale Dienstleistung dar. Sämtliche Funktionen, Inhalte und Designelemente wurden ausschließlich zu Lern- und Übungszwecken erstellt.
            </p>

            <h3>Haftungshinweis</h3>
            <p>
              Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.
            </p>
            <p>
              Da es sich um ein Ausbildungsprojekt handelt, können wir keine Gewährleistung für die Sicherheit, Verfügbarkeit oder Funktionalität der Plattform übernehmen.
            </p>
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