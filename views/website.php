<?php
require_once __DIR__ . '/../includes/config.php';
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Social Owl - Dein soziales Netzwerk für kreative Köpfe</title>
  <meta name="description" content="Social Owl - das innovative soziale Netzwerk für kreative Köpfe und Networking. Teile deine Ideen, vernetze dich und entdecke neue Inspirationen.">
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
    
    .hero-pattern-bg {
      background-image: url('<?= BASE_URL ?>/assets/img/owl_pattern.png');
      background-size: 300px;
      background-position: center;
      background-repeat: repeat;
      position: relative;
    }
    
    .hero-pattern-bg::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(120deg, rgba(35, 41, 70, 0.92) 60%, rgba(13, 110, 253, 0.85) 100%);
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .testimonial-card {
      transition: all 0.3s ease;
    }
    
    .testimonial-card:hover {
      transform: scale(1.03);
    }
    
    .img-shadow {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-img {
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .gallery-img:hover img {
      transform: scale(1.05);
    }
    
    .gallery-img img {
      transition: transform 0.5s ease;
    }
    
    .feature-icon {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex; 
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      background: #eebbc3;
    }
    
    .feature-icon i {
      font-size: 28px;
      color: #232946;
    }
    
    .circle-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex; 
      align-items: center;
      justify-content: center;
      background: #eebbc3;
    }
    
    .circle-icon i {
      font-size: 24px;
      color: #232946;
    }
    
    .team-icon-badge {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      bottom: 0;
      right: 0;
    }
    
    .pulse-animation {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% {
        transform: scale(0.95);
      }
      70% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(0.95);
      }
    }
    
    .section-divider {
      height: 100px;
      margin-top: -50px;
      margin-bottom: -50px;
      position: relative;
      z-index: 1;
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: #232946;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#hero">
        <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="36" class="me-2 align-text-top">Social Owl
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#preview">App-Vorschau</a></li>
          <li class="nav-item"><a class="nav-link" href="#gallery">Galerie</a></li>
          <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
          <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontakt">Kontakt</a></li>
        </ul>
        <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <a href="<?= BASE_URL ?>/views/login.view.php" class="btn btn-outline-light me-2" style="border-color: #eebbc3; color: #eebbc3;">Login</a>
          <a href="<?= BASE_URL ?>/views/register.view.php" class="btn" style="background: #eebbc3; color: #232946; font-weight: bold;">Registrieren</a>
        </div>
      </div>
    </div>
  </nav>

  
  <!-- Hero Section mit Pattern-Hintergrund -->
  <section id="hero" class="hero-pattern-bg text-white text-center py-5 position-relative" style="min-height: 85vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div class="container position-relative">
      <div class="row align-items-center">
        <div class="col-lg-6 text-lg-start mb-5 mb-lg-0">
          <div class="animate__animated animate__fadeInLeft">
            <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" alt="Social Owl Logo" width="90" class="mb-3 bg-white p-2 rounded-circle">
            <h1 class="display-4 fw-bold" style="color: #eebbc3;">Social Owl</h1>
            <h2 class="mb-4 fw-light" style="color: #fff;">Dein soziales Netzwerk für kreative Köpfe</h2>
            <p class="lead mb-4" style="color: #f4f4f4;">Verbinde dich, teile deine Ideen und entdecke neue Inspirationen in einer Community von Gleichgesinnten.</p>
            <div class="d-flex flex-wrap justify-content-lg-start justify-content-center gap-3">
              <a href="<?= BASE_URL ?>/views/register.view.php" class="btn btn-lg shadow " style="background: #eebbc3; color: #232946; font-weight: bold; border: none;">Jetzt kostenlos starten</a>
              <a href="#preview" class="btn btn-lg btn-outline-light">App entdecken</a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 animate__animated animate__fadeInRight">
          <div class="position-relative">
            <img src="<?= BASE_URL ?>/assets/img/social_owl_page.png" alt="Social Owl App Preview" class="img-fluid rounded-4 shadow-lg">
            <div class="position-absolute top-0 end-0 translate-middle">
              <div class="circle-icon bg-primary p-3 shadow-lg pulse-animation" style="width: 56px; height: 56px;">
                <i class="bi bi-bell-fill text-white fs-4"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- App-Vorschau Section mit Screenshot -->
  <section id="preview" class="py-5 bg-light">
    <div class="container mb-5">
      <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0 animate__animated animate__fadeInLeft">
        <img src="<?= BASE_URL ?>/assets/img/website/Übersicht.png" class="img-fluid rounded-4 shadow img-shadow" alt="Social Owl App Dashboard">
      </div>
      <div class="col-lg-6 animate__animated animate__fadeInRight">
        <h2 class="fw-bold mb-4" style="color: #232946;">Entdecke die Social Owl Erfahrung</h2>
        <p class="lead mb-4">Unsere Plattform kombiniert modernes Design mit allen Funktionen, die du von einem sozialen Netzwerk erwartest – und noch viel mehr.</p>
        <div class="d-flex mb-3">
        <div class="me-3">
          <i class="bi bi-check-circle-fill fs-3" style="color: #0d6efd;"></i>
        </div>
        <div>
          <h5 class="fw-bold">Intuitive Benutzeroberfläche</h5>
          <p>Finde dich sofort zurecht und konzentriere dich auf das Wesentliche: deine Inhalte und Verbindungen.</p>
        </div>
        </div>
        <div class="d-flex mb-3">
        <div class="me-3">
          <i class="bi bi-check-circle-fill fs-3" style="color: #0d6efd;"></i>
        </div>
        <div>
          <h5 class="fw-bold">Personalisierte Feeds</h5>
          <p>Dein Feed zeigt dir genau die Inhalte, die für dich relevant sind – von Freunden und Interessensgebieten.</p>
        </div>
        </div>
        <div class="d-flex">
        <div class="me-3">
          <i class="bi bi-check-circle-fill fs-3" style="color: #0d6efd;"></i>
        </div>
        <div>
          <h5 class="fw-bold">Schnelle Performance</h5>
          <p>Genieße ein flüssiges Nutzererlebnis durch unsere optimierte App-Architektur.</p>
        </div>
        </div>
      </div>
      </div>
    </div>
  </section>

  <!-- Features Section mit Icons -->
  <section id="features" class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold" style="color: #232946;">Was macht Social Owl besonders?</h2>
      <p class="lead text-muted">Entdecke die innovativen Funktionen unserer Plattform</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4 animate__animated animate__fadeInUp">
        <div class="p-4 rounded shadow-sm h-100 feature-card" style="background: #f4f4f4; color: #232946; transition: all 0.3s ease;">
          <div class="feature-icon">
            <i class="bi bi-chat-dots"></i>
          </div>
          <h4 class="mt-3 text-center fw-bold">Echtzeit-Chat</h4>
          <p class="text-center">Kommuniziere mit Freunden und neuen Kontakten in Echtzeit – mit Lesebestätigungen und Typing-Anzeige.</p>
          <div class="text-center mt-3">
            
          </div>
        </div>
      </div>
      <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-1s">
        <div class="p-4 rounded shadow-sm h-100 feature-card" style="background: #f4f4f4; color: #232946; transition: all 0.3s ease;">
          <div class="feature-icon">
            <i class="bi bi-image"></i>
          </div>
          <h4 class="mt-3 text-center fw-bold">Bilder & Stories</h4>
          <p class="text-center">Teile deine schönsten Momente mit Bildern und Stories und inspiriere die Community mit deinen kreativen Inhalten.</p>
          <div class="text-center mt-3">
           
          </div>
        </div>
      </div>
      <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-2s">
        <div class="p-4 rounded shadow-sm h-100 feature-card" style="background: #f4f4f4; color: #232946; transition: all 0.3s ease;">
          <div class="feature-icon">
            <i class="bi bi-bell"></i>
          </div>
          <h4 class="mt-3 text-center fw-bold">Smart Notifications</h4>
          <p class="text-center">Bleib immer auf dem Laufenden mit intelligenten Benachrichtigungen, die dir nur die wichtigsten Updates anzeigen.</p>
          <div class="text-center mt-3">
           
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-5 gx-4 gy-5">
      <div class="col-md-6">
        <div class="d-flex">
          <div class="me-3">
            <div class="circle-icon p-3">
              <i class="bi bi-shield-lock" style="font-size: 1.75rem; color: #232946;"></i>
            </div>
          </div>
          <div>
            <h4 class="fw-bold" style="color: #232946;">Datenschutz & Privatsphäre</h4>
            <p>Deine Daten sind bei uns sicher. Mit umfangreichen Privatsphäre-Einstellungen behältst du die volle Kontrolle darüber, wer deine Inhalte sehen kann.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex">
          <div class="me-3">
            <div class="circle-icon p-3">
              <i class="bi bi-emoji-smile" style="font-size: 1.75rem; color: #232946;"></i>
            </div>
          </div>
          <div>
            <h4 class="fw-bold" style="color: #232946;">Individuelle Profile</h4>
            <p>Gestalte dein Profil mit Headerbild, Profilbild und persönlicher Bio. Zeige der Welt, wer du bist und was dich ausmacht.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex">
          <div class="me-3">
            <div class="circle-icon p-3">
              <i class="bi bi-search" style="font-size: 1.75rem; color: #232946;"></i>
            </div>
          </div>
          <div>
            <h4 class="fw-bold" style="color: #232946;">Intelligente Suche</h4>
            <p>Finde Nutzer, Beiträge und Hashtags blitzschnell mit unserer leistungsstarken Suchfunktion und entdecke neue Inhalte.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex">
          <div class="me-3">
            <div class="circle-icon p-3">
              <i class="bi bi-heart" style="font-size: 1.75rem; color: #232946;"></i>
            </div>
          </div>
          <div>
            <h4 class="fw-bold" style="color: #232946;">Interaktive Community</h4>
            <p>Interagiere mit Beiträgen durch Likes und Kommentare. Baue Verbindungen auf und teile deine Gedanken mit der Community.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery Section mit statischen Bildern -->
  <section id="gallery" class="py-5" style="background: #f9f9f9;">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #232946;">Einblicke in Social Owl</h2>
        <p class="lead text-muted">Erlebe unsere Plattform in Aktion</p>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card border-0 h-100 shadow gallery-img rounded-4 overflow-hidden">
            <img src="<?= BASE_URL ?>/assets/img/website/Übersicht.png" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Social Owl Feed">
            <div class="card-body" style="background: #fff;">
              <h5 class="card-title fw-bold" style="color: #232946;">Moderne Feed-Ansicht</h5>
              <p class="card-text">Entdecke Beiträge von Freunden und Community in einer übersichtlichen Timeline mit intuitiver Navigation.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 h-100 shadow gallery-img rounded-4 overflow-hidden">
            <img src="<?= BASE_URL ?>/assets/img/website/Searchbar.png" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Social Owl Search">
            <div class="card-body" style="background: #fff;">
              <h5 class="card-title fw-bold" style="color: #232946;">Intelligente Suche</h5>
              <p class="card-text">Finde schnell und einfach Freunde, Beiträge und Themen, die dich interessieren.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 h-100 shadow gallery-img rounded-4 overflow-hidden">
            <img src="<?= BASE_URL ?>/assets/img/website/Chat.png" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Social Owl Chat">
            <div class="card-body" style="background: #fff;">
              <h5 class="card-title fw-bold" style="color: #232946;">Echtzeit-Kommunikation</h5>
              <p class="card-text">Chatten leicht gemacht: Bleib mit allen wichtigen Kontakten durch unser schnelles Nachrichtensystem in Verbindung.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section mit Bildern -->
  <section id="team" class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold" style="color: #232946;">Das Team hinter Social Owl</h2>
      <p class="lead text-muted">Lernen Sie die kreativen Köpfe kennen, die Social Owl entwickelt haben</p>
    </div>
    
    <div class="row justify-content-center g-4">
      <div class="col-lg-2 col-md-4 col-6">
        <div class="text-center">
          <div class="position-relative mb-3 mx-auto" style="width: 130px; height: 130px;">
            <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Nico Walter" class="rounded-circle border border-4 img-fluid" style="width: 130px; height: 130px; object-fit: cover; border-color: #eebbc3 !important;">
            <div class="team-icon-badge bg-primary">
              <i class="bi bi-code-slash text-white"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-1" style="color: #232946;">Nico</h5>
          <p class="text-muted mb-2">Co-Founder</p>
          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-github"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-4 col-6">
        <div class="text-center">
          <div class="position-relative mb-3 mx-auto" style="width: 130px; height: 130px;">
            <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Georg Diesendorf" class="rounded-circle border border-4 img-fluid" style="width: 130px; height: 130px; object-fit: cover; border-color: #eebbc3 !important;">
            <div class="team-icon-badge bg-primary">
              <i class="bi bi-database text-white"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-1" style="color: #232946;">Georg</h5>
          <p class="text-muted mb-2">Co-Founder & Backend</p>
          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-github"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-4 col-6">
        <div class="text-center">
          <div class="position-relative mb-3 mx-auto" style="width: 130px; height: 130px;">
            <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Andreas Wiegand" class="rounded-circle border border-4 img-fluid" style="width: 130px; height: 130px; object-fit: cover; border-color: #eebbc3 !important;">
            <div class="team-icon-badge bg-primary">
              <i class="bi bi-window text-white"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-1" style="color: #232946;">Andreas</h5>
          <p class="text-muted mb-2">Co-Founder & Frontend</p>
          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-github"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-4 col-6">
        <div class="text-center">
          <div class="position-relative mb-3 mx-auto" style="width: 130px; height: 130px;">
            <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Florian Prottengeier" class="rounded-circle border border-4 img-fluid" style="width: 130px; height: 130px; object-fit: cover; border-color: #eebbc3 !important;">
            <div class="team-icon-badge bg-primary">
              <i class="bi bi-palette text-white"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-1" style="color: #232946;">Florian</h5>
          <p class="text-muted mb-2">Co-Founder & Design</p>
          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-behance"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-4 col-6">
        <div class="text-center">
          <div class="position-relative mb-3 mx-auto" style="width: 130px; height: 130px;">
            <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="Alexander Rahn" class="rounded-circle border border-4 img-fluid" style="width: 130px; height: 130px; object-fit: cover; border-color: #eebbc3 !important;">
            <div class="team-icon-badge bg-primary">
              <i class="bi bi-bar-chart text-white"></i>
            </div>
          </div>
          <h5 class="fw-bold mb-1" style="color: #232946;">Alexander</h5>
          <p class="text-muted mb-2">Marketing & Analytics</p>
          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="circle-icon btn-sm"><i class="bi bi-twitter-x"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Community Section mit Testimonials -->
  <section id="community" class="py-5" style="background: #f4f4f4;">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #232946;">Was unsere Community sagt</h2>
        <p class="lead text-muted">Erfahrungen von Social Owl Nutzern</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded shadow h-100" style="background: #fff;">
            <div class="d-flex align-items-center mb-3">
              <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="User" width="60" height="60" class="rounded-circle me-3" style="object-fit: cover;">
              <div>
                <h5 class="mb-0 fw-bold" style="color: #232946;">Anna M.</h5>
                <p class="text-muted mb-0">Designerin & Creator</p>
              </div>
            </div>
            <p class="mb-0">"Social Owl hat mir geholfen, meine Designarbeiten mit der Welt zu teilen und wertvolles Feedback zu bekommen. Die Community ist super unterstützend und inspirierend!"</p>
            <div class="mt-3 text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded shadow h-100" style="background: #fff;">
            <div class="d-flex align-items-center mb-3">
              <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="User" width="60" height="60" class="rounded-circle me-3" style="object-fit: cover;">
              <div>
                <h5 class="mb-0 fw-bold" style="color: #232946;">Max S.</h5>
                <p class="text-muted mb-0">Student & Blogger</p>
              </div>
            </div>
            <p class="mb-0">"Die App ist super intuitiv und macht einfach Spaß! Besonders das Chat-System nutze ich täglich, um mit meinen Kommilitonen zu kommunizieren und Projekte zu organisieren."</p>
            <div class="mt-3 text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial-card p-4 rounded shadow h-100" style="background: #fff;">
            <div class="d-flex align-items-center mb-3">
              <img src="<?= BASE_URL ?>/assets/img/profil.png" alt="User" width="60" height="60" class="rounded-circle me-3" style="object-fit: cover;">
              <div>
                <h5 class="mb-0 fw-bold" style="color: #232946;">Julia K.</h5>
                <p class="text-muted mb-0">Fotografin</p>
              </div>
            </div>
            <p class="mb-0">"Als Fotografin ist mir die Bildqualität wichtig, und Social Owl enttäuscht nicht! Die Bilder werden in bester Qualität dargestellt, und die Community-Reaktionen sind immer motivierend."</p>
            <div class="mt-3 text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section mit Accordion -->
  <section id="faq" class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold" style="color: #232946;">Häufige Fragen</h2>
      <p class="lead text-muted">Antworten auf die wichtigsten Fragen zu Social Owl</p>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
            <h2 class="accordion-header" id="faq1-heading">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1" style="background: #eebbc3; color: #232946; font-weight: 600;">
                <i class="bi bi-patch-question me-2"></i>Ist Social Owl kostenlos?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Ja, die Nutzung von Social Owl ist komplett kostenlos. Wir finanzieren uns durch diskrete Werbung und Premium-Funktionen, aber alle Grundfunktionen sind und bleiben für alle Nutzer kostenlos zugänglich.</div>
            </div>
          </div>
          <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
            <h2 class="accordion-header" id="faq2-heading">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2" style="background: #eebbc3; color: #232946; font-weight: 600;">
                <i class="bi bi-person-badge me-2"></i>Wie kann ich mein Profil anpassen?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Nach der Registrierung kannst du dein Profil über den "Profil bearbeiten"-Button anpassen. Dort hast du die Möglichkeit, ein Profilbild und ein Headerbild hochzuladen, eine Bio zu schreiben und weitere persönliche Details hinzuzufügen.</div>
            </div>
          </div>
          <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
            <h2 class="accordion-header" id="faq3-heading">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3" style="background: #eebbc3; color: #232946; font-weight: 600;">
                <i class="bi bi-phone me-2"></i>Gibt es eine mobile App?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Eine mobile App für iOS und Android ist bereits in Entwicklung und wird in Kürze veröffentlicht. Bis dahin kannst du Social Owl bequem im mobilen Browser nutzen – unsere Plattform ist vollständig für mobile Geräte optimiert.</div>
            </div>
          </div>
          <div class="accordion-item mb-3 border-0 shadow-sm rounded overflow-hidden">
            <h2 class="accordion-header" id="faq4-heading">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4" style="background: #eebbc3; color: #232946; font-weight: 600;">
                <i class="bi bi-shield-lock me-2"></i>Wie sicher sind meine Daten?
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Sicherheit hat bei uns höchste Priorität. Alle Daten werden verschlüsselt gespeichert und übertragen. Wir geben keine persönlichen Daten an Dritte weiter und bieten dir umfangreiche Einstellungsmöglichkeiten, um deine Privatsphäre nach deinen Wünschen zu gestalten.</div>
            </div>
          </div>
          <div class="accordion-item border-0 shadow-sm rounded overflow-hidden">
            <h2 class="accordion-header" id="faq5-heading">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5" style="background: #eebbc3; color: #232946; font-weight: 600;">
                <i class="bi bi-question-circle me-2"></i>Wie kann ich Support erhalten?
              </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5-heading" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Bei Fragen oder Problemen kannst du jederzeit unseren Support kontaktieren. Nutze einfach das Kontaktformular auf dieser Seite oder sende uns eine E-Mail an support@social-owl.com. Wir antworten in der Regel innerhalb von 24 Stunden.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kontakt Section mit Karte -->
  <section id="kontakt" class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold" style="color: #232946;">Kontakt</h2>
      <p class="lead text-muted">Wir freuen uns auf deine Nachricht</p>
    </div>
    
    <div class="row justify-content-center align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <div class="card border-0 shadow-lg rounded-4">
          <div class="card-body p-4">
            <form>
              <div class="mb-3">
                <label for="name" class="form-label fw-bold" style="color: #232946;">Name</label>
                <div class="input-group">
                  <span class="input-group-text" style="background: #eebbc3;"><i class="bi bi-person"></i></span>
                  <input type="text" class="form-control" id="name" placeholder="Dein Name">
                </div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label fw-bold" style="color: #232946;">E-Mail</label>
                <div class="input-group">
                  <span class="input-group-text" style="background: #eebbc3;"><i class="bi bi-envelope"></i></span>
                  <input type="email" class="form-control" id="email" placeholder="deine@email.de">
                </div>
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label fw-bold" style="color: #232946;">Betreff</label>
                <div class="input-group">
                  <span class="input-group-text" style="background: #eebbc3;"><i class="bi bi-chat-left"></i></span>
                  <select class="form-select" id="subject">
                    <option selected>Bitte wählen...</option>
                    <option>Allgemeine Anfrage</option>
                    <option>Support</option>
                    <option>Feedback</option>
                    <option>Geschäftspartnerschaft</option>
                  </select>
                </div>
              </div>
              <div class="mb-4">
                <label for="message" class="form-label fw-bold" style="color: #232946;">Nachricht</label>
                <textarea class="form-control" id="message" rows="4" placeholder="Deine Nachricht an uns..."></textarea>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn py-2" style="background: #232946; color: #eebbc3; font-weight: bold;">Nachricht senden <i class="bi bi-send ms-1"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="rounded-4 overflow-hidden shadow-lg" style="border: 5px solid #eebbc3;">
          <iframe title="Nürnberg, Veilhofstraße" src="https://www.openstreetmap.org/export/embed.html?bbox=11.0925%2C49.4475%2C11.1005%2C49.4515&amp;layer=mapnik&amp;marker=49.4495%2C11.0965" style="width:100%; height:400px; border:0;"></iframe>
        </div>
        <div class="mt-3">
          <div class="d-flex align-items-center mb-2">
            <div class="me-3 circle-icon">
              <i class="bi bi-geo-alt" style="color: #232946;"></i>
            </div>
            <div>
              <p class="mb-0">Veilhofstraße, 90489 Nürnberg</p>
            </div>
          </div>
          <div class="d-flex align-items-center mb-2">
            <div class="me-3 circle-icon">
              <i class="bi bi-envelope" style="color: #232946;"></i>
            </div>
            <div>
              <p class="mb-0">info@social-owl.com</p>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <div class="me-3 circle-icon">
              <i class="bi bi-telephone" style="color: #232946;"></i>
            </div>
            <div>
              <p class="mb-0">+49 (0) 911 12345678</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="py-5 text-center" style="background: linear-gradient(120deg, #232946 0%, #0d6efd 100%);">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <h2 class="display-5 fw-bold text-white mb-3">Bereit, Teil der Community zu werden?</h2>
          <p class="lead text-white mb-4 opacity-90">Tausende Nutzer teilen bereits ihre Ideen und Kreationen auf Social Owl. Sei dabei!</p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>/views/register.view.php" class="btn btn-lg px-4 py-3" style="background: #eebbc3; color: #232946; font-weight: bold;">
              <i class="bi bi-person-plus me-2"></i> Jetzt kostenlos registrieren
            </a>
           
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
            <li class="mb-2"><a href="#hero" class="text-white text-decoration-none">Home</a></li>
            <li class="mb-2"><a href="#features" class="text-white text-decoration-none">Features</a></li>
            <li class="mb-2"><a href="#gallery" class="text-white text-decoration-none">Galerie</a></li>
            <li class="mb-2"><a href="#team" class="text-white text-decoration-none">Team</a></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h5 class="fw-bold mb-3" style="color: #eebbc3;">Support</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#faq" class="text-white text-decoration-none">FAQ</a></li>
            <li class="mb-2"><a href="#kontakt" class="text-white text-decoration-none">Kontakt</a></li>
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
          <a href="datenschutz.php" class="text-white text-decoration-none me-3"><small>Datenschutz</small></a>
          <a href="impressum.php" class="text-white text-decoration-none me-3"><small>Impressum</small></a>
          <a href="#" class="text-white text-decoration-none"><small>AGB</small></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- JS & Animation CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
  <script>
    // Smooth Scrolling für Navigation
    document.querySelectorAll('a.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
          e.preventDefault();
          window.scrollTo({
            top: target.offsetTop - 70,
            behavior: 'smooth'
          });
        }
      });
    });

    // Animation für Features
    document.addEventListener('DOMContentLoaded', function () {
      const animateElements = document.querySelectorAll('.animate__animated');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate__fadeIn');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });
      
      animateElements.forEach(el => {
        observer.observe(el);
      });
    });
    
    // Hover-Effekt für Galerie-Bilder
    document.querySelectorAll('.gallery-img').forEach(img => {
      img.addEventListener('mouseover', () => img.classList.add('shadow-lg'));
      img.addEventListener('mouseout', () => img.classList.remove('shadow-lg'));
    });

    // Animation für das Logo im Hero-Bereich
    const logo = document.querySelector('#hero img');
    setInterval(() => {
      logo.classList.add('animate__pulse');
      setTimeout(() => {
        logo.classList.remove('animate__pulse');
      }, 1000);
    }, 3000);
  </script>

  <!-- Cookie Banner -->
  <div id="cookie-banner" class="cookie-banner">
    <!-- Der Inhalt wird dynamisch durch JavaScript geladen -->
  </div>

  <!-- Cookie Banner CSS und JS einbinden -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cookie-banner.css">
  <script src="<?= BASE_URL ?>/assets/js/cookie-banner.js"></script>
</body>

</html>